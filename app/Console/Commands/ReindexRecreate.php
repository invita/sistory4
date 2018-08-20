<?php

namespace App\Console\Commands;

use App\Helpers\ElasticHelpers;
use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ReindexRecreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reindex:recreate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reindex entities from database into elastic';

    /**
     * Create a new command instance.
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
        if ($this->confirm('Are you sure you wish drop and recreate elastic index?', false)) {
            $this->info("Recreate index...");
            ElasticHelpers::recreateIndex();
            $this->info("Recreate index complete");
        }
    }
}
