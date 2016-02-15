<?php
/**
 * Frontpage generation functions
 * Creates the slider, the columns, the titles and the extra text
 *
 * @package tempera
 * @subpackage Functions
 */

//wp_enqueue_style( 'tempera-frontpage' );

function tempera_excerpt_length_slider( $length ) {
	$temperas = tempera_get_theme_options();
	return ceil($temperas['tempera_excerptwords']/2);
}

function tempera_excerpt_more_slider( $more ) {
	return '...';
}

     $temperas= tempera_get_theme_options();
     foreach ($temperas as $key => $value) { ${"$key"} = $value; } ?>

<script type="text/javascript">
     jQuery(document).ready(function() {
	// Slider creation
     jQuery('#slider').nivoSlider({
		effect: '<?php  echo $tempera_fpslideranim; ?>',
       	animSpeed: <?php echo $tempera_fpslidertime; ?>,
		<?php if($tempera_fpsliderarrows=="Hidden"): ?>directionNav: false,<?php endif;
   		      if($tempera_fpsliderarrows=="Always Visible"): ?>directionNavHide: false,<?php endif; ?>
		//controlNavThumbs: true, 
		pauseTime: <?php echo $tempera_fpsliderpause; ?>
     });
	});
</script> 

<div id="frontpage">
<?php
// When a post query has been selected from the Slider type in the admin area
     global $post;
     // Initiating query
     $custom_query = new WP_query();
     $slides = array();
	 
	 if($tempera_slideNumber>0):

     // Switch for Query type
     switch ($tempera_slideType) {
          case 'Latest Posts' :
               $custom_query->query('showposts='.$tempera_slideNumber.'&ignore_sticky_posts=1');
          break;
          case 'Random Posts' :
               $custom_query->query('showposts='.$tempera_slideNumber.'&orderby=rand&ignore_sticky_posts=1');
          break;
          case 'Latest Posts from Category' :
               $custom_query->query('showposts='.$tempera_slideNumber.'&category_name='.$tempera_slideCateg.'&ignore_sticky_posts=1');
          break;
          case 'Random Posts from Category' :
               $custom_query->query('showposts='.$tempera_slideNumber.'&category_name='.$tempera_slideCateg.'&orderby=rand&ignore_sticky_posts=1');
          break;
          case 'Sticky Posts' :
               $custom_query->query(array('post__in'  => get_option( 'sticky_posts' ), 'showposts' =>$tempera_slideNumber,'ignore_sticky_posts' => 1));
          break;
          case 'Specific Posts' :
               // Transofm string separated by commas into array
               $pieces_array = explode(",", $tempera_slideSpecific);
               $custom_query->query(array( 'post_type' => 'any', 'showposts' => -1, 'post__in' => $pieces_array, 'ignore_sticky_posts' => 1,'orderby' => 'post__in' ));
               break;
          case 'Custom Slides':

               break;
		  case 'Disabled':
			   break;
     }//switch
	 
	 endif; // slidenumber>0

	 add_filter( 'excerpt_length', 'tempera_excerpt_length_slider', 999 );
	 remove_filter( 'get_the_excerpt', 'tempera_custom_excerpt_more' ); // remove theme continue-reading on slider posts
	 add_filter( 'excerpt_more', 'tempera_excerpt_more_slider', 999 );
     // switch for reading/creating the slides
     switch ($tempera_slideType) {
		  case 'Disabled':
			   break;
          case 'Custom Slides':
               for ($i=1;$i<=5;$i++):
                    if(${"tempera_sliderimg$i"}):
                         $slide['image'] = esc_url(${"tempera_sliderimg$i"});
                         $slide['link'] = esc_url(${"tempera_sliderlink$i"});
                         $slide['title'] = ${"tempera_slidertitle$i"};
                         $slide['text'] = ${"tempera_slidertext$i"};
                         $slides[] = $slide;
                    endif;
               endfor;
               break;
          default:
			   if($tempera_slideNumber>0):	
               if ( $custom_query->have_posts() ) while ($custom_query->have_posts()) :
                    $custom_query->the_post();
                         $img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'slider');
                	$slide['image'] = $img[0];
                	$slide['link'] = get_permalink();
                	$slide['title'] = get_the_title();
                	$slide['text'] = get_the_excerpt();
                	$slides[] = $slide;
               endwhile;
			   endif; // slidenumber>0
               break;
     }; // switch


if (count($slides)>0):
     ?>
<div class="slider-wrapper theme-default <?php if($tempera_fpsliderarrows=="Visible on Hover"): ?>slider-navhover<?php endif; ?> slider-<?php echo  preg_replace("/[^a-z0-9]/i","",strtolower($tempera_fpslidernav)); ?>">
     <div class="ribbon"></div>
     <div id="slider" class="nivoSlider">
	<?php foreach($slides as $id=>$slide):
            if($slide['image']): ?>
            <a href='<?php echo ($slide['link']?$slide['link']:'#'); ?>'>
                 <img src='<?php echo $slide['image']; ?>' data-thumb='<?php echo $slide['image']; ?>' alt="<?php echo ($slide['title']?wp_kses($slide['title'],array()):''); ?>" <?php if ($slide['title'] || $slide['text']): ?> title="#caption<?php echo $id;?>" <?php endif; ?> />
            </a><?php endif; ?>
     <?php endforeach; ?>
     </div>
     <?php foreach($slides as $id=>$slide): ?>
            <div id="caption<?php echo $id;?>" class="nivo-html-caption">
                <?php echo (strlen($slide['title'])>0?'<h2>'.$slide['title'].'</h2>':'');
				      echo (strlen($slide['text'])>0?'<div class="slide-text">'.$slide['text'].'</div>':''); ?>
            </div>
	<?php endforeach; ?>
     </div>
