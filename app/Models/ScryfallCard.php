<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScryfallCard extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'scryfall_cards';

    protected $fillable = [
        'scryfall_id',
        'name',
        'mana_cost',
        'cmc',
        'type_line',
        'oracle_text',
        'power',
        'toughness',
        'colors',
        'rarity',
        'set_code',
        'image_url',
        'price_usd',
        'price_php',
        'legalities',
        'last_price_update',
    ];

    public function collectionCards()
    {
        return $this->hasMany(CollectionCard::class);
    }
}
