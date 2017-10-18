<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Classes\WysiwygBuilder;

class Wysiwyg extends Model
{


    public function setContentAttribute($value)
    {
    	$wysiwygBuilder = new WysiwygBuilder;
        $this->attributes['content'] = $wysiwygBuilder->saveReplace($value);
    }
}
