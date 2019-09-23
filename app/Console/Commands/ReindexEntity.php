<?php

namespace App\Console\Commands;

use App\Helpers\DbHierarchyHelpers;
use App\Helpers\DcHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\Si4Util;
use App\Helpers\Timer;
use App\Models\Entity;
use App\Models\Si4\MetsToSi4;
use Illuminate\Console\Command;

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

            Timer::start("keepIndexedFullText");
            // Keep fullText attributes for files if they exist
            $prevElasticData = ElasticHelpers::plainEntityById($entityId);
            $prevFullText = Si4Util::pathArg($prevElasticData, "hits/hits/0/_source/data/files/0/fullText", "");
            Timer::stop("keepIndexedFullText");

            Timer::start("entityMapping");
            $metsToSi4 = new MetsToSi4($entity->xml);
            if ($prevFullText) $metsToSi4->setFullText($prevFullText);
            $si4Data = $metsToSi4->run();

            //print_r($si4Data);
            Timer::stop("entityMapping");

            Timer::start("entityParentHierarchy");
            $hierarchy = DbHierarchyHelpers::getParentHierarchyHandles($entity->handle_id);
            Timer::stop("entityParentHierarchy");

            $indexBody = [
                "id" => $entity->id,
                "handle_id" => $entity->handle_id,
                "parent" => $entity->parent,
                "hierarchy" => $hierarchy,
                "primary" => $entity->primary,
                "collection" => $entity->collection,
                "struct_type" => $entity->struct_type,
                "struct_type_sort" => self::getStructTypeSortValue($entity->struct_type),
                "struct_subtype" => $entity->struct_subtype,
                "entity_type" => $entity->entity_type,
                "child_order" => $entity->child_order,
                "xml" => $entity->xml,
                "active" => $entity->active,
                "req_text_reindex" => $entity->req_text_reindex,
                "req_thumb_regen" => $entity->req_thumb_regen,
                "data" => $si4Data,
            ];

            //print_r($si4Data);

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
                "struct_type_sort" => self::getStructTypeSortValue($entity["struct_type"]),
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

    public static function getStructTypeSortValue($structType) {
        switch($structType) {
            case "collection": return 30;
            case "entity": return 20;
            case "file": return 10;
            default: return 0;
        }
    }

}
