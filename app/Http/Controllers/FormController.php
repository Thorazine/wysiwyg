<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wysiwyg;

class FormController extends Controller
{

	private $wysiwyg;

	public function __construct(Wysiwyg $wysiwyg)
	{
		$this->wysiwyg = $wysiwyg;
	}


    public function store(Request $request)
    {
    	$wysiwyg = $this->wysiwyg;
    	$wysiwyg->content = $request->content;
    	$wysiwyg->save();

    	return response()->json([
    		'id' => $wysiwyg->id,
    	], 200);
    }
}
