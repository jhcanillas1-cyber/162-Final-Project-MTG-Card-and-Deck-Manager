<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\ScryfallCard;

class ScryfallCardController extends Controller
{
    public function index(Request $request)
    {
        $query = ScryfallCard::query();

        if ($request->filled('set')) {
            $query->where('set_code', $request->set);
        }

        if ($request->filled('name')) {
            $query->where('name', 'LIKE', '%' . $request->name . '%');
        }

        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price_usd', 'asc');
                break;

            case 'price_desc':
                $query->orderBy('price_usd', 'desc');
                break;

            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;

            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;

            default:
                $query->orderBy('name', 'asc'); 
        }

        $cards = $query->paginate(30)->appends($request->query());

        $sets = ScryfallCard::select('set_code')->distinct()->orderBy('set_code')->pluck('set_code');

        return view('cards.index', compact('cards', 'sets'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'set_code' => 'required|string|min:3|max:5',
        ]);

        set_time_limit(300);

        try {
            $set = strtolower($request->set_code);

            Artisan::call('scryfall:import', [
                '--set' => $set
            ]);

            return back()->with('status', "Import complete for " . strtoupper($set) . "!");
        } catch (\Exception $e) {
            return back()->withErrors(['set_code' => 'Error during import: ' . $e->getMessage()]);
        }
    }
}

