<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use App\Models\EntityType;
use App\Models\Relation;
use Elasticsearch\ClientBuilder;
use \Illuminate\Http\Request;
use Elasticsearch;

class UploadController extends Controller
{
    public function entity(Request $request)
    {
        $entityType = EntityType::find($request->input("entity_type_id"));
        $file = $request->file("file");

        $entity = Entity::createFromUpload($entityType, $file);

        return ["status" => true, "data" => $entity->data, "error" =>  null];
    }

    public function showContent(Request $request)
    {
        $file = $request->file("file");

        return ["status" => true, "data" => file_get_contents($file->getPathname()), "error" =>  null];
    }
}