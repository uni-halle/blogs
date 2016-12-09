<?php
/**
 * List View Nav Template
 * This file loads the list view navigation.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/nav.php
 *
 * @package TribeEventsCalendar
 * @version 4.1
 *
 */
global $wp_query;

$events_label_plural = tribe_get_event_label_plural();

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
} ?>

<h3 class="tribe-events-visuallyhidden"><?php echo esc_html( sprintf( esc_html__( '%s List Navigation', 'the-events-calendar' ), $events_label_plural ) ); ?></h3>
<script>// <![CDATA[
jQuery(document).ready(function() {
	jQuery("#tribe_cat").change(function() {
		jQuery(".type-tribe_events").hide();
		
		var valueSelected = this.value;
		if(valueSelected=='*')
			jQuery(".type-tribe_events").show();
		else
		jQuery(".tribe-events-category-" + valueSelected).show();
    });
	
});

// ]]>
</script>
<ul class="tribe-events-sub-nav">
	<!-- Left Navigation -->

	<?php if ( tribe_has_previous_event() ) : ?>
		<li class="<?php echo esc_attr( tribe_left_navigation_classes() ); ?>">
			<a href="<?php echo esc_url( tribe_get_listview_prev_link() ); ?>" ><?php printf( '<span>&laquo;</span> ' . esc_html__( 'Previous %s', 'the-events-calendar' ), $events_label_plural ); ?></a>

		</li><!-- .tribe-events-nav-left -->
	<?php endif; ?>
	
		

	<!-- Right Navigation -->
	<?php if ( tribe_has_next_event() ) : ?>
		<li class="<?php echo esc_attr( tribe_right_navigation_classes() ); ?>">
			<a href="<?php echo esc_url( tribe_get_listview_next_link() ); ?>" ><?php printf( esc_html__( 'Next %s', 'the-events-calendar' ), $events_label_plural . ' <span>&raquo;</span>' ); ?></a>
		</li><!-- .tribe-events-nav-right -->
	<?php endif; ?>
</ul>
	<?php if ( $nav_count!=1) : ?>
<div  class="select_tribe_cat">

<?php
//echo $nav_count;
static $nav_count=1;
//echo $nav_count;
		$terms = get_terms("tribe_events_cat", array('hide_empty' => false));
		//var_export($terms);
 		$count = count($terms);
 		if ( $count > 0 ){
 		 echo '<select id="tribe_cat">';
		 echo '<option value="*">[:de]Alle Veranstaltungen[:][:en]All Events[:]</option>';
 		    foreach ( $terms as $term ) {
				//var_export($term);
 		//    if ( $term->count > 0 )
			  echo '<option value="' . $term->slug . '">' . $term->name . '</option>';

 		    }
 		   echo '</select>';
 		}

	?>

</div>
<?php endif; ?>
