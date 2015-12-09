<?php

echo '<input type ="hidden" name="q'.$i.'" id="qq'.$i.'" class ="hdHidden" value="0" />';
$qRight = "";

if($randomizeAnswers == "no") {

	for ($x = 1; $x <= 10; $x++) {

		if (${'img'.$x}){
			$defaultImage = plugin_dir_url( __FILE__ ).'featuredImageDefault.gif';
	
			if ($q == $x) {$qRight = "imageCorrect";}
			else {$qRight = 0;}

			if (${'img'.$x} != $defaultImage ) {
				$extension_pos = strrpos(${'img'.$x}, '.'); 
				$thumb = substr(${'img'.$x}, 0, $extension_pos) . '-400x400' . substr(${'img'.$x}, $extension_pos);
				echo '<div class ="imageAnswer '.$qRight.'">
					<img src ="'.$thumb.'" alt =""/>';
				if (${'q'.$x}) {
					echo '<div class ="imageInner">'.${'q'.$x}.'</div>';
				}
				echo '</div>';
			}
			else {echo '<div class ="imageAnswer '.$qRight.'"><img src = "'.plugin_dir_url( __FILE__ ).'featuredImageDefault.gif"'.' alt =""/></div>';}
		}
	} 
}
else {
	$numberOfAnswers = 0;
	$answerOrder = array();
	for ($x = 1; $x <= 10; $x++) {
		if (${'q'.$x}){$numberOfAnswers++;}
	}
	for ($x = 1; $x <= $numberOfAnswers; $x++) {
		if ($q == $x) {$qRight = "imageCorrect";}
		else {$qRight = 0;}

		$extension_pos = strrpos(${'img'.$x}, '.'); 
		$thumb = substr(${'img'.$x}, 0, $extension_pos) . '-400x400' . substr(${'img'.$x}, $extension_pos);
		$answerOrder[$x]['answerLabel'] = ${'q'.$x};
		$answerOrder[$x]['answerValue'] = $qRight;
		$answerOrder[$x]['answerImage'] = $thumb;

	}
	
	shuffle($answerOrder);
	
	foreach ($answerOrder as $key => $value){
		$x = $key + 1;

		echo '<div class ="imageAnswer '.$value['answerValue'].'">
		<img src ="'.$value['answerImage'].'" alt =""/>';
		echo '<div class ="imageInner">'.$value['answerLabel'].'</div>';		
		echo '</div>';		
	}
}

echo '<div class ="clearboth"></div>';

?>