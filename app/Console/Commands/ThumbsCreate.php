<?php

namespace App\Console\Commands;

use App\Helpers\ElasticHelpers;
use App\Helpers\FileHelpers;
use App\Helpers\ImageHelpers;
use App\Helpers\Si4Util;
use App\Helpers\Timer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ThumbsCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbs:create {entityId} {method=imagick}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create thumbs';

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
        $this->comment("Fetching entity {$entityId}");
        $elasticEntities = ElasticHelpers::searchByIdArray([$entityId]);
        $elasticEntity = Si4Util::pathArg($elasticEntities, $entityId."/_source");
        //$this->info(print_r($elasticEntity, true));

        $method = $this->argument('method');

        $handle_id = Si4Util::pathArg($elasticEntity, "handle_id");
        $struct_type = Si4Util::pathArg($elasticEntity, "struct_type");

        if ($struct_type == "file") {
            $entity_handle_id = Si4Util::pathArg($elasticEntity, "parent");
        } else {
            $entity_handle_id = Si4Util::pathArg($elasticEntity, "handle_id");
        }

        $firstFile = Si4Util::pathArg($elasticEntity, "data/files/0");
        $firstFileHandleId = Si4Util::pathArg($firstFile, "handle_id");
        $firstFileName = Si4Util::pathArg($firstFile, "fileName");

        if ($firstFileHandleId && $firstFileName) {
            $this->info("- Recreating thumbnail for entity {$entityId} handle_id={$entity_handle_id}, file_handle={$firstFileHandleId} ({$firstFileName})");

            Timer::start("thumbGeneration");

            $storageName = FileHelpers::getPublicStorageName($entity_handle_id, $firstFileName);
            $fullPath = storage_path('app')."/".$storageName;

            if (file_exists($fullPath)) {
                $ext = pathinfo($fullPath, PATHINFO_EXTENSION);

                switch ($method) {
                    case "iiif":
                        try {
                            $thumbData = file_get_contents(ImageHelpers::getMainThumbUrl($entity_handle_id, $firstFileName));
                            file_put_contents($fullPath.SI4_THUMB_FILE_POSTFIX, $thumbData);
                        } catch (\Exception $e) {
                            //
                            Log::warning("Thumb could not be created via iiif. ".
                                "sourceFile=".ImageHelpers::getMainThumbUrl($entity_handle_id, $firstFileName).", ".
                                "destination=".$fullPath.SI4_THUMB_FILE_POSTFIX." e:".$e->getMessage());
                        }
                        break;
                    case "imagick":
                    default:
                        $imInput = $fullPath;
                        if (strtolower($ext) == "pdf") $imInput .= "[0]";
                        $image = new \Imagick($imInput);
                        $image->setResolution(320, 0);
                        $image->setCompressionQuality(85);
                        $image->setImageFormat('jpeg');
                        $image->writeImage($fullPath.SI4_THUMB_FILE_POSTFIX);
                        $image->clear();
                        $image->destroy();
                        break;
                }
            } else {
                $this->warn("File does not exist: ".$fullPath);
            }

            Timer::stop("thumbGeneration");
        }
    }
}
