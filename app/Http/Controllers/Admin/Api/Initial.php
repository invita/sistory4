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
        return [
            "currentUser" => $currentUser,
            "structTypes" => Enums::$structTypes,
            "entityTypes" => Enums::$entityTypes,
            "status" => true,
            "error" =>  null
        ];
    }

}