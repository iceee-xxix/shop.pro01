<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Config::firstOrCreate(
            [
                'name' => 'ร้านค้าออนไลน์',
                'color1' => '#1479eb',
                'color2' => '#84d2f8',
                'color_font' => '#f0f0f0',
                'color_category' => '#f0f0f0',
            ]
        );
    }
}
