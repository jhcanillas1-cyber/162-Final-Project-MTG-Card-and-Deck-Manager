@extends('layouts.html')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-4">{{ $deck->name }}</h2>

    <a href="{{ route('decks.export', $deck) }}" class="btn btn-outline-secondary btn-sm mb-3">
     ⬇ Export Deck (.txt) </a>

    <div class="row g-4">
        @foreach ($deck->cards as $card)
            @php
                $owned = in_array(
                    $card->scryfall_card_id,
                    $collectionCardIds
                );
            @endphp

            <div class="col-6 col-md-4 col-lg-2">
                <div class="card shadow-sm p-2 text-center
                    {{ $owned ? '' : 'border border-danger' }}">

                    @if ($card->scryfallCard->image_url)
                        <img src="{{ $card->scryfallCard->image_url }}"
                             class="img-fluid rounded mb-2">
                    @endif

                    <h6 class="fw-bold small">
                        {{ $card->scryfallCard->name }}
                    </h6>

                    @if ($owned)
                        <span class="badge bg-success">Owned</span>
                    @else
                        <span class="badge bg-danger">Not Owned</span>
                        <small class="text-danger d-block mt-1">
                            Add to collection
                        </small>
                    @endif

                    <form method="POST" action="{{ route('decks.removeCard') }}" class="mt-1">
                        @csrf
                        <input type="hidden" name="deck_card_id" value="{{ $card->id }}">
                        <button class="btn btn-sm btn-outline-danger w-100">
                            − Remove
                        </button>
                    </form>

                </div>
            </div>
        @endforeach
    </div>

</div>
@endsection

