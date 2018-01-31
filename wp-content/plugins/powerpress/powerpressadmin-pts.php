<?php
/**
 * Class PowerPressPostToSocial
 */
class PowerPressPostToSocial {
	// member variables


	// constructor
	/**
	 * PowerPressPostToSocial constructor.
	 */
	function __construct() {
		// WordPress hooks go here
		add_action( 'load-post.php', array( $this, 'my_add_metabox_setup' ) );
		add_action( 'load-post-new.php', array( $this, 'my_add_metabox_setup' ) );
		add_action( 'do_pings', array( $this , 'do_pings' ), 11, 0 );
	}

	// destructor
	/**
	 *
	 */
	function __destruct() {

	}

	// other functions //

	/**
	 *
	 */
	function my_add_metabox_setup() {
		add_action( 'add_meta_boxes', array($this, 'my_add_metabox') );
	}

	/**
	 *
	 */
	function my_add_metabox() {
		add_meta_box( 'pps_pts', __( 'PowerPress Post to Social', 'powerpress' ),  array($this, 'display_my_metabox'), 'post', 'side', 'default' );
	}

	/**
	 *
	 */
	function display_my_metabox() {
	
		
		$can_post = false;
		if ( get_post_status( get_the_ID() ) == 'publish' ) {
			$post_id = get_the_ID();
			$guid = urlencode( get_the_guid() );
			
			$EpisodeData = powerpress_get_enclosure_data($post_id);
			if( !empty($EpisodeData) ) {
				add_thickbox();
				echo "<strong styleX='font-size: 115%; display: block; text-align: center;'><a class='thickbox button button-primary button-large' href='admin.php?action=powerpress-jquery-pts&width=600&height=550&post_id={$post_id}&guid={$guid}&TB_iframe=true' target='blank' title='Post to Social'>Post to Social</a></strong>";
				$can_post = true;
				echo "<br><br>\n";
			}
		}
		else {
			echo "Post to Social";
		}
		echo " ";
		echo "Status: "; // What is the status of the posting to social?
		if ( get_post_status( get_the_ID() ) == 'publish' ) {
			if ( get_post_meta( get_the_ID(), 'pts_scheduled', true ) ) {
				_e( 'Episode posted to social sites.', 'powerpress' );
			}
			else if( $can_post == false ) {
			
				_e( 'No podcast episode available in this post to send to social sites.', 'powerpress' );
			} else {
				_e( 'Nothing posted yet.', 'powerpress' );
				echo ' ';
				echo "<a class='thickbox' href='admin.php?action=powerpress-jquery-pts&width=600&height=550&post_id={$post_id}&guid={$guid}&TB_iframe=true' target='blank'>Post Now!</a>";
			}
		}
		else {
			echo "This post must be published before you can post to social sharing sites.";
		}
		
		echo "<p style=\"font-size: 85%; margin-top: 20px;\">";
		echo 'About: Post podcast episodes to Twitter, YouTube and Facebook using Blubrry\'s <a href="https://create.blubrry.com/resources/podcast-media-hosting/post-to-social/" target="_blank">Post to Social</a> service.';
		echo "</p>";
	}

	function do_pings() {
		$Settings = powerpress_get_settings( 'powerpress_general' );

		$post_id = get_the_ID();
		$program_keyword = $Settings['blubrry_program_keyword'];
		$guid = get_the_guid();

		$enclosure_data = powerpress_get_enclosure_data( $post_id );
		if ( !empty( $enclosure_data ) ) {
			$results = callUpdateListing($post_id, $program_keyword, $guid);
			$podcast_id = $results['podcast-id'];

			add_post_meta( $post_id, 'podcast-id', $podcast_id, true );
		}
	}
}

/**
 * @param int $post_id
 * @param string $program_keyword
 * @param string $guid
 * @return array|mixed|object|string
 */
