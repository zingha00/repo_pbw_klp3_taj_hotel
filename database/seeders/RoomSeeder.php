<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Single Room',
                'room_number' => '101',
                'description' => 'Kamar nyaman untuk 1 orang dengan tempat tidur single, AC, TV, dan WiFi gratis. Cocok untuk solo traveler atau business trip.',
                'price' => 300000,
                'capacity' => 1,
                'type' => 'single',
                'image' => 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=600',
                'status' => 'available',
                'available' => true
            ],
            [
                'name' => 'Double Room',
                'room_number' => '102',
                'description' => 'Kamar luas untuk 2 orang dengan tempat tidur queen size, AC, TV kabel, minibar, dan balkon pribadi.',
                'price' => 700000,
                'capacity' => 2,
                'type' => 'double',
                'image' => 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=600',
                'status' => 'available',
                'available' => true
            ],
            [
                'name' => 'Deluxe Room',
                'room_number' => '103',
                'description' => 'Kamar premium dengan fasilitas lengkap termasuk sofa, meja kerja, bathtub, dan pemandangan kota yang indah.',
                'price' => 1400000,
                'capacity' => 2,
                'type' => 'suite',
                'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=600',
                'status' => 'available',
                'available' => true
            ],
            [
                'name' => 'Superior Room',
                'room_number' => '104',
                'description' => 'Kamar superior untuk 2 orang dengan ruang yang lebih luas, sofa bed tambahan, dan akses ke executive lounge.',
                'price' => 2200000,
                'capacity' => 2,
                'type' => 'suite',
                'image' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=600',
                'status' => 'available',
                'available' => true
            ],
            [
                'name' => 'Couple Room',
                'room_number' => '105',
                'description' => 'Kamar romantis untuk pasangan dengan dekorasi elegan, bathtub jacuzzi, dan pemandangan laut yang menakjubkan.',
                'price' => 2300000,
                'capacity' => 2,
                'type' => 'couple',
                'image' => 'https://images.unsplash.com/photo-1566665797739-1674de7a421a?w=600',
                'status' => 'available',
                'available' => true
            ],
            [
                'name' => 'Luxury Room',
                'room_number' => '106',
                'description' => 'Kamar mewah dengan fasilitas premium, ruang tamu terpisah, pantry, dan pemandangan laut 180 derajat.',
                'price' => 2100000,
                'capacity' => 2,
                'type' => 'luxury',
                'image' => 'https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?w=600',
                'status' => 'available',
                'available' => true
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}