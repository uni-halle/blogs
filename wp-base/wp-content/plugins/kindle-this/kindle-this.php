<?php
/*
Plugin Name: Kindle This
Plugin URI: http://www.blogseye.com
Description: Sends a blog post or page to a user's kindle.
Author: Keith P. Graham
Version: 2.3
Requires at least: 2.9
Author URI: http://www.blogseye.com
Tested up to: 3.3.1
Donate link: http://www.blogseye.com/buy-the-book/

*/
/******************************************
     Widget section
*******************************************/
class widget_kindle_this extends WP_Widget {

   /** constructor */
    function widget_kindle_this() {
        parent::WP_Widget(false, $name = 'Kindle This Widget');	
    }
    /** @see WP_Widget::form */
    function form($instance) {				
		// outputs the options form on admin
		// this is the html to display the options

		$title = esc_attr($instance['title']);
       ?>
<fieldset style="border:thin black solid;padding:2px;"><legend>Title:</legend>	
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</fieldset>

<?PHP
		// end of the functional section
	}

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {				
		// processes widget options to be saved
		// have to update the new instance
		return $new_instance;
	}

    /** @see WP_Widget::widget */
    function widget($args, $instance) {	
		// start of the display section
		echo "\r\n<!-- Start of Kindle This Widget -->\r\n";
		// outputs the content of the widget
		extract( $args );
		$title = esc_attr($instance['title']);
		// the loop ids have been cached - get them back
		
		echo $before_widget;
		if ( $title) {
			echo $before_title . $title . $after_title;
		}
		?>
		<div style="font-size:.9em;">
        <?php echo kpg_get_kindle_form(); ?>
		</div>		
		<?PHP
		// close the ul or select or add a blank line
		echo $after_widget;
		
		echo "\r\n<!-- end of Kindle This Widget -->\r\n";
	}

}
add_action('widgets_init', create_function('', 'return register_widget("widget_kindle_this");'));
/******************************************
     End of Widget section
*******************************************/

/******************************************
     Plugin admin section
*******************************************/


// this is the plugins page stuff
// no unistall because I have not created any meta data to delete.
function kpg_kindle_this_init() {
   add_options_page('Kindle This', 'Kindle This', 'manage_options',__FILE__,'kpg_kindle_this_control');
}
  // Plugin added to Wordpress plugin architecture
	add_action('admin_menu', 'kpg_kindle_this_init');	
