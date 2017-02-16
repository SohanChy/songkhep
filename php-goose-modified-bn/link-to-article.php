<?php
require('vendor/autoload.php');

use Goose\Client as GooseClient;


function linkToArtice($url){
	$goose = new GooseClient(['language' => 'bangla']);
$article = $goose->extractContent($url);

// $title = $article->getTitle();
// $metaDescription = $article->getMetaDescription();
// $metaKeywords = $article->getMetaKeywords();
// $canonicalLink = $article->getCanonicalLink();
// $domain = $article->getDomain();
// $tags = $article->getTags();
// $links = $article->getLinks();
// $videos = $article->getVideos();
// $articleText = $article->getCleanedArticleText();
// $entities = $article->getPopularWords();
// $image = $article->getTopImage();
// $allImages = $article->getAllImages();

return $article;

}

?> 
