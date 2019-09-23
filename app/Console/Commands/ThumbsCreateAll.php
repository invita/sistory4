<?php

namespace App\Console\Commands;

use App\Helpers\Timer;
use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ThumbsCreateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbs:createAll {method=iiif} {--forceAll} {--noPrompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create thumbs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("--------------------");
        date_default_timezone_set('Europe/Ljubljana');
        $this->info("Starting thumb generation at ".date("Y-m-d H:i:s"));

        $method = $this->argument('method');
        $this->info("Using thumb generation method: ".$method);

        $forceAll = $this->option("forceAll");
        if ($forceAll) {
            $this->info("forceAll - All entities' thumbs will be regenerated!");
            $this->warn("This may take a lot of time!");
        } else {
            $this->info("Only require-thumb-generation marked entities' thumbs will be created");
        }

        $noPrompt = $this->option("noPrompt");

        if ($noPrompt || $this->confirm('Are you sure you wish to recreate thumbnails?', true)) {

            if ($forceAll) {
                $entities = Entity::all();
            } else {
                $entities = Entity::query()->where(["req_thumb_regen" => 1])->get();
            }

            $successCnt = 0;
            $errorCnt = 0;
            foreach ($entities as $entity) {
                if (!$noPrompt) $this->info($entity["id"]);
                try {
                    Artisan::call("thumbs:create", ["entityId" => $entity["id"], "method" => $method]);
                    $successCnt++;
                } catch (\Exception $e) {
                    $this->info("Entity: ".$entity["id"]);
                    $this->warn($e);
                    $errorCnt++;
                }
            }

            //print_r(Timer::getResults());
            $this->info("Thumbnails recreated: {$successCnt}");
            $this->info("Errors: {$errorCnt}");
            $this->info("All done!");
        }

    }
}
