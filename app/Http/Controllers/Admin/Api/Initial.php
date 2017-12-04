<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Helpers\Enums;
use App\Http\Controllers\Controller;
use App\Models\RelationType;
use \Illuminate\Http\Request;

class Initial extends Controller
{
    public function initialData(Request $request) {
        $currentUser = \Auth::user();
        $relationTypes = RelationType::all(["id", "name", "name_rev"])->toArray();
        return [
            "currentUser" => $currentUser,
            "structTypes" => Enums::$structTypes,
            "entityTypes" => Enums::$entityTypes,
            "relationTypes" => $relationTypes,
            "status" => true,
            "error" =>  null
        ];
    }

}