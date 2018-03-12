<?php

namespace App\Console\Commands;

use App\Helpers\ElasticHelpers;
use App\Models\Elastic\EntityElastic;
use App\Models\Entity;
use App\Models\Migration\MigCollection;
use App\Models\Migration\MigFile;
use App\Models\Migration\MigPublication;
use App\Models\Migration\MigUtil;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Config\Definition\Exception\Exception;

class Si4MigrateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'si4migrate:db {mode?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate si3 to si4 database';

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
        $migPubDb = MigUtil::$migDbName;

        $modes = ["test", "clean", "menus", "pubs", "files", "all", "updateParents"];
        $mode = $this->argument('mode');

        if (!in_array($mode, $modes)) {
            $this->info("Available modes are: ".join(", ", $modes));
            return;

        }

        $this->info("Selected mode is {$mode}");

        if ($mode == "test") {

            /*
            //$pgt = DB::select("SELECT ID FROM {$migPubDb}.PUB_GLAVNA_TABELA ORDER BY ID");
            $pgt = DB::select("SELECT ID FROM {$migPubDb}.PUB_GLAVNA_TABELA WHERE ID = 15100");
            foreach ($pgt as $pgt_row) {
                $ID = $pgt_row->ID;
                echo "Testing pub ".$ID."...\n";

                $migPub = new MigPublication($ID);
                $migPub->readAll();
                print_r($migPub->getData());


                $entity = $migPub->makeEntity();
                $entity->data = $migPub->makeMetsXml();
                //print_r($entity);
                //print_r($entity->data);

                //break;
            }
            */
        } else if ($mode == "clean") {
            if ($this->confirm('This will erase all entities. Confirm?', true)) {
                $this->info("Truncate entities...");
                DB::table('entities')->truncate();
                $this->info("Recreate Elastic index...");
                ElasticHelpers::recreateIndex();

                $this->info("Flush handle sequences...");
                DB::table('entity_handle_seq')->truncate();
                DB::table('entity_handle_seq')->insert([
                    "entity_struct_type" => "entity",
                    "format" => "#",
                    "last_num" => 0,
                ]);
                DB::table('entity_handle_seq')->insert([
                    "entity_struct_type" => "collection",
                    "format" => "menu#",
                    "last_num" => 0,
                ]);
                DB::table('entity_handle_seq')->insert([
                    "entity_struct_type" => "file",
                    "format" => "file#",
                    "last_num" => 0,
                ]);

            }
        } else if ($mode == "menus") {

            //$ns = DB::select("SELECT ID FROM {$migPubDb}.NAV_STRUCTURE WHERE ID = 1");
            //$ns = DB::select("SELECT ID FROM {$migPubDb}.NAV_STRUCTURE WHERE ID = 211");
            $ns = DB::select("SELECT ID FROM {$migPubDb}.NAV_STRUCTURE ORDER BY ID");
            foreach ($ns as $ns_row) {
                $ID = $ns_row->ID;

                $this->comment("* Migrating menu " . $ID . "...");

                $migCol = new MigCollection($ID);
                $migCol->readAll();
                //print_r($migPub->getData());

                $colEntity = $migCol->makeEntity();
                $colEntity->data = $migCol->makeMetsXml();
                $colEntity->save();

                // Reindex Entity
                Artisan::call("reindex:entity", ["entityId" => $colEntity->id]);

            }

        } else if ($mode == "pubs") {

            $pgt = DB::select("SELECT ID FROM {$migPubDb}.PUB_GLAVNA_TABELA WHERE ORDER BY ID");

            foreach ($pgt as $pgt_row) {
                $ID = $pgt_row->ID;
                $this->comment("* Migrating pub " . $ID . "...");

                $migPub = new MigPublication($ID);
                $migPub->readAll();

                $entity = $migPub->makeEntity();
                $entity->data = $migPub->makeMetsXml();
                $entity->save();

                // Reindex Entity
                Artisan::call("reindex:entity", ["entityId" => $entity->id]);
            }

        } else if ($mode == "files") {

            $pgt = DB::select("SELECT ID FROM {$migPubDb}.PUB_GLAVNA_TABELA ORDER BY ID");

            foreach ($pgt as $pgt_row) {
                $ID = $pgt_row->ID;

                $migPub = new MigPublication($ID);
                $migPub->readAll();

                $pubGT = $migPub->getData("PUB_GLAVNA_TABELA")[0];
                $pubData = $migPub->getData("PUB_PUBLICATION");
                foreach ($pubData as $pubPub) {

                    $fileId = $pubPub["FILE_ID"];
                    $this->comment("  - Migrating file " . $fileId . "...");
                    $migFile = new MigFile($pubGT, $pubPub);
                    $fileEntity = $migFile->makeEntity();
                    $fileEntity->data = $migFile->makeMetsXml();

                    $fileEntity->save();

                    // Reindex File
                    Artisan::call("reindex:entity", ["entityId" => $fileEntity->id]);
                    //$pubPub
                    //print_r($pubPub);
                }

                // Reindex Entity
                //Artisan::call("reindex:entity", ["entityId" => $entity->id]);
            }

        } else if ($mode == "all") {

            if ($this->confirm('This will erase all entities first. Confirm?', true)) {

                $this->info("Truncate entities...");
                DB::table('entities')->truncate();

                $this->info("Recreate Elastic index...");
                ElasticHelpers::recreateIndex();

                // Note: ... WHERE METAREF_MENU_ID = 0
                //$pgt = DB::select("SELECT ID FROM {$migPubDb}.PUB_GLAVNA_TABELA ORDER BY ID");




                //$ns = DB::select("SELECT ID FROM {$migPubDb}.NAV_STRUCTURE WHERE ID = 1");
                //$ns = DB::select("SELECT ID FROM {$migPubDb}.NAV_STRUCTURE WHERE ID = 211");
                $ns = DB::select("SELECT ID FROM {$migPubDb}.NAV_STRUCTURE ORDER BY ID");
                foreach ($ns as $ns_row) {
                    $menuId = $ns_row->ID;

                    $this->comment("* Migrating menu " . $menuId . "...");

                    $migCol = new MigCollection($menuId);
                    $migCol->readAll();

                    $colEntity = $migCol->makeEntity();
                    $colEntity->data = $migCol->makeMetsXml();
                    $colEntity->save();

                    // Reindex Collection
                    Artisan::call("reindex:entity", ["entityId" => $colEntity->id]);


                    $pgt = DB::select("SELECT ID FROM {$migPubDb}.PUB_GLAVNA_TABELA WHERE MENU_ID = {$menuId} ORDER BY ID");
                    foreach ($pgt as $pgt_row) {
                        $pubId = $pgt_row->ID;
                        $this->comment("  * Migrating pub " . $pubId . "...");

                        $migPub = new MigPublication($pubId);
                        $migPub->readAll();

                        $entity = $migPub->makeEntity();
                        $entity->data = $migPub->makeMetsXml();
                        $entity->save();

                        // Reindex Entity
                        Artisan::call("reindex:entity", ["entityId" => $entity->id]);

                        $pubGT = $migPub->getData("PUB_GLAVNA_TABELA")[0];
                        $pubData = $migPub->getData("PUB_PUBLICATION");
                        foreach ($pubData as $pubPub) {

                            $fileId = $pubPub["FILE_ID"];
                            $this->comment("    - Migrating file " . $fileId . "...");

                            $migFile = new MigFile($pubGT, $pubPub);

                            $fileEntity = $migFile->makeEntity();
                            $fileEntity->data = $migFile->makeMetsXml();
                            $fileEntity->save();

                            // Reindex File
                            Artisan::call("reindex:entity", ["entityId" => $fileEntity->id]);

                        }


                    }

                }










/*
                //$pgt = DB::select("SELECT ID FROM {$migPubDb}.PUB_GLAVNA_TABELA WHERE ID = 15100");
                $pgt = DB::select("SELECT ID FROM {$migPubDb}.PUB_GLAVNA_TABELA WHERE ID = 15832");

                foreach ($pgt as $pgt_row) {
                    $ID = $pgt_row->ID;
                    $this->comment("* Migrating pub " . $ID . "...");

                    $migPub = new MigPublication($ID);
                    $migPub->readAll();

                    $entity = $migPub->makeEntity();
                    $entity->data = $migPub->makeMetsXml();
                    $entity->save();


                    $pubGT = $migPub->getData("PUB_GLAVNA_TABELA")[0];
                    $pubData = $migPub->getData("PUB_PUBLICATION");
                    foreach ($pubData as $pubPub) {
                        $fileId = $pubPub["FILE_ID"];
                        $this->comment("  - Migrating file " . $fileId . "...");
                        $migFile = new MigFile($pubGT, $pubPub);
                        $fileEntity = $migFile->makeEntity();
                        $fileEntity->data = $migFile->makeMetsXml();

                        $fileEntity->save();

                        // Reindex File
                        Artisan::call("reindex:entity", ["entityId" => $fileEntity->id]);
                        //$pubPub
                        //print_r($pubPub);
                    }

                    //$migFile

                    //print_r($migPub->getData());
                    //print_r($entity);


                    // Reindex Entity
                    Artisan::call("reindex:entity", ["entityId" => $entity->id]);

                    break;
                }
*/
            }
            /*
            $entity = Entity::find($entityId);
            if ($entity) {
                $entityXmlParsed = $entity->dataToObject();
                //print_r($entityXmlParsed);

                $entityElastic = new EntityElastic($entityXmlParsed);

                $indexBody = [
                    "id" => $entityId,
                    "handle_id" => $entity["handle_id"],
                    "parent" => $entity["parent"],
                    "primary" => $entity["primary"],
                    "struct_type" => $entity["struct_type"],
                    "entity_type" => $entity["entity_type"],
                    "xml" => $entity["data"],
                    "data" => $entityElastic->getData()
                ];
                ElasticHelpers::indexEntity($entityId, $indexBody);
            } else {
                if (ElasticHelpers::entityExists($entityId)) {
                    ElasticHelpers::deleteEntity($entityId);
                }
            }

            //print_r($entity);
            */


        } else if ($mode == "updateParents") {
            $entities = Entity::all();
            $this->info("Count: " . count($entities));

            foreach ($entities as $entity) {
                $this->info("Updating " . $entity->id."...");
                $entity->calculateParents();
                $entity->save();

                // Reindex
                Artisan::call("reindex:entity", ["entityId" => $entity->id]);

            }
        }

    }
}
