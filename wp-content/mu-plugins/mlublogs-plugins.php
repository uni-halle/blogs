<?php
/*
	Plugin Name: mluBlogs Weitere Globale Funktionen
	Plugin URI: http://blogs.urz.uni-halle.de
	Description: Weitere globale Funktionen, die in allen Blogs aktiviert sind
	Version: 1.0
	Author: Matthias Kretschmann
	Author URI: http://matthiaskretschmann.com
	
	Instructions:
    Kommt in mu-plugins Ordner
	
	2010, Matthias Kretschmann

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


//remove the WordPress version from the code
remove_action('wp_head', 'wp_generator');

//remove the more-anchor-tag
if ( !function_exists('remove_more_anchor') ) {
	function remove_more_anchor($content) {
		global $id;
		
		return str_replace('#more-' . $id . '"', '"', $content);
	}
	add_filter('the_content', 'remove_more_anchor');
}

// Rewrite Content URLs to use https if neccessary
function mlublogs_ssl($content) {
  if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    //$content = ereg_replace("http://" . $_SERVER["SERVER_NAME"], "https://" . $_SERVER["SERVER_NAME"], $content);
    $content = str_replace('http://'.$_SERVER['SERVER_NAME'],'https://'.$_SERVER['SERVER_NAME'],$content);
  }
  //} else {
  	//$content = ereg_replace("https://" . $_SERVER["SERVER_NAME"], "http://" . $_SERVER["SERVER_NAME"], $content);
  return $content;
  //}
}
add_filter('the_content', 'mlublogs_ssl');
add_filter('the_content_rss', 'mlublogs_ssl');
add_filter('the_content_feed', 'mlublogs_ssl');
add_filter('rss2_head', 'mlublogs_ssl');
add_filter('rss_enclosure', 'mlublogs_ssl');
add_filter('rss2_item', 'mlublogs_ssl');
add_filter('atom_head', 'mlublogs_ssl');
add_filter('atom_enclosure', 'mlublogs_ssl');
add_filter('atom_item', 'mlublogs_ssl');
add_filter('widget_text', 'mlublogs_ssl');
add_filter('wp_head', 'mlublogs_ssl');
add_filter('wp_footer', 'mlublogs_ssl');
add_filter('bloginfo', 'mlublogs_ssl');
add_filter('bloginfo_url', 'mlublogs_ssl');
add_filter('home_url', 'mlublogs_ssl');
add_filter('option_siteurl', 'mlublogs_ssl');
add_filter('option_home', 'mlublogs_ssl');
add_filter('theme_mod_header_image', 'mlublogs_ssl');
add_filter('theme_mod_background_image', 'mlublogs_ssl');
add_filter('theme_mod_background_image_thumb', 'mlublogs_ssl');


//Allow more file types for upload
function addUploadMimes($mimes) {
    $mimes = array_merge($mimes, array(
        'mp4|m4v' => 'video/mp4',
        'ogg|ogv' => 'video/ogg',
        'webm' => 'video/webm',
        'm4a' => 'audio/mp4a-latm',
        'm3u' => 'audio/x-mpegurl',
        'flv' => 'video/x-flv'
    ));
    return $mimes;
}
add_filter('upload_mimes', 'addUploadMimes');

//Allow shortcodes in sidebar widgets
add_filter('widget_text', 'do_shortcode');


//Global Footer in allen Blogs
function mlublogs_footer_css() {
	echo '<style type="text/css">
	
	#global-footer	{
	font-size: 11px;
	background: rgba(255, 255, 255, 0.2);
	padding: 10px 20px;
	
	border-top: 1px solid #ccc;
	text-align: center;
	clear: both;
	}
	
	#global-footer:hover	{
		background: #9bc34b;
		color: #fff;
	}
	
	#global-footer:hover a {
		color: #fff;
	text-decoration: underline;
		text-shadow: #666 0 -1px 0;
	}
	
	#global-footer a:hover {
		color: #fff;
	text-decoration: none;
	background-color: #000;
		text-shadow: #666 0 -1px 0;
	}
	
	#global-footer p	{ 
		margin-bottom: 10px;
	margin-top: 0;
	padding: 0;
		line-height: 12px;
	}
	
	#global-footer p:last-child	{ 
		margin-bottom: 0;
	}
	</style>';
}
add_filter('wp_footer', 'mlublogs_footer_css');

function mlublogs_footer_html() {
	echo '<div id="global-footer">
		
		<p>Ein Blog von <a href="http://blogs.urz.uni-halle.de/">Blogs@MLU</a>, dem Blog-Dienst des <a href="http://www.itz.uni-halle.de/" title="IT-Servicezentrum der Martin-Luther-Universität Halle-Wittenberg">IT-Servicezentrums</a> der <a href="http://www.uni-halle.de" title="Website der Martin-Luther-Universit&auml;t Halle-Wittenberg">Martin-Luther-Universität Halle-Wittenberg</a></p>
		
		<p><a href="http://blogs.urz.uni-halle.de/dienst/" title="Features">Features</a> | <a href="http://blogs.urz.uni-halle.de/dienst/nutzungsbedingungen/" title="Nutzungsbedingungen">Nutzungsbedingungen</a> | <a href="http://blogs.urz.uni-halle.de/kontakt" title="Kontakt und Impressum">Kontakt/Impressum</a> | <a href="http://blogs.urz.uni-halle.de/dienst/disclaimer" title="Haftungssausschluss">Disclaimer</a> | <a href="http://blogs.urz.uni-halle.de/dienst/datenschutz" title="Datenschutzerkl&auml;rung">Datenschutzerkl&auml;rung</a> | <a href="https://blogs.urz.uni-halle.de/new-blog.php" title="Neuen Blog anlegen">Neuer Blog</a></p>
	
	</div>';
}
add_filter('wp_footer', 'mlublogs_footer_html');


// add to virtual robots.txt
add_action('do_robots', 'mlublogs_robots');

function mlublogs_robots() {
	echo "Disallow: /cgi-bin\n";
	echo "Disallow: /wp-admin\n";
	echo "Disallow: /wp-includes\n";
	echo "Disallow: /wp-content/plugins\n";
	echo "Disallow: /plugins\n";
	echo "Disallow: /wp-content/cache\n";
	echo "Disallow: /wp-content/themes\n";
	echo "Disallow: /trackback\n";
	echo "Disallow: /feed\n";
	echo "Disallow: /comments\n";
	echo "Disallow: /category/*/*\n";
	echo "Disallow: /kategorie/*/*\n";
	echo "Disallow: /tag/*/*\n";
	echo "Disallow: /author/*/*\n";
	echo "Disallow: /page/*/*\n";
	echo "Disallow: */trackback\n";
	echo "Disallow: */feed\n";
	echo "Disallow: */comments\n";
	echo "Disallow: /*?*\n";
	echo "Disallow: /*?\n";
	echo "Disallow: /new-blog.php\n";
	echo "Allow: /wp-content/uploads\n";
}


////////////////////////////////////////////////////////////////////
//// SIGNUP STUFF //////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

// Add Stuff to the Registration Page
//Steps Navigation
function mlublogs_signup_steps() {
	echo '<h1>Neuen Blog anlegen</h1>
		<ul id="steps">
			<li class="finished">1. Schritt: Anmelden</li>
			<li class="current">2. Schritt: Blog anlegen</li>
			<li>3. Schritt: Loslegen</li>
		</ul>';
}
add_action('before_signup_form','mlublogs_signup_steps');

//Nutzungsbedingungen
function mlublogs_nutzungsbedingungen() { ?>
	
	<!-- UNSERE NUTZUNGSBEDINGUNGEN -->
	<p id="tos">
	<label for="tos">Bedingungen:</label>
	<label class="checklabel">
	<input type="checkbox" name="tos" value="1">
	Ich akzeptiere die <a href="/dienst/nutzungsbedingungen/" target="_blank" title="Nutzungsbedingungen Blogs@MLU">&uuml;beraus spannenden Nutzungsbedingungen</a>
	</label>
	</p>
	<!-- END UNSERE NUTZUNGSBEDINGUNGEN -->
	
<?php }
add_action('signup_hidden_fields','mlublogs_nutzungsbedingungen');

?>
