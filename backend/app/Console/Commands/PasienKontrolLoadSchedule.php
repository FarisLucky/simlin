<?php

namespace App\Console\Commands;

use App\Services\KontrolService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PasienKontrolLoadSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Setiap jam 12 PM akan melakukan load untuk kontrol pasien berikutnya
     *
     * @var string
     */
    protected $signature = 'pasien:load-kontrol';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load Pasien Kontrol RSGS';

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
        KontrolService::insertKontrolRajalTemp(now()->addDay());

        Log::info('RUN LOAD PASIEN KONTROL');

        return 'BERHASIL LOAD PASIEN KONTROL';
    }
}
