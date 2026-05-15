<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            ['slug' => 'dona',    'name' => ['uz' => 'Dona',    'en' => 'Piece',   'tr' => 'Adet']],
            ['slug' => 'porsiya', 'name' => ['uz' => 'Porsiya', 'en' => 'Portion', 'tr' => 'Porsiyon']],
            ['slug' => 'kg',      'name' => ['uz' => 'Kg',      'en' => 'Kg',      'tr' => 'Kg']],
            ['slug' => 'gramm',   'name' => ['uz' => 'Gramm',   'en' => 'Gram',    'tr' => 'Gram']],
            ['slug' => 'litr',    'name' => ['uz' => 'Litr',    'en' => 'Litre',   'tr' => 'Litre']],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(['slug' => $unit['slug']], array_merge($unit, ['status' => 1]));
        }
    }
}
