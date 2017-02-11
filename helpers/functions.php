<?php

function banglaStringToSentences($article,$stemmer){

	// mb_regex_encoding('UTF-8');
	// mb_internal_encoding("UTF-8"); 

	$article_utf8 = mb_convert_encoding($article, 'utf-8', 'utf-8');

	// $sentences_raw = mb_split($GLOBALS["sentence_end_patterns"],$article_utf8);
	$sentences_raw = preg_split('/'.$GLOBALS["sentence_end_patterns"].'/u',$article_utf8);

	$sk_sentence_list = [];

	foreach($sentences_raw as $sentence_raw) {

		$sk_sentence = new SKSentence($sentence_raw,$stemmer);

		if( $sk_sentence->isValid() ){
			$sk_sentence_list[] = $sk_sentence;		
		}

	}

	return $sk_sentence_list;

}

//http://stackoverflow.com/questions/929505/bag-of-words-model-2-php-functions-same-results-why
function calcSimilarity($words_a, $words_b) {
	global $zeit_check2;
	// $zeit_s = microtime(TRUE);
    $length1 = count($words_a); // number of words
    $length2 = count($words_b); // number of words
    $score_table = array();
    foreach($words_a as $term){
    	if(!isset($score_table[$term])) $score_table[$term] = 0;
    	$score_table[$term] += 1;
    }
    $score_table2 = array();
    foreach($words_b as $term){
    	if(isset($score_table[$term])){
    		if(!isset($score_table2[$term])) $score_table2[$term] = 0;
    		$score_table2[$term] += 1;
    	}
    }
    $score = 0;
    foreach($score_table2 as $key => $entry){
    	$score += $score_table[$key] * $entry;
    }
    $score = $score/($length1*$length2);
    $score *= 500;
    // $zeit_e = microtime(TRUE);
    // $zeit_check2 += ($zeit_e-$zeit_s);
    return $score;
}

function cosineSimilarity($words_a, $words_b){

	$bag_of_words = array_unique(array_merge($words_a,$words_b));

	$tf_idf_a; $tf_idf_b;

	foreach ($bag_of_words as $word) {
		$tf_idf_a[$word] = 0;
		$tf_idf_b[$word] = 0;
	}

	foreach ($words_a as $word) {
		$tf_idf_a[$word]++;
	}

	foreach ($words_b as $word) {
		$tf_idf_b[$word]++;
	}

	$cosine = new CosineSimilarity();

	return $cosine->similarity($tf_idf_a,$tf_idf_b);
}

function genCosineSimMatrix($sk_list,$cosine_threshold){

	$nodeCount = count($sk_list);
	
	$cosineSimMatrix = array_fill(0, $nodeCount, array_fill(0, $nodeCount, 0));
	
	$csmDegrees = array_fill(0, $nodeCount, 0);
	$lexScores = array_fill(0, $nodeCount, 0);


	foreach ($sk_list as $i => $senOuter) {
		foreach ($sk_list as $j => $senInner) {

			$cosineSimMatrix[$i][$j] = cosineSimilarity(
				$sk_list[$i]->getWords(),$sk_list[$j]->getWords()
				);

			if($cosineSimMatrix[$i][$j] > $cosine_threshold){
				// $cosineSimMatrix[$i][$j] = 1;
				$csmDegrees[$i]++;
			}
			else {
				$cosineSimMatrix[$i][$j] = 0;
			}
		}
	}


	for($i = 0; $i < count($cosineSimMatrix); $i++) {
		// echo " {$i} - {$csmDegrees[$i]} <br>";
		for($j = 0; $j < count($cosineSimMatrix[$i]); $j++) {

			$cosineSimMatrix[$i][$j] = $cosineSimMatrix[$i][$j] * $csmDegrees[$i];
			// $cosineSimMatrix[$i][$j] = round($cosineSimMatrix[$i][$j], 0, PHP_ROUND_HALF_UP);

			// if($cosineSimMatrix[$i][$j] > 0 && $i!=$j){
			// 	echo " [{$i}][{$j}] - {$csmDegrees[$i]} -> {$cosineSimMatrix[$i][$j]} <hr>";
			// }
		}
	}


	// echo "<br><br><br><br>";

	return $cosineSimMatrix;


}

function calcRanks($cosineSimMatrix, $damp = 0.15){
	$nodeCount = count($cosineSimMatrix);
	$inDegreeList = array_fill(0, $nodeCount, 0);
	$outDegreeList = array_fill(0, $nodeCount, 0);

	for($i = 0; $i < $nodeCount; $i++){
		for($j = 0; $j < count($cosineSimMatrix[$i]); $j++){
			$inDegreeList[$j] = $inDegreeList[$j] + $cosineSimMatrix[$i][$j];
			$outDegreeList[$i] = $outDegreeList[$i] + $cosineSimMatrix[$i][$j];
		}
	}

	foreach ($inDegreeList as $key => $value) {
		$value = $value - ((($inDegreeList[$key] - $outDegreeList[$key]) 
			/ $outDegreeList[$key])*$damp);
	}

	return $inDegreeList;

}


function sortSentences(&$sk_sentence_list,&$lexRanks){
	array_multisort($lexRanks, $sk_sentence_list);
}

?> 
