<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ScryfallCard;

class ImportScryfallCards extends Command
{
    protected $signature = 'scryfall:import {--set=}';
    protected $description = 'Import Magic: The Gathering cards from the Scryfall API (bulk or per-set).';

    // Batch size for DB inserts
    protected $batchSize = 500;

    public function handle()
    {
        // make command safe to run from CLI, increase time/memory if needed
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $set = $this->option('set');

        if ($set) {
            // per-set paginated import (existing logic)
            $this->info("Importing set: {$set}");
            $url = "https://api.scryfall.com/cards/search?q=set:{$set}";

            return $this->importPaginated($url);
        }

        // Bulk import (ALL cards) — recommended approach
        $this->info("Fetching Scryfall bulk-data metadata (oracle-cards)...");
        $metaResp = Http::get('https://api.scryfall.com/bulk-data/oracle-cards');

        if ($metaResp->failed()) {
            $this->error('Failed to fetch bulk-data metadata from Scryfall.');
            return Command::FAILURE;
        }

        $meta = $metaResp->json();
        $downloadUrl = $meta['download_uri'] ?? null;

        if (! $downloadUrl) {
            $this->error('Bulk data download_uri not found in metadata.');
            return Command::FAILURE;
        }

        $this->info('Downloading bulk JSON (this may take a minute)...');

        // store path under storage/app/scryfall_bulk.json
        $localPath = storage_path('app/scryfall_oracle_cards.json');

        // Use Guzzle's sink via Laravel HTTP client
        try {
            Http::withOptions(['sink' => $localPath])->timeout(0)->get($downloadUrl);
        } catch (\Exception $e) {
            $this->error('Download failed: ' . $e->getMessage());
            return Command::FAILURE;
        }

        $this->info('Download complete. Starting import...');

        // Read file and decode; to avoid memory spikes we'll stream-decoding per chunk if possible.
        // Simple approach: decode all into array (works on most modern machines). If memory issues occur, use a streaming JSON parser (JsonMachine).
        $json = file_get_contents($localPath);
        if ($json === false) {
            $this->error('Could not read downloaded file.');
            return Command::FAILURE;
        }

        $cards = json_decode($json, true);
        if (! is_array($cards)) {
            $this->error('Invalid JSON or decoding error.');
            return Command::FAILURE;
        }

        $total = count($cards);
        $this->info("Total cards to import: {$total}");

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $batch = [];
        $imported = 0;

        foreach ($cards as $card) {
            // Build the record array for DB
            $record = $this->normalizeCardForInsert($card);

            // Skip if scryfall_id already exists (avoid duplicates)
            if (ScryfallCard::where('scryfall_id', $record['scryfall_id'])->exists()) {
                $bar->advance();
                continue;
            }

            $batch[] = $record;

            if (count($batch) >= $this->batchSize) {
                DB::table('scryfall_cards')->insert($batch);
                $imported += count($batch);
                $batch = [];
            }

            $bar->advance();
        }

        // insert remaining batch
        if (count($batch) > 0) {
            DB::table('scryfall_cards')->insert($batch);
            $imported += count($batch);
        }

        $bar->finish();
        $this->newLine();
        $this->info("Import finished. Imported {$imported} new cards.");

        // optionally remove downloaded file to save disk
        // unlink($localPath);

        return Command::SUCCESS;
    }

    /**
     * Import paginated 'search' endpoints (used for --set=...)
     */
    protected function importPaginated(string $url)
    {
        do {
            $response = Http::get($url);
            if ($response->failed()) {
                $this->error("Request failed for URL: {$url}");
                return Command::FAILURE;
            }

            $data = $response->json();

            if (! isset($data['data']) || ! is_array($data['data'])) {
                $this->error('Unexpected paginated response format.');
                return Command::FAILURE;
            }

            foreach ($data['data'] as $card) {
                $this->upsertSingleCard($card);
            }

            $url = $data['next_page'] ?? null;

        } while ($url);

        $this->info('Paginated import complete.');
        return Command::SUCCESS;
    }

    /**
     * Prepare and insert or update a single card into DB for paginated import path
     */
    protected function upsertSingleCard(array $card)
    {
        $record = $this->normalizeCardForInsert($card);

        // Use updateOrInsert to avoid duplicates (faster than check + insert)
        DB::table('scryfall_cards')->updateOrInsert(
            ['scryfall_id' => $record['scryfall_id']],
            $record
        );
    }

    /**
     * Normalize Scryfall API card into DB-ready associative array
     */
    protected function normalizeCardForInsert(array $card): array
    {
        // handle missing indexes safely
        $priceUsd = $card['prices']['usd'] 
            ?? $card['prices']['usd_foil'] 
            ?? $card['prices']['usd_etched'] 
            ?? 0;

        return [
            'scryfall_id'      => $card['id'] ?? null,
            'name'             => $card['name'] ?? null,
            'mana_cost'        => $card['mana_cost'] ?? null,
            'cmc'              => $card['cmc'] ?? null,
            'type_line'        => $card['type_line'] ?? null,
            'oracle_text'      => $card['oracle_text'] ?? null,
            'power'            => $card['power'] ?? null,
            'toughness'        => $card['toughness'] ?? null,
            'colors'           => isset($card['colors']) ? json_encode($card['colors']) : null,
            'rarity'           => $card['rarity'] ?? null,
            'set_code'         => $card['set'] ?? ($card['set_name'] ?? null),
            'image_url'        => $card['image_uris']['normal'] ?? ($card['card_faces'][0]['image_uris']['normal'] ?? null),
            'price_usd'        => $priceUsd,
            'price_php'        => ($priceUsd) * 50,
            'legalities'       => isset($card['legalities']) ? json_encode($card['legalities']) : null,
            'last_price_update'=> now(),
            'created_at'       => now(),
            'updated_at'       => now(),
        ];
    }
}

