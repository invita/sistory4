<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Http\Controllers\Controller;
use App\Models\EntityType;
use App\Models\RelationType;
use \Illuminate\Http\Request;

class Initial extends Controller
{
    public function initialData(Request $request) {
        $currentUser = \Auth::user();
        $entityTypes = EntityType::all(["id", "name"])->toArray();
        $relationTypes = RelationType::all(["id", "name"])->toArray();
        return [
            "currentUser" => $currentUser,
            "entityTypes" => $entityTypes,
            "relationTypes" => $relationTypes,
            "status" => true,
            "error" =>  null
        ];
    }

}