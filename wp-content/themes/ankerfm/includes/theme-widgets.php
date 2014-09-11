<?php

// =============================== Flickr widget ======================================
function flickrWidget()
{
	$settings = get_option("widget_flickrwidget");

	$id = $settings['id'];
	$number = $settings['number'];

?>

<div id="flickr" class="widget">
	<h3 class="widget_title"><?php _e('Photos on <span>flick<span>r</span></span>', 'woothemes') ?></h3>
	<div class="pictures">
		<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $id; ?>"></script>        
		<div class="fix"></div>
	</div>
</div>

<?php
}

function flickrWidgetAdmin() {

	$settings = get_option("widget_flickrwidget");

	// check if anything's been sent
	if (isset($_POST['update_flickr'])) {
		$settings['id'] = strip_tags(stripslashes($_POST['flickr_id']));
		$settings['number'] = strip_tags(stripslashes($_POST['flickr_number']));

		update_option("widget_flickrwidget",$settings);
	}

	echo '<p>
			<label for="flickr_id">Flickr ID (<a href="http://www.idgettr.com">idGettr</a>):
			<input id="flickr_id" name="flickr_id" type="text" class="widefat" value="'.$settings['id'].'" /></label></p>';
	echo '<p>
			<label for="flickr_number">Number of photos:
			<input id="flickr_number" name="flickr_number" type="text" class="widefat" value="'.$settings['number'].'" /></label></p>';
	echo '<input type="hidden" id="update_flickr" name="update_flickr" value="1" />';

}

register_sidebar_widget('Woo - Flickr', 'flickrWidget');
register_widget_control('Woo - Flickr', 'flickrWidgetAdmin', 400, 200);


// =============================== Ad 300 x 250 widget ======================================
function ad300Widget()
{
include(TEMPLATEPATH . '/ads/widget_300_ad.php');
}
register_sidebar_widget('Woo - Ad 300x250', 'ad300Widget');

// =============================== Ad 125x125 widget ======================================
function adsWidget()
{
$settings = get_option("widget_adswidget");
$number = $settings['number'];
if ($number == 0) $number = 1;
$img_url = array();
$dest_url = array();

$numbers = range(1,$number); 
$counter = 0;

if (get_option('woo_ads_rotate') == "true") {
	shuffle($numbers);
}
?>
<div class="ads125 widget">

<?php
	foreach ($numbers as $number) {	
		$counter++;
		$img_url[$counter] = get_option('woo_ad_image_'.$number);
		$dest_url[$counter] = get_option('woo_ad_url_'.$number);
	
?>
        <a href="<?php echo "$dest_url[$counter]"; ?>"><img src="<?php echo "$img_url[$counter]"; ?>" alt="Ad" /></a>
<?php } ?>


    <div class="fix"></div>
</div>

<?php

}
register_sidebar_widget('Woo - Ads 125x125', 'adsWidget');

function adsWidgetAdmin() {

	$settings = get_option("widget_adswidget");

	// check if anything's been sent
	if (isset($_POST['update_ads'])) {
		$settings['number'] = strip_tags(stripslashes($_POST['ads_number']));

		update_option("widget_adswidget",$settings);
	}

	echo '<p>
			<label for="ads_number">Number of ads (1-6):
			<input id="ads_number" name="ads_number" type="text" class="widefat" value="'.$settings['number'].'" /></label></p>';
	echo '<input type="hidden" id="update_ads" name="update_ads" value="1" />';

}
register_widget_control('Woo - Ads 125x125', 'adsWidgetAdmin', 200, 200);

// =============================== Search widget ======================================
function searchWidget()
{
include(TEMPLATEPATH . '/search-form.php');
}
register_sidebar_widget('Woo - Search', 'SearchWidget');


// =============================== CampaignMonitor Subscribe widget ===================

class woo_CampaignMonitorWidget extends WP_Widget {
	function woo_CampaignMonitorWidget() {
		$widget_ops = array('classname' => 'widget_campaign_monitor', 'description' => 'Add a Campaign Monitor subscription form' );
		$this->WP_Widget('campaign_monitor', 'Woo - Campaign Monitor', $widget_ops);
	}

	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		echo $before_widget;
		$title = empty($instance['title']) ? 'Subscribe Now' : apply_filters('widget_title', $instance['title']);
		$action = empty($instance['action']) ? '#' : apply_filters('widget_action', $instance['action']);
		$id = empty($instance['id']) ? '' : apply_filters('widget_id', $instance['id']);