function kpg_kindle_this_control() {
	// this is the Kindle This functionality.
 	$options=get_option('kpg_kindlethis_options');
	$options=array();
	if (empty($options)) $options=array();
	if (array_key_exists('count',$options)) {
		$count=$options['count'];
	}
?>

<h2>Kindle This</h2>
<div style="position:relative;float:right;width:40%;background-color:ivory;border:#333333 medium groove;padding-left:6px;">
 <p>This plugin is free and I expect nothing in return. If you would like to support my programming, you can buy my book of short stories.</p><p>Some plugin authors ask for a donation. I ask you to spend a very small amount for something that you will enjoy. eBook versions for the Kindle and other book readers start at 99&cent;. The book is much better than you might think, and it has some very good science fiction writers saying some very nice things. <br/>
 <a target="_blank" href="http://www.blogseye.com/buy-the-book/">Error Message Eyes: A Programmer's Guide to the Digital Soul</a></p>
 <p>A link on your blog to one of my personal sites would also be appreciated.</p>
 <p><a target="_blank" href="http://www.WestNyackHoney.com">West Nyack Honey</a> (I keep bees and sell the honey)<br />
	<a target="_blank" href="http://www.cthreepo.com/blog">Wandering Blog </a> (My personal Blog) <br />
	<a target="_blank" href="http://www.cthreepo.com">Resources for Science Fiction</a> (Writing Science Fiction) <br />
	<a target="_blank" href="http://www.jt30.com">The JT30 Page</a> (Amplified Blues Harmonica) <br />
	<a target="_blank" href="http://www.harpamps.com">Harp Amps</a> (Vacuum Tube Amplifiers for Blues) <br />
	<a target="_blank" href="http://www.blogseye.com">Blog&apos;s Eye</a> (PHP coding) <br />
	<a target="_blank" href="http://www.cthreepo.com/bees">Bee Progress Beekeeping Blog</a> (My adventures as a new beekeeper) </p>
</div>
<p>The Kindle-This widget is installed and working correctly.</p>

<p>Number of Kindle pages sent: <?php echo $count; ?></p>
<p>&nbsp;</p>
<p>Remember, if you don't want to use the widget, you can place a short code [kindlethis] on any page that you want the user to be able to send to their kindle</p>	
	
	<?php
	
// process form
	$kpg_kindle_template_top='';
	$kpg_kindle_template_post='';
	$kpg_kindle_template_foot='';
	if (array_key_exists('action',$_POST)) {
		// check the nonce
		if(wp_verify_nonce($_POST['kpg_kindle_this_control'],'kpgkindlethis_update')) { 
			// pressed submit
			// get the fields off from the form
			// kpg_kindle_template_top, kpg_kindle_template_post, kpg_kindle_template_foot
			if (array_key_exists('kpg_kindle_template_top',$_POST)) $kpg_kindle_template_top=stripslashes($_POST['kpg_kindle_template_top']);
			if (array_key_exists('kpg_kindle_template_post',$_POST)) $kpg_kindle_template_post=stripslashes($_POST['kpg_kindle_template_post']);
			if (array_key_exists('kpg_kindle_template_foot',$_POST)) $kpg_kindle_template_foot=stripslashes($_POST['kpg_kindle_template_foot']);
			$options['kpg_kindle_template_top']=$kpg_kindle_template_top;
			$options['kpg_kindle_template_post']=$kpg_kindle_template_post;
			$options['kpg_kindle_template_foot']=$kpg_kindle_template_foot;
			update_option('kpg_kindlethis_options',$options);
			echo "<h3>Options Updated</h3>";
		}
	}
	if (array_key_exists('kpg_kindle_template_top',$options)) $kpg_kindle_template_top=$options['kpg_kindle_template_top'];
	if (array_key_exists('kpg_kindle_template_post',$options)) $kpg_kindle_template_post=$options['kpg_kindle_template_post'];
	if (array_key_exists('kpg_kindle_template_foot',$options)) $kpg_kindle_template_foot=$options['kpg_kindle_template_foot'];

	?>
<h3>Templates</h3>
<p>Use the template settings to change what the user sees in their email and to change the way the pages sent to the kindle are formatted. The Amazon Kindle conversion, though, will remove all images and most of the css in the post. This is done at the Kindle site and is out of our control.</p>
 <form method="post" action="">
    <input type="hidden" name="action" value="update" />
     <?php wp_nonce_field('kpgkindlethis_update','kpg_kindle_this_control'); ?>
<table class="form-table">
	<tbody>
	<tr valign="top">
	<td>
	<p>This controls what will be displayed at the top before the actual content area. </p>
	<p>You can use the following tags:</p>
	<p><code>[kindle_blogname]</code> <code>[kindle_page_title]</code>  <code>[kindle_page_url]</code> <code>[kindle_blog_url]</code> <code>[kindle_blog_description]</code>  <code>[kindle_date]</code> </p>
	<textarea rows="20" cols="50" id="kpg_kindle_template_top" name="kpg_kindle_template_top"><?php
	if (!empty($kpg_kindle_template_top)) {
		echo $kpg_kindle_template_top;
	} else {
	?>
<h3>[kindle_blogname]</h3>
<h4><a href="[kindle_page_url]">[kindle_page_title]</a></h4>
<p>This is the page that your requested at [kindle_blogname].</p>
<hr/>
<?php } ?>
</textarea>
<hr/>
	<p>This controls how the loop of blog posts will be displayed. </p>
	<p>You can use the following tags, including the tags from the header:</p>
	<p>.</p>
	<p><code>[kindle_post_title]</code> <code>[kindle_post_date]</code>  <code>[kindle_post_content]</code> <code>[kindle_post_author]</code> <code>[kindle_post_url]</code> </p>
	<textarea  rows="20" cols="50" id="kpg_kindle_template_post" name="kpg_kindle_template_post"><?php
	if (!empty($kpg_kindle_template_post)) {
		echo $kpg_kindle_template_post;
	} else {
	?><h3><a href="[kindle_post_url]">[kindle_post_title]</a></h3>
<h4>[kindle_post_date] [kindle_post_author]</h4>
<div>
[kindle_post_content]
</div>

<hr/>
	<?php } ?>
	</textarea>
	<hr/>
	<p>This controls what will be displayed at the bottom after the posts. </p>
	<p>You can use all the tags from the header:</p>
	<textarea rows="20" cols="50" id="kpg_kindle_template_foot" name="kpg_kindle_template_foot"><?php
	if (!empty($kpg_kindle_template_foot)) {
		echo $kpg_kindle_template_foot;
	} else {
	?><p>Thank you for visiting [kindle_blogname]<p>
<p>&nbsp;</p><p>&nbsp;</p>
<p style="font-size:small;">The Free WordPress Kindle-This plugin was written by Keith P. Graham, author of <a href="http://www.blogseye.com/buy-the-book/">Error Message Eyes: a programmer's guide to the digital soul.</a></p>
<hr/>
<?php } ?>
	</textarea>
	
	</td>
</tr>
</tbody></table>
<p class="submit"><input class="button-primary" value="Save Changes" type="submit"></p>
</form>
	<?php

}

