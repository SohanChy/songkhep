<?php
require('../vendor/autoload.php');
//load datas
require("../data/globals.php");

//load functions
require("../helpers/functions.php");
require("../php-goose-modified-bn/link-to-article.php");

//autoload
spl_autoload_register(function ($class) {
	include '../classes/' . $class . '.php';
});

if(isset($_GET['url'])){
	// if(filter_var($_GET['url'], FILTER_VALIDATE_URL)){

	try {
		$articleFromLink = linkToArtice($_GET['url']);

		$article = $articleFromLink->getCleanedArticleText();
		$title =  $articleFromLink->getTitle();
		$url = $articleFromLink->getCanonicalLink();

		$sent_lim = 5;
		$damp = 0.1;
		$label = "সংক্ষিপ্ত ফলাফলঃ";

		if( isset($_GET['sent_lim']) ){
			$sent_lim = $_GET['sent_lim'];
		}
		if( isset($_GET['damp']) ){
			$damp = $_GET['damp'];
		}


	} catch (Exception $e) {
		header("Location: songkhep.php?failed={$e->getMessage()}"); /* Redirect browser */
	}

}
else{
	header("Location: songkhep.php?failed=empty input"); /* Redirect browser */
	exit();
}

//main code starts here



$stemmer = new Stemmer("../data/stemmer.rules");

$sk_sentence_list = banglaStringToSentences($article,$stemmer);

$cosineSimMatrix = genCosineSimMatrix($sk_sentence_list,$damp);

$ranks = calcRanks($cosineSimMatrix);

$avg = array_sum($ranks) / count($ranks);

$sentences = getSortedSentences($sk_sentence_list,$ranks,$avg,$sent_lim);

require("top_template.html");
include_once("ga.php");

?>		

<div class="content">

	<?php
	echo "<h4>{$label}</h4>
	<div class='summary'>
		<h2>{$title}</h2>";


		for($i = 0; $i < count($sentences) ; $i ++){
			echo $sentences[$i]->getOriginal()."<br />";
		}

		echo "</div>";

		echo "
		<p class='smalltext showInRight'>( <a href='$url'>সূত্র লিংক</a> | সংক্ষেপিত বাক্যসংখ্যাঃ: ".count($sentences)." | সর্বোচ্চ বাক্যসংখ্যাঃ: {$sent_lim} | বাছাইকরণ ধ্রুবকঃ: {$damp} )</p>";

		?>

		<a class="button showInRight" href="index.php">Try Another Article</a>

	</div>

	<?php
	require("bottom_template.html")
	?>

