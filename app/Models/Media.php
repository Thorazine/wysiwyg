<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Media extends Model
{
    protected $table = 'medias';


    protected $appends = [
    	'thumb',
    	'full',
    ];

    public function getThumbAttribute()
    {
    	return Storage::disk(config('filesystems.default'))->url('thumbnail/'.$this->filename);
    }


    public function getFullAttribute()
    {
    	return Storage::disk(config('filesystems.default'))->url('original/'.$this->filename);
    }
}
