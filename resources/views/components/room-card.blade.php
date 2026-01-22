{{-- Room Card Component - Sesuai dengan rooms.css --}}
@props(['room'])

@php
    // Ambil gambar utama - support relasi images() dan field image
    $imageUrl = null;
    
    // Priority 1: Primary image dari relasi
    if ($room->relationLoaded('images') && $room->images->isNotEmpty()) {
        $primaryImage = $room->images->where('is_primary', true)->first() 
                        ?? $room->images->first();
        $imageUrl = asset('storage/' . $primaryImage->image_path);
    }
    // Priority 2: Field image (backward compatibility)
    elseif ($room->image) {
        $imageUrl = asset('storage/' . $room->image);
    }
    // Priority 3: Default image
    else {
        $imageUrl = asset('assets/images/default-room.jpg');
    }
    
    $rating = $room->reviews_avg_rating ?? $room->rating ?? 0;
    
    // Handle facilities dari relasi
    $displayFacilities = $room->relationLoaded('facilities') 
        ? $room->facilities->take(3) 
        : collect();
    
    $extraFacilitiesCount = $room->relationLoaded('facilities')
        ? max(0, $room->facilities->count() - 3)
        : 0;
@endphp

<div class="room-card">
    {{-- Image Container --}}
    <div class="room-card-image-container">
        <img src="{{ $imageUrl }}" 
             alt="{{ $room->name }}" 
             class="room-card-image-real"
             onerror="this.onerror=null; this.src='{{ asset('assets/images/default-room.jpg') }}';">
        
        {{-- Rating Badge --}}
        @if($rating > 0)
            <div class="room-rating">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
                {{ number_format($rating, 1) }}
            </div>
        @endif

        {{-- Stock Badge --}}
        @if($room->stock <= 2 && $room->stock > 0)
            <div class="room-stock-badge">Tersisa {{ $room->stock }} kamar</div>
        @endif
    </div>

    {{-- Card Body --}}
    <div class="room-card-body">
        <h3 class="room-name">{{ $room->name }}</h3>

        <div class="room-details">
            <span>{{ $room->size }}</span>
            <span>•</span>
            <span>{{ $room->capacity }} Tamu</span>
        </div>

        {{-- Amenities --}}
        <div class="room-amenities">
            @if($displayFacilities->isNotEmpty())
                @foreach($displayFacilities as $facility)
                    @php
                        $facilityName = $facility->name ?? '';
                        $facilityLower = strtolower($facilityName);
                    @endphp

                    @if(str_contains($facilityLower, 'wifi') || str_contains($facilityLower, 'internet'))
                        <svg class="amenity-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
                            <path d="M1.42 9a16 16 0 0 1 21.16 0"></path>
                            <path d="M8.53 16.11a6 6 0 0 1 6.95 0"></path>
                            <line x1="12" y1="20" x2="12.01" y2="20"></line>
                        </svg>
                    @elseif(str_contains($facilityLower, 'tv') || str_contains($facilityLower, 'televisi'))
                        <svg class="amenity-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                    @elseif(str_contains($facilityLower, 'ac') || str_contains($facilityLower, 'pendingin'))
                        <svg class="amenity-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 14.76V3.5a2.5 2.5 0 0 0-5 0v11.26a4.5 4.5 0 1 0 5 0z"></path>
                        </svg>
                    @elseif(str_contains($facilityLower, 'bath') || str_contains($facilityLower, 'mandi'))
                        <svg class="amenity-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 6 6.5 3.5a1.5 1.5 0 0 0-1-.5C4.683 3 4 3.683 4 4.5V17a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-5"></path>
                            <line x1="10" x2="8" y1="5" y2="7"></line>
                            <line x1="2" x2="22" y1="12" y2="12"></line>
                            <line x1="7" x2="7" y1="19" y2="21"></line>
                            <line x1="17" x2="17" y1="19" y2="21"></line>
                        </svg>
                    @else
                        <svg class="amenity-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    @endif
                @endforeach
                
                @if($extraFacilitiesCount > 0)
                    <span class="amenity-text">+{{ $extraFacilitiesCount }}</span>
                @endif
            @else
                <svg class="amenity-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12.55a11 11 0 0 1 14.08 0"></path>
                </svg>
                <svg class="amenity-icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                </svg>
            @endif
        </div>

        {{-- Footer --}}
        <div class="room-footer">
            <div>
                <div class="room-price">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                <div class="room-price-label">/malam</div>
            </div>

            <a href="{{ route('rooms.show', $room->slug ?? $room->id) }}" class="btn-book">
                Lihat Detail
            </a>
        </div>
    </div>
</div>