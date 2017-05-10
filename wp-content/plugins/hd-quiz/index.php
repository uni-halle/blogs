<?php 
/*
 * Plugin Name: HD Quiz
 * Description: Allows you to add an unlimited amount of Quizzes to your site.
 * Plugin URI: http://harmonicdesign.ca/hd-quiz/
 * Author: Harmonic Design
 * Author URI: http://harmonicdesign.ca
 * Version: 1.4.2
*/


include( plugin_dir_path( __FILE__ ) . 'custom-meta.php');
add_image_size( 'hd_qu_size', 600, 400, true );
add_image_size( 'hd_qu_size2', 400, 400, true );

// Image upscale if someone uploads an image under 400x400
function hd_qu_image_upscale( $default, $orig_w, $orig_h, $new_w, $new_h, $crop ){
    if ( !$crop ) return null; // let the wordpress default function handle this
 
    $aspect_ratio = $orig_w / $orig_h;
    $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);
 
    $crop_w = round($new_w / $size_ratio);
    $crop_h = round($new_h / $size_ratio);
 
    $s_x = floor( ($orig_w - $crop_w) / 2 );
    $s_y = floor( ($orig_h - $crop_h) / 2 );
 
    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
}
add_filter( 'image_resize_dimensions', 'hd_qu_image_upscale', 10, 6 );


/* Check to see if the page is using HD QUIZ
________________________________________________________
-------------------------------------------------------- */

//Disable Canonical redirection for paginated quizzes
function hd_qu_disable_redirect_canonical( $redirect_url ){
	global $post;

	if(has_shortcode( $post->post_content, 'HDquiz') ) {
		$redirect_url = false;
	}
	return $redirect_url;

}
add_filter( 'redirect_canonical','hd_qu_disable_redirect_canonical' );

// load css and js files
function hd_qu_scripts() {
	global $post;
	if(has_shortcode( $post->post_content, 'HDquiz') ) {
	wp_enqueue_style( 'style-name', plugin_dir_url( __FILE__ ) . 'admin.css' );
	wp_enqueue_script(
		'custom-script',
		plugins_url( 'custom.js' , __FILE__ ),
		array( 'jquery' ),
		'1.0',
	        true
	);
	wp_localize_script('custom-script', 'pluginURL', plugin_dir_url( __FILE__ ));
	}
}
add_action('wp_enqueue_scripts', 'hd_qu_scripts');


/* add quiz template
________________________________________________________
-------------------------------------------------------- */
function hd_qu_get_cp_template($single_template) {
     global $post;

     if ($post->post_type == 'post_type_questionna') {
          $single_template = dirname( __FILE__ ) . '/template.php';
     }
     return $single_template;
}
add_filter( 'single_template', 'hd_qu_get_cp_template' );




/* Add Shortcode to render the quiz
________________________________________________________
-------------------------------------------------------- */
function custom_shortcode_hdQuestionnaire($atts) {

	// Attributes
	extract( shortcode_atts(
		array(
			'quiz' => '',
		), $atts )
	);

	// Code

	ob_start();
	include( plugin_dir_path( __FILE__ ) . 'template.php');
	return ob_get_clean();

}
add_shortcode( 'HDquiz', 'custom_shortcode_hdQuestionnaire' );




/* add HD Quiz to admin menu
________________________________________________________
-------------------------------------------------------- */
add_action('admin_menu', 'register_HD_questionnaire_admin');

function register_HD_questionnaire_admin() {
	add_submenu_page( 'edit.php?post_type=post_type_questionna', 'HD Quiz About', 'About / Options', 'manage_options', 'HD_quiz', 'register_HD_questionna_admin_callback' );
}



