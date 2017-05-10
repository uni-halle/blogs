<?php
/*
Template Name: Questionnaire Results
*/
?>

<?php 
error_reporting(0);
$paged = $_POST[quQuizPaged];
$total = 0;
$passPerc = stripslashes($_POST[quPassPercent]);
$fb = $_POST[quQuizFb];
$total_Questions = $_POST[maxValue];
$currentScore = $_POST[currentScore];

if ($paged != "-1") {
	for ($x = 1; $x <= $paged; $x++) {
		if ($_POST["q$x"]) {	   
		    $total = $total + $_POST["q$x"];
		}

	} 
}

else {
	foreach ($_POST as $key => $value) {
		if (ctype_digit($value)) {
			$total = $total + htmlspecialchars($value);
		}
	}
	// $total - $total - htmlspecialchars($value);
	$total = $total - $passPerc;
	$total = $total - $total_Questions;
	$total = $total - $currentScore;
	if($fb != "no") {$total = $total - $fb;}

}



$total = $total + $currentScore;
$total_Percent = $total / $total_Questions;
$total_Percent = $total_Percent * 100;
$total_Percent = round($total_Percent,2);

echo '<h2>You Scored <span>'.$total.'/'.$total_Questions.' or '.$total_Percent.'%</span></h2>';
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if ($total_Percent < $passPerc) { 
	$captionText = $_POST[quFail]; 
	echo '<h3>'.stripslashes(nl2br($captionText)).'</h3>';
}
else {
	$captionText = $_POST[quPass];

	echo '<h3>'.stripslashes(nl2br($captionText)).'</h3>';
}
?>



<?php if ($_POST[quQuizShare] == "yes") { 
$tw = stripslashes($_POST[quQuizTw]);
$quTitle = stripslashes($_POST[quQuizTitle]);
$quAddress = $_POST[quQuizAddress];
?>

		<div id ="hdQuShare">
			<h4>SHARE YOUR RESULTS</h4>

<?php if($fb == "no") { ?>
<a href = "http://www.facebook.com/sharer/sharer.php?u=<?php echo $quAddress;?>&title=<?php echo $quTitle;?>" target ="_blank"><img id ="fbShare" src="" alt ="Share your score!"></a>
<a href="https://twitter.com/intent/tweet?screen_name=<?php echo $tw; ?>&text=<?php echo 'I scored '.$total.'/'.$total_Questions.' on the '.$quTitle.' quiz. Can you beat me? '.$quAddress;?>" target ="_blank"><img id ="twShare" src="" alt ="Tweet your score!"/></a>

<script>
$hdQuJ("#fbShare").attr("src", pluginURL + "fbshare.png");
$hdQuJ("#twShare").attr("src", pluginURL + "twshare.png");

</script>


<?php

}

else { ?>


<div id="fb-root"></div>
<a href ="https://www.facebook.com/v2.4/dialog/share?app_id=<?php echo $fb; ?>&caption=<?php echo $captionText; ?>&description=<?php echo 'I scored '.$total.'/'.$total_Questions.' or '.$total_Percent.'%. Can you beat me?'; ?>&href=<?php echo $quAddress; ?>&locale=en_US&mobile_iframe=false&name=<?php echo $quTitle; ?>" target ="_blank"><img onclick="" style="cursor:pointer" id ="fbShare" src="" alt ="Share your score!"></a>

<a href="https://twitter.com/intent/tweet?screen_name=<?php echo $tw; ?>&text=<?php echo 'I scored '.$total.'/'.$total_Questions.' on the '.$quTitle.' quiz. Can you beat me? '.$quAddress;?>" target ="_blank"><img id ="twShare" src="" alt ="Tweet your score!"/></a>

<script>


$hdQuJ("#fbShare").attr("src", pluginURL + "fbshare.png");
$hdQuJ("#twShare").attr("src", pluginURL + "twshare.png");

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '<?php echo $fb; ?>',
      xfbml      : true,
      version    : 'v2.5'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));


function shareOnFB() {
    var e = {
        method: "feed",
        link: "<?php echo $quAddress; ?>",
        name: "<?php echo $quTitle; ?>",
        caption: "<?php echo $captionText; ?>",
        description: "<?php echo 'I scored '.$total.'/'.$total_Questions.' or '.$total_Percent.'%. Can you beat me?'; ?>"
    };
    FB.ui(e, function(t) {})
}

</script>



<?php }

?>
		</div>
<?php } ?>