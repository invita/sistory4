<?php

namespace App\Console\Commands;

use App\Models\Behaviour;
use App\Models\BehaviourField;
use App\Models\Si4\MetsToSi4;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TestTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test';

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

        $beh = Behaviour::getBehaviour("default");
        $advFields = json_decode($beh["advanced_search"], true);
        print_r($advFields);


        //$last_order = BehaviourField::getLastSortOrder("default");
        //echo "lastSortOrder: ".$last_order."\n";

        /*
        //$xmlFile = __DIR__ ."/../../../public/js/si4/entity/template/template.test.xml";
        $xmlFile = __DIR__ ."/../../../public/js/si4/entity/template/test.mods.xml";
        $xml = file_get_contents($xmlFile);

        $metsToSi4 = new MetsToSi4($xml);
        $result = $metsToSi4->run();
        print_r($result);
        */

        /*
        $doc = new \DOMDocument();
        $doc->loadXML($xml);
        $domXPath = new \DOMXPath($doc);

        $result = [];
        $result["ID"] = $domXPath->evaluate("string(/METS:mets/@ID2)");

        print_r($result);
        */

        /*
        if ($result instanceof \DOMNodeList) {
            foreach ($result as $r) {
                var_dump($r);
            }
        } else {
            var_dump($result);
        }
        */



        /*
        $agentRolesMap = [
            "CREATOR" => "creators",
            "DISSEMINATOR" => "disseminators",
        ];
        $result = [];

        // METS:mets/METS:metsHdr/METS:agent[@ROLE='?']
        foreach ($agentRolesMap as $agentRole => $agentGroup) {

            $agents = $domXPath->query("/METS:mets/METS:metsHdr/METS:agent[@ROLE='" . $agentRole . "']");
            echo "/METS:mets/METS:metsHdr/METS:agent[@ROLE='" . $agentRole . "']\n";
            var_dump($agents);

            //$agents = $metsHdr->xpath("METS:agent[@ROLE='".$agentRole."']");
            if ($agents->length) {
                $result[$agentGroup] = [];
                foreach ($agents as $agent) {
                    $agentResult = [];

                    $name = $domXPath->evaluate("string(METS:name)", $agent);
                    if ($name) $agentResult["name"] = $name;

                    echo "name: ".$name."\n";

                    $result[$agentGroup][] = $agentResult;
                }
            }
        }

        print_r($result);
        */



        /*


        */



        //$si4Fields = Si4Field::getSi4FieldsArray();
        //print_r($si4Fields);

        /*
        $entity = Entity::findOrNew(4);
        $entity->updateXml();
        print_r($entity->data);
        */

        //Artisan::call("thumbs:create", ["entityId" => 4]);

        /*
        $testPdf = __DIR__ ."/../../../storage/testData/test.pdf";
        echo $testPdf."\n";
        //file_get_contents($testPdf);
        $image = new \Imagick($testPdf."[0]");
        $image->setResolution(300, 0);
        $image->setCompressionQuality(85);
        $image->setImageFormat('jpeg');
        $image->writeImage($testPdf.".jpg");
        $image->clear();
        $image->destroy();
        */

        /*
        $serializerBuilder = SerializerBuilder::create();
        $serializerBuilder->addMetadataDir(app_path("Xsd/Test"), 'App\Xsd\Test');
        $serializerBuilder->addMetadataDir(app_path("Xsd/Mets"), 'App\Xsd\Mets');
        $serializerBuilder->addMetadataDir(app_path("Xsd/Dc"), 'App\Xsd\Dc');
        $serializerBuilder->addMetadataDir(app_path("Xsd/Premis"), 'App\Xsd\Premis');
        $serializerBuilder->addMetadataDir(app_path("Xsd/Mods"), 'App\Xsd\Mods');
        $serializerBuilder->addMetadataDir(app_path("Xsd/Entity"), 'App\Xsd\Entity');
        $serializerBuilder->addMetadataDir(app_path("Xsd/Collection"), 'App\Xsd\Collection');
        $serializerBuilder->configureHandlers(function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler()); // XMLSchema List handling
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler()); // XMLSchema date handling

            //$handler->registerSubscribingHandler(new Base64TypeHandler());
            //$handler->registerSubscribingHandler(new AnyTypeHandler());
            //$handler->registerSubscribingHandler(new AsTextTypeHandler());

        });

        $serializer = $serializerBuilder->build();

        // deserialize the XML into object
        $xml = file_get_contents(__DIR__ ."/../../../storage/testData/testTest.xml");
        $object = $serializer->deserialize($xml, 'App\Xsd\Test\Root', 'xml');
        //print_r($object);

        //$array = $object->toArray();
        //print_r($array);

        print_r($object);
        */


        // Test php2xsd
        /*
        $entity = new Entity();
        //$entity->data = file_get_contents(__DIR__ ."/../../../storage/testData/testMets.xml");
        $entity = Entity::findOrNew(4);
        $array = $entity->dataToObject();
        print_r($array);
        */

        //echo "DmdSecElName/0/MdWrapElName/XmlDataElName/TitlePropName\n";
        //print_r($array["DmdSecElName"][0]["MdWrapElName"]["XmlDataElName"]["TitlePropName"]);

        //echo "DmdSecElName/0/MdWrapElName/XmlDataElName\n";
        //print_r($array["DmdSecElName"][0]["MdWrapElName"]["XmlDataElName"]);

        //echo "AmdSecElName\n";
        //print_r($array["AmdSecElName"]);


        //echo "AmdSecElName/0/TechMDElName/0/MdWrapElName/XmlDataElName/ObjectIdentifierPropName\n";
        //print_r($array["AmdSecElName"][0]["TechMDElName"][0]["MdWrapElName"]["XmlDataElName"]["ObjectIdentifierPropName"]);

        //echo "DmdSecElName/0/MdWrapElName/XmlDataElName/TitlePropName\n";
        //print_r($array["DmdSecElName"][0]["MdWrapElName"]["XmlDataElName"]["TitlePropName"]);


        //$entity = Entity::where(["handle_id" => "1"])->first();
        //print_r($entity);


        // Fix xml data
        /*
        $entity = Entity::findOrNew(3);
        $entity->updateXml();
        $entity->save();
        Artisan::call("reindex:entity", ["entityId" => $entity->id]);
        */

        // Calculate primary
        /*
        $entity = Entity::findOrNew(7);
        $entity->calculateParents();
        echo "Calc EntityType: ".$entity->entity_type."\n";
        echo "Calc Primary: ".$entity->primary."\n";
        */


        // EntitySelect
        /*
        $this->info("EntitySelect::selectEntitiesByHandleIds");
        $result = EntitySelect::selectEntitiesByHandleIds(["menu1", "menu2"], null);
        echo "rowCount: ".$result["rowCount"]."\n";

        $this->info("EntitySelect::selectEntitiesBySystemIds");
        $result = EntitySelect::selectEntitiesBySystemIds([1,3], null);
        echo "rowCount: ".$result["rowCount"]."\n";

        $this->info("EntitySelect::selectEntitiesByParentHandle");
        $result = EntitySelect::selectEntitiesByParentHandle("menu1", null);
        echo "rowCount: ".$result["rowCount"]."\n";

        $this->info("EntitySelect::selectEntities");
        $result = EntitySelect::selectEntities();
        echo "rowCount: ".$result["rowCount"]."\n";
        */

        /*
        $result = EntitySelect::selectEntitiesByHandleIds(["menu1", "menu2"], [
            "filter" => [
                "struct_type" => "collection",
                "handle_id" => ["menu1"]
            ],
            "sortField" => null,
            "sortOrder" => "asc",
            "pageStart" => 0,
            "pageCount" => 20
        ]);
        */
        //print_r($result);
    }
}
