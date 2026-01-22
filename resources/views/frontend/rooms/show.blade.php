@extends('layouts.app')

@section('title', $room->name . ' - Hotel Booking')

@section('content')
<section class="room-detail">
    <div class="container">
        <div class="room-detail-grid">
            <!-- Room Image -->
            <div class="room-detail-image">
                <img 
    src="{{ $room->image ? asset('storage/'.$room->image) : 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=400' }}"
    alt="{{ $room->name }}"
>
            </div>

            <!-- Booking Form -->
            <div class="booking-card">
                <div class="booking-header">
                    <div class="capacity">
                        <i class="icon-users"></i>
                        <span>{{ $room->capacity }} people</span>
                    </div>
                    <div class="price">
                        <span class="amount">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                        <span class="period">/Night</span>
                    </div>
                </div>

                <form action="{{ route('reservations.store') }}" method="POST" class="booking-form" id="bookingForm">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">

                    <div class="form-group">
                        <label>Check In Date</label>
                        <input type="date" name="check_in" id="check_in" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Check Out Date</label>
                        <input type="date" name="check_out" id="check_out" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Your Name</label>
                        <input type="text" name="guest_name" class="form-control" placeholder="Full Name..." required>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="tel" name="phone" class="form-control" placeholder="e.g. 081234567890" required>
                    </div>

                    <div class="booking-summary" id="bookingSummary" style="display: none;">
                        <div class="summary-row">
                            <span>Duration:</span>
                            <span id="nights">0 night(s)</span>
                        </div>
                        <div class="summary-row">
                            <span>Price per night:</span>
                            <span>Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="summary-row total">
                            <span><strong>Total Price:</strong></span>
                            <span><strong id="totalPrice">Rp 0</strong></span>
                        </div>
                    </div>

                    <button type="submit" class="btn-reserve">Reserve Now</button>
                </form>
            </div>
        </div>

        <!-- Room Description -->
        <div class="room-description-section">
            <h2>{{ $room->name }}</h2>
            <p>{{ $room->description }}</p>
            
            <div class="room-features">
                <div class="feature-item">
                    <strong>Capacity:</strong> {{ $room->capacity }} people
                </div>
                <div class="feature-item">
                    <strong>Type:</strong> {{ ucfirst($room->type) }}
                </div>
                <div class="feature-item">
                    <strong>Status:</strong> 
                    @if($room->available)
                        <span class="badge-available">Available</span>
                    @else
                        <span class="badge-unavailable">Not Available</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Set minimum date to today
const today = new Date().toISOString().split('T')[0];
document.getElementById('check_in').setAttribute('min', today);
document.getElementById('check_out').setAttribute('min', today);

const checkInInput = document.getElementById('check_in');
const checkOutInput = document.getElementById('check_out');
const pricePerNight = {{ $room->price }};

function calculateTotal() {
    const checkIn = new Date(checkInInput.value);
    const checkOut = new Date(checkOutInput.value);
    
    if (checkIn && checkOut && checkOut > checkIn) {
        const diffTime = Math.abs(checkOut - checkIn);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        const totalPrice = diffDays * pricePerNight;
        
        document.getElementById('nights').textContent = diffDays + ' night(s)';
        document.getElementById('totalPrice').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
        document.getElementById('bookingSummary').style.display = 'block';
    } else {
        document.getElementById('bookingSummary').style.display = 'none';
    }
}

checkInInput.addEventListener('change', function() {
    // Set minimum checkout date to day after checkin
    const checkInDate = new Date(this.value);
    checkInDate.setDate(checkInDate.getDate() + 1);
    const minCheckOut = checkInDate.toISOString().split('T')[0];
    checkOutInput.setAttribute('min', minCheckOut);
    calculateTotal();
});

checkOutInput.addEventListener('change', calculateTotal);

// Form validation
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const checkIn = new Date(checkInInput.value);
    const checkOut = new Date(checkOutInput.value);
    
    if (checkOut <= checkIn) {
        e.preventDefault();
        alert('Check-out date must be after check-in date!');
        return false;
    }
});
</script>
@endsection