<?php

namespace App\Console\Commands;

use App\Helpers\DbHierarchyHelpers;
use App\Helpers\ElasticHelpers;
use App\Helpers\Si4Util;
use App\Helpers\Timer;
use App\Models\Entity;
use App\Models\Si4\MetsToSi4;
use Illuminate\Console\Command;

class ReindexCleanEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reindex:cleanEntity {entityId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean the indexed document of a single entity';

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
        $this->info("Cleaning indexed document for entity {$entityId}");

        $elasticEntityPlain = ElasticHelpers::plainEntityById($entityId);
        $hits = Si4Util::pathArg($elasticEntityPlain, "hits/total", null);

        if ($hits === null) {
            $this->error("Unexpected result for entity ".$entityId);
            print_r($elasticEntityPlain);
            return;
        }

        if (!$hits) {
            $this->warn("Entity ".$entityId." not indexed");
            return;
        }

        if ($hits > 1) {
            $this->warn("Multiple (".$hits.") entities found for id=".$entityId);
            return;
        }

        $elasticEntity = Si4Util::pathArg($elasticEntityPlain, "hits/hits/0/_source", null);

        $modified = false;

        // Clean up field fullText for non-file entities
        $struct_type = Si4Util::pathArg($elasticEntity, "struct_type", null);
        //print_r("struct_type: ".$struct_type."\n");
        if ($struct_type !== "file") {
            $this->info("Cleaning up fullText field for non-file entity");
            $files = Si4Util::pathArg($elasticEntity, "data/files", null);
            if ($files) {
                foreach ($files as $idx => $file) {
                    $elasticEntity["data"]["files"][$idx]["fullText"] = "";
                    $modified = true;
                }
            }
        }


        if ($modified) {
            ElasticHelpers::indexEntity($entityId, $elasticEntity);
        }
    }

}