/******************************************
     End Plugin admin section
*******************************************/

/******************************************
     Capture loop info.
	 This is used in sending the email
*******************************************/


// also have to hook the loop in order to catch the ids
add_action('loop_start', 'kpg_kindle_this_catchloop');	
function kpg_kindle_this_catchloop($param) {
	// this sets up the common array for the forms
	// store these items in an array for this
    global $wp_query;
	$posts = $wp_query->posts;
	if (empty($posts)) $posts=array();
	$pids=array();
	// capture the posts
	foreach ($posts as $post) {
		$id=$post->ID;
		$pids[count($pids)]=$id;
	}
    wp_cache_set( 'kindle_this', $pids );
	return $param;
}
/******************************************
     End of Capture loop info.
*******************************************/


/******************************************
     Ajax Mailer
*******************************************/

	add_action('wp_ajax_nopriv_kindle_this', 'kpg_kindle_this_mailer');	
	add_action('wp_ajax_kindle_this', 'kpg_kindle_this_mailer');	
function kpg_kindle_this_mailer() {
	// the parameters passed to the AJAX callback are in the post!

 	// there are from the get
    $p=$_GET['postarray'];
    $ke=$_GET['kindle_email'];
    $fe=$_GET['from_email'];
	$nonce=$_GET['kindlethis_nonce'];
	$kt=$_GET['kindletitle'];
	$kl=$_GET['kindleloc'];
	$kc=$_GET['kindlecom'];
	//echo "Parameters passed= p=$p, ke=$ke, fe=$fe, nonce=$nonce, kc=$kc, kt=$kt, kl=$kl\r\n";
	
	// check the nonce
	if(!wp_verify_nonce($nonce,'kpgkindlethis')) { 
		// data is returned here by echoing it out. the results are place in the span on the widget or whatever
		echo "Error: The nonce data cannot be verified '$nonce'\r\n";
		flush();		
		exit(); 
	}
	$options=array();
	$options=get_option('kpg_kindlethis_options');
	if (empty($options)||!is_array($options)) $options=array();
	$kpg_kindle_template_top='<h3>[kindle_blogname]</h3>
<h4><a href="[kindle_page_url]">[kindle_page_title]</a></h4>
<p>This is the page that your requested at [kindle_blogname].</p>

<hr/>
';
	$kpg_kindle_template_post='<h3><a href="[kindle_post_url]">[kindle_post_title]</a></h3>
<h4>[kindle_post_date] [kindle_post_author]</h4>
<div>
[kindle_post_content]
</div>


<hr/>
';
	$kpg_kindle_template_foot="<p>Thank you for visiting [kindle_blogname]<p>
<p>&nbsp;</p><p>&nbsp;</p>
<p style=\"font-size:small;\">The Free WordPress Kindle-This plugin was written by Keith P. Graham, author of <a href=\"http://www.blogseye.com/buy-the-book/\">Error Message Eyes: a programmer's guide to the digital soul.</a></p>

";
	if (array_key_exists('kpg_kindle_template_top',$options)) $kpg_kindle_template_top=$options['kpg_kindle_template_top'];
	if (array_key_exists('kpg_kindle_template_post',$options)) $kpg_kindle_template_post=$options['kpg_kindle_template_post'];
	if (array_key_exists('kpg_kindle_template_foot',$options)) $kpg_kindle_template_foot=$options['kpg_kindle_template_foot'];
	
	$blog=get_bloginfo('name');
	$blogurl=home_url();
	$kt=stripslashes($kt);
 	$kd=get_bloginfo('description');
    if (empty($kl)) $kl=$blogurl;
	if (empty($kt)) $kt=$blog;
	$filename=sanitize_title($kt).'.html';
	
	$ansa='<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head><title>test</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body>';

// here do the replacement on the header section
//[kindle_blogname] [kindle_page_title] [kindle_page_url] [kindle_blog_url] [kindle_blog_description] [kindle_date] 
	$kpg_kindle_template_top=str_replace('[kindle_blogname]',$blog,$kpg_kindle_template_top);
	$kpg_kindle_template_top=str_replace('[kindle_page_url]',$kl,$kpg_kindle_template_top);
	$kpg_kindle_template_top=str_replace('[kindle_page_title]',$kt,$kpg_kindle_template_top);
	$kpg_kindle_template_top=str_replace('[kindle_blog_url]',$blogurl,$kpg_kindle_template_top);
	$kpg_kindle_template_top=str_replace('[kindle_blog_description]',$kd,$kpg_kindle_template_top);
	$kpg_kindle_template_top=str_replace('[kindle_date]',date("Y/m/d"),$kpg_kindle_template_top);
	
	$ansa.=$kpg_kindle_template_top;
	
	$posts=unserialize($p);
	
	// remove kindle this shortcode
	remove_shortcode('kindlethis'); // in case I can't find it.
	if (!empty($ke)&&!empty($fe)&&count($posts)>0) {
		$ddate='';
	    for ($j=0;$j<count($posts);$j++) {
			$id=$posts[$j];
			$post=get_post($id);
			$title=htmlentities($post->post_title);
			$date=$post->post_date;
			$author=htmlentities($post->post_author);
			$post_url=get_permalink($id);
			if ($j==0) $ddate=$date;
			$content=$post->post_content;
			// get rid of the kindle this shortcode
			$jj=strpos($content,'[kindlethis');
			if ($jj>0) {
				$kk=strpos($content,']',$jj+2);
				if ($kk>$jj) {
					$content=substr($content,0,$jj).substr($content,$kk+1);
				}
			}
			$content=do_shortcode($content);
			$content=str_replace("\r\n","<p>",$content);
			$content=str_replace("\n","<p>",$content);
			// get rid of [kindlethis]
			$content=str_replace("[kindlethis]"," ",$content);
			// do replacements on the post
			$a=$kpg_kindle_template_post;
			$a=str_replace('[kindle_blogname]',$blog,$a);
			$a=str_replace('[kindle_page_url]',$kl,$a);
			$a=str_replace('[kindle_page_title]',$kt,$a);
			$a=str_replace('[kindle_blog_url]',$blogurl,$a);
			$a=str_replace('[kindle_blog_description]',$kd,$a);
			$a=str_replace('[kindle_date]',date("Y/m/d"),$a);
			// post specific replacements
			//[kindle_post_title] [kindle_post_date] [kindle_post_content] [kindle_post_author] [kindle_post_url]
			$a=str_replace('[kindle_post_title]',$title,$a);
			$a=str_replace('[kindle_post_date]',$date,$a);
			$a=str_replace('[kindle_post_content]',$content,$a);
			$a=str_replace('[kindle_post_author]',$author,$a);
			$a=str_replace('[kindle_post_url]',$post_url,$a);
			
			$ansa.=$a;
			
		}
		// now the bottom of the post with my little piece of self promotion
	$kpg_kindle_template_foot=str_replace('[kindle_blogname]',$blog,$kpg_kindle_template_foot);
	$kpg_kindle_template_foot=str_replace('[kindle_page_url]',$kl,$kpg_kindle_template_foot);
	$kpg_kindle_template_foot=str_replace('[kindle_page_title]',$kt,$kpg_kindle_template_foot);
	$kpg_kindle_template_foot=str_replace('[kindle_blog_url]',$blogurl,$kpg_kindle_template_foot);
	$kpg_kindle_template_foot=str_replace('[kindle_blog_description]',$kd,$kpg_kindle_template_foot);
	$kpg_kindle_template_foot=str_replace('[kindle_date]',date("Y/m/d"),$kpg_kindle_template_foot);
	$ansa.=$kpg_kindle_template_foot;
		
		
		$ansa.='</body></html>';
		$to=$ke.'@'.$kc;
		
		$subject = "Convert Web page from $kt $date";
		$headers = "From: $fe\r\nReply-To: $fe\r\nCc: $fe";
		
		$message = "
Attached is an extract from $kt at $kl 
created by the Kindle-This widget. 

If you did not ask for this then please remove $fe
from your allowed email addresses at http://kindle.com";


		$attachments=array(array($ansa,$filename,'base64','text/html'));
		$mail_sent=kpg_kindle_this_mail($to, $subject, $message, $headers,$attachments);
		//$mail_sent = @mail( $to, $subject, $message, $headers );
		//if the message is sent successfully print "Mail sent". Otherwise print "Mail failed"
		if (!$mail_sent) {
			echo "There was an error sending the email to kindle";
			flush();
			exit();
		}
	} else {
		echo "\r\nNo pages sent due to error?\r\n";
		//echo "\r\n$p, $ke, $fe, ".count($posts)."\r\n";
		// (!empty($p)&&!empty($ke)&&!empty($fe)&&!empty($posts)&&count($posts)>0)
		flush();
		exit();
	}
	// we need to record the count of pages sent
	$options=get_option('kpg_kindlethis_options');
	if (empty($options)) $options=array();
	$count=0;
	if (array_key_exists('count',$options)) {
		$count=$options['count'];
	}
	$count++;
	$options['count']=$count;
	update_option('kpg_kindlethis_options',$options);
		
	echo "done, Document sent to kindle";
	flush();
	exit();
}

