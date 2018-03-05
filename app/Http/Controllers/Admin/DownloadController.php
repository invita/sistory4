<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\EntityExport;
use App\Helpers\EntityImport;
use App\Helpers\EntitySelect;
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

    public function export(Request $request)
    {
        $postJson = json_decode($request->input("data"), true);

        $entityList = EntitySelect::selectEntities($postJson);

        $tmpFile = EntityExport::exportEntities($entityList);

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename=entities.zip');
        header('Content-Length: ' .filesize($tmpFile));
        readfile($tmpFile);
    }
}