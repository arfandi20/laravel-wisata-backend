<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //User Factory
        User::factory(100)->create();

        User::factory()->create([
            'name' => 'Arfandi',
            'email' => 'arfandi@gmail.com',
            'password' => Hash::make('12345678'),
        ]);

        //Category factory
        Category::factory(5)->create();

        //Product factory
        Product::factory(100)->create();
    }
}
