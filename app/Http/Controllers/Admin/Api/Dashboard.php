<?php
namespace App\Http\Controllers\Admin\Api;

use App\Helpers\EntityHelpers;
use App\Helpers\Si4Util;
use App\Http\Controllers\Controller;
use App\Models\RelationType;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Dashboard extends Controller
{
    public function dashboardFiles(Request $request) {

        $result = [];

        $dashboardFiles = si4config("dashboardFiles");
        if ($dashboardFiles && count($dashboardFiles)) {
            foreach ($dashboardFiles as $dashboardFile) {
                $dfName = Si4Util::pathArg($dashboardFile, "name", "default");
                $dfFilePath = Si4Util::pathArg($dashboardFile, "filePath");
                $dfTailLimit = Si4Util::pathArg($dashboardFile, "tailLimit");

                $dfFilePath = str_replace("{storagePath}", storage_path(), $dfFilePath);

                $cmd = "tail";

                $args = "";
                if ($dfTailLimit) $args .= " -".$dfTailLimit;
                $args .= " ".$dfFilePath;

                $process = new Process($cmd.$args);
                $process->run();

                if ($process->isSuccessful()) {
                    $result[] = [
                        "name" => $dfName,
                        "success" => true,
                        "text" => $process->getOutput(),
                        "filePath" => $dfFilePath,
                    ];
                } else {
                    $result[] = [
                        "name" => $dfName,
                        "success" => false,
                        "errors" => $process->getErrorOutput(),
                        "filePath" => $dfFilePath,
                    ];
                }
            }
        }

        return [
            "dashboardFiles" => $result,
            "status" => true,
            "error" =>  null
        ];
    }

}