<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{

    public function run(): void
    {
        Service::insert([
            ['title' => 'Магазин'],
            ['title' => 'Доставка'],
            ['title' => 'Приложение']
        ]);
    }
}
