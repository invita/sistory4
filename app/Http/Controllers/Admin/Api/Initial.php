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
            "dcLanguages" => Enums::$dcLanguages,
            "repositoryInfo" => [
                "name" => si4config("siteName"),
                "note" => si4config("siteUrl"),
                "handlePrefix" => si4config("handlePrefix"),
            ],
            "status" => true,
            "error" =>  null
        ];
    }

}