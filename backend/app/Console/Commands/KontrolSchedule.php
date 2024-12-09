<?php

namespace App\Console\Commands;

use App\Services\KontrolService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class KontrolSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * DiJalankan jam 12 PM untuk pasien kontrol hari berikutnya
     *
     * @var string
     */
    protected $signature = 'pasien:kontrol-kembali';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirimkan pengingat kontrol ke pasien RSGS';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        KontrolService::store();

        Log::info('RUN SCHEDULING KIRIM PENGINGAT KONTROL');

        return 'BERHASIL MENGIRIM PENGINGAT KONTROL';
    }
}
