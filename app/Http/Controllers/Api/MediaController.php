<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Image;
use Storage;

class MediaController extends Controller
{

	public function index(Request $request)
	{
		$medias = new Media;

		// if($request->filter) {
		// 	$medias = $medias->where('type', $request->filter);
		// }
		// if($request->filter) {
		// 	$medias = $medias->where('type', $request->filter);
		// }
		$medias = $medias->orderBy('id', 'desc')->paginate(25);

		return response()->json([
			'medias' => $medias
		], 200);
	}


	public function edit(Request $request)
	{
		$media = (new Media)
			->where('id', $request->id)
			->firstOrFail();

		return response()->json([
			'data' => $media,
		], 200);
	}


	public function update(Request $request)
	{
		$media = (new Media)->find($request->id);
		$media->filename = $request->filename;
		$media->save();

		return response()->json([], 200);
	}


    public function upload(Request $request)
    {
    	// bump the memory limit so we can do some nice rendering
    	ini_set('memory_limit','250M');

    	if($request->file('file')->isValid()) {

    		$file = pathinfo($request->file('file')->getClientOriginalName());

			// clean the filename
			$file['extension'] = strtolower($file['extension']);
			$file['filename'] = str_slug($file['filename']);

			$filename = $file['filename'].'.'.$file['extension'];

    		$request->file('file')->storeAs('original', $filename, config('filesystems.default'));

    		// get an instance of the original
    		$original = Image::make(Storage::disk(config('filesystems.default'))->get('original/'.$filename))->encode($file['extension'], 100);

    		$temp = clone $original;

			// create the image
			$image = $temp->fit(100, 100, function ($constraint) {
			    $constraint->aspectRatio();
			})
			->encode('jpg', 75);

			Storage::disk(config('filesystems.default'))->put('thumbnail/'.$filename, $image->getEncoded(), 'public');

			$image->destroy();

			// Prepare for insert
			$media = new Media;
			$media->filename = $filename;
			$media->save();
    		// $newFile = pathinfo($filename);
    		// $gallery = [];
    		// $gallery['filetype'] = $this->gallery->fileType($newFile['extension']);
    		// $gallery['filename'] = $newFile['filename'];
    		// $gallery['extension'] = $newFile['extension'];
    		// $gallery['title'] = $newFile['filename'];
    		// $gallery['filesize'] = @strlen((string) $original);
	    	// $gallery['width'] = $original->width();
	    	// $gallery['height'] = $original->height();
	    	// $gallery['created_at'] = date('Y-m-d H:i:s');
	    	// $gallery['updated_at'] = date('Y-m-d H:i:s');

	    	// save the image to DB
	    	// $id = $this->gallery->insertGetId($gallery);

    		return response()->json([
    			'id' => $media->id,
    			'filename' => $filename,
    			'extension' => 'jpg',
    			'name' => 'Poepie poep',
    			'size' => 1000,
    			'thumb' => Storage::disk(config('filesystems.default'))->url('thumbnail/'.$filename),
    			'full' => Storage::disk(config('filesystems.default'))->url('original/'.$filename),
    		], 200);
    	}
    	return response()->json([
    		'error' => 'not valid motherfucker',
    	], 500);
    }
}