/* Register plugin global options and display about page
________________________________________________________
-------------------------------------------------------- */
function register_HD_questionna_admin_callback() {
   // variables for the field and option names 
    $opt_name1 = 'hd_qu_fb';
    $opt_name2 = 'hd_qu_tw';
    $opt_name3 = 'hd_qu_next';
    $opt_name4 = 'hd_qu_finish';
    $opt_name5 = 'hd_qu_questionName';

    $hidden_field_name = 'hd_submit_hidden';
    $data_field_name1 = 'hd_qu_fb';
    $data_field_name2 = 'hd_qu_tw';
    $data_field_name3 = 'hd_qu_next';
    $data_field_name4 = 'hd_qu_finish';
    $data_field_name5 = 'hd__questionName';

    // Read in existing option value from database
    $opt_val1 = get_option( $opt_name1 );
    $opt_val2 = get_option( $opt_name2 );
    $opt_val3 = get_option( $opt_name3 );
    $opt_val4 = get_option( $opt_name4 );
    $opt_val5 = get_option( $opt_name5 );


    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
        $opt_val1 = sanitize_text_field($_POST[ $data_field_name1 ]);
        $opt_val2 = sanitize_text_field($_POST[ $data_field_name2 ]);
        $opt_val3 = sanitize_text_field($_POST[ $data_field_name3 ]);
        $opt_val4 = sanitize_text_field($_POST[ $data_field_name4 ]);
        $opt_val5 = sanitize_text_field($_POST[ $data_field_name5 ]);


        // Save the posted value in the database
        update_option( $opt_name1, $opt_val1 );
        update_option( $opt_name2, $opt_val2 );
        update_option( $opt_name3, $opt_val3 );
        update_option( $opt_name4, $opt_val4 );
        update_option( $opt_name5, $opt_val5 );



        // Put a "settings saved" message on the screen

?>
<div class="updated"><p style ="color:#2d2d2d;"><strong><?php _e('settings saved.', 'menu-test' ); ?></strong></p></div>
<?php

    }

wp_enqueue_style( 'style-name', plugin_dir_url( __FILE__ ) . 'admin.css' );

?>

	<div class="wrap">
		<div id ="hdTitle">
			<img src ="<?php echo plugin_dir_url( __FILE__ );?>harmonicDesignLogo.png" alt ="Harmonic Design Logo" style ="float:left; position:relative; top:-12px"/>
			<h1>HD Quiz</h1>			
		</div>


		<div class ="hdContent">

<div class ="two_third">

<h2>How To Use</h2>
<h3>Adding A New Quiz</h3>
<ul>
	<li>Select <strong>Quizzes</strong> in the left menu.</li>
	<li>Enter the name of the quiz, then click on Add A New Quiz. This will add it to the list on the right.</li>
	<li>Click the name of the newly added quiz to set the quiz options such as the needed pass percentage</li>
</ul>

<h3>Adding New Questions</h3>
<ul>
	<li>Select <strong>Add New Question</strong> in the left menu.</li>
	<li>Enter the question as the title.</li>
	<li>You can have up to ten (10) answers per question. Make sure to select which answer is the correct one.</li>
	<li>In the right sidebar, you will see a metabox called <strong>Quizzes</strong> with a list of all quizzes you have created. Select the quiz that this question belongs to.</li>
	<li>Selecting <strong>Question as Title</strong> will use the question as a title / heading.</li>
	<li>Selecting <strong>Paginate</strong> will create a new jQuery page staring with this question.</li>
</ul>


<h3>Using A Quiz</h3>
<ul>
	<li>HD Quiz uses shortcodes to render a quiz, so you can place a quiz almost anywhere on your site!</li>
	<li>To find the shortcode for a quiz, select <strong>Quizzes</strong> in the left menu.</li>
	<li>You will now see a list of all of your quizzes in a table, with the shortcode listed.</li>
	<li>Copy and paste the shortcode into any page or post you want to render that quiz!</li>
</ul>

</div>

<div class = "one_third last">


<p>HD Quiz is provided for free by Dylan of <a href ="http://harmonicdesign.ca">Harmonic Design</a>.</p>

<div class ="hdQuCallout">
	<p>Hi everyone, I've recently released my first premium WordPress plugin! HD Invoice is the easiest way to create invoices and get paid online!</p>
	<p>If you are interested, here is a $5 off coupon code as a thank you for supporting and using HD Quiz! <strong>Coupon code: hdquiz</strong></p><br/>

	<a href="https://hdinvoice.com/?utm_source=HDQuiz&amp;utm_medium=about" class="hdQuReadMore">VISIT HD INVOICE</a></p>
	<div class ="clearboth"></div>

