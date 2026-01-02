<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectionCard extends Model
{
    use HasFactory; 
    use SoftDeletes;

    protected $table = 'collection_cards';

    protected $fillable = [
        'collection_id',
        'scryfall_card_id',
        'quantity',
        'in_collection',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function scryfallCard()
    {
        return $this->belongsTo(ScryfallCard::class, 'scryfall_card_id');
    }

    public function deckCards()
    {
        return $this->hasMany(DeckCard::class);
    }
}

