<?php
/* Register Custom Post Type for Questionnaire
________________________________________________________
-------------------------------------------------------- */
function post_type_HD_Questions() {

	$labels = array(
		'name'                => _x( 'Questions', 'Post Type General Name', 'text_domain' ),
		'singular_name'       => _x( 'HD Quiz', 'Post Type Singular Name', 'text_domain' ),
		'menu_name'           => __( 'HD Quiz', 'text_domain' ),
		'name_admin_bar'      => __( 'HD Quiz', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Question:', 'text_domain' ),
		'all_items'           => __( 'All Questions', 'text_domain' ),
		'add_new_item'        => __( 'Add New Question', 'text_domain' ),
		'add_new'             => __( 'Add New Question', 'text_domain' ),
		'new_item'            => __( 'New Question', 'text_domain' ),
		'edit_item'           => __( 'Edit Question', 'text_domain' ),
		'update_item'         => __( 'Update Question', 'text_domain' ),
		'view_item'           => __( 'View Question', 'text_domain' ),
		'search_items'        => __( 'Search Question', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
	);
	$args = array(
		'label'               => __( 'HD Quiz', 'text_domain' ),
		'description'         => __( 'Post Type Description', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail','quiz'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-clipboard',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,		
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'capability_type'     => 'page',
	);
	register_post_type( 'post_type_questionna', $args );

}
add_action( 'init', 'post_type_HD_Questions', 0 );

// Register Custom Taxonomy
function custom_taxonomy_Quiz() {

	$labels = array(
		'name'                       => _x( 'Quizzes', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Quiz', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Quizzes', 'text_domain' ),
		'all_items'                  => __( 'All Quizzes', 'text_domain' ),
		'parent_item'                => __( 'Parent Quiz', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Quiz:', 'text_domain' ),
		'new_item_name'              => __( 'New Quiz Name', 'text_domain' ),
		'add_new_item'               => __( 'Add A New Quiz', 'text_domain' ),
		'edit_item'                  => __( 'Edit Quiz', 'text_domain' ),
		'update_item'                => __( 'Update Quiz', 'text_domain' ),
		'view_item'                  => __( 'View Quiz', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separate Quizzes with commas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Add or remove Quizzes', 'text_domain' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
		'popular_items'              => __( 'Popular Quizzes', 'text_domain' ),
		'search_items'               => __( 'Search Quizzes', 'text_domain' ),
		'not_found'                  => __( 'Not Found', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => false,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => false,
		'show_tagcloud'              => false,
		'rewrite'                    => false,
	);
	register_taxonomy( 'quiz', array( 'post_type_questionna' ), $args );

}
add_action( 'init', 'custom_taxonomy_Quiz', 0 );


/* Show Taxonomy filter on Questions page
________________________________________________________
-------------------------------------------------------- */

add_action( 'restrict_manage_posts', 'hd_qu_quiz_filter' );
function hd_qu_quiz_filter() {

    // only display these taxonomy filters on desired custom post_type listings
    global $typenow;
    if ($typenow == 'post_type_questionna') {

        // create an array of taxonomy slugs you want to filter by - if you want to retrieve all taxonomies, could use get_taxonomies() to build the list
        $filters = array('quiz');

        foreach ($filters as $tax_slug) {
            // retrieve the taxonomy object
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            // retrieve array of term objects per taxonomy
            $terms = get_terms($tax_slug);

            // output html for taxonomy dropdown filter
            echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
            echo "<option value=''>Show All $tax_name</option>";
            foreach ($terms as $term) {
                // output each select option line, check against the last $_GET to show the current option selected
                echo '<option value="'. $term->slug.'">' . $term->name .' (' . $term->count .')</option>';


            }
            echo "</select>";
        }
    }
}


/* Register Custom Tax Meta
________________________________________________________
-------------------------------------------------------- */

function quiz_taxonomy_custom_fields($tag) {
    wp_enqueue_style( 'hdAdmin', plugin_dir_url( __FILE__ ) . 'admin.css' );
    // Check for existing taxonomy meta for the term you're editing
    $t_id = $tag->term_id; // Get the ID of the term you're editing
    $term_meta = get_option( "taxonomy_term_$t_id" ); // Do the check
?>
<tr class="form-field h3Highlight">
	<th scope="row" valign="top" colspan ="2">
		<h3><?php _e('General Quiz Options'); ?></h3>
		<p class ="description small">The basic options for this quiz</p>
	</th>
	
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
		<label for ="term_meta[passPercent]"><?php _e('Quiz pass percentage'); ?></label>
	</th>
	<td>
<input class="widefat2" type="number" min="0" max="100" name="term_meta[passPercent]" id="term_meta[passPercent]" value="<?php echo $term_meta['passPercent'] ? $term_meta['passPercent'] : '70'; ?>" size="3" />%
<p class ="description small">Enter the percentage of questions a user needs to get correct to pass the quiz.</p>
	</td>
</tr>


<tr class="form-field">
	<th scope="row" valign="top">
		<label for ="term_meta[passText]"><?php _e('Quiz pass text'); ?></label>
	</th>
	<td>
		<?php wp_editor( stripslashes($term_meta['passText']), "hd_quiz_term_meta_passText", array('textarea_name' => 'term_meta[passText]','teeny' => true, 'media_buttons' => false, 'textarea_rows' => 1, 'quicktags' => false)); ?>
<p class ="description small">Customize the text that appears when a user completes the quiz and achieves the pass percentage or higher.</p>

	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for ="term_meta[failText]"><?php _e('Quiz fail text'); ?></label>
	</th>
	<td>
			<?php wp_editor( stripslashes($term_meta['failText']), "hd_quiz_term_meta_failText", array('textarea_name' => 'term_meta[failText]','teeny' => true, 'media_buttons' => false, 'textarea_rows' => 1, 'quicktags' => false)); ?>
<p class ="description small">Customize the text that appears when a user completes the quiz and does not achieve the pass percentage.</p>
	</td>
</tr>

<tr class="form-field h3Highlight">
	<th scope="row" valign="top" colspan ="2">
		<h3><?php _e('Quiz Results'); ?></h3>
		<p class ="description small">What happens when a user finishes a quiz</p>
	</th>
	
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label><?php _e('Share results'); ?></label>
	</th>
	<td>
<input type="radio" name="term_meta[shareResults]" id="term_meta[shareResults]1" value="yes" <?php if ($term_meta['shareResults'] =="yes") {echo 'checked';} if (! $term_meta['shareResults']) {echo 'checked';} ?>><label for="term_meta[shareResults]1"><span></span> Show</label><br/>
<input type="radio" name="term_meta[shareResults]" id="term_meta[shareResults]2" value="no" <?php if ($term_meta['shareResults'] == "no") {echo 'checked';} ?>><label for="term_meta[shareResults]2"><span></span> Hide</label>
<p class ="description small">This option shows or hides the Facebook and Twitter share buttons that appears when a user completes the quiz.</p>
	</td>
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
		<label><?php _e('Results Position'); ?></label>
	</th>
	<td>
<input type="radio" name="term_meta[resultPos]" id="term_meta[resultPos]1" value="yes" <?php if ($term_meta['resultPos'] =="yes") {echo 'checked';} if (! $term_meta['resultPos']) {echo 'checked';} ?>><label for="term_meta[resultPos]1"><span></span> Above Quiz</label><br/>
<input type="radio" name="term_meta[resultPos]" id="term_meta[resultPos]2" value="no" <?php if ($term_meta['resultPos'] == "no") {echo 'checked';} ?>><label for="term_meta[resultPos]2"><span></span> Below Quiz</label>
<p class ="description small">The site will automatically scroll to the position of the results.</p>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label><?php _e('Highlight correct / incorrect <strong>selected</strong> answers on completion'); ?></label>
	</th>
	<td>
<input type="radio" name="term_meta[showResults]" id="term_meta[showResults]1" value="yes" <?php if ($term_meta['showResults'] =="yes") {echo 'checked';} if (! $term_meta['showResults']) {echo 'checked';} ?>><label for="term_meta[showResults]1"><span></span> Yes</label><br/>
<input type="radio" name="term_meta[showResults]" id="term_meta[showResults]2" value="no" <?php if ($term_meta['showResults'] == "no") {echo 'checked';} ?>><label for="term_meta[showResults]2"><span></span> No</label>
<p class ="description small">This feature allows you to enable or disable showing what answers a user got right or wrong.</p>

	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label><?php _e('Highlight the correct answers on completion'); ?></label>
	</th>
	<td>
<input type="radio" name="term_meta[showResultsCorrect]" id="term_meta[showResultsCorrect]1" value="yes" <?php if ($term_meta['showResultsCorrect'] =="yes") {echo 'checked';} ?>><label for="term_meta[showResultsCorrect]1"><span></span> Yes</label><br/>
<input type="radio" name="term_meta[showResultsCorrect]" id="term_meta[showResultsCorrect]2" value="no" <?php if ($term_meta['showResultsCorrect'] == "no") {echo 'checked';} if (! $term_meta['showResultsCorrect']) {echo 'checked';}  ?>><label for="term_meta[showResultsCorrect]2"><span></span> No</label>
<p class ="description small">By default, HD Quiz will only show if a user's <strong>selected</strong> answer was right or wrong.</p>
<p class ="description small">Enabling this feature will go the extra step and show what the correct answer was if the user got the question wrong.</p>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label><?php _e('Show the "Text that appears if answer was wrong" even if the user got the question right.'); ?></label>
	</th>
	<td>
<input type="radio" name="term_meta[showIncorrectAnswerText]" id="term_meta[showIncorrectAnswerText]1" value="yes" <?php if ($term_meta['showIncorrectAnswerText'] =="yes") {echo 'checked';} ?>><label for="term_meta[showIncorrectAnswerText]1"><span></span> Yes</label><br/>
<input type="radio" name="term_meta[showIncorrectAnswerText]" id="term_meta[showIncorrectAnswerText]2" value="no" <?php if ($term_meta['showIncorrectAnswerText'] == "no") {echo 'checked';} if (! $term_meta['showIncorrectAnswerText']) {echo 'checked';}  ?>><label for="term_meta[showIncorrectAnswerText]2"><span></span> No</label>
<p class ="description small">Each indivdual question can have accompanying text that will show if the user selects the wrong answer.</p>
<p class ="description small">Enabling this feature will go the extra step and show this text even if the selected answer was correct.</p>
	</td>
</tr>

<tr class="form-field h3Highlight">
	<th scope="row" valign="top" colspan ="2">
		<h3><?php _e('Advanced Quiz Options'); ?></h3>
		<p class ="description small">These are the advanced options for the quiz if you want that extra control.</p>
	</th>
	
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for ="term_meta[quizTimer]"><?php _e('Timer / Countdown'); ?></label>
		<br/>leave blank to disable
	</th>
	<td>
<input class="widefat2" type="number" min="0" max="9999" name="term_meta[quizTimerS]" id="term_meta[quizTimerS]" value="<?php echo $term_meta['quizTimerS'] ? $term_meta['quizTimerS'] : '0'; ?>" size="3" /><br/>
<p class ="description">Enter how many seconds total. So 3 minutes would be 180. </p>
<p class ="description small"><strong>Please note</strong> that the timer will NOT work if the below WP Pagination feature is being used (it will work for jQuery pagination).</p>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label><?php _e('Randomize <u>Question</u> Order'); ?></label>
	</th>
	<td>
<input type="radio" name="term_meta[randomizeQuestions]" id="term_meta[randomizeQuestions]1" value="rand" <?php if ($term_meta['randomizeQuestions'] =="rand") {echo 'checked';} ?>><label for="term_meta[randomizeQuestions]1"><span></span> Yes</label><br/>
<input type="radio" name="term_meta[randomizeQuestions]" id="term_meta[randomizeQuestions]2" value="menu_order" <?php if ($term_meta['randomizeQuestions'] == "menu_order") {echo 'checked';} if (! $term_meta['randomizeQuestions']) {echo 'checked';} ?>><label for="term_meta[randomizeQuestions]2"><span></span> No</label>
<p class ="description small"><strong>Please note</strong> that randomizing the questions is NOT possible if the below WP Pagination feature is being used (it will work for jQuery pagination).</p>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label><?php _e('Randomize <u>Answer</u> Order'); ?></label>
	</th>
	<td>
<input type="radio" name="term_meta[randomizeAnswers]" id="term_meta[randomizeAnswers]1" value="yes" <?php if ($term_meta['randomizeAnswers'] =="yes") {echo 'checked';} ?>><label for="term_meta[randomizeAnswers]1"><span></span> Yes</label><br/>
<input type="radio" name="term_meta[randomizeAnswers]" id="term_meta[randomizeAnswers]2" value="no" <?php if ($term_meta['randomizeAnswers'] == "no") {echo 'checked';} if (! $term_meta['randomizeAnswers']) {echo 'checked';} ?>><label for="term_meta[randomizeAnswers]2"><span></span> No</label>
<p class ="description small">This feature will randomize the order that each answer is displayed.</p>

	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label><?php _e('Use Pool of Questions'); ?></label><br/>
		leave blank to disable
	</th>
	<td>
<input class="widefat2" type="number" min="0" max="30" name="term_meta[pool]" id="term_meta[pool]" value="<?php echo $term_meta['pool'] ? $term_meta['pool'] : '0'; ?>" size="3" /><br/>
<p class ="description">Enter how many questions to grab. </p>
<p class ="description small"><strong>Please note</strong> that this feature CANNOT be used with WP Pagination. This is a limiation of WordPress.</p>
<p class ="description small">If used, this feature will randomly grab the amount of questions entered from the total amount of questions in that quiz. <strong>Example:</strong> If your quiz has 100 questions but you want the quiz to only contain 20 questions chosen at random.</p>

	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label><?php _e('WP Pagination'); ?></label><br/>
		leave blank to disable
	</th>
	<td>
<input class="widefat2" type="number" min="0" max="30" name="term_meta[paginate]" id="term_meta[paginate]" value="<?php echo $term_meta['paginate'] ? $term_meta['paginate'] : '0'; ?>" size="3" /><br/>
<p class ="description">Enter how many questions per page. </p>
<p class ="description small"><strong>Please note</strong> that this feature should really only be used if you want to force page refreshes for ad revenue or similar.</p>
<p class ="description small">Most of you should use the jQuery pagination (found on each individual Question) instead. <a href ="http://harmonicdesign.ca/hd-quiz-pagination/">Click here to learn more</a> about the difference between WordPress pagination and jQuery pagination.</p>

	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label><?php _e('Quiz Shortcode'); ?></label>
	</th>
<td>
<p>Use the following shortcode to render this quiz:<br/> <code>[HDquiz quiz = "<?php echo  $_GET["tag_ID"]; ?>"]</code></p>
<p>The quiz will comprise of any questions attached to this quiz.</p>
<hr/>
</td>
</tr>

<?php
}


add_action( 'quiz_edit_form_fields', 'quiz_taxonomy_custom_fields', 10, 2 ); 


// A callback function to save our extra taxonomy field(s)
function save_taxonomy_custom_fields( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_term_$t_id" );
        $cat_keys = array_keys( $_POST['term_meta'] );
            foreach ( $cat_keys as $key ){
            if ( isset( $_POST['term_meta'][$key] ) ){
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        //save the option array
        update_option( "taxonomy_term_$t_id", $term_meta );
    }
}
 
add_action( 'edited_quiz', 'save_taxonomy_custom_fields', 10, 2 );  


add_filter('manage_edit-quiz_columns', 'riv_quiz_columns', 5);
add_action('manage_quiz_custom_column', 'riv_quiz_custom_columns', 5, 3);

function riv_quiz_columns($defaults) {
	unset($defaults['description']);
	unset($defaults['slug']);
	$defaults['riv_quiz_ids'] = __('Shortcode');
	return $defaults;
}

function riv_quiz_custom_columns($value, $column_name, $id) {
	if( $column_name == 'riv_quiz_ids' ) {
		return '[HDquiz quiz = "'.(int)$id.'"]';
	}
}

add_action( 'admin_footer-edit-tags.php', 'hdQu_remove_cat_tag_description' );

function hdQu_remove_cat_tag_description(){
    global $current_screen;
    switch ( $current_screen->id ) 
    {
        case 'edit-tags.php?taxonomy=quiz':
            break;        
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready( function($) {
        $('.taxonomy-quiz .term-description-wrap').remove();
        $('.taxonomy-quiz .term-slug-wrap').remove();
        $('.taxonomy-quiz .term-parent-wrap').remove();
    });
    </script>
    <?php
}


/* Register Custom Questionnaire Meta
________________________________________________________
-------------------------------------------------------- */

add_action( 'load-post.php', 'hdQue_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'hdQue_post_meta_boxes_setup' );


function hdQue_post_meta_boxes_setup() {	
	add_action( 'add_meta_boxes', 'hdQue_add_post_meta_boxes' );	
	add_action( 'save_post', 'hdQue_save_post_class_meta', 10, 2 );
}

function hdQue_add_post_meta_boxes() {
	add_meta_box(
		'hdQue-post-class',
		 esc_html__( 'Answers', 'example' ),
		'hdQue_post_class_meta_box',
		'post_type_questionna',
		'normal',
		'default'
	);	
}

function hdQue_post_class_meta_box( $object, $box ) { ?>
<?php wp_nonce_field( basename( __FILE__ ), 'hdQue_post_class_nonce' ); ?>

<?php 
wp_enqueue_style( 'hdAdmin', plugin_dir_url( __FILE__ ) . 'admin.css' );
wp_enqueue_script(
	'custom-script',
	plugins_url( 'customAdmin.js' , __FILE__ ),
	array( 'jquery' ),
	'1.0',
        true
); 
wp_enqueue_media();
?>


<div id ="hdAdvancedFeatures">
	<h3>Advanced Options</h3>

	<div class ="hdAdvancedFeatures">
		<?php $answerFeaturedImage = esc_attr( get_post_meta( $object->ID, 'hdQue_post_class23', true )); ?>
		<a class ="hdQuTooltip toolLeft">? <span><b></b>Enable this if you want a user to select an image as their answer.</span></a>
		<input type="hidden" name="hdQue-post-class23" value="no"> 		
		<label><input type="checkbox" name="hdQue-post-class23" id ="hdQue-post-class23" value="yes" <?php if ($answerFeaturedImage == "yes") {echo 'checked';} ?>> <strong>Image Answers</strong></label>
	</div>
	<div class ="hdAdvancedFeatures">

		<?php $answerAsTitle = esc_attr( get_post_meta( $object->ID, 'hdQue_post_class24', true )); ?>
		<a class ="hdQuTooltip toolLeft">? <span><b></b>Enable this if you want to use this question as a title or heading.</span></a>
		<input type="hidden" name="hdQue-post-class24" value="no"> 		
		<label><input type="checkbox" name="hdQue-post-class24" id ="hdQue-post-class24" value="yes" <?php if ($answerAsTitle == "yes") {echo 'checked';} ?>> <strong>Question as Title</strong></label>			
	</div>
	<div class ="hdAdvancedFeatures">
		<?php $paginate = esc_attr( get_post_meta( $object->ID, 'hdQue_post_class25', true )); ?>
		<a class ="hdQuTooltip toolLeft">? <span><b></b>Start a new page with this question (jQuery pagination)</span></a>
		<input type="hidden" name="hdQue-post-class25" value="no"> 		
		<label><input type="checkbox" name="hdQue-post-class25" id ="hdQue-post-class25" value="yes" <?php if ($paginate == "yes") {echo 'checked';} ?>> <strong>Paginate</strong></label>			
	</div>

</div>



<table width="100%" cellspacing="0" cellpadding="0" id = "hdQuestionnaire">
	<tr>
		<td width="20px">
			<strong>#</strong>
		</td>
		<td width ="100%">
			<strong>Options</strong>
		</td>
		<td align ="left" width="110px" class ="answerFeaturedImage <?php if ($answerFeaturedImage == "yes") {echo 'showImage'; }?>">
			<strong>Featured Image</strong>
		</td>
		<td width="40px">
			<strong>Answer</strong>
		</td>
	</tr>

	<tr>
		<td valign="top">1</td>
		<td valign="top">
			<input class="widefat" type="text" name="hdQue-post-class1" id="hdQue-post-class1" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class1', true ) ); ?>" size="30" />
		</td>
		<td width="80px" class ="answerFeaturedImage <?php if ($answerFeaturedImage == "yes") {echo 'showImage'; }?>">
		<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class13', true ) )) { ?>
			<img src ="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class13', true ) ); ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage1"/>
		<?php	} else { ?>
			<img src ="<?php echo plugin_dir_url( __FILE__ ).'includes/featuredImageDefault.gif'; ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage1"/>
		<?php }	?>
			<input type="hidden" name="hdQue-post-class13" id="hdQue-post-class13" value="<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class13', true ) )) { echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class13', true ) ); }?>" />
		</td>
		<td valign="top">
			<input type="hidden" name="hdQue-post-class2" value="no">
			<input type="radio" class ="answer" name="hdQue-post-class2" id="hdQue-post-class2" value="1" <?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class2', true )) == "1") {echo 'checked';} ?>>
		</td>
	</tr>	

	<tr>
		<td valign="top">2</td>
		<td valign="top">
			<input class="widefat" type="text" name="hdQue-post-class3" id="hdQue-post-class3" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class3', true ) ); ?>" size="30" />
		</td>
		<td width="80px" class ="answerFeaturedImage <?php if ($answerFeaturedImage == "yes") {echo 'showImage'; }?>">
		<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class14', true ) )) { ?>
			<img src ="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class14', true ) ); ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage2"/>
		<?php	} else { ?>
			<img src ="<?php echo plugin_dir_url( __FILE__ ).'includes/featuredImageDefault.gif'; ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage2"/>
		<?php }	?>
			<input type="hidden" name="hdQue-post-class14" id="hdQue-post-class14" value="<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class14', true ) )) { echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class14', true ) ); }?>"  />
		</td>
		<td valign="top">
			<input type="radio" class ="answer" name="hdQue-post-class2" id="hdQue-post-class2" value="2" <?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class2', true )) == "2") {echo 'checked';} ?>>		</td>
	</tr>	

	<tr>
		<td valign="top">3</td>
		<td valign="top">
			<input class="widefat" type="text" name="hdQue-post-class4" id="hdQue-post-class4" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class4', true ) ); ?>" size="30" />
		</td>
		<td width="80px" class ="answerFeaturedImage <?php if ($answerFeaturedImage == "yes") {echo 'showImage'; }?>">
		<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class15', true ) )) { ?>
			<img src ="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class15', true ) ); ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage3"/>
		<?php	} else { ?>
			<img src ="<?php echo plugin_dir_url( __FILE__ ).'includes/featuredImageDefault.gif'; ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage3"/>
		<?php }	?>
		<input type="hidden" name="hdQue-post-class15" id="hdQue-post-class15" value="<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class15', true ) )) { echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class15', true ) ); }?>" /> 
			
		</td>
		<td valign="top">
			<input type="radio" class ="answer" name="hdQue-post-class2" id="hdQue-post-class2" value="3" <?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class2', true )) == "3") {echo 'checked';} ?>>		</td>
	</tr>	

	<tr>
		<td valign="top">4</td>
		<td valign="top">
			<input class="widefat" type="text" name="hdQue-post-class5" id="hdQue-post-class5" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class5', true ) ); ?>" size="30" />
		</td>
		<td width="80px" class ="answerFeaturedImage <?php if ($answerFeaturedImage == "yes") {echo 'showImage'; }?>">
		<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class16', true ) )) { ?>
			<img src ="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class16', true ) ); ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage4"/>
		<?php	} else { ?>
			<img src ="<?php echo plugin_dir_url( __FILE__ ).'includes/featuredImageDefault.gif'; ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage4"/>
		<?php }	?>
			<input type="hidden" name="hdQue-post-class16" id="hdQue-post-class16" value="<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class16', true ) )) { echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class16', true ) ); }?>" /> 
		</td>
		<td valign="top">
			<input type="radio" class ="answer" name="hdQue-post-class2" id="hdQue-post-class2" value="4" <?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class2', true )) == "4") {echo 'checked';} ?>>		</td>
	</tr>	

	<tr>
		<td valign="top">5</td>
		<td valign="top">
			<input class="widefat" type="text" name="hdQue-post-class6" id="hdQue-post-class6" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class6', true ) ); ?>" size="30" />
		</td>
		<td width="80px" class ="answerFeaturedImage <?php if ($answerFeaturedImage == "yes") {echo 'showImage'; }?>">
		<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class17', true ) )) { ?>
			<img src ="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class17', true ) ); ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage5"/>
		<?php	} else { ?>
			<img src ="<?php echo plugin_dir_url( __FILE__ ).'includes/featuredImageDefault.gif'; ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage5"/>
		<?php }	?>
			<input type="hidden" name="hdQue-post-class17" id="hdQue-post-class17" value="<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class17', true ) )) { echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class17', true ) ); }?>"  />
		</td>
		<td valign="top">
			<input type="radio" class ="answer" name="hdQue-post-class2" id="hdQue-post-class2" value="5" <?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class2', true )) == "5") {echo 'checked';} ?>>		</td>
	</tr>	

	<tr>
		<td valign="top">6</td>
		<td valign="top">
			<input class="widefat" type="text" name="hdQue-post-class7" id="hdQue-post-class7" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class7', true ) ); ?>" size="30" />
		</td>
		<td width="80px" class ="answerFeaturedImage <?php if ($answerFeaturedImage == "yes") {echo 'showImage'; }?>">
		<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class18', true ) )) { ?>
			<img src ="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class18', true ) ); ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage6"/>
		<?php	} else { ?>
			<img src ="<?php echo plugin_dir_url( __FILE__ ).'includes/featuredImageDefault.gif'; ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage6"/>
		<?php }	?>
			<input type="hidden" name="hdQue-post-class18" id="hdQue-post-class18" value="<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class18', true ) )) { echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class18', true ) ); }?>" /> 
		</td>
		<td valign="top">
			<input type="radio" class ="answer" name="hdQue-post-class2" id="hdQue-post-class2" value="6" <?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class2', true )) == "6") {echo 'checked';} ?>>		</td>
	</tr>	

	<tr>
		<td valign="top">7</td>
		<td valign="top">
			<input class="widefat" type="text" name="hdQue-post-class8" id="hdQue-post-class8" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class8', true ) ); ?>" size="30" />
		</td>
		<td width="80px" class ="answerFeaturedImage <?php if ($answerFeaturedImage == "yes") {echo 'showImage'; }?>">
		<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class19', true ) )) { ?>
			<img src ="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class19', true ) ); ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage7"/>
		<?php	} else { ?>
			<img src ="<?php echo plugin_dir_url( __FILE__ ).'includes/featuredImageDefault.gif'; ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage7"/>
		<?php }	?>
			<input type="hidden" name="hdQue-post-class19" id="hdQue-post-class19" value="<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class19', true ) )) { echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class19', true ) ); }?>"  />
		</td>
		<td valign="top">
			<input type="radio" class ="answer" name="hdQue-post-class2" id="hdQue-post-class2" value="7" <?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class2', true )) == "7") {echo 'checked';} ?>>		</td>
	</tr>	

	<tr>
		<td valign="top">8</td>
		<td valign="top">
			<input class="widefat" type="text" name="hdQue-post-class9" id="hdQue-post-class9" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class9', true ) ); ?>" size="30" />
		</td>
		<td width="80px" class ="answerFeaturedImage <?php if ($answerFeaturedImage == "yes") {echo 'showImage'; }?>">
		<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class20', true ) )) { ?>
			<img src ="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class20', true ) ); ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage8"/>
		<?php	} else { ?>
			<img src ="<?php echo plugin_dir_url( __FILE__ ).'includes/featuredImageDefault.gif'; ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage8"/>
		<?php }	?>
			<input type="hidden" name="hdQue-post-class20" id="hdQue-post-class20" value="<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class20', true ) )) { echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class20', true ) ); }?>" /> 
		</td>
		<td valign="top">
			<input type="radio" class ="answer" name="hdQue-post-class2" id="hdQue-post-class2" value="8" <?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class2', true )) == "8") {echo 'checked';} ?>>		</td>
	</tr>	

	<tr>
		<td valign="top">9</td>
		<td valign="top">
			<input class="widefat" type="text" name="hdQue-post-class10" id="hdQue-post-class10" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class10', true ) ); ?>" size="30" />
		</td>
		<td width="80px" class ="answerFeaturedImage <?php if ($answerFeaturedImage == "yes") {echo 'showImage'; }?>">
		<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class21', true ) )) { ?>
			<img src ="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class21', true ) ); ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage9"/>
		<?php	} else { ?>
			<img src ="<?php echo plugin_dir_url( __FILE__ ).'includes/featuredImageDefault.gif'; ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage9"/>
		<?php }	?>
			<input type="hidden" name="hdQue-post-class21" id="hdQue-post-class21" value="<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class21', true ) )) { echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class21', true ) ); }?>" /> 
		</td>
		<td valign="top">
			<input type="radio" class ="answer" name="hdQue-post-class2" id="hdQue-post-class2" value="9" <?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class2', true )) == "9") {echo 'checked';} ?>>		</td>
	</tr>	

	<tr>
		<td valign="top">10</td>
		<td valign="top">
			<input class="widefat" type="text" name="hdQue-post-class11" id="hdQue-post-class11" value="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class11', true ) ); ?>" size="30" />
		</td>
		<td width="80px" class ="answerFeaturedImage <?php if ($answerFeaturedImage == "yes") {echo 'showImage'; }?>">
		<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class22', true ) )) { ?>
			<img src ="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class22', true ) ); ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage10"/>
		<?php	} else { ?>
			<img src ="<?php echo plugin_dir_url( __FILE__ ).'includes/featuredImageDefault.gif'; ?>" width ="100" height ="100"class ="uploadimage answerFeaturedImage10"/>
		<?php }	?>
			<input type="hidden" name="hdQue-post-class22" id="hdQue-post-class22" value="<?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class22', true ) )) { echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class22', true ) ); }?>"  />
		</td>
		<td valign="top">
			<input type="radio" class ="answer" name="hdQue-post-class2" id="hdQue-post-class2" value="10" <?php if (esc_attr( get_post_meta( $object->ID, 'hdQue_post_class2', true )) == "10") {echo 'checked';} ?>>		</td>
	</tr>	

