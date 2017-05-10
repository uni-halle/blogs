<?php
/**
 * The template for displaying Questionnaire
*/
?>

<?php
	$t_id = $quiz; 
	$term_meta = get_option( "taxonomy_term_$t_id" ); 
	$resultsPos = esc_attr($term_meta['resultPos']);
	$resultsShare = esc_attr($term_meta['shareResults']);
	$showResults = esc_attr($term_meta['showResults']);
	$showResultsCorrect = esc_attr($term_meta['showResultsCorrect']);
	$hdpageID = '';
	// lots of sanitation to ensure no line breaks occur and that links work correctly
	// there's almost definitely a better way to do this. Must optimize in future.

	$quizPagination = "true";
	$quizPool = $term_meta['pool'];
	$passText = $term_meta['passText'];
	$failText = $term_meta['failText'];
	$showQuestionContent = "no";
	$showQuestionContent = $term_meta['showIncorrectAnswerText'];

	$passText = nl2br($passText);
	$failText = nl2br($failText);
	$passText = strip_tags(stripslashes($passText), '<a><i><em><b><strong><u><br><p>');
	$failText = strip_tags(stripslashes($failText), '<a><i><em><b><strong><u><br><p>');
	$passText = trim(preg_replace('/\s+/', ' ', $passText));
	$failText = trim(preg_replace('/\s+/', ' ', $failText));

	$passPercent = esc_attr($term_meta['passPercent']);
	$quizTimerS = esc_attr($term_meta['quizTimerS']);
	if (!$quizTimerS) {$quizTimerS = "0";}
	$quizPaginate = esc_attr($term_meta['paginate']);
	if (!$quizPaginate) {$quizPaginate = "-1";}
	$randomizeQuestions = esc_attr($term_meta['randomizeQuestions']);
	if (!$randomizeQuestions) {$randomizeQuestions = "menu_order";}
	$randomizeAnswers = esc_attr($term_meta['randomizeAnswers']);
	if (!$randomizeAnswers) {$randomizeAnswers = "no";}
	if (isset($_GET['currentScore'])) {$currentScore = $_GET['currentScore'];}
	else {$currentScore ="NaN";}
	if (isset($_GET['maxValue'])) {$prevValue = $_GET['maxValue'];}
	else {$prevValue ="NaN";}
	$fb = get_option( 'hd_qu_fb' );
	$tw = get_option( 'hd_qu_tw' );
	$nextText = get_option( 'hd_qu_next' );
	$finishText = get_option( 'hd_qu_finish' );
	$questionText = get_option( 'hd_qu_questionName' );
	if (!$questionText) {$questionText = "Question";}
	if (!$resultsPos) {$resultsPos = "yes";}
	if (!$showResults) {$showResults = "yes";}
	if (!$resultsShare) {$resultsShare = "yes";}
	if (!$passText) {$passText = "Congratulations, you passed!";}
	if (!$failText) {$failText = "I'm sorry but you did not achieve the required score.";}
	if (!$passPercent) {$passPercent = "50";}
	if (!$tw) {$tw = "harmonic_design";}
	if (!$fb) {$fb = "no";}
        wp_localize_script('custom-script', 'jPagination', "no");
	$jPagination = "no";
	$jPage = 0;
?>

<div id ="hdQuestionnaireContent">

<?php
if ($quizTimerS != "0") { 
	
	wp_localize_script('custom-script', 'hdQuizTimer', "yes");
	wp_localize_script('custom-script', 'quizTimerS', $quizTimerS);

	echo '<div class ="hdCountdown"></div>';
}
else {
	wp_localize_script('custom-script', 'hdQuizTimer', "no");
}

if ($showResultsCorrect != "no") { 	
	wp_localize_script('custom-script', 'hdQuizShowResultsCorrect', "yes");
}
else {
	wp_localize_script('custom-script', 'hdQuizShowResultsCorrect', "no");
}
?>