function callUpdateListing( $post_id, $program_keyword, $guid ) {
	$Settings = powerpress_get_settings('powerpress_general');
	$episodeData = powerpress_get_enclosure_data( $post_id );
	if( empty($episodeData['duration']) )
		$episodeData['duration'] = '';
	
	$subtitle = '';
	if( !empty($episodeData['subtitle']) )
		$subtitle = $episodeData['subtitle'];

	if ( empty( $subtitle ) && !empty($episodeData['summary']) ) {
		$subtitle = substr( $episodeData['summary'], 0, 255 );
	}
	if ( empty( $subtitle ) ) {
		$subtitle = powerpress_get_the_exerpt( false, true, $post_id );
	}
	if ( empty( $subtitle ) ) {
		$subtitle = substr( get_the_content( $post_id ), 0, 255 );
	}

	$FeedSettings = powerpress_get_settings( 'powerpress_feed' );

	$post_params = array(
		'feed-url'  => '',                                           // required
		'title'     => get_the_title( $post_id ),                    // required
		'date'      => get_the_date( 'r', $post_id ),                // required
		'guid'      => $guid,
		'media-url' => $episodeData['url'],                          // required
		'subtitle'  => $subtitle,
		'duration'  => $episodeData['duration'], // hh:mm:ss format; we assume no podcast episode will exceed 24 hours
		'filesize'  => $episodeData['size'],                         // required
		'explicit'  => $FeedSettings['itunes_explicit'],
		'link'      => get_the_permalink( $post_id ),
		'image'     => $FeedSettings['itunes_image'],
	);

	$api_url_array = powerpress_get_api_array();

	foreach ( $api_url_array as $api_url ) {
		$response = powerpress_remote_fopen( "{$api_url}social/{$program_keyword}/update-listing.json", $Settings['blubrry_auth'], json_encode( $post_params ) );
		
		mail('cio@rawvoice.com', 'update listing response', $response);
		if ( $response ) {
			break;
		}
	}

	if ( $response ) {
		$result = json_decode( $response, true );
		if( !empty($result) )
			return $result;
			
		return $response;
	}
	else {
		return false;
	}
}

/**
 * @param string $program_keyword
 * @return array|mixed|object|string
 */
function callGetSocialOptions( $program_keyword ) {
	$Settings = powerpress_get_settings( 'powerpress_general' );

	$api_url_array = powerpress_get_api_array();

	foreach ( $api_url_array as $api_url ) {
		$response = powerpress_remote_fopen("{$api_url}social/{$program_keyword}/get-social-options.json", $Settings['blubrry_auth'] );

		if ( $response ) {
			break;
		}
	}

	if ( $response ) {
		return json_decode( $response, true );
	}
}

/**
 * Generates an HTML text input
 *
 * @param $label
 * @param string $name
 * @param string $value
 *
 * @param string $placeholder
 * @param string $help_text
 * @param int $rows
 * @param int $maxlength
 *
 * @return string
 */
function generate_text_field( $label, $name, $value='', $placeholder='', $help_text='', $rows=1, $maxlength=4000 ) {
	$text_field = '<div class="form-group">' ."\n";
	$text_field .= "<label for='{$name}'>{$label}</label>\n";

	if ( $rows === 1 ) {
		$text_field .= '<input type="text" ';
	}
	else {
		$text_field .= "<textarea rows='{$rows}'";
	}

	$text_field .= "name='{$name}' ";
	$text_field .= "id='{$name}' ";
	$text_field .= "placeholder='{$placeholder}' ";
	$text_field .= "maxlength='{$maxlength}' ";

	if ( $rows === 1 ) {
		$text_field .= "value='{$value}' ";
	}

	$text_field .= "class='form-control' aria-describedby='{$name}-help'>";
	if ( $rows > 1 ) {
		$text_field .= $value;
		$text_field .= '</textarea>';
	}

	$text_field .= "\n<span id='{$name}-help' class='help-block'>{$help_text}</span>";
	$text_field .= "\n</div>";
	return $text_field;
}

/**
 * Generates an HTML checkbox input
 *
 * @param string $label
 * @param string $name
 * @param string $value
 * @param string $checked 'checked' to have the box checked, '' otherwise
 *
 * @return string
 */
function generate_checkbox( $label, $name, $value, $checked='' ) {
	$checkbox = '<label>' ."\n";
	$checkbox .= "<input type='checkbox' name='{$name}' value='{$value}' {$checked}> {$label}\n";
	$checkbox .= '</label>' ."\n";

	return $checkbox;
}

/**
 * Generates an HTML radio input
 *
 * @param string $label
 * @param string $name
 * @param string $value
 * @param string $checked 'checked' to have the radio selected, '' otherwise
 *
 * @return string
 */
