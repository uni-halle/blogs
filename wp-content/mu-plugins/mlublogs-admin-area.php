<?php
/*
	Plugin Name: mluBlogs Admin Area
	Plugin URI: http://blogs.urz.uni-halle.de
	Description: Alle eigenen Funktionen, die der Veränderung des Admin-Bereichs in allen Blogs dienen
	Version: 1.0
	Author: Matthias Kretschmann
	Author URI: http://matthiaskretschmann.com
	
	Instructions:
    Kommt in mu-plugins Ordner
	
	2011, Matthias Kretschmann

	This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

// Custom Admin Bar
function mlublogs_admin_bar() {
	$home_url = site_url();
	wp_register_style('mlublogs_admin_bar_css', "$home_url/wp-content/themes/blogsmluHome/style/css/mlublogs-admin-bar.css");
	wp_enqueue_style('mlublogs_admin_bar_css');
}
//add_action( 'admin_bar_init', 'mlublogs_admin_bar' , 5 );

// Custom Admin Bar - Remove some stuff
function mlublogs_admin_bar_remove_links() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('wp-logo');
}
add_action( 'wp_before_admin_bar_render', 'mlublogs_admin_bar_remove_links' );

// Custom Admin Bar - Add mluBlogs menu item
function mlublogs_admin_bar_menu() {
	global $wp_admin_bar;
	$wp_admin_bar->add_menu(array(
		'id' => 'mlublogs-wp',
		'title' => __('mluBlogs'),
		'href' => 'http://blogs.urz.uni-halle.de'
	));
	$wp_admin_bar->add_menu(array(
		'id' => 'mlublogs-wp-dienst',
		'parent' => 'mlublogs-wp',
		'title' => __('Blogdienst'),
		'href' => 'http://blogs.urz.uni-halle.de'
	));
	$wp_admin_bar->add_menu(array(
		'id' => 'mlublogs-wp-firststeps',
		'parent' => 'mlublogs-wp',
		'title' => __('Erste Schritte'),
		'href' => 'http://blogs.urz.uni-halle.de/blog/2009/07/erste-schritte/'
	));
	$wp_admin_bar->add_menu(array(
		'id' => 'mlublogs-wp-anleitungen',
		'parent' => 'mlublogs-wp',
		'title' => __('Anleitungen'),
		'href' => 'http://blogs.urz.uni-halle.de/blog/kategorie/anleitungen/'
	));
	/*
	$wp_admin_bar->add_menu(array(
		'id' => 'mlublogs-wp-twitter',
		'parent' => 'mlublogs-wp',
		'title' => __('Twitter'),
		'href' => 'http://twitter.com/mlublogs'
	));
	$wp_admin_bar->add_menu(array(
		'id' => 'mlublogs-wp-facebook',
		'parent' => 'mlublogs-wp',
		'title' => __('Facebook'),
		'href' => 'http://www.facebook.com/pages/blogsURZ/156259138175'
	));
	*/
}
add_action('admin_bar_menu', 'mlublogs_admin_bar_menu');


//Custom Login Screen CSS
add_action('login_head', 'mlublogs_custom_login');

function mlublogs_custom_login() {
	echo '<link rel="stylesheet" type="text/css" href="' . site_url() . '/wp-content/themes/blogsmluHome/style/css/mlublogs-login.css" />';
}

