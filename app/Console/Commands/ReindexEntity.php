<?php

namespace App\Console\Commands;

use App\Helpers\ElasticHelpers;
use App\Models\Entity;
use Illuminate\Console\Command;
use Symfony\Component\Config\Definition\Exception\Exception;

class ReindexEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reindex:entity {entityId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex a single entity';

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
        $entityId = $this->argument('entityId');
        $this->info("Indexing entity {$entityId}");

        $entity = Entity::find($entityId);
        if ($entity) {
            $entityXmlParsed = $entity->dataToObject();
            $indexBody = [
                "id" => $entityId,
                "entity_type_id" => $entity["entity_type_id"],
                "xml" => $entity["data"],
                "data" => $entityXmlParsed
            ];
            ElasticHelpers::indexEntity($entityId, $indexBody);
        } else {
            ElasticHelpers::deleteEntity($entityId);
        }

        //print_r($entity);
        //$this->info("Indexing {$entity["entity_type_id"]->toArray()}");


    }
}
