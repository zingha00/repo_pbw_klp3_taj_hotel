<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use Illuminate\Support\Str;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Deluxe Room',
                'slug' => 'deluxe-room',
                'description' => 'Kamar deluxe dengan pemandangan kota yang menakjubkan. Dilengkapi dengan tempat tidur king size, TV LED 42 inch, dan kamar mandi mewah dengan bathtub.',
                'price' => 1500000,
                'capacity' => 2,
                'size' => '35 m²',
                'facilities' => json_encode(['AC', 'TV LED 42"', 'King Bed', 'Bathtub', 'WiFi Gratis', 'Mini Bar', 'Safe Box', 'Coffee Maker']), // ← UBAH INI
                'images' => json_encode(['rooms/deluxe-1.jpg', 'rooms/deluxe-2.jpg', 'rooms/deluxe-3.jpg']), // ← UBAH INI
                'rating' => 4.8,
                'reviews_count' => 45,
                'status' => 'available',
                'stock' => 10,
            ],
            [
                'name' => 'Executive Suite',
                'slug' => 'executive-suite',
                'description' => 'Suite eksklusif dengan ruang tamu terpisah dan area kerja yang nyaman. Sempurna untuk perjalanan bisnis maupun liburan keluarga.',
                'price' => 2500000,
                'capacity' => 3,
                'size' => '55 m²',
                'facilities' => json_encode(['AC', 'TV LED 55"', 'King Bed', 'Jacuzzi', 'WiFi Gratis', 'Mini Bar', 'Safe Box', 'Coffee Maker', 'Living Room', 'Work Desk']), // ← UBAH INI
                'images' => json_encode(['rooms/executive-1.jpg', 'rooms/executive-2.jpg', 'rooms/executive-3.jpg']), // ← UBAH INI
                'rating' => 4.9,
                'reviews_count' => 38,
                'status' => 'available',
                'stock' => 5,
            ],
            [
                'name' => 'Presidential Suite',
                'slug' => 'presidential-suite',
                'description' => 'Suite presidensial paling mewah dengan dua kamar tidur, ruang makan, dan balkon pribadi. Pengalaman menginap yang tak terlupakan.',
                'price' => 5000000,
                'capacity' => 4,
                'size' => '120 m²',
                'facilities' => json_encode(['AC', 'TV LED 65"', '2 King Beds', 'Jacuzzi', 'WiFi Gratis', 'Mini Bar', 'Safe Box', 'Coffee Maker', 'Living Room', 'Dining Room', 'Private Balcony', 'Butler Service']), // ← UBAH INI
                'images' => json_encode(['rooms/presidential-1.jpg', 'rooms/presidential-2.jpg', 'rooms/presidential-3.jpg']), // ← UBAH INI
                'rating' => 5.0,
                'reviews_count' => 12,
                'status' => 'available',
                'stock' => 2,
            ],
            [
                'name' => 'Superior Room',
                'slug' => 'superior-room',
                'description' => 'Kamar superior dengan desain modern dan fasilitas lengkap. Pilihan sempurna untuk perjalanan bisnis atau liburan singkat.',
                'price' => 1200000,
                'capacity' => 2,
                'size' => '30 m²',
                'facilities' => json_encode(['AC', 'TV LED 40"', 'Queen Bed', 'Shower', 'WiFi Gratis', 'Mini Bar', 'Safe Box']), // ← UBAH INI
                'images' => json_encode(['rooms/superior-1.jpg', 'rooms/superior-2.jpg']), // ← UBAH INI
                'rating' => 4.6,
                'reviews_count' => 67,
                'status' => 'available',
                'stock' => 15,
            ],
            [
                'name' => 'Family Suite',
                'slug' => 'family-suite',
                'description' => 'Suite keluarga dengan dua kamar tidur yang terhubung. Ideal untuk liburan keluarga dengan anak-anak.',
                'price' => 3000000,
                'capacity' => 5,
                'size' => '70 m²',
                'facilities' => json_encode(['AC', 'TV LED 50"', 'King Bed', 'Twin Beds', 'Bathtub', 'WiFi Gratis', 'Mini Bar', 'Safe Box', 'Coffee Maker', 'Kids Amenities']), // ← UBAH INI
                'images' => json_encode(['rooms/family-1.jpg', 'rooms/family-2.jpg', 'rooms/family-3.jpg']), // ← UBAH INI
                'rating' => 4.7,
                'reviews_count' => 29,
                'status' => 'available',
                'stock' => 8,
            ],
            [
                'name' => 'Standard Room',
                'slug' => 'standard-room',
                'description' => 'Kamar standard yang nyaman dengan fasilitas dasar lengkap. Harga terjangkau tanpa mengurangi kualitas.',
                'price' => 900000,
                'capacity' => 2,
                'size' => '25 m²',
                'facilities' => json_encode(['WiFi', 'AC', 'TV']), // ← SUDAH BENAR
                'images' => json_encode(['rooms/standard-1.jpg']), // ← SUDAH BENAR
                'rating' => 4.3,
                'reviews_count' => 89,
                'status' => 'available',
                'stock' => 20,
            ],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate(
                ['slug' => $room['slug']],
                $room
            );
        }
    }
}