// this is the clone of wp_mail that uses strings instead of files

function kpg_kindle_this_mail( $to, $subject, $message, $headers = '', $attachments = array() ) {
	// Compact the input, apply the filters, and extract them back out
	extract( compact( 'to', 'subject', 'message', 'headers', 'attachments' ) );

	if ( !is_array($attachments) )
		$attachments = explode( "\n", str_replace( "\r\n", "\n", $attachments ) );

	global $phpmailer;

	// (Re)create it, if it's gone missing
	if ( !is_object( $phpmailer ) || !is_a( $phpmailer, 'PHPMailer' ) ) {
		require_once ABSPATH . WPINC . '/class-phpmailer.php';
		require_once ABSPATH . WPINC . '/class-smtp.php';
		$phpmailer = new PHPMailer();
	}

	// Headers
	if ( empty( $headers ) ) {
		$headers = array();
	} else {
		if ( !is_array( $headers ) ) {
			// Explode the headers out, so this function can take both
			// string headers and an array of headers.
			$tempheaders = explode( "\n", str_replace( "\r\n", "\n", $headers ) );
		} else {
			$tempheaders = $headers;
		}
		$headers = array();

		// If it's actually got contents
		if ( !empty( $tempheaders ) ) {
			// Iterate through the raw headers
			foreach ( (array) $tempheaders as $header ) {
				if ( strpos($header, ':') === false ) {
					if ( false !== stripos( $header, 'boundary=' ) ) {
						$parts = preg_split('/boundary=/i', trim( $header ) );
						$boundary = trim( str_replace( array( "'", '"' ), '', $parts[1] ) );
					}
					continue;
				}
				// Explode them out
				list( $name, $content ) = explode( ':', trim( $header ), 2 );

				// Cleanup crew
				$name    = trim( $name    );
				$content = trim( $content );

				switch ( strtolower( $name ) ) {
					// Mainly for legacy -- process a From: header if it's there
					case 'from':
						if ( strpos($content, '<' ) !== false ) {
							// So... making my life hard again?
							$from_name = substr( $content, 0, strpos( $content, '<' ) - 1 );
							$from_name = str_replace( '"', '', $from_name );
							$from_name = trim( $from_name );

							$from_email = substr( $content, strpos( $content, '<' ) + 1 );
							$from_email = str_replace( '>', '', $from_email );
							$from_email = trim( $from_email );
						} else {
							$from_email = trim( $content );
						}
						break;
					case 'content-type':
						if ( strpos( $content, ';' ) !== false ) {
							list( $type, $charset ) = explode( ';', $content );
							$content_type = trim( $type );
							if ( false !== stripos( $charset, 'charset=' ) ) {
								$charset = trim( str_replace( array( 'charset=', '"' ), '', $charset ) );
							} elseif ( false !== stripos( $charset, 'boundary=' ) ) {
								$boundary = trim( str_replace( array( 'BOUNDARY=', 'boundary=', '"' ), '', $charset ) );
								$charset = '';
							}
						} else {
							$content_type = trim( $content );
						}
						break;
					case 'cc':
						$cc = array_merge( (array) $cc, explode( ',', $content ) );
						break;
					case 'bcc':
						$bcc = array_merge( (array) $bcc, explode( ',', $content ) );
						break;
					default:
						// Add it to our grand headers array
						$headers[trim( $name )] = trim( $content );
						break;
				}
			}
		}
	}

	// Empty out the values that may be set
	$phpmailer->ClearAddresses();
	$phpmailer->ClearAllRecipients();
	$phpmailer->ClearAttachments();
	$phpmailer->ClearBCCs();
	$phpmailer->ClearCCs();
	$phpmailer->ClearCustomHeaders();
	$phpmailer->ClearReplyTos();

	// From email and name
	// If we don't have a name from the input headers
	if ( !isset( $from_name ) )
		$from_name = 'WordPress';

	/* If we don't have an email from the input headers default to wordpress@$sitename
	 * Some hosts will block outgoing mail from this address if it doesn't exist but
	 * there's no easy alternative. Defaulting to admin_email might appear to be another
	 * option but some hosts may refuse to relay mail from an unknown domain. See
	 * http://trac.wordpress.org/ticket/5007.
	 */

	if ( !isset( $from_email ) ) {
		// Get the site domain and get rid of www.
		$sitename = strtolower( $_SERVER['SERVER_NAME'] );
		if ( substr( $sitename, 0, 4 ) == 'www.' ) {
			$sitename = substr( $sitename, 4 );
		}

		$from_email = 'wordpress@' . $sitename;
	}

	// Plugin authors can override the potentially troublesome default
	$phpmailer->From     = apply_filters( 'wp_mail_from'     , $from_email );
	$phpmailer->FromName = apply_filters( 'wp_mail_from_name', $from_name  );

	// Set destination addresses
	if ( !is_array( $to ) )
		$to = explode( ',', $to );

	foreach ( (array) $to as $recipient ) {
		$phpmailer->AddAddress( trim( $recipient ) );
	}

	// Set mail's subject and body
	$phpmailer->Subject = $subject;
	$phpmailer->Body    = $message;

	// Add any CC and BCC recipients
	if ( !empty( $cc ) ) {
		foreach ( (array) $cc as $recipient ) {
			$phpmailer->AddCc( trim($recipient) );
		}
	}

	if ( !empty( $bcc ) ) {
		foreach ( (array) $bcc as $recipient) {
			$phpmailer->AddBcc( trim($recipient) );
		}
	}

	// Set to use PHP's mail()
	$phpmailer->IsMail();

	// Set Content-Type and charset
	// If we don't have a content-type from the input headers
	if ( !isset( $content_type ) )
		$content_type = 'text/plain';

	$content_type = apply_filters( 'wp_mail_content_type', $content_type );

	$phpmailer->ContentType = $content_type;

	// Set whether it's plaintext, depending on $content_type
	if ( 'text/html' == $content_type )
		$phpmailer->IsHTML( true );

	// If we don't have a charset from the input headers
	if ( !isset( $charset ) )
		$charset = get_bloginfo( 'charset' );

	// Set the content-type and charset
	$phpmailer->CharSet = apply_filters( 'wp_mail_charset', $charset );

	// Set custom headers
	if ( !empty( $headers ) ) {
		foreach( (array) $headers as $name => $content ) {
			$phpmailer->AddCustomHeader( sprintf( '%1$s: %2$s', $name, $content ) );
		}

		if ( false !== stripos( $content_type, 'multipart' ) && ! empty($boundary) )
			$phpmailer->AddCustomHeader( sprintf( "Content-Type: %s;\n\t boundary=\"%s\"", $content_type, $boundary ) );
	}

// function AddStringAttachment($string, $filename, $encoding = 'base64', $type = 'application/octet-stream') {

	if ( !empty( $attachments ) ) {
		foreach ( $attachments as $attachment ) {
			$phpmailer->AddStringAttachment($attachment[0],$attachment[1],$attachment[2],$attachment[3]);
		}
	}

	//do_action_ref_array( 'phpmailer_init', array( &$phpmailer ) );

	// Send!
	$result = @$phpmailer->Send();

	return $result;
}
// shortcode for form
// same as widget, except uses the single post id to send to amazon.
// form is styled a little larger in a div