if ( is_admin() ) {
	
	// admin CSS
	// add_action('admin_init', 'mlublogs_admin_init' , 4);
	
	function mlublogs_admin_init() {
		$home_url = site_url();
		wp_register_style('mlublogs_admin_css', "$home_url/wp-content/themes/blogsmluHome/style/css/mlublogs-admin.css");
		wp_enqueue_style('mlublogs_admin_css');
	
	}
	
	// Change admin footer text
	function add_footer_text() {
	  return 'Danke f&uuml;r die Nutzung von <a href="'.get_blog_option( 1, 'siteurl').'">'.get_site_option('site_name').'</a> | <a href="'.get_blog_option( 1, 'siteurl').'/blog/kategorie/anleitungen/" class="smoothbutton">Anleitungen</a> <a href="https://twitter.com/mlublogs" class="smoothbutton" title="blogs@URZ auf twitter">Twitter</a> <a href="http://www.facebook.com/pages/blogsURZ/156259138175" class="smoothbutton" title="Werde Fan auf Facebook">Facebook</a> <a href="'.get_blog_option( 1, 'siteurl').'/kontakt/" class="smoothbutton" title="blogs@URZ Team kontaktieren">Kontakt</a>';
	}
	add_filter('admin_footer_text', 'add_footer_text');	
	
	// Custom Dashboard Widgets
	function mlublogs_dashboard_help_content() {
	    echo 	'<div id="mlublogs-quickstart"><p><img src="/wp-content/themes/blogsmluHome/style/images/blogsmlu-logo48px.png" align="left" style="margin-right:10px;float:left">Willkommen im Blog-Dienst des Rechenzentrums der Martin-Luther-Universität Halle-Wittenberg. Hier einige Anleitungen, damit du schnell starten kannst: </p>
	    		<ul>
	    			<li><a href="'.get_blog_option( 1, 'siteurl').'/?p=68">Erste Schritte mit eurem neuen Blog</a></li>
	    			<li><a href="'.get_blog_option( 1, 'siteurl').'/?p=97">Artikel und Seiten</a></li>
	    			<li><a href="'.get_blog_option( 1, 'siteurl').'/?p=165">Ein Blog mit mehreren Benutzern</a></li>
	    			<li><a href="'.get_blog_option( 1, 'siteurl').'/?p=441">Eigener Header und Hintergrund</a></li>
	    		';
	    echo '</ul>
	    	
	    	  <p class="everything">Alle Anleitungen und Neuigkeiten zum Blog-Dienst findest du in unserem <a href="'.get_blog_option( 1, 'siteurl').'/blog">Haupt-Blog</a> sowie auf <a href="http://twitter.com/mlublogs">Twitter</a> &amp; <a href="http://www.facebook.com/pages/blogsURZ/156259138175">Facebook</a>.</p> 
	    	  <p>Wenn du weitere Fragen hast, kannst du das Blogs-Team jederzeit <a href="'.get_blog_option( 1, 'siteurl').'/kontakt/">kontaktieren</a>.</p>
	    	 
	    	 </div>';
	}
	
	function mlublogs_dashboard_widgets() {
	   global $wp_meta_boxes;
	   
	   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']); //remove Plugins Widget
	   add_meta_box( 'mlublogs_dashboard_help', 'Schnellstart', 'mlublogs_dashboard_help_content', 'dashboard', 'side', 'high' );
	}
	add_action('wp_dashboard_setup', 'mlublogs_dashboard_widgets');
	
	
	// check to see if the tagline is set to default
	// show an admin notice to update if it hasn't been changed
	if (get_option('blogdescription') === 'Ein weiterer Blog rund um die MLU') { 
		add_action('admin_notices', create_function('', "echo '<div class=\"error\"><p>" . sprintf(__('Hey, dein Blog Slogan ist noch auf den Standard "Ein weiterer Blog rund um die MLU" eingestellt. Diesen kannst du <a href="%s">einfach hier anpassen</a>.', 'mlublogs'), admin_url('options-general.php')) . "</p></div>';"));
	};
	
	// More Contact Methods
	function mlublogs_userprofile( $userprofile ) {
		
		$userprofile['icq'] = 'ICQ';
	  	$userprofile['twitter'] = '<a href="http://twitter.com">Twitter</a> Benutzername<br /><small style="color:#666666;font-style:italic">nur euren Benutzernamen angeben</small>';
	  	$userprofile['facebook'] = '<a href="http://facebook.com">Facebook</a> Profil<br /><small style="color:#666666;font-style:italic">gesamte URL inklusive http://</small>';
	  	$userprofile['studivz'] = '<a href="http://studivz.net">StudiVZ</a> Profil<br /><small style="color:#666666;font-style:italic">gesamte URL inklusive http://</small>';
	  	$userprofile['studip'] = '<a href="http://studip.uni-halle.de">Stud.IP</a> Homepage<br /><small style="color:#666666;font-style:italic">nur euren Benutzernamen angeben</small>';
	  	$userprofile['xing'] = '<a href="http://xing.com">XING</a> Profil<br /><small style="color:#666666;font-style:italic">gesamte URL inklusive http://</small>';
	  	$userprofile['linkedin'] = '<a href="http://linkedin.com">LinkedIn</a> Profil<br /><small style="color:#666666;font-style:italic">gesamte URL inklusive http://</small>';
	  	$userprofile['flickr'] = '<a href="http://flickr.com">Flickr</a> Photostream<br /><small style="color:#666666;font-style:italic">gesamte URL inklusive http://</small>';
	  
	  return $userprofile;
	}
	add_filter('user_contactmethods','mlublogs_userprofile',10,1);
	
	// Uni Rolle
	function mlublogs_unirole_show($user) { 
	?>
		
		<h3>Funktion(en) an der Martin-Luther-Universit&auml;t</h3>
		  <table class="form-table">
			<tr>
				<th><label for="unirole1">Deine Rolle(n) an der MLU</label></th>
				<td>
					<select name="unirole1" id="unirole1">
						
						<option value="" <?php if (esc_attr(get_the_author_meta('unirole1',$user->ID)) == "") { ?>selected="selected"<?php } ?>>- Rolle w&auml;hlen -</option>
						<option value="Student/in" <?php if (esc_attr(get_the_author_meta('unirole1',$user->ID)) == "Student/in") { ?>selected="selected"<?php } ?>>Student/in</option>
						<option value="Dozent/in" <?php if (esc_attr(get_the_author_meta('unirole1',$user->ID)) == "Dozent/in") { ?>selected="selected"<?php } ?>>Dozent/in</option>
						<option value="Mitarbeiter/in" <?php if (esc_attr(get_the_author_meta('unirole1',$user->ID)) == "Mitarbeiter/in") { ?>selected="selected"<?php } ?>>Mitarbeiter/in</option>
						<option value="Tutor/in" <?php if (esc_attr(get_the_author_meta('unirole1',$user->ID)) == "Tutor/in") { ?>selected="selected"<?php } ?>>Tutor/in</option>
						<option value="Team" <?php if (esc_attr(get_the_author_meta('unirole1',$user->ID)) == "Team") { ?>selected="selected"<?php } ?>>Team</option>
						<option value="Gast" <?php if (esc_attr(get_the_author_meta('unirole1',$user->ID)) == "Gast") { ?>selected="selected"<?php } ?>>Gast</option>
	
					</select>
				</td>
			</tr>
			<tr>
				<th></th>
				<td>
					<select name="unirole2" id="unirole2">
						
						<option value="" <?php if (esc_attr(get_the_author_meta('unirole2',$user->ID)) == "") { ?>selected="selected"<?php } ?>>- Rolle w&auml;hlen -</option>
						<option value="Student/in" <?php if (esc_attr(get_the_author_meta('unirole2',$user->ID)) == "Student/in") { ?>selected="selected"<?php } ?>>Student/in</option>
						<option value="Dozent/in" <?php if (esc_attr(get_the_author_meta('unirole2',$user->ID)) == "Dozent/in") { ?>selected="selected"<?php } ?>>Dozent/in</option>
						<option value="Mitarbeiter/in" <?php if (esc_attr(get_the_author_meta('unirole2',$user->ID)) == "Mitarbeiter/in") { ?>selected="selected"<?php } ?>>Mitarbeiter/in</option>
						<option value="Tutor/in" <?php if (esc_attr(get_the_author_meta('unirole2',$user->ID)) == "Tutor/in") { ?>selected="selected"<?php } ?>>Tutor/in</option>
						<option value="Team" <?php if (esc_attr(get_the_author_meta('unirole2',$user->ID)) == "Team") { ?>selected="selected"<?php } ?>>Team</option>
						<option value="Gast" <?php if (esc_attr(get_the_author_meta('unirole2',$user->ID)) == "Gast") { ?>selected="selected"<?php } ?>>Gast</option>
	
					</select>
				</td>
			</tr>
		</table>
		
	<?php }
	add_action('show_user_profile','mlublogs_unirole_show');
	add_action('edit_user_profile','mlublogs_unirole_show');
	//Update User in Database
	function mlublogs_unirole_update_user($user_id) {
	    update_usermeta($user_id, 'unirole1', ( isset($_POST['unirole1']) ? $_POST['unirole1'] : '' ) );
	    update_usermeta($user_id, 'unirole2', ( isset($_POST['unirole2']) ? $_POST['unirole2'] : '' ) );
	}
	add_action('personal_options_update','mlublogs_unirole_update_user');
	add_action('edit_user_profile_update','mlublogs_unirole_update_user');
	
	// add gravatar preview to profile page
	function profile_gravatar() {
	  global $profileuser, $blog_id, $user_level;
	  ?>
	  <h3>Avatar (Profile Picture)</h3>
	    <table class="form-table">
	  	<tr>
	  		<th style="text-align:center;">Current Gravatar:<br /><?php echo get_avatar($profileuser->user_email, 100); ?></th>
	  		<td><?php echo get_site_option('site_name'); ?> uses Gravatars (<b>g</b>lobally <b>r</b>ecognized <b>avatars</b>) provided freely by Gravatar.com to add your custom image to comments and listings across the site.
	      By setting up a Gravatar, you gain the added benefit of your image following you to millions of blogs and websites appearing beside your name when you comment on gravatar enabled sites.
	      Setting up your Gravatar is simple and fast. Your Gravatar is tied to your email address, so be sure to set it up exactly as "<b><?php echo $profileuser->user_email; ?></b>" for it to be tied to your profile. 
	      <a href="http://en.gravatar.com/signup" title="Setup Your Gravatar" target="_blank">Click here to get started &raquo;</a>
	      <p>If you've already set one up, you can <a href="http://en.gravatar.com/login" title="Edit Your Gravatar" target="_blank">modify your Gravatar here</a>.</p></td>
	  	</tr>
	  </table>
	  <?php
	}
	add_action('profile_personal_options', 'profile_gravatar');

} ?>
