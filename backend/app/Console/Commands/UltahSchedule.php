<?php

namespace App\Console\Commands;

use App\Services\UcapanService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UltahSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pasien:ultah';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengirimkan ucapan ultah ke pasien RSGS';

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
        UcapanService::store();

        Log::info('RUN SCHEDULING KIRIM UCAPAN ULTAH');

        return 'BERHASIL MENGIRIM UCAPAN ULTAH';
    }
}
