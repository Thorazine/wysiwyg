<?php

namespace App\Classes;

class WysiwygBuilder {

	private $content;
	private $config;
	private $imageIds;
	private $pageIds;

	public function saveReplace($content)
	{
		$this->content = $content;

		$this->replacers = config('wysiwyg.replace');

		foreach($this->replacers as $index => $replacer) {
			$this->replacers[$index]['ids'] = [];
			$this->replacers[$index]['ids'] = $this->findModelIds($replacer['find']);
		}

		$this->findImages();
	}


	private function findImages()
	{
		preg_match_all('/<[^>]*class="[^"]*\image-id-([0-9]{1,10})\b[^"]*"[^>]*>/i', $this->content, $matches);
		if(count($matches)) {
			$this->imageIds = $matches[1];
		}
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
