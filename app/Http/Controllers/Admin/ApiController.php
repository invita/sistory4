<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity;
use \Illuminate\Http\Request;

class ApiController extends Controller
{
    public function entityList(Request $request)
    {
        $entity = Entity::all();

        return ["status" => true, "data" => $entity, "error" =>  null];
    }

    public function saveEntity(Request $request)
    {
        $entity = new Entity();
        $entity->entity_type_id = $request->input("entity_type_id");
        $entity->data = $request->input("xml");
        $entity->save();

        return ["status" => true, "error" =>  null];
    }
}