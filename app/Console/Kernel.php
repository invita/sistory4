<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ReindexRecreate::class,
        Commands\ReindexEntity::class,
        Commands\ReindexEntities::class,
        Commands\ReindexEntityText::class,
        Commands\ReindexEntitiesText::class,
        Commands\ThumbsCreate::class,
        Commands\ThumbsCreateAll::class,
        Commands\EntityTestElasticConvert::class,
        Commands\Si4MigrateDatabase::class,
        Commands\Si4NumSeq::class,
        Commands\Si4MappingReseed::class,
        Commands\Si4FieldsReseed::class,
        Commands\LangDbToFile::class,
        Commands\LangFileToDb::class,
        Commands\TestTest::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