function kpg_kindlethis_sc($atts, $content=null) {
	extract( shortcode_atts( array(
		'style' => ''
		), $atts ) );
	if (empty($title)) $title='Send this to Kindle';
	$posts=wp_cache_get( 'kindle_this');
	if (empty($posts)) $posts=array();
	$posts=serialize($posts);
    $ansa=kpg_get_kindle_form();
	return $ansa;
}
add_shortcode('kindlethis', 'kpg_kindlethis_sc');




function kpg_kindle_this_uninstall() {
	if(!current_user_can('manage_options')) {
		die('Access Denied');
	}
	delete_option('kpg_kindlethis_options'); 
	return;
}  
if ( function_exists('register_uninstall_hook') ) {
	register_uninstall_hook(__FILE__, 'kpg_kindle_this_uninstall');
}
// load the javascript into the header
	add_action('wp_head', 'kpg_head_ajax');	

function kpg_head_ajax() {
	$url=plugins_url( 'kindle-this.js' , __FILE__ )
    // put link to js in head.

?>
<script type="text/javascript" src="<?php echo $url?>"></script>
<?php
}

$kpg_kindle_count=0;
function kpg_get_kindle_form() {
	// made one function to return the kindle form.
	global $kpg_kindle_count;
	$kpg_kindle_count=$kpg_kindle_count+1;
	$ajax_url=admin_url('admin-ajax.php');
	$nonce=wp_create_nonce('kpgkindlethis');
    $posts=wp_cache_get( 'kindle_this');
	$p=serialize($posts);

	$ansa="<form  action=\"\" method=\"GET\" onsubmit=\"kpg_kindle_it(this);return false;\">
				<span style=\"color:red;font-weight:bold\" id=\"kpg_kc_$kpg_kindle_count\"></span>
				<fieldset style=\"border:thin black solid;padding:2px;\"><legend>your kindle user name:</legend>
				<input style=\"font-size:.9em;\" size=\"32\" name=\"kindle_email\" type=\"text\" value=\"your-id\"/><br/>(you@kindle.com, without @kindle.com)</fieldset>
				<fieldset style=\"border:thin black solid;padding:2px;\"><legend>Approved E-mail:</legend>
				<input style=\"font-size:.9em;\" size=\"32\" name=\"from_email\" type=\"text\" value=\"good@email\"/><br/>(Approved E-mail that kindle will accept)
				</fieldset>
				<fieldset style=\"border:thin black solid;padding:2px;\"><legend>Kindle base email</legend>
				  <input name=\"kindlecom\" type=\"radio\" value=\"kindle.com\" checked=\"checked\" />
  kindle.com | 
  <input name=\"kindlecom\" type=\"radio\" value=\"free.kindle.com\" /> 
  free.kindle.com
<br/>(Use kindle.com to download on wispernet or wifi, use free.kindle.com for wifi only.)<br/><i>using kindle.com may incur charges</i>)
				</fieldset>
				<input type=\"submit\" name=\"kpg_ksub\" value=\"send to kindle\"/>
				<input type=\"hidden\" name=\"kpg_kindle_count\" value=\"$kpg_kindle_count\"/>
				<input type=\"hidden\" name=\"kpg_kindle_aurl\" value=\"$ajax_url\"/>
				<input type=\"hidden\" name=\"kpg_kindle_nonce\" value=\"$nonce\"/>
				<input type=\"hidden\" name=\"kpg_kindle_posts\" value=\"$p\"/>
		</form>";
		
		return $ansa;

}

