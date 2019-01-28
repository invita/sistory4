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


            // oai_dc

            $group_oai_dc = $this->addOaiGroup("oai_dc",
                "http://www.openarchives.org/OAI/2.0/oai_dc.xsd",
                "http://www.openarchives.org/OAI/2.0/oai_dc/",
                ["default"],
                [
                    ["key" => "xmlns", "value" => "http://www.openarchives.org/OAI/2.0/oai_dc/"],
                    ["key" => "xmlns:dc", "value" => "http://purl.org/dc/elements/1.1/"],
                    ["key" => "xmlns:xsi", "value" => "http://www.w3.org/2001/XMLSchema-instance"],
                    ["key" => "xsi:schemaLocation", "value" => "http://www.openarchives.org/OAI/2.0/oai_dc.xsd"],
                ]);
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


            // oai_datacite

            $group_oai_datacite = $this->addOaiGroup("oai_datacite",
                "http://schema.datacite.org/meta/kernel-3/metadata.xsd",
                "http://datacite.org/schema/kernel-3",
                ["default"],
                [
                    ["key" => "xmlns", "value" => "http://datacite.org/schema/kernel-4"],
                    ["key" => "xmlns:xsi", "value" => "http://www.w3.org/2001/XMLSchema-instance"],
                    ["key" => "xsi:schemaLocation", "value" => "http://datacite.org/schema/kernel-4 http://schema.datacite.org/meta/kernel-4/metadata.xsd"],
                ]);

            $this->addOaiField($group_oai_datacite, "Identifier", false, "", "identifier", $this->elementMapping("identifier", [
                [ "path" => "", "value" => "value" ],
                [ "path" => "@identifierType", "value" => "identifierType" ]]));
            $this->addOaiField($group_oai_datacite, "Title", true, "titles", "title", $this->elementMapping("title", [
                [ "path" => "", "value" => "value" ],
                [ "path" => "@titleType", "value" => "titleType" ]]));
            $this->addOaiField($group_oai_datacite, "Creator", false, "creators", "creator", $this->elementMapping("creator", [
                [ "path" => "creatorName", "value" => "value" ]]));
            $this->addOaiField($group_oai_datacite, "Publisher", false, "", "publisher", $this->simpleMapping("publisher"));

            //$this->addOaiField($group_oai_datacite, "Publication Year", false, "", "publicationYear", $this->simpleMapping("publicationYear"));

            $this->addOaiField($group_oai_datacite, "Subject", true, "subjects", "subject", $this->simpleMapping("subject"));

            $this->addOaiField($group_oai_datacite, "Contributor", false, "contributors", "contributor", $this->elementMapping("contributor", [
                [ "path" => "@contributorType", "value" => "\"Funder\"" ],
                [ "path" => "contributorName", "value" => "value" ],
                [ "path" => "nameIdentifier", "value" => "info" ],
                [ "path" => "nameIdentifier/@nameIdentifierScheme", "value" => "\"info\"" ],
            ]));

            $this->addOaiField($group_oai_datacite, "Date", false, "dates", "date", $this->elementMapping("date", [
                [ "path" => "", "value" => "value" ],
                [ "path" => "@dateType", "value" => "dateType" ]]));

            $this->addOaiField($group_oai_datacite, "Language", false, "", "language", $this->simpleMapping("language"));

            //$this->addOaiField($group_oai_datacite, "Resource Type", false, "", "resourceType", $this->simpleMapping("resourceType"));

            $this->addOaiField($group_oai_datacite, "Size", false, "sizes", "size", $this->simpleMapping("size"));
            $this->addOaiField($group_oai_datacite, "Format", false, "formats", "format", $this->simpleMapping("format"));
            $this->addOaiField($group_oai_datacite, "Version", false, "", "version", $this->simpleMapping("version"));
            $this->addOaiField($group_oai_datacite, "Rights", false, "rightsList", "rights", $this->simpleMapping("rights"));

            $this->addOaiField($group_oai_datacite, "Description", false, "descriptions", "description", $this->elementMapping("description", [
                [ "path" => "", "value" => "value" ],
                [ "path" => "@descriptionType", "value" => "descriptionType" ]]));

            // TODO ...


            $this->info("Inserted OAI groups: ".OaiGroup::count());
            $this->info("Inserted OAI fields: ".OaiField::count());
            $this->info("All done!");
        }
    }


    private function addOaiGroup($groupName, $schema, $namespace, $behaviours, $attrs) {
        $oaiGroup = new OaiGroup();
        $oaiGroup->name = $groupName;
        $oaiGroup->schema = $schema;
        $oaiGroup->namespace = $namespace;
        $oaiGroup->behaviours = json_encode($behaviours);
        $oaiGroup->attrs = json_encode($attrs);
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

    private function simpleMapping($si4field, $value = "value") {
        return json_encode([["si4field" => $si4field, "xml_values" => [["path" => "", "value" => $value]]]]);
    }
    private function elementMapping($si4field, $xml_values = []) {
        return json_encode([["si4field" => $si4field, "xml_values" => $xml_values ]]);
    }
}
