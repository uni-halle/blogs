<?php
$themename = "States";
$shortname = "wp_states";

$options = array (

	array(	"type" => "open"),

	array(	"name" => "State",
			"desc" => "Pick which State theme you'd like to use.",
			"id" => $shortname."_state",			
			"type" => "select"),
			"std" => "usa",
																										
	array(	"type" => "close")
	
);

function mytheme_add_admin() {

    global $themename, $shortname, $options;

    if ( $_GET['page'] == basename(__FILE__) ) {
    
        if ( 'save' == $_REQUEST['action'] ) {

                foreach ($options as $value) {
                    update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }

                foreach ($options as $value) {
                    if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }

                header("Location: themes.php?page=functions.php&saved=true");
                die;

        } else if( 'reset' == $_REQUEST['action'] ) {

            foreach ($options as $value) {
                delete_option( $value['id'] ); }

            header("Location: themes.php?page=functions.php&reset=true");
            die;

        }
    }

    add_theme_page($themename." Options", "".$themename." Options", 'edit_themes', basename(__FILE__), 'mytheme_admin');

}

function wp_states_state(){
	if ( get_option('wp_states_state' ) == "") {
		echo "usa";
	} else {
		echo (get_option('wp_states_state'));
	}
}

function mytheme_admin() {

    global $themename, $shortname, $options;

    if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings saved.</strong></p></div>';
    if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$themename.' settings reset.</strong></p></div>';
    
?>
<div class="wrap">
<h2><?php echo $themename; ?> settings</h2>

<form method="post">



<?php foreach ($options as $value) { 
    
	switch ( $value['type'] ) {
	
		case "open":
		?>
        <table width="100%" border="0" style="background-color:#eef5fb; padding:10px;">
		
        
        
		<?php break;
		
		case "close":
		?>
		
        </table><br />
        
		<?php break;
		
		case 'select':
		?>
        <tr>
            <td width="20%" rowspan="2" valign="middle"><strong><?php echo $value['name']; ?></strong></td>
            <td width="80%">
				<label for="<?php echo $value['id']; ?>">
					<select id="<?php echo $value['id']; ?>" name="<?php echo $value['id']; ?>">
						<option<?php if ( get_settings( $value['id'] ) == "usa") { echo ' selected="selected"'; } ?> value="usa">USA</option>
						<option<?php if ( get_settings( $value['id'] ) == "alabama") { echo ' selected="selected"'; } ?> value="alabama">Alabama</option>
						<option<?php if ( get_settings( $value['id'] ) == "alaska") { echo ' selected="selected"'; } ?> value="alaska">Alaska</option>
						<option<?php if ( get_settings( $value['id'] ) == "arizona") { echo ' selected="selected"'; } ?> value="arizona">Arizona</option>
						<option<?php if ( get_settings( $value['id'] ) == "arkansas") { echo ' selected="selected"'; } ?> value="arkansas">Arkansas</option>
						<option<?php if ( get_settings( $value['id'] ) == "california") { echo ' selected="selected"'; } ?> value="california">California</option>
						<option<?php if ( get_settings( $value['id'] ) == "colorado") { echo ' selected="selected"'; } ?> value="colorado">Colorado</option>
						<option<?php if ( get_settings( $value['id'] ) == "connecticut") { echo ' selected="selected"'; } ?> value="connecticut">Connecticut</option>
						<option<?php if ( get_settings( $value['id'] ) == "delaware") { echo ' selected="selected"'; } ?> value="delaware">Delaware</option>
						<option<?php if ( get_settings( $value['id'] ) == "dc") { echo ' selected="selected"'; } ?> value="dc">Dist of Columbia</option>
						<option<?php if ( get_settings( $value['id'] ) == "florida") { echo ' selected="selected"'; } ?> value="florida">Florida</option>
						<option<?php if ( get_settings( $value['id'] ) == "georgia") { echo ' selected="selected"'; } ?> value="georgia">Georgia</option>
						<option<?php if ( get_settings( $value['id'] ) == "hawaii") { echo ' selected="selected"'; } ?> value="hawaii">Hawaii</option>
						<option<?php if ( get_settings( $value['id'] ) == "idaho") { echo ' selected="selected"'; } ?> value="idaho">Idaho</option>
						<option<?php if ( get_settings( $value['id'] ) == "illinois") { echo ' selected="selected"'; } ?> value="illinois">Illinois</option>
						<option<?php if ( get_settings( $value['id'] ) == "indiana") { echo ' selected="selected"'; } ?> value="indiana">Indiana</option>
						<option<?php if ( get_settings( $value['id'] ) == "iowa") { echo ' selected="selected"'; } ?> value="iowa">Iowa</option>
						<option<?php if ( get_settings( $value['id'] ) == "kansas") { echo ' selected="selected"'; } ?> value="kansas">Kansas</option>
						<option<?php if ( get_settings( $value['id'] ) == "kentucky") { echo ' selected="selected"'; } ?> value="kentucky">Kentucky</option>
						<option<?php if ( get_settings( $value['id'] ) == "louisiana") { echo ' selected="selected"'; } ?> value="louisiana">Louisiana</option>
						<option<?php if ( get_settings( $value['id'] ) == "maine") { echo ' selected="selected"'; } ?> value="maine">Maine</option>
						<option<?php if ( get_settings( $value['id'] ) == "maryland") { echo ' selected="selected"'; } ?> value="maryland">Maryland</option>
						<option<?php if ( get_settings( $value['id'] ) == "massachesetts") { echo ' selected="selected"'; } ?> value="massachesetts">Massachusetts</option>
						<option<?php if ( get_settings( $value['id'] ) == "michigan") { echo ' selected="selected"'; } ?> value="michigan">Michigan</option>
						<option<?php if ( get_settings( $value['id'] ) == "minnesota") { echo ' selected="selected"'; } ?> value="minnesota">Minnesota</option>
						<option<?php if ( get_settings( $value['id'] ) == "mississippi") { echo ' selected="selected"'; } ?> value="mississippi">Mississippi</option>
						<option<?php if ( get_settings( $value['id'] ) == "missouri") { echo ' selected="selected"'; } ?> value="missouri">Missouri</option>
						<option<?php if ( get_settings( $value['id'] ) == "montana") { echo ' selected="selected"'; } ?> value="montana">Montana</option>
						<option<?php if ( get_settings( $value['id'] ) == "nebraska") { echo ' selected="selected"'; } ?> value="nebraska">Nebraska</option>
						<option<?php if ( get_settings( $value['id'] ) == "nevada") { echo ' selected="selected"'; } ?> value="nevada">Nevada</option>
						<option<?php if ( get_settings( $value['id'] ) == "new-hampshire") { echo ' selected="selected"'; } ?> value="new-hampshire">New Hampshire</option>
						<option<?php if ( get_settings( $value['id'] ) == "new-jersey") { echo ' selected="selected"'; } ?> value="new-jersey">New Jersey</option>
						<option<?php if ( get_settings( $value['id'] ) == "new-mexico") { echo ' selected="selected"'; } ?> value="new-mexico">New Mexico</option>
						<option<?php if ( get_settings( $value['id'] ) == "new-york") { echo ' selected="selected"'; } ?> value="new-york">New York</option>
						<option<?php if ( get_settings( $value['id'] ) == "north-carolina") { echo ' selected="selected"'; } ?> value="north-carolina">North Carolina</option>
						<option<?php if ( get_settings( $value['id'] ) == "north-dakota") { echo ' selected="selected"'; } ?> value="north-dakota">North Dakota</option>
						<option<?php if ( get_settings( $value['id'] ) == "ohio") { echo ' selected="selected"'; } ?> value="ohio">Ohio</option>
						<option<?php if ( get_settings( $value['id'] ) == "oklahoma") { echo ' selected="selected"'; } ?> value="oklahoma">Oklahoma</option>
						<option<?php if ( get_settings( $value['id'] ) == "oregon") { echo ' selected="selected"'; } ?> value="oregon">Oregon</option>
						<option<?php if ( get_settings( $value['id'] ) == "pennsylvania") { echo ' selected="selected"'; } ?> value="pennsylvania">Pennsylvania</option>
						<option<?php if ( get_settings( $value['id'] ) == "rhode-island") { echo ' selected="selected"'; } ?> value="rhode-island">Rhode Island</option>
						<option<?php if ( get_settings( $value['id'] ) == "south-carolina") { echo ' selected="selected"'; } ?> value="south-carolina">South Carolina</option>
						<option<?php if ( get_settings( $value['id'] ) == "north-carolina") { echo ' selected="selected"'; } ?> value="north-carolina">South Dakota</option>
						<option<?php if ( get_settings( $value['id'] ) == "tennessee") { echo ' selected="selected"'; } ?> value="tennessee">Tennessee</option>
						<option<?php if ( get_settings( $value['id'] ) == "texas") { echo ' selected="selected"'; } ?> value="texas">Texas</option>
						<option<?php if ( get_settings( $value['id'] ) == "utah") { echo ' selected="selected"'; } ?> value="utah">Utah</option>
						<option<?php if ( get_settings( $value['id'] ) == "vermont") { echo ' selected="selected"'; } ?> value="vermont">Vermont</option>
						<option<?php if ( get_settings( $value['id'] ) == "virginia") { echo ' selected="selected"'; } ?> value="virginia">Virginia</option>
						<option<?php if ( get_settings( $value['id'] ) == "washington") { echo ' selected="selected"'; } ?> value="washington">Washington</option>
						<option<?php if ( get_settings( $value['id'] ) == "west-virginia") { echo ' selected="selected"'; } ?> value="west-virginia">West Virginia</option>
						<option<?php if ( get_settings( $value['id'] ) == "wisconsin") { echo ' selected="selected"'; } ?> value="wisconsin">Wisconsin</option>
						<option<?php if ( get_settings( $value['id'] ) == "wyoming") { echo ' selected="selected"'; } ?> value="wyoming">Wyoming</option>
					</select>
				</label>
			</td>
       </tr>
                
       <tr>
            <td><small><?php echo $value['desc']; ?></small></td>
       </tr><tr><td colspan="2" style="margin-bottom:5px;border-bottom:1px dotted #000000;">&nbsp;</td></tr><tr><td colspan="2">&nbsp;</td></tr>

		<?php
        break;
 
	} 
}
?>

<!--</table>-->

	<p class="submit">
		<input name="save" type="submit" value="Save changes" />    
		<input type="hidden" name="action" value="save" />
	</p>
</form>

<?php
}

