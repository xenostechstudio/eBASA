<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Indonesian-style full names for more realistic demo data
        $names = [
            'Andi Pratama',
            'Siti Rahmawati',
            'Budi Santoso',
            'Rina Wulandari',
            'Dewi Lestari',
            'Agus Setiawan',
            'Fitri Handayani',
            'Rizky Maulana',
            'Nur Aisyah',
            'Hendra Kurniawan',
            'Lia Kartika',
            'Fajar Ramadhan',
            'Yuni Safitri',
            'Arif Budiman',
            'Intan Permata',
            'Yoga Saputra',
            'Mega Sari',
            'Faisal Akbar',
            'Novi Cahya',
            'Rudi Hartono',
            'Tia Anggraini',
            'Iqbal Prakoso',
            'Mira Yuliani',
            'Dani Firmansyah',
            'Sari Kusuma',
            'Bayu Nugraha',
            'Dian Puspitasari',
            'Eko Wahyudi',
            'Maya Sari',
            'Galih Pradana',
            'Rizka Amelia',
            'Hadi Saputra',
            'Nadia Putri',
            'Yusuf Hidayat',
            'Citra Dewi',
            'Fajar Hidayat',
            'Ayu Lestari',
            'Rangga Pratama',
            'Nisa Khairunisa',
            'Rama Wijaya',
            'Dewi Anggraini',
            'Dimas Prakoso',
            'Salsa Aprilia',
            'Iqra Kurnia',
            'Gilang Ramdhan',
            'Sri Wahyuni',
            'Rafi Setiawan',
            'Wulan Sari',
            'Ilham Maulana',
            'Putri Ayuningtyas',
            'Taufik Hidayat',
            'Mega Rachmawati',
            'Rio Saputra',
            'Nurul Fadilah',
            'Zaki Pratama',
            'Bella Safitri',
            'Adi Nugroho',
            'Yuli Astuti',
        ];

        foreach ($names as $index => $name) {
            $baseSlug = Str::slug($name);
            $number   = $index + 1;

            User::factory()->create([
                'name' => $name,
                'email' => $baseSlug.'_'.$number.'@example.test',
                // Roughly half verified, half pending
                'email_verified_at' => $number % 2 === 0 ? now() : null,
            ]);
        }
    }
}
