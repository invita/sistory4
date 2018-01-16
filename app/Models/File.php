<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


/**
 * App\Models\File
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereData($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereStructTypeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Entity whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class File extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'path',
        'type'
    ];


    public function getUrl() {
        return "/storage/preview/?path=".$this->type."/".$this->path."/".$this->name;
    }
    public function getStorageName() {
        return self::makeStorageName($this->type, $this->path, $this->name);
    }
    public function getStorageFile() {
        return Storage::get($this->getStorageName());
    }

    public static function makeStorageName($type, $path, $name) {
        return "public/".$type."/".$path."/".$name;
    }

}