add_action('admin_menu', 'mytheme_add_admin'); ?>
<?php
if ( function_exists('register_sidebar') )
	register_sidebar(array(
        'before_widget' => '<li id="%1$s" class="widget %2$s"><div class="widget-container">',
        'after_widget' => '</div></li>',
        'before_title' => '<div class="widget-title">',
        'after_title' => '</div>',
    ));


/**
 * A script for showing a breadcrumb menu within template files.
 * Use the template tag breadcrumb_trail() to get it to display.
 * Two filter hooks are available for developers to change the
 * output: breadcrumb_trail_args and breadcrumb_trail.
 *
 * @copyright 2008 - 2009
 * @version 0.1.1
 * @author Justin Tadlock
 * @link http://justintadlock.com/archives/2009/04/05/breadcrumb-trail-wordpress-plugin
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * Localized to match the Hybrid theme
 * _e('Text', 'hybrid')
 * __('Text', 'hybrid')
 *
 * @package BreadcrumbTrail
 */

/**
 * Shows a breadcrumb for all types of pages.  Themes and 
 * plugins can filter $args or input directly.  Allow filtering of 
 * only the $args using get_the_breadcrumb_args.
 *
 * @since 0.1
 * @global $post The current post's DB object.
 * @param array $args Mixed arguments for the menu.
 * @return string Output of the breadcrumb menu.
 */
