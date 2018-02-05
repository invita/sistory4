<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntityHandleSeq extends Model
{
    protected $table = 'entity_handle_seq';
    protected $primaryKey = 'entity_struct_type';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'entity_struct_type',
        'format',
        'last_num'
    ];

    public static function nextNumSeq($entity_struct_type) {
        $entityNumSeq = EntityHandleSeq::find($entity_struct_type);
        $entityNumSeq->format;
        $entityNumSeq->last_num++;
        $entityNumSeq->save();

        $result = str_replace("#", $entityNumSeq->last_num, $entityNumSeq->format);
        return $result;
    }

}
