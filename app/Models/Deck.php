<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deck extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'description', 'format', 'user_id'];

    public function cards()
    {
        return $this->hasMany(DeckCard::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
