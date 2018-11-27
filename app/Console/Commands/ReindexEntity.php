<?php

namespace App\Console\Commands;

use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\TikaParseDoc;
use App\Helpers\Timer;
use App\Models\Elastic\EntityElastic;
use App\Models\Entity;
use App\Models\Si4\MetsToSi4;
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

            Timer::start("entityMapping");
            $metsToSi4 = new MetsToSi4($entity->xml);
            $si4Data = $metsToSi4->run();
            Timer::stop("entityMapping");

            $indexBody = [
                "id" => $entity->id,
                "handle_id" => $entity->handle_id,
                "parent" => $entity->parent,
                "primary" => $entity->primary,
                "collection" => $entity->collection,
                "struct_type" => $entity->struct_type,
                "struct_type_sort" => DcHelpers::getStructTypeSortValue($entity->struct_type),
                "struct_subtype" => $entity->struct_subtype,
                "entity_type" => $entity->entity_type,
                "child_order" => $entity->child_order,
                "xml" => $entity->xml,
                "active" => $entity->active,
                "req_text_reindex" => $entity->req_text_reindex,
                "data" => $si4Data,
            ];

            Timer::start("elasticIndex");
            ElasticHelpers::indexEntity($entityId, $indexBody);
            Timer::stop("elasticIndex");



            /*
            $entityXmlParsed = $entity->dataToObject();
            //print_r($entityXmlParsed);

            // Keep fullText attributes for files if they exist
            $prevElasticData = ElasticHelpers::plainEntityById($entityId);
            $prevElasticSource = null;
            if (isset($prevElasticData["hits"]) &&
                isset($prevElasticData["hits"]["hits"]) &&
                isset($prevElasticData["hits"]["hits"][0]))
            {
                $prevElasticSource = $prevElasticData["hits"]["hits"][0]["_source"];
            }

            Timer::start("entityMapping");
            $entityElastic = new EntityElastic($entity, $entityXmlParsed, $prevElasticSource);
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
                "child_order" => $entity["child_order"],
                "active" => $entity["active"],
                "xml" => $entity["data"],
                "data" => $entityElastic->getData()
            ];
            Timer::start("elasticIndex");
            ElasticHelpers::indexEntity($entityId, $indexBody);
            Timer::stop("elasticIndex");
            */

            // TODO: Mark entity for fullText reindex
            // Set db entity req_text_reindex

        } else {
            Timer::start("elasticIndex");
            if (ElasticHelpers::entityExists($entityId)) {
                ElasticHelpers::deleteEntity($entityId);
            }
            Timer::stop("elasticIndex");
        }


        //print_r($entity);

        //print_r(Timer::getResults());

    }
}
