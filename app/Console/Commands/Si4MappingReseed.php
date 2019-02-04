<?php

namespace App\Console\Commands;

use App\Models\MappingField;
use App\Models\MappingGroup;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Si4MappingReseed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'si4:mappingReseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush Metadata mapping tables and seed them';

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
        if ($this->confirm('Are you sure you wish to flush and reseed mapping tables?', true)) {

            MappingField::truncate();
            MappingGroup::truncate();

            $dcBaseXPath = "/METS:mets/METS:dmdSec/METS:mdWrap/METS:xmlData";
            $modsBaseXPath = "/METS:mets/METS:dmdSec/METS:mdWrap/METS:xmlData/mods:mods";
            $premisBaseXPath = "/METS:mets/METS:amdSec/METS:techMD[@ID='tech.premis']/METS:mdWrap[@MDTYPE='PREMIS:OBJECT']/METS:xmlData";

            // *** DC ***

            $groupDC = $this->addMappingGroup("DC", $dcBaseXPath, "DublinCore mappings");
            $this->addMappingField($groupDC, "title", "dc:title", "string()", "string(@xml:lang)");
            $this->addMappingField($groupDC, "creator", "dc:creator", "string()", "");
            $this->addMappingField($groupDC, "subject", "dc:subject", "string()", "string(@xml:lang)");
            $this->addMappingField($groupDC, "description", "dc:description", "string()", "string(@xml:lang)");
            $this->addMappingField($groupDC, "publisher", "dc:publisher", "string()", "");
            $this->addMappingField($groupDC, "contributor", "dc:contributor", "string()", "");
            $this->addMappingField($groupDC, "date", "dc:date", "string()", "");
            $this->addMappingField($groupDC, "type", "dc:type", "string()", "");
            $this->addMappingField($groupDC, "format", "dc:format", "string()", "");
            $this->addMappingField($groupDC, "identifier", "dc:identifier", "string()", "");
            $this->addMappingField($groupDC, "source", "dc:source", "string()", "");
            $this->addMappingField($groupDC, "language", "dc:language", "string()", "");
            $this->addMappingField($groupDC, "relation", "dc:relation", "string()", "");
            $this->addMappingField($groupDC, "coverage", "dc:coverage", "string()", "");
            $this->addMappingField($groupDC, "rights", "dc:rights", "string()", "");


            // *** Mods ***

            $groupDC = $this->addMappingGroup("Mods", $modsBaseXPath, "Mods mappings");
            $this->addMappingField($groupDC, "title", "mods:titleInfo[not(@type='alternative' or @type='translated')]/mods:title", "string()", "string(@xml:lang)");
            $this->addMappingField($groupDC, "title", "mods:titleInfo[not(@type='alternative' or @type='translated')]/mods:subTitle", "string()", "string(@xml:lang)");
            $this->addMappingField($groupDC, "alternativeTitle", "mods:titleInfo[@type='alternative' or @type='translated']/mods:title", "string()", "string(@xml:lang)");
            $this->addMappingField($groupDC, "creator", "mods:name[mods:role[mods:roleTerm[@type='code']='cre']]/mods:namePart[not(@type)]", "string()", "");
            $this->addMappingField($groupDC, "editor", "mods:name[mods:role[mods:roleTerm[@type='code']='edt']]/mods:namePart[not(@type)]", "string()", "");
            $this->addMappingField($groupDC, "contributor", "mods:name[mods:role[not(mods:roleTerm[@type='code']='cre' or mods:roleTerm[@type='code']='edt')]]",
                "concat(mods:namePart[not(@type)], ' (', mods:role/mods:roleTerm[@type='text'][@xml:lang], ')')", "");
            $this->addMappingField($groupDC, "type", "mods:typeOfResource", "string()", "string(@xml:lang)");
            $this->addMappingField($groupDC, "genre", "mods:genre", "string()", "string(@xml:lang)");
            $this->addMappingField($groupDC, "date", "mods:originInfo/*[self::*[@keyDate]]", "string()", "");
            $this->addMappingField($groupDC, "dateIssued", "mods:originInfo/mods:dateIssued[not(@point)]", "string()", "");
            $this->addMappingField($groupDC, "dateIssued", "mods:originInfo[mods:dateIssued[@point='start'] and mods:dateIssued[@point='end']]",
                "concat(mods:dateIssued[@point='start'] ,'-', mods:dateIssued[@point='end'])", "");
            $this->addMappingField($groupDC, "dateCreated", "mods:originInfo/mods:dateCreated[not(@point)]", "string()", "");
            $this->addMappingField($groupDC, "dateCreated", "mods:originInfo[mods:dateCreated[@point='start'] and mods:dateCreated[@point='end']]",
                "concat(mods:dateCreated[@point='start'] ,'-', mods:dateCreated[@point='end'])", "");
            $this->addMappingField($groupDC, "dateCaptured", "mods:originInfo/mods:dateCaptured[not(@point)]", "string()", "");
            $this->addMappingField($groupDC, "dateCaptured", "mods:originInfo[mods:dateCaptured[@point='start'] and mods:dateCaptured[@point='end']]",
                "concat(mods:dateCaptured[@point='start'] ,'-', mods:dateCaptured[@point='end'])", "");
            $this->addMappingField($groupDC, "dateModified", "mods:originInfo/mods:dateModified[not(@point)]", "string()", "");
            $this->addMappingField($groupDC, "dateModified", "mods:originInfo[mods:dateModified[@point='start'] and mods:dateModified[@point='end']]",
                "concat(mods:dateModified[@point='start'] ,'-', mods:dateModified[@point='end'])", "");
            $this->addMappingField($groupDC, "dateValid", "mods:originInfo/mods:dateValid[not(@point)]", "string()", "");
            $this->addMappingField($groupDC, "dateValid", "mods:originInfo[mods:dateValid[@point='start'] and mods:dateValid[@point='end']]",
                "concat(mods:dateValid[@point='start'] ,'-', mods:dateValid[@point='end'])", "");
            $this->addMappingField($groupDC, "dateOther", "mods:originInfo/mods:dateOther[not(@point)]", "string()", "");
            $this->addMappingField($groupDC, "dateOther", "mods:originInfo[mods:dateOther[@point='start'] and mods:dateOther[@point='end']]",
                "concat(mods:dateOther[@point='start'] ,'-', mods:dateOther[@point='end'])", "");
            $this->addMappingField($groupDC, "dateCopyrighted", "mods:originInfo/mods:dateCopyrighted[not(@point)]", "string()", "");
            $this->addMappingField($groupDC, "dateCopyrighted", "mods:originInfo[mods:dateCopyrighted[@point='start'] and mods:dateCopyrighted[@point='end']]",
                "concat(mods:dateCopyrighted[@point='start'] ,'-', mods:dateCopyrighted[@point='end'])", "");
            $this->addMappingField($groupDC, "publisher", "mods:originInfo/mods:publisher", "string()", "");
            $this->addMappingField($groupDC, "pubPlace", "mods:originInfo/mods:place/mods:placeTerm[@type='text']", "string()", "");
            $this->addMappingField($groupDC, "edition", "mods:originInfo/mods:edition", "string()", "");
            $this->addMappingField($groupDC, "issuance", "mods:originInfo/mods:issuance", "string()", "");
            $this->addMappingField($groupDC, "frequency", "mods:originInfo/mods:frequency", "string()", "");
            $this->addMappingField($groupDC, "language", "mods:language/mods:languageTerm", "string()", "");
            $this->addMappingField($groupDC, "script", "mods:language/mods:scriptTerm", "string()", "");
            $this->addMappingField($groupDC, "form", "mods:physicalDescription/mods:form", "string()", "");
            $this->addMappingField($groupDC, "format", "mods:physicalDescription/mods:internetMediaType", "string()", "");
            $this->addMappingField($groupDC, "extent", "mods:physicalDescription/mods:extent", "string()", "");
            $this->addMappingField($groupDC, "abstract", "mods:abstract", "string()", "");
            $this->addMappingField($groupDC, "tableOfContents", "mods:tableOfContents", "string()", "");
            $this->addMappingField($groupDC, "audience", "mods:targetAudience", "string()", "");
            $this->addMappingField($groupDC, "description", "mods:note", "string()", "");
            $this->addMappingField($groupDC, "subject", "mods:subject/mods:topic", "string()", "");
            $this->addMappingField($groupDC, "subject", "mods:subject/mods:name", "string-join(mods:namePart,' ')", "");
            $this->addMappingField($groupDC, "subject", "mods:subject/mods:titleInfo", "string-join(mods:title,': ')", "");
            $this->addMappingField($groupDC, "subject", "mods:subject/mods:occupation", "string()", "");
            $this->addMappingField($groupDC, "coverageTemporal", "mods:subject/mods:temporal", "string()", "");
            $this->addMappingField($groupDC, "coverageSpatial", "mods:subject/mods:geographic", "string()", "");
            $this->addMappingField($groupDC, "coverageSpatial", "mods:subject/mods:hierarchicalGeographic", "string-join(*,'--')", "");
            $this->addMappingField($groupDC, "genre", "mods:subject/mods:genre", "string()", "");
            $this->addMappingField($groupDC, "subject", "mods:classification", "string()", "");
            $this->addMappingField($groupDC, "hasPart", "mods:relatedItem[@type='constituent']/mods:titleInfo", "string-join(mods:title,': ')", "");
            $this->addMappingField($groupDC, "isReferencedBy", "mods:relatedItem[@type='isReferencedBy']/mods:titleInfo", "string-join(mods:title,': ')", "");
            $this->addMappingField($groupDC, "original", "mods:relatedItem[@type='original']/mods:titleInfo", "string-join(mods:title,': ')", "");
            $this->addMappingField($groupDC, "hasFormat", "mods:relatedItem[@type='otherFormat']/mods:titleInfo", "string-join(mods:title,': ')", "");
            $this->addMappingField($groupDC, "hasVersion", "mods:relatedItem[@type='otherVersion']/mods:titleInfo", "string-join(mods:title,': ')", "");
            $this->addMappingField($groupDC, "preceding", "mods:relatedItem[@type='preceding']/mods:titleInfo", "string-join(mods:title,': ')", "");
            $this->addMappingField($groupDC, "references", "mods:relatedItem[@type='references']/mods:titleInfo", "string-join(mods:title,': ')", "");
            $this->addMappingField($groupDC, "isReviewedBy", "mods:relatedItem[@type='reviewOf']/mods:titleInfo", "string-join(mods:title,': ')", "");
            $this->addMappingField($groupDC, "succeeding", "mods:relatedItem[@type='succeeding']/mods:titleInfo", "string-join(mods:title,': ')", "");
            $this->addMappingField($groupDC, "series", "mods:relatedItem[@type='series']/mods:titleInfo", "string-join(mods:title,': ')", "");


            // *** Premis ***

            $groupDC = $this->addMappingGroup("Premis", $premisBaseXPath, "Premis mappings");
            $this->addMappingField($groupDC, "identifier", "premis:objectIdentifier/premis:objectIdentifierValue", "string()", "",
                json_encode([["name" => "identifierType", "value" => "string(../premis:objectIdentifierType)"]]));

            // TODO: dcterms
            // TODO: oaire


            $this->info("Inserted mapping groups: ".MappingGroup::count());
            $this->info("Inserted mapping fields: ".MappingField::count());
            $this->info("All done!");
        }
    }


    private function addMappingGroup($groupName, $baseXpath, $groupDesc) {
        $mappingGroup = new MappingGroup();
        $mappingGroup->name = $groupName;
        $mappingGroup->base_xpath = $baseXpath;
        $mappingGroup->description = $groupDesc;
        $mappingGroup->data = "";
        $mappingGroup->save();
        return $mappingGroup;
    }

    private function addMappingField($mappingGroup, $targetField, $srcXPath, $valueXPath, $langXPath, $variables = "") {
        $mappingField = new MappingField();
        $mappingField->mapping_group_id = $mappingGroup->id;
        $mappingField->target_field = $targetField;
        $mappingField->source_xpath = $srcXPath;
        $mappingField->value_xpath = $valueXPath;
        $mappingField->lang_xpath = $langXPath;
        $mappingField->variables = $variables;
        $mappingField->save();
        return $mappingField;
    }
}
