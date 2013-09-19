<?php
/******************************************
/* Shortcodes
******************************************/
/**** Clean UP SHORTCODES ****///Clean Up WordPress Shortcode Formatting - important for nested shortcodes
//adjusted from http://donalmacarthur.com/articles/cleaning-up-wordpress-shortcode-formatting/
function parse_shortcode_content( $content ) {

   /* Parse nested shortcodes and add formatting. */
    $content = trim( do_shortcode( shortcode_unautop( $content ) ) );

    /* Remove '' from the start of the string. */
    if ( substr( $content, 0, 4 ) == '' )
        $content = substr( $content, 4 );

    /* Remove '' from the end of the string. */
    if ( substr( $content, -3, 3 ) == '' )
        $content = substr( $content, 0, -3 );

    /* Remove any instances of ''. */
    $content = str_replace( array( '<p></p>' ), '', $content );
    $content = str_replace( array( '<p>  </p>' ), '', $content );

    return $content;
}

//move wpautop filter to AFTER shortcode is processed
remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 99);
add_filter( 'the_content', 'shortcode_unautop',100 );



//OLG Shortcodes
//[iww]
function iww_func( $atts ){
echo "<!-- IWW BOX -->
				    <h2>Stay informed</h2>
								<p>
									Es gibt immer wieder Neuigkeiten in Halle: zu den Studieng&auml;ngen, zur Uni und
				zur Stadt. Unsere Studienbotschafter &ndash; 18 Studentinnen und Studenten
				verschiedener Fachrichtungen &ndash; informieren zu diesen Themen auf ihrer
				Webseite <a href='http://www.ich-will-wissen.de' target='_blank'>www.ich-will-wissen.de</a>. Wenn Du hier Deine E-Mail-Adresse
				einträgst, bekommst Du per E-Mail eine Einladung, Dich auf
				<a href='http://www.ich-will-wissen.de' target='_blank'>www.ich-will-wissen.de</a> zu registrieren. Wenn Du Dich registrierst, erhältst
				Du einen pers&ouml;nlichen Bereich, in dem ausschlie&szlig;lich die f&uuml;r Dein
				Studieninteresse zutreffenden Informationen zusammengestellt werden.</p>";
if(isset($_COOKIE["iwwForm"])) {
echo '<p>Sie haben sich bereits mit der E-Mail <strong>'.$_COOKIE["iwwForm"].'</strong> eingetragen!</p>';
} else {	
echo "<p>Die Felder, die mit einem Stern (*) markiert sind, m&uuml;ssen ausgef&uuml;llt werden!<br>Nach dem Klick auf den Button &quot;Angaben speichern&quot; öffnet sich ein neues Fenster.</p>
								<form target='_blank' action='".get_bloginfo ( 'template_directory' )."/send.php' method='post'>
								  <dl>
								    <dt><label for='tx_kofointerested_pi1_email'>E-Mail-Adresse <sup>*</sup></label></dt>
								    <dd><input type='text' id='tx_kofointerested_pi1_email' name='email' value=''/></dd>
								    <dt><label for='tx_kofointerested_pi1_last_name'>Nachname</label></dt>
								    <dd><input type='text' id='tx_kofointerested_pi1_last_name' name='last_name' value=''/></dd>
								    <dt><label for='tx_kofointerested_pi1_first_name'>Vorname</label></dt>
								    <dd><input type='text' id='tx_kofointerested_pi1_first_name' name='first_name' value=''/></dd>
								    <dt><label for='tx_kofointerested_pi1_agreeprivacypolicy'>Zustimmung <a title='Datenschutzbestimmungen, öffnet in neuem Fenster' href='http://www.ich-will-wissen.de/impressum/#c9976' target='_blank'>Datenschutz&shy;bestimmungen</a> <sup>*</sup></label></dt>
								    <dd><input type='checkbox' id='tx_kofointerested_pi1_agreeprivacypolicy' name='agree' value='1'/></dd>
								  </dl>
								  <span class='clear'></span>
								  <input type='submit' class='submitbutton' name='submit' value='Angaben speichern'/>
								</form>
				<br class='clear'>
				<!-- /IWW BOX -->
";
}
}
add_shortcode( 'iww', 'iww_func' );

///////[iww end]
//[googlemap]
function googlemap_function($atts, $content = null) {
   extract(shortcode_atts(array(
      "width" => '570',
      "height" => '271',
      "src" => '//maps.google.de/maps?q=Karl-Freiherr-von-Fritsch-Str.+3+Halle+(Saale),+Sachsen-Anhalt,+06120,+Deutschland&hl=de&ie=UTF8&sll=51.485534,11.979149&sspn=0.017077,0.042229&hnear=Karl-Freiherr-von-Fritsch-Stra%C3%9Fe&t=m&z=16'
   ), $atts));
   return '<iframe width="'.$width.'" height="'.$height.'" src="'.$src.'&output=embed" ></iframe>';
}
add_shortcode("googlemap", "googlemap_function");


//OLG Shortcodes end
//highlights
function highlight_shortcode( $atts, $content = null )
{
	extract( shortcode_atts(
	array(
      'color' => 'yellow',
      ),
	  $atts ) );

      return '<span class="text-highlight highlight-' . $color . '">' . $content . '</span>';

}
add_shortcode('highlight', 'highlight_shortcode');

//Break
function line_break_shortcode() {
   return '<br />';
}

add_shortcode( 'br', 'line_break_shortcode' );

//clear
function clear_shortcode() {
   return '<div class="clear"></div>';
}

add_shortcode( 'clear', 'clear_shortcode' );

//Buttons
function button_shortcode( $atts, $content = null )
{
	extract( shortcode_atts( array(
      'color' => 'default',
	  'url' => '',
	  'text' => ''
      ), $atts ) );
	  if($url) {
		return '<a href="' . $url . '" class="button ' . $color . '" target="_blank"><span>' . $text . $content . '</span></a>';
	  } else {
		return '<div class="button ' . $color . '"><span>' . $text . $content . '</span></div>';
	}
}
add_shortcode('button', 'button_shortcode');

//Boxes
function box_shortcode( $atts, $content = null )
{
	extract( shortcode_atts( array(
      'color' => 'orange',
      'size' => 'normal',
      'type' => '',
	  'align' => 'default',
      ), $atts ) );

      return '<div class="box-shortcode box-' . $color . '">' . $content . '</div>';

}
add_shortcode('box', 'box_shortcode');

//Columns
function column_shortcode( $atts, $content = null )
{
	extract( shortcode_atts( array(
	  'offset' =>'',
      'size' => '',
	  'position' =>''
      ), $atts ) );


	  if($offset !='') { $column_offset = $offset; } else { $column_offset ='one'; }
		
      return '<div class="'.$column_offset.'-' . $size . ' column-'.$position.'">' . do_shortcode($content) . '</div>';

}
add_shortcode('column', 'column_shortcode');

/*shortcode filters - alow shortcodes in widgets*/
add_filter('the_content', 'do_shortcode');
add_filter('widget_text', 'do_shortcode');
?>