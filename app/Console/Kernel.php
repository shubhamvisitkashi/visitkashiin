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
        // Auto-convert newly uploaded images to WebP every hour
        $schedule->command('images:webp --quality=82')->hourly()->withoutOverlapping();
        // Re-minify CSS/JS assets daily (after any manual edits)
        $schedule->command('assets:minify')->daily()->withoutOverlapping();
        // NOTE: route:cache is intentionally NOT scheduled — this project uses
        // wildcard catch-all routes (/{slug}, /{a}/{b}) that conflict with route caching.
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
