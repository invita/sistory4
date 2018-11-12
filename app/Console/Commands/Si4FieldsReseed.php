<?php

namespace App\Console\Commands;

use App\Models\Si4Field;
use Illuminate\Console\Command;
use App\Helpers\Si4Util;

class Si4FieldsReseed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'si4:fieldsReseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush si4l fields and seed them anew';

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
        if ($this->confirm('Are you sure you wish to flush and reseed si4 fields?', true)) {

            Si4Field::truncate();
            $this->info("Table truncated.");

            $fields = self::si4DefaultFieldDefinitions();
            foreach ($fields as $field) {
                $si4Field = new Si4Field();
                $si4Field->field_name = $field["field_name"];
                $si4Field->translate_key = $field["translate_key"];
                $si4Field->has_language = $field["has_language"];
                $si4Field->show_all_languages = $field["show_all_languages"];
                $si4Field->inline = $field["inline"];
                $si4Field->inline_separator = $field["inline_separator"];
                $si4Field->display_frontend = $field["display_frontend"];
                $si4Field->save();
            }

            $this->info("Inserted fields: ".Si4Field::count());
            $this->info("All done!");
        }
    }



    // Return a new field definition assuming default values for non-specified attributes
    private static function si4FieldWithDefaults($fieldSpecs) {
        return [
            "field_name" => $fieldSpecs["field_name"],
            "translate_key" => Si4Util::getArg($fieldSpecs, "translate_key", "si4_field_".$fieldSpecs["field_name"]),
            "has_language" => Si4Util::getArg($fieldSpecs, "has_language", false),
            "show_all_languages" => Si4Util::getArg($fieldSpecs, "show_all_languages", false),
            "inline" => Si4Util::getArg($fieldSpecs, "inline", false),
            "inline_separator" => Si4Util::getArg($fieldSpecs, "inline_separator", ""),
            "display_frontend" => Si4Util::getArg($fieldSpecs, "display_frontend", false),
        ];
    }

    // Si4 field definitions
    public static function si4DefaultFieldDefinitions() {
        $result = [
            /*
            // Notes:
            "field_name" => self::si4FieldWithDefaults([
                "field_name" => "field_name",    // * Mandatory * Field name
                "translate_key" => "field_key",  // Translation key
                "has_language" => true,          // Field data have lang attribute
                "show_all_languages" => true,    // Display all languages, not only FE selected language
                "inline" => false,               // Values are displayed inline
                "inline_separator" => ":",       // If inline, defines values separator
                "display_frontend" => false,     // Should this field be displayed on frontend
            ]),
            */
            "title" => self::si4FieldWithDefaults([
                "field_name" => "title",
                "has_language" => true,
                "show_all_languages" => true,
                "display_frontend" => true,
            ]),
            "creator" => self::si4FieldWithDefaults([
                "field_name" => "creator",
                "inline" => true,
                "inline_separator" => " / ",
                "display_frontend" => true,
            ]),
            "subject" => self::si4FieldWithDefaults([
                "field_name" => "subject",
                "inline" => true,
                "inline_separator" => ", ",
                "display_frontend" => true,
            ]),
            "description" => self::si4FieldWithDefaults([
                "field_name" => "description",
            ]),
            "publisher" => self::si4FieldWithDefaults([
                "field_name" => "publisher",
                "inline" => true,
                "inline_separator" => " / ",
                "display_frontend" => true,
            ]),
            "contributor" => self::si4FieldWithDefaults([
                "field_name" => "contributor",
                "inline" => true,
                "inline_separator" => " / ",
                "display_frontend" => true,
            ]),
            "date" => self::si4FieldWithDefaults([
                "field_name" => "date",
            ]),
            "type" => self::si4FieldWithDefaults([
                "field_name" => "type",
                "inline" => true,
                "inline_separator" => " / ",
            ]),
            "format" => self::si4FieldWithDefaults([
                "field_name" => "format",
            ]),
            "identifier" => self::si4FieldWithDefaults([
                "field_name" => "identifier",
            ]),
            "source" => self::si4FieldWithDefaults([
                "field_name" => "source",
            ]),
            "language" => self::si4FieldWithDefaults([
                "field_name" => "language",
                "inline" => true,
                "inline_separator" => " / ",
            ]),
            "relation" => self::si4FieldWithDefaults([
                "field_name" => "relation",
            ]),
            "coverage" => self::si4FieldWithDefaults([
                "field_name" => "coverage",
                "inline" => true,
                "inline_separator" => ", ",
            ]),
            "rights" => self::si4FieldWithDefaults([
                "field_name" => "rights",
            ]),


            "alternativeTitle" => self::si4FieldWithDefaults([
                "field_name" => "alternativeTitle",
            ]),
            "tableOfContents" => self::si4FieldWithDefaults([
                "field_name" => "tableOfContents",
            ]),
            "abstract" => self::si4FieldWithDefaults([
                "field_name" => "abstract",
            ]),
            "dateCreated" => self::si4FieldWithDefaults([
                "field_name" => "dateCreated",
            ]),
            "dateValid" => self::si4FieldWithDefaults([
                "field_name" => "dateValid",
            ]),
            "dateAvailable" => self::si4FieldWithDefaults([
                "field_name" => "dateAvailable",
            ]),
            "dateIssued" => self::si4FieldWithDefaults([
                "field_name" => "dateIssued",
            ]),
            "dateModified" => self::si4FieldWithDefaults([
                "field_name" => "dateModified",
            ]),
            "dateAccepted" => self::si4FieldWithDefaults([
                "field_name" => "dateAccepted",
            ]),
            "dateCopyrighted" => self::si4FieldWithDefaults([
                "field_name" => "dateCopyrighted",
            ]),
            "dateSubmitted" => self::si4FieldWithDefaults([
                "field_name" => "dateSubmitted",
            ]),
            "extent" => self::si4FieldWithDefaults([
                "field_name" => "extent",
            ]),
            "medium" => self::si4FieldWithDefaults([
                "field_name" => "medium",
            ]),
            "isVersionOf" => self::si4FieldWithDefaults([
                "field_name" => "isVersionOf",
            ]),
            "hasVersion" => self::si4FieldWithDefaults([
                "field_name" => "hasVersion",
            ]),
            "isReplacedBy" => self::si4FieldWithDefaults([
                "field_name" => "isReplacedBy",
            ]),
            "replaces" => self::si4FieldWithDefaults([
                "field_name" => "replaces",
            ]),
            "isRequiredBy" => self::si4FieldWithDefaults([
                "field_name" => "isRequiredBy",
            ]),
            "requires" => self::si4FieldWithDefaults([
                "field_name" => "requires",
            ]),
            "isPartOf" => self::si4FieldWithDefaults([
                "field_name" => "isPartOf",
            ]),
            "hasPart" => self::si4FieldWithDefaults([
                "field_name" => "hasPart",
            ]),
            "isReferencedBy" => self::si4FieldWithDefaults([
                "field_name" => "isReferencedBy",
            ]),
            "references" => self::si4FieldWithDefaults([
                "field_name" => "references",
            ]),
            "isFormatOf" => self::si4FieldWithDefaults([
                "field_name" => "isFormatOf",
            ]),
            "hasFormat" => self::si4FieldWithDefaults([
                "field_name" => "hasFormat",
            ]),
            "conformsTo" => self::si4FieldWithDefaults([
                "field_name" => "conformsTo",
            ]),
            "coverageSpatial" => self::si4FieldWithDefaults([
                "field_name" => "coverageSpatial",
            ]),
            "coverageTemporal" => self::si4FieldWithDefaults([
                "field_name" => "coverageTemporal",
            ]),
            "audience" => self::si4FieldWithDefaults([
                "field_name" => "audience",
            ]),
            "accrualMethod" => self::si4FieldWithDefaults([
                "field_name" => "accrualMethod",
            ]),
            "accrualPeriodicity" => self::si4FieldWithDefaults([
                "field_name" => "accrualPeriodicity",
            ]),
            "accrualPolicy" => self::si4FieldWithDefaults([
                "field_name" => "accrualPolicy",
            ]),
            "instructionalMethod" => self::si4FieldWithDefaults([
                "field_name" => "instructionalMethod",
            ]),
            "provenance" => self::si4FieldWithDefaults([
                "field_name" => "provenance",
            ]),
            "rightsHolder" => self::si4FieldWithDefaults([
                "field_name" => "rightsHolder",
            ]),
            "mediator" => self::si4FieldWithDefaults([
                "field_name" => "mediator",
            ]),
            "educationLevel" => self::si4FieldWithDefaults([
                "field_name" => "educationLevel",
            ]),
            "accessRights" => self::si4FieldWithDefaults([
                "field_name" => "accessRights",
            ]),
            "license" => self::si4FieldWithDefaults([
                "field_name" => "license",
            ]),
            "bibliographicCitation" => self::si4FieldWithDefaults([
                "field_name" => "bibliographicCitation",
            ]),
            "alternateIdentifier" => self::si4FieldWithDefaults([
                "field_name" => "alternateIdentifier",
            ]),
            "funder" => self::si4FieldWithDefaults([
                "field_name" => "funder",
            ]),
            "contactPerson" => self::si4FieldWithDefaults([
                "field_name" => "contactPerson",
            ]),
            "dataCollector" => self::si4FieldWithDefaults([
                "field_name" => "dataCollector",
            ]),
            "dataCurator" => self::si4FieldWithDefaults([
                "field_name" => "dataCurator",
            ]),
            "dataManager" => self::si4FieldWithDefaults([
                "field_name" => "dataManager",
            ]),
            "distributor" => self::si4FieldWithDefaults([
                "field_name" => "distributor",
            ]),
            "editor" => self::si4FieldWithDefaults([
                "field_name" => "editor",
            ]),
            "hostingInstitution" => self::si4FieldWithDefaults([
                "field_name" => "hostingInstitution",
            ]),
            "producer" => self::si4FieldWithDefaults([
                "field_name" => "producer",
            ]),
            "projectLeader" => self::si4FieldWithDefaults([
                "field_name" => "projectLeader",
            ]),
            "projectManager" => self::si4FieldWithDefaults([
                "field_name" => "projectManager",
            ]),
            "projectMember" => self::si4FieldWithDefaults([
                "field_name" => "projectMember",
            ]),
            "registrationAgency" => self::si4FieldWithDefaults([
                "field_name" => "registrationAgency",
            ]),
            "registrationAuthority" => self::si4FieldWithDefaults([
                "field_name" => "registrationAuthority",
            ]),
            "relatedPerson" => self::si4FieldWithDefaults([
                "field_name" => "relatedPerson",
            ]),
            "researchGroup" => self::si4FieldWithDefaults([
                "field_name" => "researchGroup",
            ]),
            "researcher" => self::si4FieldWithDefaults([
                "field_name" => "researcher",
            ]),
            "sponsor" => self::si4FieldWithDefaults([
                "field_name" => "sponsor",
            ]),
            "supervisor" => self::si4FieldWithDefaults([
                "field_name" => "supervisor",
            ]),
            "workPackageLeader" => self::si4FieldWithDefaults([
                "field_name" => "workPackageLeader",
            ]),
            "dateCollected" => self::si4FieldWithDefaults([
                "field_name" => "dateCollected",
            ]),
            "dateUpdated" => self::si4FieldWithDefaults([
                "field_name" => "dateUpdated",
            ]),
            "genre" => self::si4FieldWithDefaults([
                "field_name" => "genre",
            ]),
            "isCitedBy" => self::si4FieldWithDefaults([
                "field_name" => "isCitedBy",
            ]),
            "cites" => self::si4FieldWithDefaults([
                "field_name" => "cites",
            ]),
            "isSupplementTo" => self::si4FieldWithDefaults([
                "field_name" => "isSupplementTo",
            ]),
            "isSupplementedBy" => self::si4FieldWithDefaults([
                "field_name" => "isSupplementedBy",
            ]),
            "isContinuedBy" => self::si4FieldWithDefaults([
                "field_name" => "isContinuedBy",
            ]),
            "continues " => self::si4FieldWithDefaults([
                "field_name" => "continues ",
            ]),
            "hasMetadata" => self::si4FieldWithDefaults([
                "field_name" => "hasMetadata",
            ]),
            "isMetadataFor" => self::si4FieldWithDefaults([
                "field_name" => "isMetadataFor",
            ]),
            "isNewVersionOf" => self::si4FieldWithDefaults([
                "field_name" => "isNewVersionOf",
            ]),
            "isPreviousVersionOf" => self::si4FieldWithDefaults([
                "field_name" => "isPreviousVersionOf",
            ]),
            "isDocumentedBy" => self::si4FieldWithDefaults([
                "field_name" => "isDocumentedBy",
            ]),
            "documents" => self::si4FieldWithDefaults([
                "field_name" => "documents",
            ]),
            "isCompiledBy" => self::si4FieldWithDefaults([
                "field_name" => "isCompiledBy",
            ]),
            "compiles" => self::si4FieldWithDefaults([
                "field_name" => "compiles",
            ]),
            "isVariantFormOf" => self::si4FieldWithDefaults([
                "field_name" => "isVariantFormOf",
            ]),
            "isOriginalFormOf" => self::si4FieldWithDefaults([
                "field_name" => "isOriginalFormOf",
            ]),
            "isIdenticalTo" => self::si4FieldWithDefaults([
                "field_name" => "isIdenticalTo",
            ]),
            "isReviewedBy" => self::si4FieldWithDefaults([
                "field_name" => "isReviewedBy",
            ]),
            "reviews" => self::si4FieldWithDefaults([
                "field_name" => "reviews",
            ]),
            "isDerivedFrom" => self::si4FieldWithDefaults([
                "field_name" => "isDerivedFrom",
            ]),
            "isSourceOf" => self::si4FieldWithDefaults([
                "field_name" => "isSourceOf",
            ]),
            "size" => self::si4FieldWithDefaults([
                "field_name" => "size",
            ]),
            "version" => self::si4FieldWithDefaults([
                "field_name" => "version",
            ]),
            "methods" => self::si4FieldWithDefaults([
                "field_name" => "methods",
            ]),
            "seriesInformation" => self::si4FieldWithDefaults([
                "field_name" => "seriesInformation",
            ]),
            "geoLocationPoint" => self::si4FieldWithDefaults([
                "field_name" => "geoLocationPoint",
            ]),
            "geoLocationBox" => self::si4FieldWithDefaults([
                "field_name" => "geoLocationBox",
            ]),
            "geoLocationPlace" => self::si4FieldWithDefaults([
                "field_name" => "geoLocationPlace",
            ]),
            "funderIdentifier" => self::si4FieldWithDefaults([
                "field_name" => "funderIdentifier",
            ]),
            "fundingStream" => self::si4FieldWithDefaults([
                "field_name" => "fundingStream",
            ]),
            "awardNumber" => self::si4FieldWithDefaults([
                "field_name" => "awardNumber",
            ]),
            "awardTitle" => self::si4FieldWithDefaults([
                "field_name" => "awardTitle",
            ]),
            "isDescribedBy" => self::si4FieldWithDefaults([
                "field_name" => "isDescribedBy",
            ]),
            "describes" => self::si4FieldWithDefaults([
                "field_name" => "describes",
            ]),
            "publicationType" => self::si4FieldWithDefaults([
                "field_name" => "publicationType",
            ]),
            "objectWebPage" => self::si4FieldWithDefaults([
                "field_name" => "objectWebPage",
            ]),
            "licenseCondition" => self::si4FieldWithDefaults([
                "field_name" => "licenseCondition",
            ]),
            "host" => self::si4FieldWithDefaults([
                "field_name" => "host",
            ]),
            "volume" => self::si4FieldWithDefaults([
                "field_name" => "volume",
            ]),
            "issue" => self::si4FieldWithDefaults([
                "field_name" => "issue",
            ]),
            "startPage" => self::si4FieldWithDefaults([
                "field_name" => "startPage",
            ]),
            "endPage" => self::si4FieldWithDefaults([
                "field_name" => "endPage",
            ]),
            "edition" => self::si4FieldWithDefaults([
                "field_name" => "edition",
            ]),
            "conferencePlace" => self::si4FieldWithDefaults([
                "field_name" => "conferencePlace",
            ]),
            "audience" => self::si4FieldWithDefaults([
                "field_name" => "audience",
            ]),
            "distributionLocation" => self::si4FieldWithDefaults([
                "field_name" => "distributionLocation",
            ]),
            "tool" => self::si4FieldWithDefaults([
                "field_name" => "tool",
            ]),
            "distributionForm" => self::si4FieldWithDefaults([
                "field_name" => "distributionForm",
            ]),
            "modsContributor" => self::si4FieldWithDefaults([
                "field_name" => "modsContributor",
            ]),
            "dateCaptured" => self::si4FieldWithDefaults([
                "field_name" => "dateCaptured",
            ]),
            "dateOther" => self::si4FieldWithDefaults([
                "field_name" => "dateOther",
            ]),
            "pubPlace" => self::si4FieldWithDefaults([
                "field_name" => "pubPlace",
            ]),
            "issuance" => self::si4FieldWithDefaults([
                "field_name" => "issuance",
            ]),
            "frequency" => self::si4FieldWithDefaults([
                "field_name" => "frequency",
            ]),
            "script" => self::si4FieldWithDefaults([
                "field_name" => "script",
            ]),
            "form" => self::si4FieldWithDefaults([
                "field_name" => "form",
            ]),
            "original" => self::si4FieldWithDefaults([
                "field_name" => "original",
            ]),
            "preceding" => self::si4FieldWithDefaults([
                "field_name" => "preceding",
            ]),
            "succeeding" => self::si4FieldWithDefaults([
                "field_name" => "succeeding",
            ]),
            "series" => self::si4FieldWithDefaults([
                "field_name" => "series",
            ]),
        ];

        return $result;
    }
}
