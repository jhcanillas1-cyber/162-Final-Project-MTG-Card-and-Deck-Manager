<?php

namespace App\Http\Controllers;

use App\Models\Deck;
use App\Models\DeckCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DeckController extends Controller
{
    public function index()
    {
        $decks = Auth::user()->decks;
        return view('decks.index', compact('decks'));
    }

    public function show($id)
    {
        $deck = Deck::with(['cards.scryfallCard'])
        ->where('user_id', auth()->id())
        ->findOrFail($id);

        $collectionCardIds = auth()->user()
            ->collection
            ->collectionCards
            ->pluck('scryfall_card_id')
            ->toArray();

        return view('decks.show', compact('deck', 'collectionCardIds'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Deck::create(['user_id' => auth()->id(),
                      'name' => $request->name,]);

        return redirect()->route('decks.index')->with('success', 'Deck created!');
    }

    public function addCard(Request $request)
    {
        $request->validate(['deck_id' => 'required|exists:decks,id',
            'scryfall_card_id' => 'required|exists:scryfall_cards,id',]);

        $deck = Deck::where('id', $request->deck_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $entry = DeckCard::firstOrCreate(
            ['deck_id' => $deck->id,
             'scryfall_card_id' => $request->scryfall_card_id],
            ['quantity' => 0]
        );

        $entry->increment('quantity');

        return back()->with('success', 'Card added to deck!');
    }

    public function export(Deck $deck)
    {
        $deck->load('cards.scryfallCard');

        $filename = Str::slug($deck->name) . '.txt';

        return response()->streamDownload(function () use ($deck) {

            foreach ($deck->cards as $deckCard) {
                if (!$deckCard->scryfallCard) continue;

                echo $deckCard->quantity . " " .
                    $deckCard->scryfallCard->name . "\n";
            }

        }, $filename, [
            'Content-Type' => 'text/plain',
        ]);
    }
    public function removeCard(Request $request)
    {
        $request->validate([
            'deck_card_id' => 'required|exists:deck_cards,id'
        ]);

        $card = DeckCard::where('id', $request->deck_card_id)
            ->whereHas('deck', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->firstOrFail();

        if ($card->quantity > 1) {
            $card->decrement('quantity');
        } else {
            $card->delete();
        }

        return back()->with('success', 'Card removed from deck.');
    }
}