		echo '<div id="campaignmonitor" class="widget">';
		echo '<h3>'.$title.'</h3>';
		echo '<form name="campaignmonitorform" id="campaignmonitorform" action="'.$action.'" method="post">';
		echo '<input type="text" name="cm-'.$id.'" id="'.$id.'" class="field" value="Enter your e-mail address" onfocus="if (this.value == \'Enter your e-mail address\') {this.value = \'\';}" onblur="if (this.value == \'\') {this.value = \'Enter your e-mail address\';}" />';
		echo '<input id="newsletter-submit" class="submit" type="submit" name="submit" value="Submit" />';
		echo '</form>';
		echo '</div><!-- /campaignmonitor -->';
		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['action'] = $new_instance['action'];
		$instance['id'] = strip_tags($new_instance['id']);
		return $instance;
	}

	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'action' => '', 'id' => '' ) );
		$title = strip_tags($instance['title']);
		$action = $instance['action'];
		$id = strip_tags($instance['id']);
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('action'); ?>">Form Action: <input class="widefat" id="<?php echo $this->get_field_id('action'); ?>" name="<?php echo $this->get_field_name('action'); ?>" type="text" value="<?php echo attribute_escape($action); ?>" /></label></p>
			<p><label for="<?php echo $this->get_field_id('id'); ?>">Campaign Monitor ID: <input class="widefat" id="<?php echo $this->get_field_id('id'); ?>" name="<?php echo $this->get_field_name('id'); ?>" type="text" value="<?php echo attribute_escape($id); ?>" /></label></p>
<?php
	}
}
register_widget('woo_CampaignMonitorWidget');

// =============================== BS Media TAG Widget ======================================

class Bueno_featured extends WP_Widget {

   function Bueno_featured() {
	   $widget_ops = array('description' => 'Populate your sidebar with posts from a tag category.' );
       parent::WP_Widget(false, __('Woo - Featured Posts', 'woothemes'),$widget_ops);      
   }
   

   function widget($args, $instance) {  
   
    $tag_id = $instance['tag_id'];
    $num = $instance['num'];
	$title = $instance['title'];
    $content = $instance['content'];

     $tag_name = get_term_by('id', $tag_id, 'post_tag');
     $string = "tag=" . $tag_name->name ."&showposts=$num";
     $posts = get_posts($string);
	 
	 if($title == ''){ $title = 'Featured Posts'; } 
	 
     global $post;
     ?>
     <div id="featured" class="widget">
     <h3><?php echo $title; ?></h3>
        <ul>
                    
            <?php if ($posts) : $count = 0; ?>
            <?php foreach ($posts as $post) : setup_postdata($post); $count++; ?>
                                                                        
			<li>
				<span class="thumb">
					<?php woo_get_image('image',70,70,'thumbnail',90,get_the_id(),'src',1,0,'','',true); ?>
				</span>
				<div class="right">
					<h4><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h4>
					<p><?php 
					    if($content == "excerpt") { the_excerpt(); }
                       elseif($content == "content"){ the_content(); }
					   ?></p>
				</div>
			</li>
                <!-- Post Ends -->
                
            <?php endforeach; else: ?>
            <?php endif; ?>
            </ul>
            
            <div class="fix"></div>
            
            </div>
            
            <?php
			
           
		   	
            
   }

   function update($new_instance, $old_instance) {                
       return $new_instance;
   }

   function form($instance) {        
       
       $tag_id = esc_attr($instance['tag_id']);
       $num = esc_attr($instance['num']);
       $title = esc_attr($instance['title']);
       $content = esc_attr($instance['content']);
      
	if($content == '') {$content = 'exerpt';}

       ?>
       

		<p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','woothemes'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
        </p>
        <p>
	   	   <label for="<?php echo $this->get_field_id('tag_id'); ?>"><?php _e('Media Tag:','woothemes'); ?></label>
	       <?php $tags = get_tags(); print_r($cats); ?>
	       <select name="<?php echo $this->get_field_name('tag_id'); ?>" class="widefat" id="<?php echo $this->get_field_id('tag_id'); ?>">
           <option value="">-- Please Select --</option>
			<?php
			
           	foreach ($tags as $tag){
           	?><option value="<?php echo $tag->term_id; ?>" <?php if($tag_id == $tag->term_id){ echo "selected='selected'";} ?>><?php echo $tag->name . ' (' . $tag->count . ')'; ?></option><?php
           	}
           ?>
           </select>
       </p>
       <p>
          <label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Show Content:','woothemes'); ?></label>
          <select name="<?php echo $this->get_field_name('content'); ?>" class="widefat" id="<?php echo $this->get_field_id('content'); ?>">
           <option value="content" <?php if($content == "content"){ echo "selected='selected'";} ?>>The Content</option> 
           <option value="excerpt" <?php if($content == "excerpt"){ echo "selected='selected'";} ?>>The Excerpt</option>
           </select>
       </p>  
      <?php
   }

} 

register_widget('Bueno_featured');



/* Deregister Default Widgets */

/*
function woo_deregister_widgets(){
    unregister_widget('WP_Widget_Search');         
}
add_action('widgets_init', 'woo_deregister_widgets');  
*/

?>