<?php if ($resultsPos == "yes") { ?>
	<div id ="results">
		<?php
			if ( has_post_thumbnail($hdpageID) ) { 
			    // save this for when I finish implementing pass/fail featured image
			    echo '<div class ="hd_qu_featuredImage_wrapper"></div>';
			}
		?>
		<div id ="resultsTotal"></div>

	
	</div>

<?php } ?>



	<form action ="<?php echo plugin_dir_url( __FILE__ );?>result.php" method = "post" id ="questionnaire1" name = "questionnaire1">
		
	<?php
	wp_reset_postdata();
	wp_reset_query();
	global $post;
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;

	if ($randomizeQuestions == "rand"){		
		$quizPaginate = "-1";
	}

	if($quizPool > 0){
		$quizPaginate = $quizPool; //set the posts-per-page to the quiz pool amount
		$randomizeQuestions = "rand"; //force the quiz to randomize the questions
		$quizPagination = "false"; // disable WP pagination
	}

	// WP_Query arguments
	$args2 = array (
		'post_type'              => array( 'post_type_questionna' ),
		'tax_query' => array(
        		array(
           		'taxonomy' => 'quiz',
           		'terms' => $quiz,
        		)
    		),
		'pagination'             => $quizPagination,
		'posts_per_page'         => $quizPaginate,
		'paged' 		 => $paged,
		'orderby'                => $randomizeQuestions,
		'order'                  => 'ASC',
	);

	
	// The Query
	$query = new WP_Query( $args2 );
	$i = 0;
        $questionNumber = 0;
	if ($quizPaginate >= 1 && $paged > 1) { $questionNumber = ($paged * $quizPaginate) - $quizPaginate +1 ; }

	// The Loop
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post(); 
			$i++; 

			$answerFeaturedImage = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class23', true ) );
			$tooltip = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class12', true ) );
			$q = intval(esc_attr( get_post_meta( $post->ID, 'hdQue_post_class2', true ) ));
			$qRight = 0; 
			$q1 = get_post_meta( $post->ID, 'hdQue_post_class1', true );
			$q2 = get_post_meta( $post->ID, 'hdQue_post_class3', true );
			$q3 = get_post_meta( $post->ID, 'hdQue_post_class4', true );
			$q4 = get_post_meta( $post->ID, 'hdQue_post_class5', true );
			$q5 = get_post_meta( $post->ID, 'hdQue_post_class6', true );
			$q6 = get_post_meta( $post->ID, 'hdQue_post_class7', true );
			$q7 = get_post_meta( $post->ID, 'hdQue_post_class8', true );
			$q8 = get_post_meta( $post->ID, 'hdQue_post_class9', true );
			$q9 = get_post_meta( $post->ID, 'hdQue_post_class10', true );
			$q10 = get_post_meta( $post->ID, 'hdQue_post_class11', true );
			$img1 = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class13', true ) );
			$img2 = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class14', true ) );
			$img3 = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class15', true ) );
			$img4 = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class16', true ) );
			$img5 = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class17', true ) );
			$img6 = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class18', true ) );
			$img7 = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class19', true ) );
			$img8 = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class20', true ) );
			$img9 = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class21', true ) );
			$img10 = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class22', true ) );
			$questionContent = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class26', true ) );
			$questionAsTitle = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class24', true ) );
			$jPaginate = esc_attr( get_post_meta( $post->ID, 'hdQue_post_class25', true ) );


	?>
<?php
	if ($jPaginate == "yes") { 
		echo '<div class ="jPaginate"></div>'; 
		$jPage++;
		if ($jPagination == "no") { 
			wp_localize_script('custom-script', 'jPagination', "yes");
			$jPagination = "yes";			
		}
	}
?>
			
		<div class="question <?php if ($questionAsTitle == "yes") { echo "qTitle";} ?>">
