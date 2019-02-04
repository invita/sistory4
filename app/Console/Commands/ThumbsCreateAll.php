<?php

namespace App\Console\Commands;

use App\Models\Entity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ThumbsCreateAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thumbs:createAll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create thumbs';

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
        if ($this->confirm('Are you sure you wish to recreate all thumbnails?', true)) {

            $entities = Entity::all();
            $cnt = 0;
            foreach ($entities as $entity) {
                $this->info($entity["id"]);
                Artisan::call("thumbs:create", ["entityId" => $entity["id"]]);
                $cnt++;
            }

            $this->info("All done! Thumbnails recreated: {$cnt}");
        }

    }
}