function breadcrumb_trail( $args = array() ) {
	global $post, $wp_query;

	/* Set up the default arguments for the breadcrumb. */
	$defaults = array(
		'separator' => '&middot;',
		'before' => '<span class="breadcrumb-title">' . __('', 'hybrid') . '</span>',
		'after' => false,
		'front_page' => true,
		'show_home' => __('Home', 'hybrid'),
		'single_tax' => 'category',
		'format' => 'flat', // Implement later
		'echo' => true,
	);

	/* Apply filters to the arguments. */
	$args = apply_filters( 'breadcrumb_trail_args', $args );

	/* Parse the arguments and extract them for easy variable naming. */
	$args = wp_parse_args( $args, $defaults );
	extract( $args );

	/* Put spaces around the separator. */
	$separator = ' ' . $separator . ' ';

	/* If it is the front page and $front_page is false, return no value. */
	if ( is_front_page() && !$front_page )
		return;

	/* Begin the breadcrumb. */
	$breadcrumb = '<div class="breadcrumb breadcrumbs"><div class="breadcrumb-trail">' . $before . ' ';

	/* Display home link. */
	if ( $show_home && !is_home() && !is_front_page() )
		$breadcrumb .= '<a href="' . get_bloginfo( 'url' ) . '" title="' . get_bloginfo( 'name' ) . '" rel="home" class="trail-begin">' . $show_home . '</a>' . $separator;

	/* If it is the home page or front page. */
	if ( ( is_front_page() && $front_page ) || is_home() ) :
		$breadcrumb .= '<span class="trail-end">' . $show_home . '</span>';

	/* If it is a page. */
	elseif ( is_page() ) :
		$parent_id = $post->post_parent;
		while ( $parent_id ) :
			$page = get_page( $parent_id );
			$parents[]  = '<a href="' . get_permalink( $page->ID ) . '" title="' . get_the_title( $page->ID ) . '">' . get_the_title( $page->ID ) . '</a> ' . $separator;
			$parent_id  = $page->post_parent;
		endwhile;
		if ( $parents ) :
			$parents = array_reverse( $parents );
			$breadcrumb .= join( ' ', $parents );
		endif;
		$breadcrumb .= '<span class="trail-end">' . get_the_title() . '</span>';

	/* If is attachment page. */
	elseif ( is_attachment() ) :
		$breadcrumb .= '<a href="' . get_permalink( $post->post_parent ) . '" title="' . get_the_title( $post->post_parent ) . '">' . get_the_title( $post->post_parent ) . '</a>';
		$breadcrumb .= $separator . '<span class="trail-end">' . get_the_title() . '</span>';

	/* Single posts. */
	elseif ( is_single() ) :
		if ( $single_tax && $terms = get_the_term_list( $post->ID, $single_tax, '', ', ', '' ) ) :
			$breadcrumb .= $terms . $separator;
		endif;
		$breadcrumb .= '<span class="trail-end">' . single_post_title( false, false ) . '</span>';

	/* Taxonomy (tags, categories, etc.) archives. */
	elseif ( is_tax() || is_category() || is_tag() ) :
		$term = $wp_query->get_queried_object();

		if ( is_category() && $term->parent ) :
			$parents = get_category_parents( $term->parent, true, $separator, false );
			if ( $parents ) :
				$breadcrumb .= $parents;
			endif;
		endif;

		$breadcrumb .= '<span class="trail-end">' . $term->name . '</span>';

	/* Author archives. */
	elseif ( is_author() ) :
		$breadcrumb .= '<span class="trail-end">' . get_the_author_meta( 'display_name', get_query_var( 'author' ) ) . '</span>';

	/* Search results. */
	elseif ( is_search() ) :
		$breadcrumb .= '<span class="trail-end">' . sprintf( __('Search results for &quot;%1$s&quot;', 'hybrid'), esc_attr( get_search_query() ) ) . '</span>';

	/* Minutely and hourly archives. */
	elseif ( get_query_var( 'minute' ) && get_query_var( 'hour' ) ) :
		$breadcrumb .= '<span class="trail-end">' . get_the_time( __('g:i a', 'hybrid') ) . '</span>';

	/* Minutely archives. */
	elseif ( get_query_var( 'minute' ) ) :
		$breadcrumb .= '<span class="trail-end">' . sprintf( __('Minute %1$s', 'hybrid'), get_the_time( __('i', 'hybrid') ) ) . '</span>';

	/* Hourly archives. */
	elseif ( get_query_var( 'hour' ) ) :
		$breadcrumb .= '<span class="trail-end">' . get_the_time( __('g a', 'hybrid') ) . '</span>';

	/* Daily archives. */
	elseif ( is_day() ) :
		$breadcrumb .= '<a href="' . get_year_link( get_the_time( __('Y', 'hybrid') ) ) . '" title="' . get_the_time( __('Y', 'hybrid') ) . '">' . get_the_time( __('Y', 'hybrid') ) . '</a>' . $separator;
		$breadcrumb .= '<a href="' . get_month_link( get_the_time( __('Y', 'hybrid') ), get_the_time( __('m', 'hybrid') ) ) . '" title="' . get_the_time( __('F', 'hybrid') ) . '">' . get_the_time( __('F', 'hybrid') ) . '</a>' . $separator;
		$breadcrumb .= '<span class="trail-end">' . get_the_time( __('j', 'hybrid') ) . '</span>';

	/* Weekly archives. */
	elseif ( get_query_var( 'w' ) ) :
		$breadcrumb .= '<a href="' . get_year_link( get_the_time( __('Y', 'hybrid') ) ) . '" title="' . get_the_time( __('Y', 'hybrid') ) . '">' . get_the_time( __('Y', 'hybrid') ) . '</a>' . $separator;
		$breadcrumb .= '<span class="trail-end">' . sprintf( __('Week %1$s', 'hybrid' ), get_the_time( __('W', 'hybrid') ) ) . '</span>';

	/* Monthly archives. */
	elseif ( is_month() ) :
		$breadcrumb .= '<a href="' . get_year_link( get_the_time( __('Y', 'hybrid') ) ) . '" title="' . get_the_time( __('Y', 'hybrid') ) . '">' . get_the_time( __('Y', 'hybrid') ) . '</a>' . $separator;
		$breadcrumb .= '<span class="trail-end">' . get_the_time( __('F', 'hybrid') ) . '</span>';

	/* Yearly archives. */
	elseif ( is_year() ) :
		$breadcrumb .= '<span class="trail-end">' . get_the_time( __('Y', 'hybrid') ) . '</span>';

	/* 404 error pages. */
	elseif ( is_404() ) :
		$breadcrumb .= '<span class="trail-end">' . __('404 Not Found', 'hybrid') . '</span>';

	/* Everything else. */
	else :
		$breadcrumb .= '<span class="trail-end">' . wp_title( false, false, false ) . '</span>';

	endif;

	/* End the breadcrumb. */
	$breadcrumb .= $after . '</div></div>';

	/* Output the breadcrumb. */
	if ( $echo )
		echo apply_filters( 'breadcrumb_trail', $breadcrumb );
	else
		return apply_filters( 'breadcrumb_trail', $breadcrumb );
}

?>