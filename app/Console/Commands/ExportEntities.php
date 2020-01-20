<?php

namespace App\Console\Commands;

use App\Helpers\ElasticHelpers;
use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Config\Definition\Exception\Exception;

class ExportEntities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:entities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export entities from database to filesystem';

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
        if ($this->confirm('Are you sure you wish to export all entities to filesystem?', true)) {

            $entities = Entity::all();
            $cnt = 0;
            $errCnt = 0;
            foreach ($entities as $entity) {
                $this->info($entity["id"]);

                try {
                    $entity->backupXml();
                    $cnt++;
                } catch (\Exception $e) {
                    $this->error($e);
                    $errCnt++;
                }
            }

            $this->info("All done! Entities exported: {$cnt}");
            $this->info("Error count: {$errCnt}");
        }
    }
}
