<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeckCard extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['deck_id', 'scryfall_card_id', 'quantity', 'is_sideboard'];

    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }

    public function scryfallCard()
    {
        return $this->belongsTo(ScryfallCard::class);
    }
}
