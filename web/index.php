<?php
require("top_template.html");
include_once("ga.php");
?>
<div class="content">
		<p>
			যেকোন বাংলা আর্টিকেল এর লিংক অথবা পুরো লিখাটুকু কপি পেস্ট করুন, 
সংক্ষেপ আট বাক্যের কমে আপনাকে সেটির মূল কথাগুলো এনে দিবে। 

		</p>
<form action="songkhep.php" method="post" accept-charset="UTF-8">

			<textarea placeholder="লিংক অথবা লিখাটুকু এখানে পেস্ট করুন" rows="30" 
			name="article" type="text" id="comment_text" cols="50" class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"></textarea>
			<br />
			
			<label class="marginleft">সর্বোচ্চ বাক্যসংখ্যাঃ</label>			
			<input type="number" name="sent_lim" value="7" min="1" max="10">
			<label>বাছাইকরণ ধ্রুবকঃ</label>
			<input type="number" name="damp" value="0.1" min="0.1" step="0.05" max=".9">

			<br />

			<input class="button" type="submit" value="সংক্ষেপ করুন">

		</form>
</div>

<?php
require("bottom_template.html")
?>