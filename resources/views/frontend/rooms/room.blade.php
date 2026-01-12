<!-- Rooms Section -->
<section class="rooms-section">
    <div class="container">
        <div class="section-header">
            <h2>ROOMS & RATES</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Sequi, repellat.</p>
        </div>

        <div class="rooms-grid">
            @foreach($rooms as $room)
<div class="room-card">
    <div class="room-image">
        <img src="{{ $room->image ? asset('storage/'.$room->image) : 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=400' }}"alt="{{ $room->name }}>
    </div>
    <div class="room-info">
        <h3>{{ $room->name }}</h3>
        <p class="room-price">Rp {{ number_format($room->price, 0, ',', '.') }} <span>/night</span></p>
        <div class="room-meta">
            <span><i class="icon-user"></i> {{ $room->capacity }} people</span>
        </div>
        <a href="{{ route('rooms.show', $room->id) }}" class="btn-orange">Book Now</a>
    </div>
</div>
@endforeach
        </div>
    </div>
</section>
@endsection 