</div>

<div class ="clearboth"></div>

<?php add_thickbox(); ?>
<h3>Changing Question Order</h3>
<ul>
	<li>For now, there is no super easy way to order questions - they will be ordered by creation date (most recent first).</li>
	<li>In the meantime, I strongly recommend installing and using the <a href ="plugin-install.php?tab=plugin-information&plugin=intuitive-custom-post-order&TB_iframe=true&width=772&height=905" class = "thickbox">Intuitive Custom Post Order</a> plugin.</li>
	<li>I have ZERO affiliation with this plugin: It's just a temporary solution until I integrate similar functionality into HD Quiz.</li>
</ul>

<h3>Need help?</h3>
<ul>
	<li>This is a <em>free</em> Premium WordPress plugin, so we just get pure unfiltered satisfaction knowing that you use and love HD Quiz.</li>
	<li>So, loyal HD Quiz lover, if you need help, please don't hesitate to leave us a message or question on the <a href ="https://wordpress.org/support/plugin/hd-quiz">official WordPress HD Quiz Support Forum</a>, or on our own support page at <a href ="http://harmonicdesign.ca/hd-quiz/">Harmonic Design</a>. </li>
</ul>

</div>


<div class ="clearboth"></div>

			<h2>Settings</h2>
			<form name="form1" method="post" action="">
			<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

			<table cellpadding ="20px" cellspacing ="0" class ="hdTable">
				<tr>
					<td valign ="top"><a class ="hdQuTooltip">? <span><b></b>This is needed to allow Facebook to share dynamic content - the results of the quiz. If this is not used, then Facebook will share the page without the results.</span></a>Facebook APP ID</td>
					<td valign ="top">
						<input type="text" class ="widefat" name="<?php echo $data_field_name1; ?>" id="<?php echo $data_field_name1; ?>1" value="<?php echo stripslashes($opt_val1);?>">
<p><a href ="http://harmonicdesign.ca/hd-quiz-create-a-facebook-app/">click here to learn how to quickly create a facebook app</a></p>
					</td>
				</tr>
				<tr/>
					<td valign ="top"><a class ="hdQuTooltip">? <span><b></b>This is used if you have sharing results enabled. The sent tweet will contain a mention to your account for extra exposure.</span></a>Twitter Handle</td>
					<td valign ="top">
						<input type="text" class ="widefat" name="<?php echo $data_field_name2; ?>" id="<?php echo $data_field_name2; ?>1" value="<?php echo stripslashes($opt_val2);?>">
<p>please <strong>do NOT</strong> include the @ symbol.</p>
					</td>
				</tr>
				<tr/>
					<td valign ="top"><a class ="hdQuTooltip">? <span><b></b>The Next button is used on quizzes with pagination enabled</span></a>Next Button Text</td>
					<td valign ="top">
						<input type="text" class ="widefat" name="<?php echo $data_field_name3; ?>" id="<?php echo $data_field_name3; ?>1" value="<?php echo stripslashes($opt_val3);?>">
					</td>
				</tr>
				<tr/>
					<td valign ="top"><a class ="hdQuTooltip">? <span><b></b>The button a user clicks to submit the quiz</span></a>Finish Button Text</td>
					<td valign ="top">
						<input type="text" class ="widefat" name="<?php echo $data_field_name4; ?>" id="<?php echo $data_field_name4; ?>1" value="<?php echo stripslashes($opt_val4);?>">
					</td>
				</tr>
				<tr/>
					<td valign ="top"><a class ="hdQuTooltip">? <span><b></b>Each question is prefixed by the word 'Question' and the question #. Rename 'question' here.</span></a>Rename 'Question'.</td>
					<td valign ="top">
						<input type="text" class ="widefat" name="<?php echo $data_field_name5; ?>" id="<?php echo $data_field_name5; ?>1" value="<?php echo stripslashes($opt_val5);?>">
					</td>
				</tr>
				<tr>
					<td valign ="middle">&nbsp;</td><td valign ="middle"><input type ="submit" class ="button button-primary" style ="float:right" value ="UPDATE" /></td>
				</tr>
			</table>
			</form>






	</div>
</div>

<?php
}
?>