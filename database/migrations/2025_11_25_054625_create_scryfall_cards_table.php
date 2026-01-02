<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('scryfall_cards', function (Blueprint $table) {
            $table->id();
            $table->uuid('scryfall_id')->unique();
            $table->string('name', 255);
            $table->string('mana_cost', 50)->nullable();
            $table->decimal('cmc', 4, 2)->nullable();
            $table->string('type_line', 255)->nullable();
            $table->text('oracle_text')->nullable();
            $table->string('power', 10)->nullable();
            $table->string('toughness', 10)->nullable();
            $table->json('colors')->nullable();
            $table->string('rarity', 50)->nullable();
            $table->string('set_code', 10)->nullable();
            $table->string('collector_number', 10)->nullable();
            $table->string('image_url', 500)->nullable();
            $table->decimal('price_usd', 10, 2)->nullable();
            $table->decimal('price_php', 10, 2)->nullable();
            $table->json('legalities')->nullable();
            $table->timestamp('last_price_update')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scryfall_cards');
    }
};

