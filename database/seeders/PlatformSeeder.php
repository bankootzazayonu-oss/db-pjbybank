<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            ['Platform_ID' => 'P001', 'Platform_Name' => 'Netflix'],
            ['Platform_ID' => 'P002', 'Platform_Name' => 'Disney+ Hotstar'],
            ['Platform_ID' => 'P003', 'Platform_Name' => 'Prime Video'],
            ['Platform_ID' => 'P004', 'Platform_Name' => 'HBO GO'],
            ['Platform_ID' => 'P005', 'Platform_Name' => 'Apple TV+'],
            ['Platform_ID' => 'P006', 'Platform_Name' => 'TrueID'],
            ['Platform_ID' => 'P007', 'Platform_Name' => 'iQIYI'],
            ['Platform_ID' => 'P008', 'Platform_Name' => 'Viu'],
        ];

        foreach ($platforms as $p) {
            Platform::firstOrCreate(
                ['Platform_ID' => $p['Platform_ID']],
                ['Platform_Name' => $p['Platform_Name']]
            );
        }
    }
}
