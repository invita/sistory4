<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Http\Controllers\Controller;
use App\Models\EntityType;
use \Illuminate\Http\Request;

class Initial extends Controller
{
    public function initialData(Request $request) {
        $currentUser = \Auth::user();
        $entityTypes = EntityType::all(["id", "name"])->toArray();
        return [
            "currentUser" => $currentUser,
            "entityTypes" => $entityTypes,
            "status" => true,
            "error" =>  null
        ];
    }

}