</table>

<br/>

<label for ="hdQue-post-class12">Tool tip text <br/>
			<input class="widefatText" name="hdQue-post-class12" id="hdQue-post-class12" value ="<?php echo esc_attr( get_post_meta( $object->ID, 'hdQue_post_class12', true ) ); ?>" /></label>

<br/><br/>


<label for ="hdQue-post-class26">Text that appears if answer was wrong<br/>
<?php wp_editor(htmlspecialchars_decode(nl2br(esc_attr(get_post_meta( $object->ID, 'hdQue_post_class26', true)))), "hdQue-post-class26", array('textarea_name' => 'hdQue-post-class26','teeny' => true, 'media_buttons' => false, 'textarea_rows' => 3, 'quicktags' => false)); ?>
</label>


<?php }


function hdQue_save_post_class_meta( $post_id, $post ) {
	
	if ( !isset( $_POST['hdQue_post_class_nonce'] ) || !wp_verify_nonce( $_POST['hdQue_post_class_nonce'], basename( __FILE__ ) ) )
		return $post_id;
	
	$post_type = get_post_type_object( $post->post_type );
	
	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
		return $post_id;
	
$new_meta_value = array();
$meta_key = array();
$new_meta_value = array();

 for ($i=1; $i<=26; $i++){

 	$new_meta_value[$i] = $_POST['hdQue-post-class'.$i];
	$meta_key[$i] = 'hdQue_post_class'.$i;
	$meta_value[$i] = get_post_meta( $post_id, $meta_key[$i], true );

	if ( $new_meta_value[$i] && '' == $meta_value[$i] )
		add_post_meta( $post_id, $meta_key[$i], $new_meta_value[$i], true );
	elseif ( $new_meta_value[$i] && $new_meta_value[$i] != $meta_value[$i] )
		update_post_meta( $post_id, $meta_key[$i], $new_meta_value[$i] );
	
	elseif ( '' == $new_meta_value[$i] && $meta_value[$i] )
		delete_post_meta( $post_id, $meta_key[$i], $meta_value[$i] );
  }
	
}

?>