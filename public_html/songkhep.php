<!DOCTYPE html>
<html>
<head>
	<title>Songkhep - A bangla text summarizer </title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<style>
		body {background: #bdc3c7;}


		h2,h3 {margin-left: 55px;margin-right: 55px;}

		textarea {
			margin-top: 10px;
			margin-left: 50px;
			width: 80%;
			height: 200px;
			-moz-border-bottom-colors: none;
			-moz-border-left-colors: none;
			-moz-border-right-colors: none;
			-moz-border-top-colors: none;
			background: none repeat scroll 0 0 rgba(0, 0, 0, 0.07);
			border-color: -moz-use-text-color #FFFFFF #FFFFFF -moz-use-text-color;
			border-image: none;
			border-radius: 6px 6px 6px 6px;
			border-style: none solid solid none;
			border-width: medium 1px 1px medium;
			box-shadow: 0 1px 2px rgba(0, 0, 0, 0.12) inset;
			color: #555555;
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
			font-size: 1em;
			line-height: 1.4em;
			padding: 5px 8px;
			transition: background-color 0.2s ease 0s;
		}

		.button {
			margin-left: 40%;
			background-color: #2980b9; /* Green */
			border: none;
			color: white;
			padding: 15px 32px;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 16px;
		}


		textarea:focus {
			background: none repeat scroll 0 0 #FFFFFF;
			outline-width: 0;
		}

		.content {
			font-family: solaimanLipi;
			font-size: 18px;
			font-weight: 400;
			margin-left: 55px;
			margin-right: 55px;
			background-color: #ecf0f1;
			width: 80%;
			min-height: 50%;
			padding: 50px;

		}
	</style>
</head>
<body>

	<h2>SongKhep - A bangla text summarizer </h2>
	<h3> Author: <a href="http://sohanchy.com">Sohan Chowdhury</a> <br />
		Input a long bangla news article/text, and Songkhep will try to give you a short summary in less than 8 sentences.
		<br/>
		The modified combination of textrank and lexrank algorithm is still in its infancy,
		currently cosine similarity is combined with df-idf for graph weights, I hope to try a eigenvector approach soon. 
		<span style="color:#c0392b">The output may not be as good as you expect.</span>
		<br></h3>
		<h2>The algorithm is raw here, so no html parsing is done, please paste only plain text.
		</h2>
		
		<div class="content">
			<?php

//load datas
			require("../data/globals.php");

//load functions
			require("../helpers/functions.php");

//autoload
			spl_autoload_register(function ($class) {
				include '../classes/' . $class . '.php';
			});


			if(isset($_POST['article'])){
				$article = htmlspecialchars($_POST['article']);

			}
			else{
				$article = file_get_contents('../data/test.txt');
			}

// echo $article;

//main code starts here



			$stemmer = new Stemmer("../data/stemmer.rules");

			$sk_sentence_list = banglaStringToSentences($article,$stemmer);

			$cosineSimMatrix = genCosineSimMatrix($sk_sentence_list,0.1);

			$ranks = calcRanks($cosineSimMatrix);

			$avg = array_sum($ranks) / count($ranks);

			sortSentences($sk_sentence_list,$ranks);

			for($i = 0; $i <= 8 && $ranks[$i] < $avg ; $i ++){
				echo $sk_sentence_list[$i]->getOriginal();
			}

/*foreach($sk_sentence_list as $key => $sk_sentence){

	echo $key." -> ".$sk_sentence->getOriginal()." <br>";

	if($key < count($sk_sentence_list) - 1){
		$similarity = calcSimilarity($sk_sentence_list[$key]->getWords(),
			$sk_sentence_list[$key+1]->getWords());
		echo "/\\  ".$similarity."  \/";
	}

	foreach ($sk_sentence->getWords() as $st_word) {
		echo $st_word." + ";
	}
	echo "<br>";
	
}
*/

?> 


</div><br />
<a class="button" href="index.html">Try Another Article</a>

</body>
</html>

