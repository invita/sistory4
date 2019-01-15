<?php

namespace App\Console\Commands;

use App\Models\OaiField;
use App\Models\OaiGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Si4OaiReseed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'si4:oaiReseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush Oai tables and seed them';

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
        if ($this->confirm('Are you sure you wish to flush and reseed OAI tables?', true)) {

            OaiGroup::truncate();
            OaiField::truncate();

            $group_oai_dc = $this->addOaiGroup("oai_dc");
            //$this->addOaiField($group_oai_dc, "Title", true, "", "dc:title", json_encode([["si4field" => "title", "xml_value" => "value", "xml_attributes" => [["name" => "oaiAttr", "value" => "si4Attr" ]] ]]));
            $this->addOaiField($group_oai_dc, "Title", true, "", "dc:title", $this->simpleMapping("title"));
            $this->addOaiField($group_oai_dc, "Creator", false, "", "dc:creator", $this->simpleMapping("creator"));
            $this->addOaiField($group_oai_dc, "Subject", false, "", "dc:subject", $this->simpleMapping("subject"));
            $this->addOaiField($group_oai_dc, "Description", false, "", "dc:description", $this->simpleMapping("description"));
            $this->addOaiField($group_oai_dc, "Publisher", false, "", "dc:publisher", $this->simpleMapping("publisher"));
            $this->addOaiField($group_oai_dc, "Contributor", false, "", "dc:contributor", $this->simpleMapping("contributor"));
            $this->addOaiField($group_oai_dc, "Date", false, "", "dc:date", $this->simpleMapping("date"));
            $this->addOaiField($group_oai_dc, "Type", false, "", "dc:type", $this->simpleMapping("type"));
            $this->addOaiField($group_oai_dc, "Format", false, "", "dc:format", $this->simpleMapping("format"));
            $this->addOaiField($group_oai_dc, "Identifier", false, "", "dc:identifier", $this->simpleMapping("identifier"));
            $this->addOaiField($group_oai_dc, "Source", false, "", "dc:source", $this->simpleMapping("source"));
            $this->addOaiField($group_oai_dc, "Language", false, "", "dc:language", $this->simpleMapping("language"));
            $this->addOaiField($group_oai_dc, "Relation", false, "", "dc:relation", $this->simpleMapping("relation"));
            $this->addOaiField($group_oai_dc, "Coverage", false, "", "dc:coverage", $this->simpleMapping("coverage"));
            $this->addOaiField($group_oai_dc, "Rights", false, "", "dc:rights", $this->simpleMapping("rights"));

            $group_oai_data = $this->addOaiGroup("oai_data");
            // ...

            $this->info("Inserted OAI groups: ".OaiGroup::count());
            $this->info("Inserted OAI fields: ".OaiField::count());
            $this->info("All done!");
        }
    }


    private function addOaiGroup($groupName) {
        $oaiGroup = new OaiGroup();
        $oaiGroup->name = $groupName;
        $oaiGroup->save();
        return $oaiGroup;
    }

    private function addOaiField($oaiGroup, $name, $has_language, $xml_path, $xml_name, $mapping) {
        $oaiField = new OaiField();
        $oaiField->oai_group_id = $oaiGroup->id;
        $oaiField->name = $name;
        $oaiField->has_language = $has_language;
        $oaiField->xml_path = $xml_path;
        $oaiField->xml_name = $xml_name;
        $oaiField->mapping = $mapping;
        $oaiField->save();
        return $oaiField;
    }

    private function simpleMapping($si4field) {
        return json_encode([["si4field" => $si4field, "xml_value" => "value", "xml_attributes" => [] ]]);
    }
}
