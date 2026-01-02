<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function collectionCards()
    {
        return $this->hasMany(CollectionCard::class);
    }

    public function scryfallCards()
    {
        return $this->hasManyThrough(
            ScryfallCard::class,
            CollectionCard::class,
            'collection_id',      
            'id',                 
            'id',                 
            'scryfall_card_id'    
        );
    }
}

