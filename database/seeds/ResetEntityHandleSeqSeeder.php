<?php

use Illuminate\Database\Seeder;
use App\Models\EntityHandleSeq;

class ResetEntityHandleSeqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EntityHandleSeq::truncate();
        EntityHandleSeq::create([
            "entity_struct_type" => "entity",
            "format" => "entity#",
            "last_num" => 0
        ]);
        EntityHandleSeq::create([
            "entity_struct_type" => "collection",
            "format" => "collection#",
            "last_num" => 0
        ]);
        EntityHandleSeq::create([
            "entity_struct_type" => "file",
            "format" => "file#",
            "last_num" => 0
        ]);
    }
}
