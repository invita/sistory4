<?php

namespace App\Console\Commands;

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
    protected $signature = 'thumbs:createAll {method=imagick}';

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
        $method = $this->argument('method');
        $this->info("Using thumb generation method: ".$method);

        if ($this->confirm('Are you sure you wish to recreate all thumbnails?', true)) {

            $entities = Entity::all();
            $successCnt = 0;
            $errorCnt = 0;
            foreach ($entities as $entity) {
                $this->info($entity["id"]);
                try {
                    Artisan::call("thumbs:create", ["entityId" => $entity["id"], "method" => $method]);
                    $successCnt++;
                } catch (\Exception $e) {
                    $this->warn($e);
                    $errorCnt++;
                }
            }

            $this->info("Thumbnails recreated: {$successCnt}");
            $this->info("Errors: {$errorCnt}");
            $this->info("All done!");
        }

    }
}
