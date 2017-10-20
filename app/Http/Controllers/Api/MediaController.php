<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
    	if($request->file->isValid()) {
    		return response()->json([
    			'filename' => 'poepie',
    			'extension' => 'jpg',
    			'name' => 'Poepie poep',
    			'size' => 1000,
    			'thumb' => 'http://localhost/wysiwyg/public/uploads/placeholder/100x100.jpg',
    			'full' => 'http://localhost/wysiwyg/public/uploads/original/hd1.jpg',
    		], 200);
    	}
    }
}