<?php endif; ?> 
<div class="slider-shadow"></div>
<div id="pp-afterslider">
<?php
// First FrontPage Title
if($tempera_fronttext1) {?><div id="front-text1"> <h1><?php echo do_shortcode($tempera_fronttext1) ?> </h1></div><?php }
if($tempera_fronttext3) {?><div id="front-text3"> <blockquote><?php echo do_shortcode($tempera_fronttext3) ?> </blockquote></div><?php }

//COLUMNS
     // Initiating query
     $custom_query2 = new WP_query();
     $columns = array();
	 
	 if($tempera_columnNumber>0):
     // Switch for Query type
     switch ($tempera_columnType) {
          case 'Latest Posts' :
               $custom_query2->query('showposts='.$tempera_columnNumber.'&ignore_sticky_posts=1');
          break;
          case 'Random Posts' :
               $custom_query2->query('showposts='.$tempera_columnNumber.'&orderby=rand&ignore_sticky_posts=1');
          break;
          case 'Latest Posts from Category' :
               $custom_query2->query('showposts='.$tempera_columnNumber.'&category_name='.$tempera_columnCateg.'&ignore_sticky_posts=1');
          break;
          case 'Random Posts from Category' :
               $custom_query2->query('showposts='.$tempera_columnNumber.'&category_name='.$tempera_columnCateg.'&orderby=rand&ignore_sticky_posts=1');
          break;
          case 'Sticky Posts' :
               $custom_query2->query(array('post__in'  => get_option( 'sticky_posts' ), 'showposts' =>$tempera_columnNumber,'ignore_sticky_posts' => 1));
          break;
          case 'Specific Posts' :
               // Transform string separated by commas into array
               $pieces_array = explode(",", $tempera_columnSpecific);
               $custom_query2->query(array( 'post_type' => 'any', 'post__in' => $pieces_array, 'ignore_sticky_posts' => 1,'orderby' => 'post__in' ));
               break;
          case 'Widget Columns':
		  
			   break;
		  case 'Disabled':

               break;
     }//switch
	 
	 endif; // columnNumber>0
	 
	 
	    // switch for reading/creating the columns
     switch ($tempera_columnType) {
		  case 'Disabled':
			   break;
          case 'Widget Columns':
		       // if widgets loaded 
               if (is_active_sidebar('presentation-page-columns-area')) {
					echo "<div id='front-columns'>";
					dynamic_sidebar( 'presentation-page-columns-area' );
					echo "</div>";
				}
				// if no widgets loaded use the defaults
			   else {
					global $tempera_column_defaults;
					tempera_columns($tempera_column_defaults,$tempera_nrcolumns, $tempera_columnreadmore); 
				}
               break;
          default: 
			   if($tempera_columnNumber>0):
               if ( $custom_query2->have_posts() ) 
					while ($custom_query2->have_posts()) :
						$custom_query2->the_post();
                        $img = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ),'columns');
						$column['image'] = $img[0];
						$column['link'] = get_permalink();
						$column['text'] = get_the_excerpt();
						$column['title'] = get_the_title();
						$columns[] = $column;
					endwhile;
					tempera_columns($columns,$tempera_nrcolumns, $tempera_columnreadmore);
			   endif; // columnNumber>0
               break;
     }; // switch
	
function tempera_columns($columns,$nr_columns,$readmore){
	$counter=0;
	$temperas = tempera_get_theme_options();
    foreach ($temperas as $key => $value) { ${"$key"} = $value; }
	?>
 <div id="front-columns"> 
 <?php
	foreach($columns as $column):
		if($column['image']) :
			$counter++;
			if (!isset($column['blank'])) $column['blank'] = 0;
			$coldata = array(
				'colno' => (($counter%$nr_columns)?$counter%$nr_columns:$nr_columns),
				'counter' => $counter,
				'image' => esc_url($column['image']),
				'link' => esc_url($column['link']),
				'blank' => ($column['blank']?'target="_blank"':''),
				'title' =>  wp_kses_data($column['title']),
				'text' => wp_kses_data($column['text']),
				'readmore' => wp_kses_data($readmore),
			);
			tempera_singlecolumn_output($coldata);
		endif;
	endforeach; ?>
</div><?php
} // tempera_columns()

// tempera_singlecolumn_output() moved to includes/widget.php and made pluggable

// Second FrontPage title
if($tempera_fronttext2) {?><div id="front-text2"> <h1><?php echo do_shortcode($tempera_fronttext2) ?> </h1></div><?php }

// Frontpage second text area
if($tempera_fronttext4) {?><div id="front-text4"> <blockquote><?php echo do_shortcode($tempera_fronttext4) ?> </blockquote></div><?php }

	 remove_filter( 'excerpt_length', 'tempera_excerpt_length_slider', 999 );
	 remove_filter( 'excerpt_more', 'tempera_excerpt_more_slider', 999 );
if ($tempera_frontposts=="Enable"): get_template_part('content/content', 'frontpage'); endif; ?>
</div> <!-- #pp-afterslider -->
</div> <!-- #frontpage -->
<?php // End of tempera_frontpage_generator
