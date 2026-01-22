@extends('layouts.app')

@section('title', 'My Reservations - Hotel Booking')

@section('content')
<section class="page-header">
    <div class="container">
        <h1>My Reservations</h1>
        <p>Manage your hotel bookings</p>
    </div>
</section>

<section class="reservations-section">
    <div class="container">
        @forelse($reservations as $reservation)
        <div class="reservation-card">
            <div class="reservation-image">
                <img 
    src="{{ $reservation->room->image 
        ? asset('storage/'.$reservation->room->image) 
        : 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=300' 
    }}"
    alt="{{ $reservation->room->name }}"
>
            </div>
            <div class="reservation-details">
                <h3>{{ $reservation->room->name }}</h3>
                <div class="detail-row">
                    <strong>Guest:</strong> {{ $reservation->guest_name }}
                </div>
                <div class="detail-row">
                    <strong>Phone:</strong> {{ $reservation->phone }}
                </div>
                <div class="detail-row">
                    <strong>Check In:</strong> {{ \Carbon\Carbon::parse($reservation->check_in)->format('d M Y') }}
                </div>
                <div class="detail-row">
                    <strong>Check Out:</strong> {{ \Carbon\Carbon::parse($reservation->check_out)->format('d M Y') }}
                </div>
                <div class="detail-row">
                    <strong>Total Price:</strong> Rp {{ number_format($reservation->total_price, 0, ',', '.') }}
                </div>
                <div class="detail-row">
                    <strong>Status:</strong> 
                    @if($reservation->status == 'pending')
                        <span class="badge-pending">Pending</span>
                    @elseif($reservation->status == 'confirmed')
                        <span class="badge-confirmed">Confirmed</span>
                    @else
                        <span class="badge-cancelled">Cancelled</span>
                    @endif
                </div>
            </div>
            <div class="reservation-actions">
                <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan reservasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">Cancel Booking</button>
                </form>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <h3>No Reservations Yet</h3>
            <p>You haven't made any reservations. Start booking now!</p>
            <a href="{{ route('rooms.index') }}" class="btn-orange">Browse Rooms</a>
        </div>
        @endforelse
    </div>
</section>
@endsection