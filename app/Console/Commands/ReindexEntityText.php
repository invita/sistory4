<?php

namespace App\Console\Commands;

use App\Helpers\ElasticHelpers;
use App\Helpers\Timer;
use App\Models\Elastic\EntityElastic;
use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ReindexEntityText extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reindex:entityText {entityId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex single entity files to support full text search';

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
        $entityId = $this->argument('entityId');
        $this->info("Indexing entity texts for {$entityId}");

        $entity = Entity::find($entityId);
        if (!$entity) {
            if (ElasticHelpers::entityExists($entityId)) {
                $this->info("Entity not in DB, but found in elastic index. Removing from index: entityId={$entityId}");
                ElasticHelpers::deleteEntity($entityId);
            } else {
                $this->info("Entity not in DB, not in elastic index either. Nothing to do. entityId={$entityId}");
            }
            return;
        }

        $this->info("Indexing basic entity.");
        Artisan::call("reindex:entity", ["entityId" => $entityId]);
        ElasticHelpers::refreshIndex();

        $elasticData = ElasticHelpers::plainEntityById($entityId);
        if (!isset($elasticData["hits"]) ||
            !isset($elasticData["hits"]["hits"]) ||
            !isset($elasticData["hits"]["hits"][0]))
        {
            $this->warn("Entity not found in elastic index: {$entityId}");
            return;
        }

        $elasticSource = $elasticData["hits"]["hits"][0]["_source"];

        $this->info("Extracting text from files...");
        Timer::start("fileTextExtraction");
        $tikaStatus = EntityElastic::extractTextFromFiles($entity, $elasticSource);
        Timer::stop("fileTextExtraction");

        if (!$tikaStatus) {
            $this->info("No files or text. Nothing to do.");
            $entity->req_text_reindex = false;
            $entity->save();
            return;
        }

        Timer::start("elasticIndex");
        ElasticHelpers::indexEntity($entityId, $elasticSource);
        Timer::stop("elasticIndex");

        $entity->req_text_reindex = false;
        $entity->save();

        //print_r(Timer::getResults());
        $this->info("All done for entity {$entityId}");

    }

}
