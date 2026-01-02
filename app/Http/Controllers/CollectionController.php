<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\CollectionCard;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    /**
     * Show the user's SINGLE collection
     */
    public function index()
    {
        $user = Auth::user();

        // Ensure the user always has exactly one collection
        $collection = Collection::firstOrCreate(
            ['user_id' => $user->id],
            ['name' => $user->name . "'s Collection"]
        );

        $cards = $collection->collectionCards()
            ->with('scryfallCard')
            ->paginate(20);

        return view('collections.index', compact('collection', 'cards'));
    }

    public function addCard(Request $request)
    {
        $request->validate([
            'scryfall_card_id' => 'required|exists:scryfall_cards,id',
        ]);

        $user = Auth::user();

        $collection = Collection::firstOrCreate(
            ['user_id' => $user->id],
            ['name' => $user->name . "'s Collection"]
        );

        $card = CollectionCard::firstOrCreate(
            [
                'collection_id' => $collection->id,
                'scryfall_card_id' => $request->scryfall_card_id,
            ],
            [
                'quantity' => 1,
            ]
        );

        if (! $card->wasRecentlyCreated) {
            $card->increment('quantity');
        }

        return back()->with('success', 'Card added to your collection!');
    }

    public function removeCard(Request $request)
    {
        $request->validate([
            'collection_card_id' => 'required|exists:collection_cards,id'
        ]);

        $card = CollectionCard::where('id', $request->collection_card_id)
            ->whereHas('collection', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->firstOrFail();

        if ($card->quantity > 1) {
            $card->decrement('quantity');
        } else {
            $card->delete(); // soft delete
        }

        return back()->with('success', 'Card removed from collection.');
    }
}



