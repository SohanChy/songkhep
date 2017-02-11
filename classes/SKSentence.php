<?php

class SKSentence
{
	private $original,$processed,$validity,$words;

	public function __construct($sentence,$stemmer)
	{
		$this->original = $sentence;
		$this->validity = $this->filterInvalidSentence($this->original);

		if($this->validity){
			$this->processed = $this->removeStopWords($this->original);
			$this->words = preg_split('/\s+/', $this->processed);

			if(count($this->words) >= 5 && $stemmer != null){
				foreach($this->words as $key => $word){
					$this->words[$key] = $stemmer->stemOfWord($word);
				}
			}
			else {
				$this->validity = false;
			}


		}
	}

	public function getOriginal(){
		return $this->original;
	}
	public function getProcessed(){
		return $this->processed;
	}
	public function getWords(){
		return $this->words;
	}
	public function isValid(){
		return $this->validity;
	}

	private function removeStopWords($sentence){

		foreach ($GLOBALS["stop_words"] as $word) {

			$highlight_replacement = "<span style='color:red'> ".$word ." </span>";
			$remove = " ";
			
			$sentence = trim($sentence);
			$sentence = str_replace('।', $remove ,$sentence);			
			$sentence = str_replace(' '.$word.' ', $remove ,$sentence);
		}
		return $sentence;

	}

	private function filterInvalidSentence($original){
		if(strlen($original) < 5){
			return false;
		}
		return true;
	}

	function isEnglish(){
		$sentence = $this->original;

		$unicode_len = mb_strlen($sentence, 'utf-8');
		$raw_len = strlen($sentence);

		if( $unicode_len >= $raw_len*.60 ){
			return true;
		}

		return false;
	}
}
?>
