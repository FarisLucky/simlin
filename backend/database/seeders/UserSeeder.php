<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert(
            [
                [
                    'name' => 'anak',
                    'username' => 'anak',
                    'password' => bcrypt('rsgs@321'),
                    'role' => 'UNIT',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'vk',
                    'username' => 'vk',
                    'password' => bcrypt('rsgs@321'),
                    'role' => 'UNIT',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'general',
                    'username' => 'general',
                    'password' => bcrypt('rsgs@321'),
                    'role' => 'UNIT',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'igd',
                    'username' => 'igd',
                    'password' => bcrypt('rsgs@321'),
                    'role' => 'UNIT',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'maternal',
                    'username' => 'maternal',
                    'password' => bcrypt('rsgs@321'),
                    'role' => 'UNIT',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'neo',
                    'username' => 'neo',
                    'password' => bcrypt('rsgs@321'),
                    'role' => 'UNIT',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'pav',
                    'username' => 'pav',
                    'password' => bcrypt('rsgs@321'),
                    'role' => 'UNIT',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'polianak',
                    'username' => 'polianak',
                    'password' => bcrypt('rsgs@321'),
                    'role' => 'UNIT',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'polist',
                    'username' => 'polist',
                    'password' => bcrypt('rsgs@321'),
                    'role' => 'UNIT',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'name' => 'polisp',
                    'username' => 'polisp',
                    'password' => bcrypt('rsgs@321'),
                    'role' => 'UNIT',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]
        );
    }
}
