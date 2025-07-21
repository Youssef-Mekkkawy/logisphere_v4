<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => 'United Arab Emirates', 'code' => 'ARE', 'iso_code' => 'AE', 'phone_code' => '+971', 'currency' => 'AED'],
            ['name' => 'Saudi Arabia', 'code' => 'SAU', 'iso_code' => 'SA', 'phone_code' => '+966', 'currency' => 'SAR'],
            ['name' => 'Egypt', 'code' => 'EGY', 'iso_code' => 'EG', 'phone_code' => '+20', 'currency' => 'EGP'],
            ['name' => 'Qatar', 'code' => 'QAT', 'iso_code' => 'QA', 'phone_code' => '+974', 'currency' => 'QAR'],
            ['name' => 'Kuwait', 'code' => 'KWT', 'iso_code' => 'KW', 'phone_code' => '+965', 'currency' => 'KWD'],
            ['name' => 'Bahrain', 'code' => 'BHR', 'iso_code' => 'BH', 'phone_code' => '+973', 'currency' => 'BHD'],
            ['name' => 'Oman', 'code' => 'OMN', 'iso_code' => 'OM', 'phone_code' => '+968', 'currency' => 'OMR'],
            ['name' => 'Jordan', 'code' => 'JOR', 'iso_code' => 'JO', 'phone_code' => '+962', 'currency' => 'JOD'],
            ['name' => 'Lebanon', 'code' => 'LBN', 'iso_code' => 'LB', 'phone_code' => '+961', 'currency' => 'LBP'],
            ['name' => 'Syria', 'code' => 'SYR', 'iso_code' => 'SY', 'phone_code' => '+963', 'currency' => 'SYP'],
            ['name' => 'Iraq', 'code' => 'IRQ', 'iso_code' => 'IQ', 'phone_code' => '+964', 'currency' => 'IQD'],
            ['name' => 'Iran', 'code' => 'IRN', 'iso_code' => 'IR', 'phone_code' => '+98', 'currency' => 'IRR'],
            ['name' => 'Turkey', 'code' => 'TUR', 'iso_code' => 'TR', 'phone_code' => '+90', 'currency' => 'TRY'],
            ['name' => 'United States', 'code' => 'USA', 'iso_code' => 'US', 'phone_code' => '+1', 'currency' => 'USD'],
            ['name' => 'United Kingdom', 'code' => 'GBR', 'iso_code' => 'GB', 'phone_code' => '+44', 'currency' => 'GBP'],
            ['name' => 'Germany', 'code' => 'DEU', 'iso_code' => 'DE', 'phone_code' => '+49', 'currency' => 'EUR'],
            ['name' => 'France', 'code' => 'FRA', 'iso_code' => 'FR', 'phone_code' => '+33', 'currency' => 'EUR'],
            ['name' => 'Italy', 'code' => 'ITA', 'iso_code' => 'IT', 'phone_code' => '+39', 'currency' => 'EUR'],
            ['name' => 'Spain', 'code' => 'ESP', 'iso_code' => 'ES', 'phone_code' => '+34', 'currency' => 'EUR'],
            ['name' => 'Netherlands', 'code' => 'NLD', 'iso_code' => 'NL', 'phone_code' => '+31', 'currency' => 'EUR'],
            ['name' => 'Belgium', 'code' => 'BEL', 'iso_code' => 'BE', 'phone_code' => '+32', 'currency' => 'EUR'],
            ['name' => 'China', 'code' => 'CHN', 'iso_code' => 'CN', 'phone_code' => '+86', 'currency' => 'CNY'],
            ['name' => 'Japan', 'code' => 'JPN', 'iso_code' => 'JP', 'phone_code' => '+81', 'currency' => 'JPY'],
            ['name' => 'South Korea', 'code' => 'KOR', 'iso_code' => 'KR', 'phone_code' => '+82', 'currency' => 'KRW'],
            ['name' => 'Singapore', 'code' => 'SGP', 'iso_code' => 'SG', 'phone_code' => '+65', 'currency' => 'SGD'],
            ['name' => 'Malaysia', 'code' => 'MYS', 'iso_code' => 'MY', 'phone_code' => '+60', 'currency' => 'MYR'],
            ['name' => 'Thailand', 'code' => 'THA', 'iso_code' => 'TH', 'phone_code' => '+66', 'currency' => 'THB'],
            ['name' => 'Indonesia', 'code' => 'IDN', 'iso_code' => 'ID', 'phone_code' => '+62', 'currency' => 'IDR'],
            ['name' => 'Philippines', 'code' => 'PHL', 'iso_code' => 'PH', 'phone_code' => '+63', 'currency' => 'PHP'],
            ['name' => 'Vietnam', 'code' => 'VNM', 'iso_code' => 'VN', 'phone_code' => '+84', 'currency' => 'VND'],
            ['name' => 'India', 'code' => 'IND', 'iso_code' => 'IN', 'phone_code' => '+91', 'currency' => 'INR'],
            ['name' => 'Pakistan', 'code' => 'PAK', 'iso_code' => 'PK', 'phone_code' => '+92', 'currency' => 'PKR'],
            ['name' => 'Bangladesh', 'code' => 'BGD', 'iso_code' => 'BD', 'phone_code' => '+880', 'currency' => 'BDT'],
            ['name' => 'Sri Lanka', 'code' => 'LKA', 'iso_code' => 'LK', 'phone_code' => '+94', 'currency' => 'LKR'],
            ['name' => 'Australia', 'code' => 'AUS', 'iso_code' => 'AU', 'phone_code' => '+61', 'currency' => 'AUD'],
            ['name' => 'New Zealand', 'code' => 'NZL', 'iso_code' => 'NZ', 'phone_code' => '+64', 'currency' => 'NZD'],
            ['name' => 'Canada', 'code' => 'CAN', 'iso_code' => 'CA', 'phone_code' => '+1', 'currency' => 'CAD'],
            ['name' => 'Mexico', 'code' => 'MEX', 'iso_code' => 'MX', 'phone_code' => '+52', 'currency' => 'MXN'],
            ['name' => 'Brazil', 'code' => 'BRA', 'iso_code' => 'BR', 'phone_code' => '+55', 'currency' => 'BRL'],
            ['name' => 'Argentina', 'code' => 'ARG', 'iso_code' => 'AR', 'phone_code' => '+54', 'currency' => 'ARS'],
            ['name' => 'South Africa', 'code' => 'ZAF', 'iso_code' => 'ZA', 'phone_code' => '+27', 'currency' => 'ZAR'],
            ['name' => 'Nigeria', 'code' => 'NGA', 'iso_code' => 'NG', 'phone_code' => '+234', 'currency' => 'NGN'],
            ['name' => 'Kenya', 'code' => 'KEN', 'iso_code' => 'KE', 'phone_code' => '+254', 'currency' => 'KES'],
            ['name' => 'Morocco', 'code' => 'MAR', 'iso_code' => 'MA', 'phone_code' => '+212', 'currency' => 'MAD'],
            ['name' => 'Tunisia', 'code' => 'TUN', 'iso_code' => 'TN', 'phone_code' => '+216', 'currency' => 'TND'],
            ['name' => 'Algeria', 'code' => 'DZA', 'iso_code' => 'DZ', 'phone_code' => '+213', 'currency' => 'DZD'],
            ['name' => 'Libya', 'code' => 'LBY', 'iso_code' => 'LY', 'phone_code' => '+218', 'currency' => 'LYD'],
            ['name' => 'Sudan', 'code' => 'SDN', 'iso_code' => 'SD', 'phone_code' => '+249', 'currency' => 'SDG'],
            ['name' => 'Ethiopia', 'code' => 'ETH', 'iso_code' => 'ET', 'phone_code' => '+251', 'currency' => 'ETB'],
            ['name' => 'Russia', 'code' => 'RUS', 'iso_code' => 'RU', 'phone_code' => '+7', 'currency' => 'RUB'],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(
                ['name' => $country['name']],
                array_merge($country, ['status' => 'Active'])
            );
        }
    }
}
