<?php

namespace App\Console\Commands;

use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\Timer;
use App\Models\Elastic\EntityElastic;
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
            //print_r($entityXmlParsed);

            Timer::start("entityMapping");
            $entityElastic = new EntityElastic($entityXmlParsed);
            Timer::stop("entityMapping");

            $indexBody = [
                "id" => $entityId,
                "handle_id" => $entity["handle_id"],
                "parent" => $entity["parent"],
                "primary" => $entity["primary"],
                "collection" => $entity["collection"],
                "struct_type" => $entity["struct_type"],
                "struct_type_sort" => DcHelpers::getStructTypeSortValue($entity["struct_type"]),
                "struct_subtype" => $entity["struct_subtype"],
                "entity_type" => $entity["entity_type"],
                "active" => $entity["active"],
                "xml" => $entity["data"],
                "data" => $entityElastic->getData()
            ];
            Timer::start("elasticIndex");
            ElasticHelpers::indexEntity($entityId, $indexBody);
            Timer::stop("elasticIndex");
        } else {
            Timer::start("elasticIndex");
            if (ElasticHelpers::entityExists($entityId)) {
                ElasticHelpers::deleteEntity($entityId);
            }
            Timer::stop("elasticIndex");
        }

        //print_r($entity);


    }
}
