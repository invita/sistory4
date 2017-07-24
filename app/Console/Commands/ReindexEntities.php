<?php

namespace App\Console\Commands;

use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Config\Definition\Exception\Exception;

class ReindexEntities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reindex:entities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex entities from database into elastic';

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

        if ($this->confirm('Are you sure you wish to reindex all entities?', true)) {
            $entities = Entity::all();
            $cnt = 0;
            foreach ($entities as $entity) {
                $this->info($entity["id"]);
                Artisan::call("reindex:entity", ["entityId" => $entity["id"]]);
                $cnt++;
            }
            $this->info("All done! Entities reindexed: {$cnt}");
        }
    }
}
