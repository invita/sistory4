<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\EntityExport;
use App\Helpers\EntityImport;
use App\Helpers\EntitySelect;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\Relation;
use Elasticsearch\ClientBuilder;
use \Illuminate\Http\Request;
use Elasticsearch;

class DownloadController extends Controller
{
    public function entity(Request $request)
    {
        /*
        $structType = EntityType::find($request->input("struct_type_id"));
        $file = $request->file("file");

        $entity = Entity::createFromUpload($entityType, $file);

        return ["status" => true, "data" => $entity->data, "error" =>  null];
        */
    }

    public function exportMets(Request $request)
    {
        $postJson = json_decode($request->input("data"), true);
        $entityList = EntitySelect::selectEntities($postJson);
        $tmpFile = EntityExport::exportEntitiesMets($entityList);

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=entities.zip');
        header('Content-Length: ' .filesize($tmpFile));
        readfile($tmpFile);
    }

    public function exportCsv(Request $request)
    {
        $postJson = json_decode($request->input("data"), true);
        $filter = Si4Util::getArg($postJson, "filter", []);
        $filter_structType = Si4Util::getArg($filter, "struct_type", "");
        $entityList = EntitySelect::selectEntities($postJson);

        switch ($filter_structType) {
            case "entity": default:
                $columns = [
                    "id" => "id",
                    "handle_id" => "handle_id",
                    "parent" => "parent",
                    "primary" => "primary",
                    "collection" => "collection",
                    "struct_type" => "content_type",
                    "struct_subtype" => "content_subtype",
                    "entity_type" => "entity_type",
                    "title" => "title",
                    "creator" => "creator",
                    "date" => "date",
                ];
                break;
            case "collection":
                $columns = [
                    "id" => "id",
                    "handle_id" => "handle_id",
                    "parent" => "parent",
                    "struct_type" => "content_type",
                    "struct_subtype" => "content_subtype",
                    "entity_type" => "collection_type",
                    "title" => "title",
                ];
                break;
        }

        $tmpFile = EntityExport::exportEntitiesCsv($entityList, $columns);

        header('Content-Type: ');
        //header('Content-disposition: attachment; filename=entities.csv');
        header('Content-Length: ' .filesize($tmpFile));

        readfile($tmpFile);
    }
}