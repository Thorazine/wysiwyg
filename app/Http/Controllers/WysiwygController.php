<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wysiwyg;
use App\Models\Media;

class WysiwygController extends Controller
{

	private $wysiwyg;
	private $media;

	public function __construct(Wysiwyg $wysiwyg, Media $media)
	{
		$this->wysiwyg = $wysiwyg;
		$this->Media = $media;
	}


	public function index()
	{
		return view('wysiwyg');
	}


	public function image(Request $request)
	{
		return response()->json([
			'url' => 'http://www.readersdigest.ca/wp-content/uploads/2011/01/4-ways-cheer-up-depressed-cat.jpg',
		]);
	}

}
