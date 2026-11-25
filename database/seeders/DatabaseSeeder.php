<?php

namespace Database\Seeders;

use App\Models\ShortLink;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        ShortLink::updateOrCreate(
            ['code' => 'umkm01'],
            [
                'original_url' => 'https://example.com/katalog-umkm',
                'is_active' => true,
                'expires_at' => null,
            ]
        );
    }
}