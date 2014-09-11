<?php
if ( ! get_option('violinesth') && function_exists('register_sidebar') ) {
		register_sidebar(array(
		'name' => 'Left Sidebar',
		'id' => 'left-sidebar',
		'before_widget' => '<li>',
	        'after_widget' => '</li>',
        	'before_title' => '<h4>',
	        'after_title' => '</h4>',
	));
}

if ( function_exists('register_sidebar') )
    register_sidebar(array(
	'name' => 'Right Sidebar',
	'id' => 'right-sidebar',
	'before_widget' => '<li>',
        'after_widget' => '</li>',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
));

$themecolors = array(
	'bg' => 'ffffff',
	'text' => '000000',
	'link' => '333333'
);

define('HEADER_TEXTCOLOR', '');
define('HEADER_IMAGE', '%s/images/vm.jpg'); // %s is theme dir uri
define('HEADER_IMAGE_WIDTH', 1024);
define('HEADER_IMAGE_HEIGHT', 279);
define('NO_HEADER_TEXT', true );

$content_width = 500;

function violinesth_admin_header_style() {
?>
<style type="text/css">
#headimg{
	background: url(<?php header_image() ?>) no-repeat;
	background-repeat: no-repeat;
	height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
	width:<?php echo HEADER_IMAGE_WIDTH; ?>px;
}

#headimg h1, #headimg #desc {
	display: none;
}
</style>
<?php
}


function header_style() {
?>
<style type="text/css" media="screen">
#header{background: url(<?php header_image() ?>) no-repeat center; border: none;}
</style>
<?php
}

add_custom_image_header('header_style', 'violinesth_admin_header_style');

if ( ! function_exists('get_option') )
	die("This isn't what you're looking for. Try <a href=\"/\" title=\"Site Root\">this</a>.");

function get_violinesth_colors() {
	return array(
		'Default (3 Columns) ' => '',
		'2 Columns ' => 'styles');
}

function get_violinesth_color() {
	$violinesth_color = get_option('violinesth');
	
	return clean_violinesth_color($violinesth_color);
}

function violinesth_color_select() {
	echo "<select id='violinesth' name='violinesth'>";
	$colors = get_violinesth_colors();
	$current_color = get_violinesth_color();
	foreach ( $colors as $name => $value ) {
		$selected = ($current_color == $value) ? " selected='selected'" : '';
		echo "<option value='$value'$selected>$name</option>";
	}
	echo "</select>\n";
}

function violinesth_color_radios() {
	$colors = get_violinesth_colors();
	$current_color = get_violinesth_color();
	$theme_uri = get_template_directory_uri();
	$i = 0;
	foreach ( $colors as $name => $value ) {
		$checked = ($current_color == $value) ? " checked='checked'" : '';
		$filename = "preview{$value}.jpg";
		echo "<tr valign='middle'><td align='right'><input type='radio' name='violinesth_color' id='violinesth_color_{$i}' value='{$value}'$checked /></td><td><label for='violinesth_color_{$i}'> <img align='middle' src='{$theme_uri}/{$filename}' alt='{$name} Color Sample' /> $name</label></td></tr>\n";
		++$i;
	}
}

function clean_violinesth_color($violinesth_color) {
	$violinesth_colors = get_violinesth_colors();
	if ( in_array($violinesth_color, $violinesth_colors) )
		return $violinesth_color;
	else
		return '';
}

function violinesth_color() {
	echo get_violinesth_color();
}

function violinesth_css() {
	$violinesth_color = get_violinesth_color();

	$url = get_bloginfo('stylesheet_url');

	if ( !empty($violinesth_color) ) {
		$time = time();
		$url = str_replace('style.css', "{$violinesth_color}.css?{$time}", $url);
	}

	$url = apply_filters('bloginfo', $url, 'stylesheet_url');
	$url = convert_chars($url);
	echo $url;
}

add_action('admin_menu', 'violinesth_add_theme_page');

function violinesth_add_theme_page() {
	global $violinesth_message;
	if ( isset($_POST['action']) && 'violinesth_update' == $_POST['action'] ) {
		$color = clean_violinesth_color($_POST['violinesth']);
		update_option('violinesth', $color);

		$colors = array_flip(get_violinesth_colors());
		$Color = __($colors[$color]);
		$message = sprintf(__("Column changed to %s"), $Color);
		$violinesth_message = "<div style=\"background-color: rgb(207, 235, 247);\" id=\"message\" class=\"updated fade\"><p><strong>$message</strong></p></div>";
	}

	add_theme_page("Theme Column", "Column Setting", 'edit_themes', basename(__FILE__), 'violinesth_theme_page');
}

function violinesth_theme_page() {
	global $violinesth_message;
	echo $violinesth_message;
?>
<div class="wrap">
 <h2>Select a Theme Column</h2>

  <form method="post"> 
	<?php violinesth_color_select(); ?>

	<p class="submit"><input type="submit" name="Submit" value="Apply Theme" /></p>
	<input type="hidden" name="action" value="violinesth_update" /> 
  </form>

</div>
<?php
}

?>