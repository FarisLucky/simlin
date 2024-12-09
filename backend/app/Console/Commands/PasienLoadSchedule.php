<?php

namespace App\Console\Commands;

use App\Services\UcapanService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PasienLoadSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pasien:load';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load Pasien RSGS';

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
        UcapanService::insertTemp(now());

        Log::info('RUN LOAD PASIEN ULTAH');

        return 'BERHASIL LOAD PASIEN ULTAH';
    }
}
