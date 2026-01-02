@extends('layouts.html')

@section('content')
    <div class="row g-4">

        <div class="col-md-4">
            <div class="card-box">
                <h4 class="fw-bold">Cards</h4>
                <p class="text-muted mb-3">
                    Browse all Magic cards imported from Scryfall.
                </p>
                <a href="{{ route('cards.index') }}" class="btn btn-primary w-100">
                    View Cards
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <h4 class="fw-bold">Collection</h4>
                <p class="text-muted mb-3">
                    View and manage the cards you own.
                </p>
                <a href="{{ route('collections.index') }}" class="btn btn-primary w-100">
                    Open Collection
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box">
                <h4 class="fw-bold">Decks</h4>
                <p class="text-muted mb-3">
                    Build decks using cards from your collection.
                </p>
                <a href="{{ route('decks.index') }}" class="btn btn-primary w-100">
                    Manage Decks
                </a>
            </div>
        </div>

    </div>

</div>

@endsection
