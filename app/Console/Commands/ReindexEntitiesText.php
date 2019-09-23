<?php

namespace App\Console\Commands;

use App\Helpers\Timer;
use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ReindexEntitiesText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reindex:entitiesText {--forceAll} {--noPrompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex all entity files, to support full text search';

    /**
     * Create a new command instance.
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
        $this->info("Starting entities fullText reindex at ".date("Y-m-d H:i:s"));

        $forceAll = $this->option("forceAll");
        if ($forceAll) {
            $this->info("forceAll - All entities texts will be reindexed");
        } else {
            $this->info("Only marked entities texts will be reindexed");
        }

        $noPrompt = $this->option("noPrompt");

        if ($noPrompt || $this->confirm('Are you sure you wish to continue with text reindex', true)) {

            if ($forceAll) {
                $entities = Entity::all();
            } else {
                $entities = Entity::query()->where(["req_text_reindex" => 1])->get();
            }

            $this->info("Queued entity count: ".count($entities));

            $cnt = 0;
            foreach ($entities as $entity) {
                if (!$noPrompt) $this->info($entity["id"]);
                Artisan::call("reindex:entityText", ["entityId" => $entity["id"]]);
                $cnt++;
            }

            print_r(Timer::getResults());
            $this->info("All done! Entities text reindexed: {$cnt}");
        }
    }
}
