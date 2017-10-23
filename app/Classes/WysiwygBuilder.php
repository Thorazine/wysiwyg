<?php

namespace App\Classes;

use App\Models\Media;

class WysiwygBuilder {

	private $content;
	private $config;
	private $images;
	private $pageIds;

	public function saveReplace($content)
	{
		$this->content = $content;

		$this->findImages();

		$medias = Media::whereIn('id', array_keys($this->images))
			->get();

		// remove the ones that are not refrenced anymore

		// update the ones that are refrenced if needed
		foreach($medias as $media) {
			if($this->images[$media->id]['width'] != $media->width || $this->images[$media->id]['height'] != $media->height) {
				// dimensions changed
				$updateMedia = MediaMedia::find($media->id);
				$updateMedia->width = $this->images[$media->id]['width'];
				$updateMedia->height = $this->images[$media->id]['height'];
			}
		}

		// $this->replacers = config('wysiwyg.replace');



		foreach($this->replacers as $index => $replacer) {
			$this->replacers[$index]['ids'] = [];
			$this->replacers[$index]['ids'] = $this->findModelIds($replacer['find']);
		}

		$this->findImages();
	}


	private function findImages()
	{
		preg_match_all('/<[^>]*class="[^"]*\media-id-([0-9]{1,10})\b[^"]*"[^>]*width="([0-9]{1,10})"[^>]*height="([0-9]{1,10})"[^>]*>/i', $this->content, $matches);

		$images = [];

		foreach($matches as $index => $match) {
			$images[$match[1]] = [
				'id' => $match[1],
				'width' => $match[2],
				'height' => $match[3],
			];
		}

		$this->images = $images;

		return $this;
	}


	private function findModelIds($classPrefix)
	{
		preg_match_all('/<[^>]*class="[^"]*'.$classPrefix.'([0-9]{1,12})\b[^"]*"[^>]*>/i', $this->content, $matches);
		if(count($matches)) {
			$this->imageIds = $matches[1];
		}
		return $this;
	}

}
