<?php

namespace App\Console\Commands;

use App\Helpers\ElasticHelpers;
use App\Models\Entity;
use App\Models\Elastic\EntityElastic;
use Illuminate\Console\Command;
use Symfony\Component\Config\Definition\Exception\Exception;

class EntityTestElasticConvert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entityTest:elasticConvert {entityId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test entity to elastic conversion';

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
        $entityId = $this->argument('entityId');

        $this->info("Entity {$entityId}");

        $entity = Entity::find($entityId);
        if ($entity) {
            $entityXmlParsed = $entity->dataToObject();
            $ee = new EntityElastic($entityXmlParsed);
            print_r($ee->getData());
        } else {
            $this->info("Entity {$entityId} not found");
        }
    }
}
