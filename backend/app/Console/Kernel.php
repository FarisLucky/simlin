<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('pasien:ultah')->everyTwoMinutes();
       // $schedule->command('pasien:ultah')->hourlyAt(15);
        // $iteration = UcapanService::iteration();
        // for ($i=0; $i < $iteration; $i++) {
        //     if($i < 1){
        //     }
        //     $schedule->command('pasien:ultah')
        //     ->everyTwoHours();
        // }
        // throw new \Exception('selesai');
        // $schedule->command('pasien:kontrol-kembali');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
