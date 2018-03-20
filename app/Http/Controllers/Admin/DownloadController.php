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
    }

    public function exportMets(Request $request)
    {
        $postJson = json_decode($request->input("data"), true);
        $filter_struct_type = Si4Util::pathArg($postJson, "filter/struct_type");
        $outFileName = self::getFileName($filter_struct_type);

        $entityList = EntitySelect::selectEntities($postJson);
        $tmpFile = EntityExport::exportEntitiesMets($entityList, $outFileName);

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$outFileName.'.zip');
        header('Content-Length: ' .filesize($tmpFile));
        readfile($tmpFile);
    }

    public function exportCsv(Request $request)
    {
        $postJson = json_decode($request->input("data"), true);
        $filter_struct_type = Si4Util::pathArg($postJson, "filter/struct_type");
        $outFileName = self::getFileName($filter_struct_type);

        $entityList = EntitySelect::selectEntities($postJson);

        switch ($filter_struct_type) {
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
        header('Content-disposition: attachment; filename='.$outFileName.'.csv');
        header('Content-Length: ' .filesize($tmpFile));

        readfile($tmpFile);
    }


    // Helper methods

    private static function getFileName($filter_struct_type) {
        switch ($filter_struct_type) {
            case "entity": default: $result = "entities"; break;
            case "collection": $result = "collections"; break;
            case "file": $result = "files"; break;
        }
        return $result;
    }

}