function generate_radio( $label, $name, $value, $checked='' ) {
	$checkbox = '<label>' ."\n";
	$checkbox .= "<input type='radio' name='{$name}' value='{$value}' {$checked}> {$label}\n";
	$checkbox .= '</label>' ."\n";

	return $checkbox;
}

function powerpress_ajax_pts($Settings)
{
	//$Settings = powerpress_get_settings('powerpress_general');
	powerpress_admin_jquery_header( __( 'Post to Social', 'powerpress' ) );

	if ( !current_user_can('publish_posts' ) ) {
		powerpress_page_message_add_notice( __( 'You do not have sufficient permission to do this.', 'powerpress' ) );
		powerpress_page_message_print();
		?>
		<p style="text-align: center;"><a href="#" onclick="self.parent.tb_remove();"><?php echo __( 'Close', 'powerpress' ); ?></a></p>
		<?php
		powerpress_admin_jquery_footer();
		exit;
	}

	if ( empty( $Settings['blubrry_program_keyword'] ) ) {
		powerpress_page_message_add_notice( __( 'You must connect your Blubrry account and set up a program.', 'powerpress' ) );
		powerpress_page_message_print();
		?>
		<p style="text-align: center;"><a href="#" onclick="self.parent.tb_remove();"><?php echo __( 'Close', 'powerpress' ); ?></a></p>
		<?php
		powerpress_admin_jquery_footer();
		exit;
	}

	// Make API calls here //
	$program_keyword = $Settings['blubrry_program_keyword'];
	$post_id = (int) $_GET['post_id'];
	$guid    = urldecode( $_GET['guid'] );

	// make sure the podcast episode is in the Blubrry directory using the `update-listing` api call
	if ( get_post_meta( $post_id, 'podcast-id', true ) ) {
		$response = array( 'podcast-id' => get_post_meta( $post_id, 'podcast-id', true ) );
	}
	else {
		$response = callUpdateListing( $post_id, $program_keyword, $guid );
	}

	if ( !is_array( $response ) ) { // an error occurred\	
		echo "<br /><br />";
		echo $response;
		//var_dump($response);
		exit;
	}
	
	//die('ok2');
	
	if ( isset( $response['error'] ) ) {
		powerpress_page_message_add_notice( $response['error'] );
		powerpress_page_message_print();
		?>
		<p style="text-align: center;"><a href="#" onclick="self.parent.tb_remove();"><?php echo __( 'Close', 'powerpress' ); ?></a></p>
		<?php
		powerpress_admin_jquery_footer();
		exit;
	}

	if ( isset( $response['warnings'] ) ) {
		powerpress_page_message_add_notice( $response['warnings'] );
		powerpress_page_message_print();
	}

	$podcast_id = $response['podcast-id'];
	add_post_meta( $post_id, 'podcast-id', $podcast_id, true );

	// get the info necessary to create the post to social form using the `get-social-options` api call
	$response = callGetSocialOptions( $program_keyword );

	if ( !is_array( $response ) ) { // a cURL error occurred
		echo $response;
		exit;
	}

	if ( isset( $response['error'] ) ) {
		powerpress_page_message_add_notice( __( 'There was a problem fetching your post to social settings. ', 'powerpress' ) .$response['error'] );
		powerpress_page_message_print();
		?>
		<p style="text-align: center;"><a href="#" onclick="self.parent.tb_remove();"><?php echo __( 'Close', 'powerpress' ); ?></a></p>
		<?php
		powerpress_admin_jquery_footer();
		exit;
	}

	// build the post to social form
	if( !empty($response['success']) )
		echo "<h3>{$response['success']}</h3>";
	//else
		//var_dump($response);
	?>
	<script>var linkel = document.createElement('link'); linkel.rel = 'stylesheet'; linkel.href = 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'; document.head.appendChild(linkel);</script>
	<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

	<form action="admin.php?action=powerpress-jquery-pts-post" method="POST">
		<input type="hidden" name="podcast-id" value="<?php echo $podcast_id; ?>">
						<input type="hidden" name="post-id" value="<?php echo $post_id; ?>">
	<?php
	foreach ( $response['social-options'] as $option ) {
		echo '<h4>' ."<img src='{$option['social-image']}'>" .$option['social-title'] .'</h4>';

		foreach ( $option['form-data'] as $form_field ) {
			if( !isset($form_field['value']) )
				$form_field['value'] = '';
			if( !isset($form_field['placeholder']) )
				$form_field['placeholder'] = '';	
			if( !isset($form_field['help-text']) )
				$form_field['help-text'] = '';	
			if( !isset($form_field['maxlength']) )
				$form_field['maxlength'] = '';
			if( !isset($form_field['checked']) )	
				$form_field['checked'] = '';
				
			$label = htmlspecialchars( $form_field['label'] );

			if ( $form_field['field-type'] == 'input-text' ) {
				$rows = (!empty($form_field['rows']) ? $form_field['rows'] : 1);
				echo generate_text_field( $label, $option['social-type'] . '-' . $form_field['name'], $form_field['value'], htmlspecialchars( $form_field['placeholder'] ), htmlspecialchars( $form_field['help-text'] ), $rows, $form_field['maxlength'] );
			}
			elseif  ( $form_field['field-type'] == 'input-checkbox' ) {
				echo generate_checkbox( $label, $option['social-type'] . '-' . $form_field['name'], $form_field['value'], $form_field['checked'] );
			}
			elseif  ( $form_field['field-type'] == 'input-radio' ) {
				echo generate_radio( $label, $option['social-type'] . '-' . $form_field['name'], $form_field['value'], $form_field['checked'] );
			}
		}
	}
	?>
		<br>
		<button type="submit">Submit</button>
	</form>
	<p style="text-align: center;"><a href="#" onclick="self.parent.tb_remove();"><?php echo __( 'Close', 'powerpress' ); ?></a></p>
	<?php
	powerpress_admin_jquery_footer();
}

