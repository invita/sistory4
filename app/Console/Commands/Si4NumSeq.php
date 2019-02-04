<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Si4NumSeq extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'si4:numSeq {seqName} {seqValue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set entity number sequence';

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
        $seqName = $this->argument('seqName');

        if ($seqName != "entity" && $seqName != "collection" && $seqName != "file") {
            $this->info("seqName parameter must be one of: entity, collection, file");
            return false;
        }

        $seqValue = $this->argument('seqValue');
        if (!is_numeric($seqValue)) {
            $this->info("seqValue parameter must be numeric");
            return false;
        }
        $seqValue = intval($seqValue);


        $tableName = 'entity_handle_seq';
        $whereColumn = 'entity_struct_type';
        $setColumn = 'last_num';

        DB::table($tableName)->where([$whereColumn => $seqName])->update([$setColumn => $seqValue]);
        $this->info("OK");
        print_r(DB::table($tableName)->get());
    }
}
