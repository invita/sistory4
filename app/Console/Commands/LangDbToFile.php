<?php

namespace App\Console\Commands;

use App\Helpers\Enums;
use App\Models\Translation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class LangDbToFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:dbToFile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump translations from database into file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (Enums::$feLangModules as $module) {
            foreach (Enums::$feLanguages as $lang) {

                // Read file translations
                $langPath = resource_path()."/lang/".$lang."/".$module.".php";


                // Read db translations
                $dbTranslations = Translation::getDbTranslations($module, $lang)->toArray();

                $this->info("Writing DB translations into ".$langPath." ...");

                $now = date("Y-m-d");
                $fContent = <<<HERE
<?php
// This file is auto-generated using artisan lang:dbToFile command.
// [date={$now}, module={$module}, lang={$lang}]
return [

HERE;
                foreach ($dbTranslations as $dbTranslation) {
                    $fContent .= "\t'".$dbTranslation["key"]."' => '".$dbTranslation["value"]."',\n";
                }

                $fContent .= "];\n";

                file_put_contents($langPath, $fContent);
                $this->info(count($dbTranslations). " translations written.");
            }
        }

        $this->info("All done.");
    }
}
