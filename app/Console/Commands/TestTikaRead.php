<?php

namespace App\Console\Commands;

use App\Helpers\ElasticHelpers;
use App\Helpers\Si4Util;
use App\Helpers\TikaParseDoc;
use App\Models\Elastic\EntityElastic;
use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TestTikaRead extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:tikaRead {entityId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Apache Tika read';

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
        $this->info("Reading text for entity {$entityId}");

        $entity = Entity::find($entityId);

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

        $tikaStatus = EntityElastic::extractTextFromFiles($entity, $elasticSource);

        $this->info("tikaStatus: ".$tikaStatus);

        //print_r(array_keys($elasticSource));
        if (isset($elasticSource["data"]) && isset($elasticSource["data"]["files"])) {
            foreach ($elasticSource["data"]["files"] as $file) {
                $fileName = Si4Util::getArg($file, "fileName", "(no filename)");

                $this->info("Preview for ".$fileName);

                $fullText = $file["fullText"];
                echo mb_substr($fullText, 0, 300)." ... \n";
            }
        }

        $this->info("All done for entity {$entityId}");
    }
}
