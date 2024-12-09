<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        echo bcrypt('2024');
        // $this->call([
        //     JenisSeeder::class
        //     // UserSeeder::class
        // ]);
    }
}
