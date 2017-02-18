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

$failed = true;

if(isset($_POST['article']) && isset($_POST['sent_lim']) && isset($_POST['damp']) && strlen($_POST['article']) > 8){
	$sent_lim = $_POST['sent_lim'];
	$damp = $_POST['damp'];
	$label = "সংক্ষিপ্ত ফলাফলঃ";

	if (strlen($_POST['article']) <700) 
	{
		// && filter_var(html_entity_decode(urldecode($_POST['article'])), FILTER_VALIDATE_URL)
		$url = html_entity_decode(urldecode($_POST['article']));
		
	    // $articleLink = linkToArtice($_POST['article']);
	    // $article = $articleLink->getCleanedArticleText();
	    // echo $articleLink->getTitle();

	    // echo $url;
	    header("Location: url-songkhep.php?url={$url}&sent_lim={$sent_lim}&damp={$damp}"); /* Redirect browser */
		exit();

	}
	else if(strlen($_POST['article']) >=700){
		$article = strip_tags(htmlentities($_POST['article']));
		$failed = false;
	}
}
if($failed){
	$article = file_get_contents('../data/test.txt');
	$sent_lim = 5;
	$damp = 0.1;
	$label = "আপনার দেয়া ইনপুট গ্রহণযোগ্য ছিলোনা, উদাহরণসরুপ একটি খবরের সংক্ষেপ দেয়া হলোঃ";

	if( isset($_GET['failed']) ){
		$label = $label . "<br />".$_GET['failed'];
	}
}

// echo $article;

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
		<div class='summary'>";

	for($i = 0; $i < count($sentences) ; $i ++){
		echo $sentences[$i]->getOriginal()."<br />";
	}

	echo "</div>";

echo "<p class='smalltext showInRight'>( সংক্ষেপিত বাক্যসংখ্যাঃ: ".count($sentences)." | সর্বোচ্চ বাক্যসংখ্যাঃ: {$sent_lim} | বাছাইকরণ ধ্রুবকঃ: {$damp} )</p>";

	?>

<a class="button showInRight" href="index.php">Try Another Article</a>

</div>

<?php
require("bottom_template.html")
?>