<?php
	if (!$questionAsTitle || $questionAsTitle != "yes") { ?>

		<h3><?php if ($tooltip) {echo '<a class ="hdQuTooltip">? <span><b></b>'.$tooltip.'</span></a>';} ?><?php echo $questionText; ?> #<?php if ($questionNumber >= 1) { echo $questionNumber; $questionNumber++; } else { echo $i;}?>: <?php the_title();?></h3>


<?php

if ( has_post_thumbnail() ) { 
    echo '<div class ="hd_qu_featuredImage_wrapper">';
    the_post_thumbnail( 'hd_qu_size' , array( 'class' => 'hd_qu_featuredImage' ) );
    echo '</div>';
}
?>


<?php
		if ($answerFeaturedImage == "yes") { include('includes/templateAnswerFeaturedImage.php'); }
		else { include('includes/templateNormal.php'); }
	}
	else { $i = $i - 1; ?>
		<h3><?php if ($tooltip) {echo '<a class ="hdQuTooltip">? <span><b></b>'.$tooltip.'</span></a>';} ?><?php the_title();?></h3>
	<?php }

		if ($questionContent) {
			echo '<div class ="questionContent">'.htmlspecialchars_decode($questionContent).'</div>';
		}

?>
			
		</div>


	<?php	}	
	} else {
		// no posts found
	}

	// Restore original Post Data
	wp_reset_postdata();

	?>	

		<div class ="HDhidden" style ="display:none;">

		<textarea name ="quPass" id ="quPass"><?php echo $passText; ?></textarea>
		<textarea name ="quFail" id ="quFail"><?php echo $failText; ?></textarea>
		<input type="hidden" name ="showQuestionContent" id ="showQuestionContent" value="<?php echo stripslashes($showQuestionContent); ?>">
		<input type="hidden" name ="showResults" id ="showResults" value="<?php echo stripslashes($showResults); ?>">
		<input type="hidden" name ="quPassPercent" id ="quPassPercent" value="<?php echo esc_attr($passPercent); ?>">
		<input type="hidden" name ="quQuizTitle" id ="quQuizTitle" value="<?php the_title(); ?>">
		<input type="hidden" name ="quQuizAddress" id ="quQuizAddress" value="<?php the_permalink(); ?>">
		<input type="hidden" name ="quQuizShare" id ="quQuizShare" value="<?php echo $resultsShare; ?>">
		<input type="hidden" name ="quQuizTw" id ="quQuizTw" value="<?php echo $tw; ?>">
		<input type="hidden" name ="quQuizFb" id ="quQuizFb" value="<?php echo $fb; ?>">
		<input type="hidden" name ="quQuizPaged" id ="quQuizPaged" value="<?php echo $quizPaginate; ?>">

		<input type="hidden" name ="maxValue" id ="maxValue" value="<?php if ($prevValue != "NaN") { echo $prevValue + $i; } else {echo $i; } ?>">
		<input type="hidden" name ="currentScore" id ="currentScore" value="<?php echo $currentScore;?>">

		</div>
<?php 
	if ($query->max_num_pages > 1 ||  $quizPaginate != "-1") {
		if($quizPool == 0){
			if ($query->max_num_pages != $paged) { ?>
				<a href ="<?php the_permalink();?>page/<?php echo $paged + 1;?>?currentScore=" id ="hdQuNext"><?php if ($nextText) {echo $nextText;} else echo 'next'; ?></a>
			<?php }
		}
	}
 
	if ($query->max_num_pages == $paged || $quizPaginate == "-1" || $quizPool > 0) { ?>
		<p style ="text-align:center;"><input type ="submit" id ="hdQuFinish" value ="<?php if ($finishText) {echo $finishText;} else echo 'finish'; ?>"></p>
<?php }

	if ($jPagination == "yes") { 
		wp_localize_script('custom-script', 'jPaged', "$jPage");
	?>
		<p style ="text-align:center;"><a id ="jPaginateNext"><?php if ($nextText) {echo $nextText;} else echo 'next'; ?></a></p>
	<?php }
?>


					<div class="hdQuFin"></div>

	</form>

<?php if ($resultsPos == "no") { ?>
	<div id ="results">
		<?php
			if ( has_post_thumbnail($quiz) ) { 
			    // save this for when I finish implementing pass/fail featured image
			    echo '<div class ="hd_qu_featuredImage_wrapper"></div>';
			}
		?>
		<div id ="resultsTotal"></div>
		
	</div>

<?php } ?>


</div>