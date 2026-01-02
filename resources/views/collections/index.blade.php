@extends('layouts.html')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-4">{{ $collection->name }}</h2>

    <div class="row g-4">
        @foreach ($cards as $item)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card shadow-sm p-2 text-center">

                    @if ($item->scryfallCard->image_url)
                        <img src="{{ $item->scryfallCard->image_url }}"
                             class="img-fluid rounded mb-2">
                    @endif

                    <h6 class="fw-bold small">
                        {{ $item->scryfallCard->name }}
                    </h6>

                    <p class="text-muted small mb-1">
                        {{ strtoupper($item->scryfallCard->set_code) }}
                    </p>

                    <span class="badge bg-primary">
                        ×{{ $item->quantity }}
                    </span>

                    <form method="POST" action="{{ route('collections.removeCard') }}" class="mt-1">
                    @csrf
                    <input type="hidden" name="collection_card_id" value="{{ $item->id }}">
                    <button class="btn btn-sm btn-outline-danger w-100">
                        − Remove
                    </button>
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

