<!DOCTYPE html>
<html>
<head>
	<title> Songkhep - A Bangla Text Summarization Engine </title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

	<style>
		body {background: #F0F0F0;}


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

		.marginleft {
			margin-left: 55px; 
		}

		.content a{
			font-size: 10px;
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
		</style>
	</head>
	<body>

		<h2> Songkhep - A Bangla Text Summarization Engine </h2>
		<h3> Author: <a href="http://sohanchy.com">Sohan Chowdhury</a> <br />
			Input a long bangla news article/text, and Songkhep will try to give you a short summary in less than 8 sentences.
			<br/>
			The modified combination of textrank and lexrank algorithm is still in its infancy,
			currently cosine similarity is combined with df-idf for graph weights, I hope to try a eigenvector + random walks approach soon. 
			<span style="color:#c0392b">The output may not be as good as you expect.</span>
			<br>
		</h3>
		<h2>The algorithm is raw here, so no html parsing is done, please paste only plain text.
		</h2>

		<form action="songkhep.php" method="post" accept-charset="UTF-8">

			<textarea placeholder="Copy paste a long bangla news article here." rows="30" 
			name="article" type="text" id="comment_text" cols="50" class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"></textarea>
			<br />
			
			<label class="marginleft">Sentence Limit:</label>			
			<input type="number" name="sent_lim" value="7" min="3" max="8">
			<label>Damping:</label>
			<input type="number" name="damp" value="0.1" min="0.1" step="0.05" max=".9">

			<br />

			<input class="button" type="submit" value="Submit">

		</form>


		<br />


		<div class="content">
			<b>References: </b> <br />
			<a href="https://web.eecs.umich.edu/~mihalcea/papers/mihalcea.emnlp04.pdf">TextRank: Bringing Order into Texts - Rada Mihalcea and Paul Tarau</a>
			<br />
			<a href="http://www.cs.cmu.edu/afs/cs/project/jair/pub/volume22/erkan04a-html/erkan04a.html">LexRank: Graph-based Lexical Centrality as Salience in Text Summarization -Güneş Erkan,Dragomir R. Radev</a><br />
			<a href="https://arxiv.org/pdf/1602.03606.pdf">Variations of the Similarity Function of
				TextRank for Automated Summarization - Federico Barrios,Federico L´opez,Luis Argerich, Rosita Wachenchauzer12</a>
				<br />

			</div>

		</body>
		</html>