<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NationalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $nationalities = [
            ['nationality_code' => 'EGY', 'nationality_name' => 'Egyptian', 'country_code' => 'EG', 'country_name' => 'Egypt', 'flag_emoji' => '🇪🇬', 'currency_code' => 'EGP'],
            ['nationality_code' => 'SAU', 'nationality_name' => 'Saudi', 'country_code' => 'SA', 'country_name' => 'Saudi Arabia', 'flag_emoji' => '🇸🇦', 'currency_code' => 'SAR'],
            ['nationality_code' => 'ARE', 'nationality_name' => 'Emirati', 'country_code' => 'AE', 'country_name' => 'United Arab Emirates', 'flag_emoji' => '🇦🇪', 'currency_code' => 'AED'],
            ['nationality_code' => 'USA', 'nationality_name' => 'American', 'country_code' => 'US', 'country_name' => 'United States', 'flag_emoji' => '🇺🇸', 'currency_code' => 'USD'],
            ['nationality_code' => 'CHN', 'nationality_name' => 'Chinese', 'country_code' => 'CN', 'country_name' => 'China', 'flag_emoji' => '🇨🇳', 'currency_code' => 'CNY'],
            ['nationality_code' => 'IND', 'nationality_name' => 'Indian', 'country_code' => 'IN', 'country_name' => 'India', 'flag_emoji' => '🇮🇳', 'currency_code' => 'INR'],
            ['nationality_code' => 'DNK', 'nationality_name' => 'Danish', 'country_code' => 'DK', 'country_name' => 'Denmark', 'flag_emoji' => '🇩🇰', 'currency_code' => 'DKK'],
            ['nationality_code' => 'GBR', 'nationality_name' => 'British', 'country_code' => 'GB', 'country_name' => 'United Kingdom', 'flag_emoji' => '🇬🇧', 'currency_code' => 'GBP'],
            ['nationality_code' => 'DEU', 'nationality_name' => 'German', 'country_code' => 'DE', 'country_name' => 'Germany', 'flag_emoji' => '🇩🇪', 'currency_code' => 'EUR'],
            ['nationality_code' => 'FRA', 'nationality_name' => 'French', 'country_code' => 'FR', 'country_name' => 'France', 'flag_emoji' => '🇫🇷', 'currency_code' => 'EUR'],
        ];

        foreach ($nationalities as $nationality) {
            DB::table('nationalities')->insert(array_merge($nationality, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }
}
