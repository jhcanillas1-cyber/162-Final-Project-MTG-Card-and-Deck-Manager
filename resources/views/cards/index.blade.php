@extends('layouts.html')

@section('content')

<div class="container py-5">

    <h1 class="mb-4 text-center fw-bold">MTG Cards by Sets</h1>

    <form method="GET" action="{{ route('cards.index') }}" class="p-3 mb-4 bg-white shadow rounded">

        <div class="row g-3">

            <div class="col-md-3">
                <label class="fw-semibold">Set</label>
                <select name="set" class="form-select">
                    <option value="">All Sets</option>
                    @foreach ($sets as $set)
                        <option value="{{ $set }}" {{ request('set') == $set ? 'selected' : '' }}>
                            {{ strtoupper($set) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="fw-semibold">Card Name</label>
                <input type="text" name="name" class="form-control"
                       placeholder="Search name..."
                       value="{{ request('name') }}">
            </div>

            <div class="col-md-3">
                <label class="fw-semibold">Sort</label>
                <select name="sort" class="form-select">
                    <option value="name_asc"  {{ request('sort')=='name_asc' ? 'selected' : '' }}>Name (A → Z)</option>
                    <option value="name_desc" {{ request('sort')=='name_desc' ? 'selected' : '' }}>Name (Z → A)</option>
                    <option value="price_asc" {{ request('sort')=='price_asc' ? 'selected' : '' }}>Price (Low → High)</option>
                    <option value="price_desc"{{ request('sort')=='price_desc' ? 'selected' : '' }}>Price (High → Low)</option>
                </select>
            </div>

            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-primary w-100">Apply Filters</button>
            </div>

        </div>
    </form>

    <div class="mb-6 p-4 bg-gray-100 rounded">
    <form action="{{ route('cards.import') }}" method="POST" id="importForm">
        @csrf
        <div class="flex items-center gap-2">
            <input type="text" name="set_code" placeholder="Enter Set Code (e.g. NEO)" 
                   class="border p-2 rounded uppercase" required>
            
            <button type="submit" id="submitBtn" class="bg-blue-500 text-white px-4 py-2 rounded">
                Import Set
            </button>
        </div>
    </form>
    
    <p id="loadingMsg" class="hidden mt-2 text-blue-600 font-bold animate-pulse">
        Check set codes through this link: https://scryfall.com/sets
    </p>
    </div>

    <script>
    document.getElementById('importForm').onsubmit = function() {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitBtn').classList.add('opacity-50', 'cursor-not-allowed');
        document.getElementById('loadingMsg').classList.remove('hidden');
    };
    </script>

    <div class="row g-4">
        @foreach ($cards as $card)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card-box text-center">

                    @if ($card->image_url)
                        <img src="{{ $card->image_url }}" class="img-fluid mb-2">
                    @endif

                    <h6 class="fw-bold">{{ $card->name }}</h6>
                    <p class="text-muted small mb-1">{{ strtoupper($card->set_code) }}</p>

                    <p class="fw-bold mb-0">USD ${{ $card->price_usd ?? '0.00' }}</p>
                    <p class="text-success fw-semibold">PHP ₱{{ $card->price_php ?? '0' }}</p>

                   <form action="{{ route('collections.addCard') }}" method="POST" class="mt-2">
                        @csrf
                        <input type="hidden" name="scryfall_card_id" value="{{ $card->id }}">
                        <button type="submit" class="btn btn-sm btn-primary w-100">
                            + Add to Collection
                        </button>
                    </form>

                    <form method="POST" action="{{ route('decks.addCard') }}">
                        @csrf
                        <input type="hidden" name="scryfall_card_id" value="{{ $card->id }}">
                        <select name="deck_id">
                            @foreach (auth()->user()->decks as $deck)
                                <option value="{{ $deck->id }}">{{ $deck->name }}</option>
                            @endforeach
                        </select>
                        <button>Add to Deck</button>
                    </form>
                    
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex justify-content-center">
            {{ $cards->links('pagination::bootstrap-5') }}
        </div>

</div>

@endsection