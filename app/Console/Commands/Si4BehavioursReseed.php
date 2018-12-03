<?php

namespace App\Console\Commands;

use App\Models\Behaviour;
use App\Models\BehaviourField;
use App\Models\Si4Field;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Si4BehavioursReseed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'si4:behavioursReseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush behaviour tables and seed them';

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
        if ($this->confirm('Are you sure you wish to flush and reseed behaviour tables?', true)) {

            Behaviour::truncate();
            BehaviourField::truncate();

            $behDefault = $this->addBehaviour("default");
            $this->addBehaviourField($behDefault, "title", true, false, "", true);
            $this->addBehaviourField($behDefault, "creator", false, true, " / ", true);
            $this->addBehaviourField($behDefault, "subject", false, true, ", ", true);
            $this->addBehaviourField($behDefault, "description", false, false, "", false);
            $this->addBehaviourField($behDefault, "publisher", false, true, " / ", true);
            $this->addBehaviourField($behDefault, "contributor", false, true, " / ", true);
            $this->addBehaviourField($behDefault, "date", false, false, "", false);
            $this->addBehaviourField($behDefault, "type", false, true, " / ", false);
            $this->addBehaviourField($behDefault, "format", false, false, "", false);
            $this->addBehaviourField($behDefault, "identifier", false, false, "", false);
            $this->addBehaviourField($behDefault, "source", false, false, "", false);
            $this->addBehaviourField($behDefault, "language", false, true, " / ", false);
            $this->addBehaviourField($behDefault, "relation", false, false, "", false);
            $this->addBehaviourField($behDefault, "coverage", false, true, ", ", false);
            $this->addBehaviourField($behDefault, "rights", false, false, "", false);

            $behEverything = $this->addBehaviour("everything");
            $si4Fields = Si4Field::getSi4FieldsArray();
            foreach($si4Fields as $fieldName => $fieldDef) {
                $this->addBehaviourField($behEverything, $fieldName, true, false, "", true);
            }

            $this->info("Inserted behaviours: ".Behaviour::count());
            $this->info("Inserted behaviour fields: ".BehaviourField::count());

            $this->info("All done!");
        }
    }

    private function addBehaviour($name, $desc = "", $template_entity = "", $template_collection = "",
                                  $template_file = "", $advSearch = "[]") {
        $behaviour = new Behaviour();
        $behaviour->name = $name;
        $behaviour->description = $desc;
        $behaviour->template_entity = $template_entity;
        $behaviour->template_collection = $template_collection;
        $behaviour->template_file = $template_file;
        $behaviour->advanced_search = $advSearch;
        $behaviour->save();
        return $behaviour;
    }

    private function addBehaviourField($behaviour, $fieldName, $showAllLangs = false,
                                       $inline = false, $inlineSep = "", $displayFE = false) {
        $behField = new BehaviourField();
        $behField->behaviour_name = $behaviour->name;
        $behField->field_name = $fieldName;
        $behField->show_all_languages = $showAllLangs;
        $behField->inline = $inline;
        $behField->inline_separator = $inlineSep;
        $behField->display_frontend = $displayFE;
        $behField->save();
        return $behField;
    }
}
