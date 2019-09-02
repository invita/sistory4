<?php

namespace App\Console\Commands;

use App\Helpers\ElasticHelpers;
use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Config\Definition\Exception\Exception;

class ReindexCleanEntities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reindex:cleanEntities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean all the indexed documents';

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

        if ($this->confirm('Are you sure you wish to clean up all indexed entities?', true)) {

            $entities = Entity::all();
            $cnt = 0;
            $errCnt = 0;
            foreach ($entities as $entity) {
                $this->info($entity["id"]);

                try {
                    Artisan::call("reindex:cleanEntity", ["entityId" => $entity["id"]]);
                    $cnt++;
                } catch (\Exception $e) {
                    $this->error($e);
                    $errCnt++;
                }
            }

            $this->info("All done! Entities cleaned: {$cnt}");
            $this->info("Error count: {$errCnt}");

        }
    }
}