function powerpress_ajax_pts_post($Settings)
{
	powerpress_admin_jquery_header( __( 'Post to Social', 'powerpress' ) );

	//$Settings = powerpress_get_settings('powerpress_general');

	$api_url_array = powerpress_get_api_array();

	$program_keyword = $Settings['blubrry_program_keyword'];
	$podcast_id = $_POST['podcast-id'];
	$post_id = $_POST['post-id'];

	unset( $_POST['podcast-id'] );
	unset( $_POST['post-id'] );

	$post_data = array();

	foreach ( $_POST as $key => $value ) {
		if ( $value ) { // we don't allow empty messages to be posted to social media

			preg_match("/-(\d+)-?/", $key, $matches);
			$social_id = $matches[1];

			preg_match("/^(\w+)-/i", $key, $matches);
			$social_type = strtolower($matches[1]);

			if ( !isset( $post_data[ $social_id ] ) ) {
				$post_data[ $social_id ] = array(
					'social-id' => $social_id,
					'social-type' => $social_type,
				);
			}

			if ( !isset( $post_data[ $social_id ]['social-data'] ) ) {
				$post_data[ $social_id ]['social-data'] = array();
			}

			$field_name = preg_replace( "/^\w+-/i", "", $key );

			$post_data[ $social_id ]['social-data'][ $field_name ] = $value;
		}
	}

	$post_params = array( 'podcast-id' => $podcast_id, 'post-data' => $post_data );

	foreach ( $api_url_array as $api_url ) {
		$response = powerpress_remote_fopen( "{$api_url}social/{$program_keyword}/post.json", $Settings['blubrry_auth'], json_encode( $post_params ) );

		if ( $response ) {
			break;
		}
	}

	$response = json_decode( $response, true );

	if ( $response['status'] == 'success' ) {
		powerpress_page_message_add_notice( __( 'Post to social has been scheduled.', 'powerpress' ) );
		powerpress_page_message_print();

		add_post_meta( $post_id, 'pts_scheduled', 1, true );
	}
	else {
		powerpress_page_message_add_notice( $response['error'] );
		powerpress_page_message_print();
	}
	?>
	<p style="text-align: center;"><a href="#" onclick="self.parent.tb_remove();"><?php echo __( 'Close', 'powerpress' ); ?></a></p>
	<?php
	powerpress_admin_jquery_footer();
}

$powerpress_PTS = new PowerPressPostToSocial();


