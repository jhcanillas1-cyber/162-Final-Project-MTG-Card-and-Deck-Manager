@extends('layouts.html')

@section('content')

<h1 class="fw-bold mb-4">My Decks</h1>

{{-- Create Deck --}}
<form method="POST" action="{{ route('decks.store') }}" class="mb-4">
    @csrf
    <div class="input-group">
        <input type="text" name="name" class="form-control" placeholder="New deck name" required>
        <button class="btn btn-primary">Create Deck</button>
    </div>
</form>

{{-- Deck List --}}
<div class="row g-3">
    @foreach ($decks as $deck)
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ $deck->name }}</h5>

                    <p class="text-muted small mb-2">
                        {{ $deck->cards->count() }} cards
                    </p>

                    <a href="{{ route('decks.show', $deck) }}" class="btn btn-sm btn-outline-primary">
                        View Deck
                    </a>
                </div>
            </div>
        </div>
    @endforeach
</div>

@endsection

