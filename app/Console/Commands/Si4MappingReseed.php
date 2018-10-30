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

            // *** DC ***

            $groupDC = $this->addMappingGroup("DC", "DublinCore mappings");
            $this->addMappingField($groupDC, "title",
                "dc:title",
                "/",
                "/@xml:lang");
            $this->addMappingField($groupDC, "creator",
                "dc:creator",
                "/",
                "");


            // *** Mods ***

            $groupDC = $this->addMappingGroup("Mods", "Mods mappings");
            $this->addMappingField($groupDC, "title",
                "mods:mods/mods:titleInfo[not(@type='alternative' or @type='translated')]/mods:title",
                "/",
                "/@xml:lang");
            $this->addMappingField($groupDC, "title",
                "mods:mods/mods:titleInfo[not(@type='alternative' or @type='translated')]/mods:subTitle",
                "/",
                "/@xml:lang");
            $this->addMappingField($groupDC, "alternativeTitle",
                "mods:mods/mods:titleInfo[@type='alternative' or @type='translated']/mods:title",
                "/",
                "/@xml:lang");

            $this->info("Inserted mapping groups: ".MappingGroup::count());
            $this->info("Inserted mapping fields: ".MappingField::count());
            $this->info("All done!");
        }
    }


    private function addMappingGroup($groupName, $groupDesc) {
        $mappingGroup = new MappingGroup();
        $mappingGroup->name = $groupName;
        $mappingGroup->description = $groupDesc;
        $mappingGroup->data = "";
        $mappingGroup->save();
        return $mappingGroup;
    }

    private function addMappingField($mappingGroup, $targetField, $srcXPath, $valueXPath, $langXPath) {
        $mappingField = new MappingField();
        $mappingField->mapping_group_id = $mappingGroup->id;
        $mappingField->target_field = $targetField;
        $mappingField->source_xpath = $srcXPath;
        $mappingField->value_xpath = $valueXPath;
        $mappingField->lang_xpath = $langXPath;
        $mappingField->data = "";
        $mappingField->save();
        return $mappingField;
    }
}
