@extends('layouts.html')

@section('content')


<div class="row justify-content-center">

    <div class="col-md-8 text-center">

        <div class="card-box">

            <h2 class="fw-bold mb-3">MTG Deck Builder</h2>

            @auth
                <p class="text-muted mb-4">
                    Welcome back, <strong>{{ Auth::user()->name }}</strong>.
                </p>

                <div class="d-grid gap-2 d-md-flex justify-content-center">
                    <a href="{{ route('cards.index') }}" class="btn btn-primary">
                        View Cards
                    </a>
                    <a href="{{ route('collections.index') }}" class="btn btn-primary">
                        My Collection
                    </a>
                    <a href="{{ route('decks.index') }}" class="btn btn-primary">
                        My Decks
                    </a>
                </div>
            @else
                <p class="text-muted mb-4">
                    Please log in or create an account to start building your collection and decks.
                </p>

                <div class="d-grid gap-2 d-md-flex justify-content-center">
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-secondary">
                        Register
                    </a>
                </div>
            @endauth

        </div>

    </div>

</div>

@endsection

