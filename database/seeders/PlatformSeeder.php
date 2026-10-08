<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            'Netflix',
            'Disney+ Hotstar',
            'Prime Video',
            'HBO GO',
            'Apple TV+',
            'TrueID',
            'iQIYI',
            'Viu',
        ];

        foreach ($platforms as $name) {
            Platform::firstOrCreate(['name' => $name]);
        }
    }
}
