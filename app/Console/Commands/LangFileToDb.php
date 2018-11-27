<?php

namespace App\Console\Commands;

use App\Helpers\Enums;
use App\Models\Translation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class LangFileToDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:fileToDb {mode?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import new translations from file into database';

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
        $mode = $this->argument('mode');
        if ($mode === "truncate") {
            if ($this->confirm('Are you sure you wish to TRUNCATE translations table first?', true)) {
                Translation::truncate();
            } else {
                $this->info("Aborting.");
                return;
            }
        }

        foreach (Enums::$feLanguages as $lang) {
            foreach (Enums::$feLangModules as $module) {

                // Read file translations
                $langPath = resource_path()."/lang/".$lang."/".$module.".php";
                $fTranslationsKV = include($langPath);

                // Read db translations
                $dbTranslationsArray = Translation::getDbTranslations($module, $lang)->toArray();
                $dbTranslationsKV = [];
                foreach ($dbTranslationsArray as $dbTranslation) {
                    $dbTranslationsKV[$dbTranslation["key"]] = $dbTranslation["value"];
                }

                $this->info("Analysing ".count($fTranslationsKV)." translations from ".$langPath." ...");

                $insertCount = 0;
                foreach ($fTranslationsKV as $fKey => $fValue) {

                    if (!isset($dbTranslationsKV[$fKey])) {
                        // Insert file translation into DB
                        $translation = new Translation();
                        $translation->language = $lang;
                        $translation->module = $module;
                        $translation->key = $fKey;
                        $translation->value = $fValue;
                        $translation->save();
                        $insertCount++;
                    }
                }
                $this->info($insertCount." translations inserted!");
            }
        }

        $this->info("All done.");
    }
}
