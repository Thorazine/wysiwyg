<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wysiwyg;
use App\Models\Image;

class WysiwygController extends Controller
{

	private $wysiwyg;
	private $image;

	public function __construct(Wysiwyg $wysiwyg, Image $image)
	{
		$this->wysiwyg = $wysiwyg;
		$this->image = $image;
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
