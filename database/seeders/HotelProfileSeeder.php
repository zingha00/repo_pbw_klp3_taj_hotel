<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HotelProfile;

class HotelProfileSeeder extends Seeder
{
    public function run(): void
    {
        HotelProfile::create([
            'name' => 'Grand Azure Hotel',
            'description' => 'Hotel bintang lima dengan layanan terbaik dan fasilitas mewah di pusat kota Jakarta.',
            'address' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta',
            'phone' => '+62 21 1234 5678',
            'email' => 'info@grandazure.com',
            'website' => 'https://grandazure.com',
            'check_in_time' => '14:00',
            'check_out_time' => '12:00',
            'cancellation_policy' => 'Pembatalan gratis hingga 24 jam sebelum check-in. Pembatalan kurang dari 24 jam akan dikenakan biaya 50% dari total harga.',
        ]);
    }
}