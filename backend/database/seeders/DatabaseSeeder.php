<?php

namespace Database\Seeders;

use App\Models\Mutu;
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
        // echo bcrypt('2024');

        // $mutus = Mutu::with('daftar')->chunkById(100, function ($records) {
        //     foreach ($records as $record) {
        //         // Process record
        //         $mutu = Mutu::where('id', $record->id)->first();
        //         $mutu->update([
        //             'tgl_daftar' => \Carbon\Carbon::make($record->daftar->pengajuan)->format('Y-m-d')
        //         ]);
        //     }
        // });

        // $this->call([
        //     JenisSeeder::class
        //     // UserSeeder::class
        // ]);
    }
}
