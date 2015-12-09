<?php	

if($randomizeAnswers == "no") {
	for ($x = 1; $x <= 10; $x++) {
		if (${'q'.$x}){
			if ($q == $x) {$qRight = 1;}
			else {$qRight = 0;}
			echo '<input type="radio" name="q'.$i.'" id="qq'.$i.''.$x.'" value="'.$qRight.'" ><label for="qq'.$i.''.$x.'"><span></span> '.${'q'.$x}.'</label>';				
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
		if ($q == $x) {$qRight = 1;}
		else {$qRight = 0;}

		$answerOrder[$x]['answerLabel'] = ${'q'.$x};
		$answerOrder[$x]['answerValue'] = $qRight;
	}
	
	shuffle($answerOrder);
	
	foreach ($answerOrder as $key => $value){
		$x = $key + 1;
		echo '<input type="radio" name="q'.$i.'" id="qq'.$i.''.$x.'" value="'.$value['answerValue'].'" ><label for="qq'.$i.''.$x.'"><span></span> '.$value['answerLabel'].'</label>';			
	}

}
?>