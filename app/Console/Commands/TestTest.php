<?php

namespace App\Console\Commands;

use App\Helpers\ElasticHelpers;
use App\Helpers\EntitySelect;
use App\Models\Elastic\EntityElastic;
use App\Models\Entity;
use Illuminate\Console\Command;

class TestTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test';

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

        // Calculate primary
        $entity = Entity::findOrNew(7);
        $entity->calculatePrimary();
        echo "Calc EntityType: ".$entity->entity_type."\n";
        echo "Calc Primary: ".$entity->primary."\n";

        // EntitySelect
        /*
        $this->info("EntitySelect::selectEntitiesByHandleIds");
        $result = EntitySelect::selectEntitiesByHandleIds(["menu1", "menu2"], null);
        echo "rowCount: ".$result["rowCount"]."\n";

        $this->info("EntitySelect::selectEntitiesBySystemIds");
        $result = EntitySelect::selectEntitiesBySystemIds([1,3], null);
        echo "rowCount: ".$result["rowCount"]."\n";

        $this->info("EntitySelect::selectEntitiesByParentHandle");
        $result = EntitySelect::selectEntitiesByParentHandle("menu1", null);
        echo "rowCount: ".$result["rowCount"]."\n";

        $this->info("EntitySelect::selectEntities");
        $result = EntitySelect::selectEntities();
        echo "rowCount: ".$result["rowCount"]."\n";
        */

        /*
        $result = EntitySelect::selectEntitiesByHandleIds(["menu1", "menu2"], [
            "filter" => [
                "struct_type" => "collection",
                "handle_id" => ["menu1"]
            ],
            "sortField" => null,
            "sortOrder" => "asc",
            "pageStart" => 0,
            "pageCount" => 20
        ]);
        */
        //print_r($result);
    }
}
