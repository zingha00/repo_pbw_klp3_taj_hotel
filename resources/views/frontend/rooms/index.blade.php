@extends('layouts.app')

@section('title', 'All Rooms - Hotel Booking')

@section('content')
<section class="page-header">
    <div class="container">
        <h1>Our Rooms</h1>
        <p>Choose your perfect room</p>
    </div>
</section>

<section class="rooms-section">
    <div class="container">
        <div class="rooms-grid">
            @forelse($rooms as $room)
            <div class="room-card">
                <div class="room-image">
                    <img 
                        src="{{ $room->image ? asset('storage/'.$room->image) : 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=400' }}"
                        alt="{{ $room->name }}">
                    @if(!$room->available)
                        <span class="badge-unavailable">Not Available</span>
                    @endif
                </div>
                <div class="room-info">
                    <h3>{{ $room->name }}</h3>
                    <p class="room-description">{{ Str::limit($room->description, 80) }}</p>
                    <p class="room-price">Rp {{ number_format($room->price, 0, ',', '.') }} <span>/night</span></p>
                    <div class="room-meta">
                        <span><i class="icon-user"></i> {{ $room->capacity }} people</span>
                    </div>
                    <a href="{{ route('rooms.show', $room->id) }}" class="btn-orange">View Details</a>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <p>Belum ada kamar tersedia.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection