<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Helpers\Enums;
use App\Helpers\Si4Helpers;
use App\Http\Controllers\Controller;
use App\Models\RelationType;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class Initial extends Controller
{
    public function initialData(Request $request) {
        $currentUser = \Auth::user();
        return [
            "currentUser" => $currentUser,
            "structTypes" => Enums::$structTypes,
            "entityTypes" => Enums::$entityTypes,
            "dcLanguages" => Enums::$dcLanguages,
            "fieldDefinitions" => Si4Helpers::$si4FieldDefinitions,
            "translations" => Lang::get('fe'),
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