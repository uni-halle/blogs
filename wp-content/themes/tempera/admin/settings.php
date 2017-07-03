<?php
// Callback functions

// General suboptions description idly idling doing nothing
function cryout_section_layout_fn() { };
function cryout_section_presentation_fn() { };
function cryout_section_header_fn() { };
function cryout_section_text_fn() { };
function cryout_section_graphics_fn() { };
function cryout_section_post_fn() { };
function cryout_section_excerpt_fn() { };
function cryout_section_appereance_fn() { };
function cryout_section_featured_fn() { };
function cryout_section_social_fn() { };
function cryout_section_misc_fn() { };
// nothing at all


////////////////////////////////
//// LAYOUT SETTINGS ///////////
////////////////////////////////


// RADIO-BUTTON - Name: tempera_settings[side]
function cryout_setting_side_fn() {
global $temperas;
	$items = array("1c", "2cSr", "2cSl", "3cSr" , "3cSl", "3cSs");
	$layout_text["1c"] = __("One column (no sidebars)","tempera");
	$layout_text["2cSr"] = __("Two columns, sidebar on the right","tempera");
	$layout_text["2cSl"] = __("Two columns, sidebar on the left","tempera");
	$layout_text["3cSr"] = __("Three columns, sidebars on the right","tempera");
	$layout_text["3cSl"] = __("Three columns, sidebars on the left","tempera");
	$layout_text["3cSs"] = __("Three columns, one sidebar on each side","tempera");

	foreach($items as $item) {
		$checkedClass = ($temperas['tempera_side']==$item) ? ' checkedClass' : '';
		echo "<label id='$item' class='layouts $checkedClass'><input ";
		checked($temperas['tempera_side'],$item);
		echo " value='$item' onClick=\"changeBorder('$item','layouts');\" name='tempera_settings[tempera_side]' type='radio' /><img title='$layout_text[$item]' src='".get_template_directory_uri()."/admin/images/".$item.".png'/></label>";
	}
	echo "<div><small>".__("Choose your layout. Possible options are: <br> No sidebar, a single sidebar on either left of right, two sidebars on either left or right and two sidebars on each side.<br>This can be overriden in pages by using Page Templates.","tempera")."</small></div>";
}

 //SLIDER - Name: tempera_settings[sidewidth]
function cryout_setting_sidewidth_fn() {
     global $temperas; ?>
     <script type="text/javascript">

	jQuery(function() {

		jQuery( "#slider-range" ).slider({
			range: true,
			step:10,
			min: 0,
			max: 1920,
			values: [ <?php echo $temperas['tempera_sidewidth'] ?>, <?php echo ($temperas['tempera_sidewidth']+$temperas['tempera_sidebar']); ?> ],
			slide: function( event, ui ) {
          			range=ui.values[ 1 ] - ui.values[ 0 ];

           			if (ui.values[ 0 ]<500) {ui.values[ 0 ]=500; return false;};
          			if (	range<220 || range>800 ) { ui.values[ 1 ] = <?php echo $temperas['tempera_sidebar']+$temperas['tempera_sidewidth'];?>; return false; };

          			jQuery( "#tempera_sidewidth" ).val( ui.values[ 0 ] );
          			jQuery( "#tempera_sidebar" ).val( ui.values[ 1 ] - ui.values[ 0 ] );
          			jQuery( "#totalsize" ).html( ui.values[ 1 ]);
          			jQuery( "#contentsize" ).html( ui.values[ 0 ]);jQuery( "#barsize" ).html( ui.values[ 1 ]-ui.values[ 0 ]);

          			var percentage = parseInt( jQuery( "#slider-range .ui-slider-range" ).css('width') );
          			var leftwidth = parseInt( jQuery( "#slider-range .ui-slider-range" ).position().left );
          			jQuery( "#barb" ).css('left',-80+leftwidth+percentage/2+"px");
          			jQuery( "#contentb" ).css('left',-50+leftwidth/2+"px");
          			jQuery( "#totalb" ).css('width',(percentage+leftwidth)+"px");
               }
		});

		jQuery( "#tempera_sidewidth" ).val( <?php echo $temperas['tempera_sidewidth'];?> );
		jQuery( "#tempera_sidebar" ).val( <?php echo $temperas['tempera_sidebar'];?> );
		var percentage = <?php echo ($temperas['tempera_sidebar']/1920)*100;?> ;
		var leftwidth = <?php echo ($temperas['tempera_sidewidth']/1920)*100;?> ;

		jQuery( "#barb" ).css('left',(-18+leftwidth+percentage/2)+"%");
		jQuery( "#contentb" ).css('left',(-8+leftwidth/2)+"%");
		jQuery( "#totalb" ).css('width',(-2+percentage+leftwidth)+"%");
	});

     </script>

     <div id="absolutedim">

     	<b id="contentb"><?php _e("Content =","tempera");?> <span id="contentsize"><?php echo $temperas['tempera_sidewidth'];?></span>px</b>
     	<b id="barb"><?php _e("Sidebar(s) =","tempera");?> <span id="barsize"><?php echo $temperas['tempera_sidebar'];?></span>px</b>
     	<b id="totalb"> <?php _e("Total width =","tempera");?> <span id="totalsize"><?php echo $temperas['tempera_sidewidth']+ $temperas['tempera_sidebar'];?></span>px</b>

     <p> <?php
     echo "<input type='hidden' name='tempera_settings[tempera_sidewidth]' id='tempera_sidewidth' />";
	echo "<input type='hidden' name='tempera_settings[tempera_sidebar]' id='tempera_sidebar' />"; ?>
     </p>
     <div id="slider-range"></div>
     <?php echo "<div><small>".__("Select the width of your <b>content</b> and <b>sidebar(s)</b>. When using a 3 columns layout (with 2 sidebars) they will each have half the configured width.","tempera")."</small></div>"; ?>
     </div><!-- End absolutedim -->

<?php } // cryout_setting_sidewidth_fn()

//CHECKBOX - Name: tempera_settings[magazinelayout]
function cryout_setting_magazinelayout_fn() {
	global $temperas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_magazinelayout' name='tempera_settings[tempera_magazinelayout]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_magazinelayout'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Enable the Magazine Layout. This layout applies to pages with posts and shows 2 posts per row.","tempera")."</small></div>";
}

//CHECKBOX - Name: ma_options[mobile]
function cryout_setting_mobile_fn() {
	global $temperas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<span class='cryout_select'><select id='tempera_mobile' name='tempera_settings[tempera_mobile]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_mobile'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select></span>&nbsp;";
	cryout_proto_field( $temperas, "checkbox", "tempera_zoom", $temperas['tempera_zoom'], __('Allow zoom', 'tempera'));
	echo "<div><small>".__("Enable to make Tempera fully responsive. The layout and general sizes of your blog will adjust depending on what device and what resolution it is viewed in.<br> Do not disable unless you have a good reason to.","tempera")."</small></div>";
} // cryout_setting_mobile_fn()


//////////////////////////////
/////HEADER SETTINGS//////////
/////////////////////////////

 //SELECT - Name: tempera_settings[hheight]
function cryout_setting_hheight_fn() {
	global $temperas; $totally = $temperas['tempera_sidebar']+$temperas['tempera_sidewidth'];
	cryout_proto_field( $temperas, "input4", "tempera_hheight", $temperas['tempera_hheight'], " px");
	echo "<div><small>".__("Select the header's height. After saving the settings go and upload your new header image. The header's width will be ","tempera")."<strong>".$totally."px</strong>.</small></div>";
}

function cryout_setting_himage_fn() {
	global $temperas;
	//$checkedClass = ($temperas['tempera_hcenter']=='1') ? ' checkedClass' : '';
	echo "<a href=\"?page=custom-header\" class=\"button\" target=\"_blank\">".__('Define header image','tempera')."</a><br>";
	cryout_proto_field( $temperas, "checkbox", "tempera_hcenter", $temperas['tempera_hcenter'], __('Center the header image horizontally','tempera'));
	echo "<br>";
	cryout_proto_field( $temperas, "checkbox", "tempera_hratio", $temperas['tempera_hratio'], __('Keep header image aspect ratio.', 'tempera'));
	echo "<div><small>".__("By default the header has a minimum height set. This option removes that and the header becomes fully responsive, scalling to any size.<br> Only enable this if you're <b>not</b> using a logo or site title and description in the header. ","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[linkheader]
function cryout_setting_siteheader_fn() {
	global $temperas;
	cryout_proto_field( $temperas, "select", "tempera_siteheader",
			array("Site Title and Description" , "Custom Logo" , "Clickable header image" , "Empty"),
			array( __("Site Title and Description","tempera"), __("Custom Logo","tempera"), __("Clickable header image","tempera"), __("Empty","tempera"))
	);
	echo "<div><small>".__("Choose what to display inside your header area.","tempera")."</small></div>";
}

// TEXTBOX - Name: tempera_settings[favicon]
function cryout_setting_logoupload_fn() {
	global $temperas; ?>
	<div><img  src='<?php echo  ($temperas['tempera_logoupload']!='')? esc_url($temperas['tempera_logoupload']):get_template_directory_uri().'/admin/images/placeholder.gif'; ?>' class="imagebox" style="max-height:60px" /><br> <?php
	cryout_proto_field( $temperas, "input40url", "tempera_logoupload", $temperas['tempera_logoupload'], '','slideimages');
	echo "<div><small>".__("Custom Logo upload. The logo will appear over the header image if you have used one.","tempera")."</small></div>"; ?>
	<span class="description"><br><a href="#" class="upload_image_button button"><?php _e( 'Select / Upload Image', 'tempera' );?></a> </span> <?php
}

function  cryout_setting_headermargin_fn() {
	global $temperas;
	cryout_proto_field( $temperas, "input4str", "tempera_headermargintop", $temperas['tempera_headermargintop'], ' px '.__("top","tempera")."&nbsp; &nbsp;" );
	cryout_proto_field( $temperas, "input4str", "tempera_headermarginleft", $temperas['tempera_headermarginleft'], ' px '.__("left","tempera") );
	echo "<div><small>".__("Select the top and left spacing for the header content. Use it to better position your site title and description or custom logo inside the header. ","tempera")."</small></div>";
}

// TEXTBOX - Name: tempera_settings[favicon]
function cryout_setting_favicon_fn() {
	global $temperas;?>
	<div><img src='<?php echo  ($temperas['tempera_favicon']!='')? esc_url($temperas['tempera_favicon']):get_template_directory_uri().'/admin/images/placeholder.gif'; ?>' class="imagebox" width="64" height="64"/><br> <?php
	cryout_proto_field( $temperas, "input40url", "tempera_favicon", $temperas['tempera_favicon'], '','slideimages');
	//cryout_proto_field( $temperas, "", "", $temperas[''], '');
	echo "<div><small>".__("Limitations: It has to be an image. It should be max 64x64 pixels in dimensions. Recommended file extensions .ico and .png. <br> <strong>Note that some browsers do not display the changed favicon instantly.</strong>","tempera")."</small></div>"; ?>
	<span class="description"><br><a href="#" class="upload_image_button button"><?php _e( 'Select / Upload Image', 'tempera' );?></a> </span>
</div> <?php
}

// SELECT - Name: tempera_settings[headerwidgetwidth]
function cryout_setting_headerwidgetwidth_fn() {
	global $temperas;
	cryout_proto_field( $temperas, "select", "tempera_headerwidgetwidth",
			array("60%" , "50%" , "33%" , "25%"),
			array( __("60%","tempera"), __("50%","tempera"), __("33%","tempera"), __("25%","tempera"))
	);
	echo "<div><small>".__("Limit the header widget area max width as percentage of the entire header width.","tempera")."</small></div>";
}


////////////////////////////////
//// PRESENTATION SETTINGS /////
////////////////////////////////


//CHECKBOX - Name: tempera_settings[frontpage]
function cryout_setting_frontpage_fn() {
	global $temperas;
	cryout_proto_field( $temperas, "select", "tempera_frontpage",
		array("Enable" , "Disable"),
		array( __("Enable","tempera"), __("Disable","tempera"))
	);
	echo "<div><small>".__("Enable the presentation front-page. This will become your new home page. <br> If you want another page to hold your latest blog posts, choose 'Blog Template (Posts Page)' from Page Templates while creating or editing that page.","tempera")."</small></div>";
	if ($temperas['tempera_frontpage'] == 'Enable' && get_option('show_on_front') != 'posts') {
		printf ( '<div class="slmini" style="color:#cb5920;">'.__('WordPress\' <em>Front page displays</em> option is set to use a static page. WordPress guidelines require that the static page option have priority over theme options.<br> Go to %1$s and set the <em>Front page displays</em> option to <em><strong>Your latest posts</strong></em> to enable the Presentation Page.',"tempera").'</div>', '<a href="/wp-admin/options-reading.php" > Settings &raquo; Reading</a>');
	};
} // cryout_setting_frontpage_fn()

function cryout_setting_frontposts_fn() {
	global $temperas;
	cryout_proto_field( $temperas, "select", "tempera_frontposts",
		array("Enable" , "Disable"),
		array( __("Enable","tempera"), __("Disable","tempera"))
	);
	echo "<div><small>".__("Enable to display latest posts on the presentation page, below the columns. Sticky posts are always displayed and not counted.","tempera")."</small></div><br>";

	echo "<div class='slmini'><b>".__("Show:","tempera")."</b> ";
	echo "<input type='text' id='tempera_frontpostscount' name='tempera_settings[tempera_frontpostscount]' size='3' value='";
 	echo $temperas['tempera_frontpostscount']."'> ".__('posts','tempera');
	echo "<div><small>".__("The number of posts to show on the Presentation Page. The same number of posts will be loaded with the <em>More Posts</em> button.","tempera")."</small></div><br>";
	echo "</div>";

	echo "<div class='slmini'><b>".__("Posts per row:","tempera")."</b> ";
	$items = array ("1", "2");
	echo "<select id='tempera_frontpostsperrow' name='tempera_settings[tempera_frontpostsperrow]'>";
	foreach($items as $item) {
		echo "<option value='$item'";
		selected($temperas['tempera_frontpostsperrow'],$item);
		echo ">$item</option>";
	}
	echo "</select></div>"; ?>
	<div class='slmini'><b><?php _e("More Posts text:","tempera");?></b>
		<input id='tempera_frontmoreposts' name='tempera_settings[tempera_frontmoreposts]' size='30' type='text' value='<?php echo esc_attr( $temperas['tempera_frontmoreposts'] ) ?>' />
		<small><?php _e("The label of the 'More Posts' button at the bottom of the Presentation Page.","tempera") ?></small></div>
    </div>
	<?php

} // cryout_setting_frontpage_fn()

//CHECKBOX - Name: tempera_settings[frontslider]
function cryout_setting_frontslider_fn() {
	global $temperas;

	echo "<div class='slmini'><b>".__("Slider Dimensions:","tempera")."</b> ";
	echo "<input id='tempera_fpsliderwidth' name='tempera_settings[tempera_fpsliderwidth]' size='4' type='text' value='".esc_attr( $temperas['tempera_fpsliderwidth'] )."' /> px (".__("width","tempera").") <strong>X</strong> ";
	echo "<input id='tempera_fpsliderheight' name='tempera_settings[tempera_fpsliderheight]' size='4' type='text' value='".esc_attr( $temperas['tempera_fpsliderheight'] )."' /> px (".__("height","tempera").")";
	echo "<small>".__("The dimensions of your slider. Make sure your images are of the same size.","tempera")."</small></div>";

	echo "<div class='slmini'><b>".__("Animation:","tempera")."</b> ";
	$items = array ("random" , "fold", "fade", "slideInRight", "slideInLeft", "sliceDown", "sliceDownLeft", "sliceUp", "sliceUpLeft", "sliceUpDown" , "sliceUpDownLeft", "boxRandom", "boxRain", "boxRainReverse", "boxRainGrow" , "boxRainGrowReverse");
	$itemsare = array( __("Random","tempera"), __("Fold","tempera"), __("Fade","tempera"), __("SlideInRight","tempera"), __("SlideInLeft","tempera"), __("SliceDown","tempera"), __("SliceDownLeft","tempera"), __("SliceUp","tempera"), __("SliceUpLeft","tempera"), __("SliceUpDown","tempera"), __("SliceUpDownLeft","tempera"), __("BoxRandom","tempera"), __("BoxRain","tempera"), __("BoxRainReverse","tempera"), __("BoxRainGrow","tempera"), __("BoxRainGrowReverse","tempera"));
	echo "<select id='tempera_fpslideranim' name='tempera_settings[tempera_fpslideranim]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_fpslideranim'],$item);
		echo ">$itemsare[$id]</option>";
	}

	echo "</select>";
	echo "<small>".__("The transition effect of your slides.","tempera")."</small></div>";

	echo "<div class='slmini'><b>".__("Animation Time:","tempera")."</b> ";
	echo "<input id='tempera_fpslidertime' name='tempera_settings[tempera_fpslidertime]' size='4' type='text' value='".esc_attr( $temperas['tempera_fpslidertime'] )."' /> ".__("milliseconds","tempera");
	echo "<small>".__("The time in which the transition animation will take place.","tempera")."</small></div>";

	echo "<div class='slmini'><b>".__("Pause Time:","tempera")."</b> ";
	echo "<input id='tempera_fpsliderpause' name='tempera_settings[tempera_fpsliderpause]' size='4' type='text' value='".esc_attr( $temperas['tempera_fpsliderpause'] )."' /> ".__("milliseconds","tempera");
	echo "<small>".__("The time in which a slide will be still and visible.","tempera")."</small></div>";

	echo "<div class='slmini'><b>".__("Slider navigation:","tempera")."</b> ";
	$items = array ("Numbers" , "Bullets" ,"None");
	$itemsare = array( __("Numbers","tempera"), __("Bullets","tempera"), __("None","tempera"));
	echo "<select id='tempera_fpslidernav' name='tempera_settings[tempera_fpslidernav]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_fpslidernav'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<small>".__("Your slider navigation type. Shown under the slider.","tempera")."</small></div>";

	echo "<div class='slmini'><b>".__("Slider arrows:","tempera")."</b> ";
	$items = array ("Always Visible" , "Visible on Hover" ,"Hidden");
	$itemsare = array( __("Always Visible","tempera"), __("Visible on Hover","tempera"), __("Hidden","tempera"));
	echo "<select id='tempera_fpsliderarrows' name='tempera_settings[tempera_fpsliderarrows]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_fpsliderarrows'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<small>".__("The Left and Right arrows on your slider","tempera")."</small></div>";

	echo "<div class='slmini'><b>".__("Slider Border Width:","tempera")."</b> ";
	echo "<input id='tempera_fpslider_bordersize' name='tempera_settings[tempera_fpslider_bordersize]' size='4' type='text' value='".esc_attr( $temperas['tempera_fpslider_bordersize'] )."' /> ".__("px","tempera");
	echo "<small>".__("The slider's border width. You can also edit its color from the Color Settings. Use a border width when your slider is smaller than the total site width.","tempera")."</small></div>";

	echo "<div class='slmini'><b>".__("Slider Top Margin:","tempera")."</b> ";
	echo "<input id='tempera_fpslider_topmargin' name='tempera_settings[tempera_fpslider_topmargin]' size='4' type='text' value='".esc_attr( $temperas['tempera_fpslider_topmargin'] )."' /> ".__("px","tempera");
	echo "<small>".__("Add a top margin for the slider. By default this margin is 0 and you will want to increase this value when the width of the slider is smaller than the total width of the site.","tempera")."</small></div>";

?>

<?php /*
// reserved for future use
<script type="text/javascript">
var $categoryName;

jQuery(document).ready(function(){
	jQuery('#categ-dropdown').change(function(){
			$categoryName=this.options[this.selectedIndex].value.replace(/\/category\/archives\//i,"");
			doAjaxRequest();
	});

});
function doAjaxRequest(){
// here is where the request will happen
	jQuery.ajax({
          url: ajaxurl,
          data:{
               'action':'do_ajax',
               'fn':'get_latest_posts',
               'count':10,
				'categName':$categoryName
               },
          dataType: 'JSON',
          success:function(data){
		 jQuery('#post-dropdown').html(data);


                             },
          error: function(errorThrown){
               alert('error');
               console.log(errorThrown);
          }

     });

}
</script>
<!--
<select name="categ-dropdown" id="categ-dropdown" multiple='multiple' >
 <option value=""><?php echo esc_attr(__('Select Category','tempera')); ?></option>
 <?php
  $categories=  get_categories();
  foreach ($categories as $category) {
  	$option = '<option value="/category/archives/'.$category->category_nicename.'">';
	$option .= $category->cat_name;
	$option .= ' ('.$category->category_count.')';
	$option .= '</option>';
	echo $option;
  }
 ?>
</select>
<select name="post-dropdown" id="post-dropdown">
</select>
--> */ ?>

<?php
} // cryout_setting_frontslider_fn()

//CHECKBOX - Name: tempera_settings[frontslider2]
function cryout_setting_frontslider2_fn() {
	global $temperas;

     $items = array("Slider Shortcode", "Custom Slides", "Latest Posts", "Random Posts", "Sticky Posts", "Latest Posts from Category" , "Random Posts from Category", "Specific Posts","Disabled");
	$itemsare = array( __("Slider Shortcode","tempera"), __("Custom Slides","tempera"), __("Latest Posts","tempera"), __("Random Posts","tempera"),__("Sticky Posts","tempera"), __("Latest Posts from Category","tempera"), __("Random Posts from Category","tempera"), __("Specific Posts","tempera"), __("Disabled","tempera"));
	echo __("<strong>Slides content:</strong>","tempera");
	echo "<select id='tempera_slideType' name='tempera_settings[tempera_slideType]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_slideType'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Only the slides with a defined image will become active and visible in the live slider.<br>When using slides from posts, make sure the selected posts have featured images.<br>Read the FAQs for more info.","tempera")."</small></div>";
     ?>

     <div class="underSelector">
    
		 <div id="sliderShortcode" class="slideDivs"> 
			    <span><?php _e('Enter the desired slider plugin shortcode below:','tempera'); ?> </span> 
					<input id='tempera_slideShortcode' name='tempera_settings[tempera_slideShortcode]' size='44' type='text' value='<?php echo esc_attr( $temperas['tempera_slideShortcode'] ) ?>' /> 
		  </div> 
          <div id="sliderLatestPosts" class="slideDivs">
               <span><?php _e('Latest posts will be loaded into the slider.','tempera'); ?> </span>
          </div>

          <div id="sliderRandomPosts" class="slideDivs">
               <span><?php _e('Random posts will be loaded into the slider.','tempera'); ?> </span>
          </div>

          <div id="sliderLatestCateg" class="slideDivs">
               <span><?php _e('Latest posts from the category you choose will be loaded in the slider.','tempera'); ?> </span>
          </div>

          <div id="sliderRandomCateg" class="slideDivs">
               <span><?php _e('Random posts from the category you choose will be loaded into the slider.','tempera'); ?> </span>
          </div>

          <div id="sliderStickyPosts" class="slideDivs">
               <span><?php _e('Only sticky posts will be loaded into the slider.','tempera'); ?> </span>
          </div>

          <div id="sliderSpecificPosts" class="slideDivs">
               <span><?php _e('List the post IDs you want to display (separated by a comma): ','tempera'); ?> </span>
               <input id='tempera_slideSpecific' name='tempera_settings[tempera_slideSpecific]' size='44' type='text' value='<?php echo esc_attr( $temperas['tempera_slideSpecific'] ) ?>' />
          </div>

          <div id="slider-category">
               <span><?php _e('<br> Choose the category: ','tempera'); ?> </span>
               <select id="tempera_slideCateg" name='tempera_settings[tempera_slideCateg]'>
               <option value=""><?php echo esc_attr(__('Select Category','tempera')); ?></option>
               <?php echo $temperas["tempera_slideCateg"];
               $categories = get_categories();
               foreach ($categories as $category) {
                 	$option = '<option value="'.$category->category_nicename.'" ';
               	$option .= selected($temperas["tempera_slideCateg"], $category->category_nicename, false).' >';
               	$option .= $category->cat_name;
               	$option .= ' ('.$category->category_count.')';
               	$option .= '</option>';
               	echo $option;
               } ?>
               </select>
          </div>

          <span id="slider-post-number"><?php _e('Number of posts to show:','tempera'); ?>
               <input id='tempera_slideNumber' name='tempera_settings[tempera_slideNumber]' size='3' type='text' value='<?php echo esc_attr( $temperas['tempera_slideNumber'] ) ?>' />
          </span>

          <div id="sliderCustomSlides" class="slideDivs">
		       <span><?php _e('Custom slides are limited to a maximum of 5.','tempera'); ?> </span> 
          <?php
          for ($i=1;$i<=5;$i++):
          // let's generate the slides
          ?>
               <div class="slidebox">
               <h4 class="slidetitle" ><?php _e("Slide","tempera");?> <?php echo $i; ?></h4>
               <div class="slidercontent">
                    <h5><?php _e("Image","tempera");?></h5>
                    <input type="text" value="<?php echo esc_url($temperas['tempera_sliderimg'.$i]); ?>" name="tempera_settings[tempera_sliderimg<?php echo $i; ?>]"
                         id="tempera_sliderimg<?php echo $i; ?>" class="slideimages" />
                    <span class="description"><a href="#" class="upload_image_button button"><?php _e( 'Select / Upload Image', 'tempera' );?></a> </span>
                    <h5> <?php _e("Title","tempera");?> </h5>
                    <input id='tempera_slidertitle<?php echo $i; ?>' name='tempera_settings[tempera_slidertitle<?php echo $i; ?>]' size='50' type='text'
                         value='<?php echo esc_attr( $temperas['tempera_slidertitle'.$i] ) ?>' />
                    <h5> <?php _e("Text","tempera");?> </h5>
                    <textarea id='tempera_slidertext<?php echo $i; ?>' name='tempera_settings[tempera_slidertext<?php echo $i; ?>]' rows='3' cols='50'
                         type='textarea'><?php echo esc_attr($temperas['tempera_slidertext'.$i]) ?></textarea>
                    <h5> <?php _e("Link","tempera");?> </h5>
                    <input id='tempera_sliderlink<?php echo $i; ?>' name='tempera_settings[tempera_sliderlink<?php echo $i; ?>]' size='50' type='text'
                         value='<?php echo esc_url( $temperas['tempera_sliderlink'.$i] ) ?>' />
               </div>
               </div>

          <?php endfor; ?>
          </div> <!-- customSlides -->
     </div>
<?php
} // cryout_setting_frontslider2_fn()

//CHECKBOX - Name: tempera_settings[frontcolumns]
function cryout_setting_frontcolumns_fn() {
	global $temperas;

	echo '<div class="slmini">';
	$items = array("Widget Columns", "Latest Posts", "Random Posts", "Sticky Posts", "Latest Posts from Category" , "Random Posts from Category", "Specific Posts", "Disabled");
	$itemsare = array( __("Widget Columns","tempera"), __("Latest Posts","tempera"), __("Random Posts","tempera"),__("Sticky Posts","tempera"), __("Latest Posts from Category","tempera"), __("Random Posts from Category","tempera"), __("Specific Posts","tempera"), __("Disabled","tempera"));
	echo __("<strong>Columns content:</strong>","tempera");
	echo "<select id='tempera_columnType' name='tempera_settings[tempera_columnType]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_columnType'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Only the columns with a defined image will become active and visible on the presentation page.<br>When using columns from posts, make sure the selected posts have featured images.<br>Read the FAQs for more info.","tempera")."</small></div>";
     ?>

     <div class="underSelector">
          <div id="columnLatestPosts" class="columnDivs">
               <span><?php _e('Latest posts will be loaded into the columns.','tempera'); ?> </span>
          </div>

          <div id="columnRandomPosts" class="columnDivs">
               <span><?php _e('Random posts will be loaded into the columns.','tempera'); ?> </span>
          </div>

          <div id="columnLatestCateg" class="columnDivs">
               <span><?php _e('Latest posts from the category you choose will be loaded in the columns.','tempera'); ?> </span>
          </div>

          <div id="columnRandomCateg" class="columnDivs">
               <span><?php _e('Random posts from the category you choose will be loaded into the columns.','tempera'); ?> </span>
          </div>

          <div id="columnStickyPosts" class="columnDivs">
               <span><?php _e('Only sticky posts will be loaded into the columns.','tempera'); ?> </span>
          </div>

          <div id="columnSpecificPosts" class="columnDivs">
               <span><?php _e('List the post IDs you want to display (separated by a comma): ','tempera'); ?> </span>
               <input id='tempera_columnSpecific' name='tempera_settings[tempera_columnSpecific]' size='44' type='text' value='<?php echo esc_attr( $temperas['tempera_columnSpecific'] ) ?>' />
          </div>

		  <div id="columnWidgets" class="columnDivs">
			  <span><?php _e('Load your custom Widgets as columns. Go to <a>Appearance >> Widgets</a> and create your custom columns using the Columns widget. You can use as many as you want.','tempera'); ?> </span>
          </div>
		  <script>jQuery('#columnWidgets span a').attr('href','<?php echo esc_url(get_admin_url());?>widgets.php');</script>

          <div id="column-category">
               <span><?php _e('<br> Choose the category: ','tempera'); ?> </span>
               <select id="tempera_columnCateg" name='tempera_settings[tempera_columnCateg]'>
               <option value=""><?php echo esc_attr(__('Select Category','tempera')); ?></option>
               <?php echo $temperas["tempera_columnCateg"];
               $categories = get_categories();
               foreach ($categories as $category) {
                 	$option = '<option value="'.$category->category_nicename.'" ';
               	$option .= selected($temperas["tempera_columnCateg"], $category->category_nicename, false).' >';
               	$option .= $category->cat_name;
               	$option .= ' ('.$category->category_count.')';
               	$option .= '</option>';
               	echo $option;
               } ?>
               </select>
          </div>

          <span id="column-post-number"><?php _e('Number of posts to show:','tempera'); ?>
               <input id='tempera_columnNumber' name='tempera_settings[tempera_columnNumber]' size='3' type='text' value='<?php echo esc_attr( $temperas['tempera_columnNumber'] ) ?>' />
          </span>

     </div>
</div>

<?php
	echo "<div class='slmini'><b>".__("Column Display:","tempera")."</b> ";
	$items = array ("0" , "1", "2");
	$itemsare = array( __("Animated","tempera"), __("Static on Image","tempera"), __("Static under Image","tempera"));
	echo "<select id='tempera_coldisplay' name='tempera_settings[tempera_coldisplay]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_coldisplay'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<small>".__("How to display your Presentation Page Columns.", "tempera")."</small></div>";

	echo "<div class='slmini'><b>".__("Columns per row:","tempera")."</b> ";
	$items = array ("1", "2" , "3" , "4");
	echo "<select id='tempera_nrcolumns' name='tempera_settings[tempera_nrcolumns]'>";
	foreach($items as $item) {
		echo "<option value='$item'";
		selected($temperas['tempera_nrcolumns'],$item);
		echo ">$item</option>";
	}
	echo "</select></div>";

	echo "<div class='slmini'><b>".__("Image Size:","tempera")."</b> ";
	echo __("Height: ","tempera")."<input id='tempera_colimageheight' name='tempera_settings[tempera_colimageheight]' size='4' type='text' value='".esc_attr( $temperas['tempera_colimageheight'] )."' /> px &nbsp;&nbsp;";
	echo __("Width: ","tempera")."<span id='tempera_colimagewidth_show'></span> px"."<input id='tempera_colimagewidth' type='hidden' name='tempera_settings[tempera_colimagewidth]' value='".esc_attr( $temperas['tempera_colimagewidth'] )."' />";
	echo "<small>".__("The sizes for your column images. The width is dependent on total site width and not configurable.","tempera")."</small></div>";
     ?>
     <div class='slmini'><b><?php _e("Read more text:","tempera");?></b>
     <input id='tempera_columnreadmore' name='tempera_settings[tempera_columnreadmore]' size='30' type='text' value='<?php echo esc_attr( $temperas['tempera_columnreadmore'] ) ?>' />
     <?php
	echo "<small>".__("The linked text that appears at the bottom of each column. Leave empty to hide the link.","tempera")."</small></div>";

} // cryout_setting_frontcolumns_fn()


//CHECKBOX - Name: tempera_settings[fronttext]
function cryout_setting_fronttext_fn() {
	global $temperas;

     echo "<div class='slidebox'><h4 class='slidetitle'> ".__("Extra Text","tempera")." </h4><div class='slidercontent'>";

     echo "<div style='width:100%;'><span>" . __("Text for the Presentation Page","tempera") . "</span><small>" .
          __("More text for the Presentation Page. The top title is just below the slider, the second title is below the columns. A text area supporting HTML tags and shortcodes below each title<br>
		  It's all optional so leave any input field empty to not display it.", "tempera") . "</small></div>";

	echo "<h5>".__("Top Title","tempera")."</h5><br>";
     echo "<input id='tempera_fronttext1' name='tempera_settings[tempera_fronttext1]' size='50' type='text' value='".esc_attr( $temperas['tempera_fronttext1'] )."' />";
	   echo "<h5>".__("Top Text","tempera")."</h5> ";
	echo "<textarea id='tempera_fronttext3' name='tempera_settings[tempera_fronttext3]' rows='3' cols='50' type='textarea' >".esc_attr($temperas['tempera_fronttext3'])." </textarea>";

     echo "<h5>".__("Second Title","tempera")."</h5> ";
	echo "<input id='tempera_fronttext2' name='tempera_settings[tempera_fronttext2]' size='50' type='text' value='".esc_attr( $temperas['tempera_fronttext2'] )."' />";
     echo "<h5>".__("Second text","tempera")." </h5> ";
	echo "<textarea id='tempera_fronttext4' name='tempera_settings[tempera_fronttext4]' rows='3' cols='50' type='textarea' >".esc_attr($temperas['tempera_fronttext4'])." </textarea></div></div>";

     echo "<div class='slidebox'><h4 class='slidetitle'>".__("Hide areas","tempera")." </h4><div  class='slidercontent'>";

     echo "<div style='width:100%;'>".__("Choose the areas to hide on the first page.","tempera")."</div>";

		$items = array( "FrontHeader", "FrontMenu", "FrontWidget" , "FrontFooter","FrontBack");

		$checkedClass0 = ($temperas['tempera_fronthideheader']=='1') ? ' checkedClass0' : '';
		$checkedClass1 = ($temperas['tempera_fronthidemenu']=='1') ? ' checkedClass1' : '';
		$checkedClass2 = ($temperas['tempera_fronthidewidget']=='1') ? ' checkedClass2' : '';
		$checkedClass3 = ($temperas['tempera_fronthidefooter']=='1') ? ' checkedClass3' : '';
		$checkedClass4 = ($temperas['tempera_fronthideback']=='1') ? ' checkedClass4' : '';

	echo " <label id='$items[0]' for='$items[0]$items[0]' class='hideareas $checkedClass0'><input "; checked($temperas['tempera_fronthideheader'],'1');
	echo " value='1' id='$items[0]$items[0]'  name='tempera_settings[tempera_fronthideheader]' type='checkbox' /> ".__("Hide the header area (logo/title and/or image/empty area).","tempera")." </label>";

	echo " <label id='$items[1]' for='$items[1]$items[1]' class='hideareas $checkedClass1'><input "; checked($temperas['tempera_fronthidemenu'],'1');
	echo " value='1' id='$items[1]$items[1]'  name='tempera_settings[tempera_fronthidemenu]' type='checkbox' /> ".__("Hide the main menu and the top menu.","tempera")." </label>";

	echo " <label id='$items[2]' for='$items[2]$items[2]' class='hideareas $checkedClass2'><input "; checked($temperas['tempera_fronthidewidget'],'1');
	echo " value='1' id='$items[2]$items[2]'  name='tempera_settings[tempera_fronthidewidget]' type='checkbox' /> ".__("Hide the footer widgets. ","tempera")." </label>";

	echo " <label id='$items[3]' for='$items[3]$items[3]' class='hideareas $checkedClass3'><input "; checked($temperas['tempera_fronthidefooter'],'1');
	echo " value='1' id='$items[3]$items[3]'  name='tempera_settings[tempera_fronthidefooter]' type='checkbox' /> ".__("Hide the footer (copyright area).","tempera")." </label>";

     echo "</div></div>";
}


////////////////////////////////
//// TEXT SETTINGS /////////////
////////////////////////////////

//SELECT - Name: tempera_settings[fontfamily]
function  cryout_setting_fontfamily_fn() {
	global $temperas;
	global $tempera_fonts;
	$sizes = array ("10px", "11px", "12px", "13px" , "14px" , "15px" , "16px", "17px", "18px", "19px", "20px");
	cryout_proto_font(
		$tempera_fonts,
		$sizes,
		$temperas['tempera_fontsize'],
		$temperas['tempera_fontfamily'],
		$temperas['tempera_googlefont'],
		'tempera_fontsize',
		'tempera_fontfamily',
		'tempera_googlefont'
	);
	echo "<div><small>".__("Select the general font family and size or insert the Google Font name you'll use in your blog. This will affect all text except the one controlled by the options below. ","tempera")."</small></div><br>";
}

//SELECT - Name: tempera_settings[fonttitle]
function  cryout_setting_fonttitle_fn() {
	global $temperas;
	global $tempera_fonts;
	$sizes = array ( "14px" , "16px" , "18px" , "20px", "22px" , "24px" , "26px" , "28px" , "30px" , "32px" , "34px" , "36px", "38px" , "40px");
	cryout_proto_font(
		$tempera_fonts,
		$sizes,
		$temperas['tempera_headfontsize'],
		$temperas['tempera_fonttitle'],
		$temperas['tempera_googlefonttitle'],
		'tempera_headfontsize',
		'tempera_fonttitle',
		'tempera_googlefonttitle',
		__('General Font','tempera')
	);
	echo "<div><small>".__("Select the font family and size or insert the Google Font name you want for your titles. It will affect post titles and page titles. Leave 'General Font' and the general font values you selected will be used.","tempera")."</small></div><br>";
}

//SELECT - Name: tempera_settings[fontside]
function  cryout_setting_fontside_fn() {
	global $temperas;
	global $tempera_fonts;
	for ($i=14;$i<31;$i+=2): $sizes[] = "${i}px"; endfor;
	cryout_proto_font(
		$tempera_fonts,
		$sizes,
		$temperas['tempera_sidefontsize'],
		$temperas['tempera_fontside'],
		$temperas['tempera_googlefontside'],
		'tempera_sidefontsize',
		'tempera_fontside',
		'tempera_googlefontside',
		__('General Font','tempera')
	);
	echo "<div><small>".__("Select the font family and size or insert the Google Font name you want your widget titles to have. Leave 'General Font' and the general font values you selected will be used.","tempera")."</small></div><br>";
}

function  cryout_setting_sitetitlefont_fn() {
	global $temperas;
	global $tempera_fonts;
	for ($i=30;$i<51;$i+=2): $sizes[] = "${i}px"; endfor;
	cryout_proto_font(
		$tempera_fonts,
		$sizes,
		$temperas['tempera_sitetitlesize'],
		$temperas['tempera_sitetitlefont'],
		$temperas['tempera_sitetitlegooglefont'],
		'tempera_sitetitlesize',
		'tempera_sitetitlefont',
		'tempera_sitetitlegooglefont',
		__('General Font','tempera')
	);
	echo "<div><small>".__("Select the font family and size or insert the Google Font name you want your site title and tagline to use. Leave 'General Font' and the general font values you selected will be used.","tempera")."</small></div><br>";
}

function  cryout_setting_menufont_fn() {
	global $temperas;
	global $tempera_fonts;
	$sizes = array ( "8px" , "9px" , "10px" , "11px", "12px" , "13px" , "14px" , "15px" , "16px" , "17px" , "18px", "19px", "20px");
	cryout_proto_font(
		$tempera_fonts,
		$sizes,
		$temperas['tempera_menufontsize'],
		$temperas['tempera_menufont'],
		$temperas['tempera_menugooglefont'],
		'tempera_menufontsize',
		'tempera_menufont',
		'tempera_menugooglefont',
		__('General Font','tempera')
	);
	echo "<div><small>".__("Select the font family and size or insert the Google Font name you want your main menu to use. Leave 'General Font' and the general font values you selected will be used.","tempera")."</small></div><br>";
}


//SELECT - Name: tempera_settings[fontsubheader]
function  cryout_setting_fontheadings_fn() {
	global $temperas;
	global $tempera_fonts;
	//$sizes = array ( "0.8em", "0.9em","1em","1.1em","1.2em","1.3em","1.4em","1.5em","1.6em","1.7em","1.8em","1.9em","2em");
	$sizes = array("60%","70%","80%","90%","100%","110%","120%","130%","140%","150%");
	cryout_proto_font(
		$tempera_fonts,
		$sizes,
		$temperas['tempera_headingsfontsize'],
		$temperas['tempera_headingsfont'],
		$temperas['tempera_headingsgooglefont'],
		'tempera_headingsfontsize',
		'tempera_headingsfont',
		'tempera_headingsgooglefont',
		__('General Font','tempera')
	);
	echo "<div><small>".__("Select the font family and size or insert the Google Font name you want your headings to have (h1 - h6 tags will be affected). Leave 'General Font' and the general font values you selected will be used.","tempera")."</small></div><br>";
}

//SELECT - Name: tempera_settings[textalign]
function  cryout_setting_textalign_fn() {
	global $temperas;
	cryout_proto_field( $temperas, "select", "tempera_textalign",
		array("Default" , "Left" , "Right" , "Justify" , "Center"),
		array( __("Default","tempera"), __("Left","tempera"), __("Right","tempera"), __("Justify","tempera"), __("Center","tempera"))
	);
	echo "<div><small>".__("This overwrites the text alignment in posts and pages. Leave 'Default' for normal settings (alignment will remain as declared in posts, comments etc.).","tempera")."</small></div>";
}

//SELECT - Name: tempera_settings[parindent]
function  cryout_setting_parindent_fn() {
	global $temperas;
	cryout_proto_field( $temperas, "select", "tempera_parindent",
		array("0px" , "5px" , "10px" , "15px" , "20px"),
		array("0px" , "5px" , "10px" , "15px" , "20px")
	);
	echo "<div><small>".__("Choose the indent for your paragraphs.","tempera")."</small></div>";
}


//CHECKBOX - Name: tempera_settings[headerindent]
function cryout_setting_headingsindent_fn() {
	global $temperas;
	cryout_proto_field( $temperas, "select", "tempera_headingsindent",
		array("Enable" , "Disable"),
		array( __("Enable","tempera"), __("Disable","tempera"))
	);
	echo "<div><small>".__("Disable the default headings indent (left margin).","tempera")."</small></div>";
}

//SELECT - Name: tempera_settings[lineheight]
function  cryout_setting_lineheight_fn() {
	global $temperas;
	$temperas['tempera_lineheight'] = str_replace( 'em', '', $temperas['tempera_lineheight'] ); // backwards compatibility for <1.5
	cryout_proto_field( $temperas, "select", "tempera_lineheight",
		array("0.8", "0.9", "1.0", "1.1", "1.2", "1.3", "1.4", "1.5", "1.6", "1.7", "1.8" , "1.9", "2.0", "2.1", "2.2", "2.3", "2.4", "2.5", "2.6", "2.7", "2.8"),
		array( "0.8em", "0.9em", "1.0em", "1.1em", "1.2em", "1.3em", "1.4em", "1.5em", "1.6em", "1.7em", "1.8em", "1.9em", "2.0em", "2.1em", "2.2em", "2.3em", "2.4em", "2.5em", "2.6em", "2.7em", "2.8em")
	);
	echo "<div><small>".__("Text line height. The height between 2 rows of text.","tempera")."</small></div>";
}

//SELECT - Name: tempera_settings[wordspace]
function  cryout_setting_wordspace_fn() {
	global $temperas;
	cryout_proto_field( $temperas, "select", "tempera_wordspace",
		array("Default" ,"-3px" , "-2px", "-1px" , "0px" , "1px" , "2px", "3px" , "4px" , "5px" , "10px"),
		array( __("Default","tempera"),"-3px" , "-2px", "-1px" , "0px" , "1px" , "2px", "3px" , "4px" , "5px" , "10px")
	);
	echo "<div><small>".__("The space between <i>words</i>. Leave 'Default' for normal settings (size value will be as set in the CSS).","tempera")."</small></div>";
}

//SELECT - Name: tempera_settings[letterspace]
function  cryout_setting_letterspace_fn() {
	global $temperas;
	cryout_proto_field( $temperas, "select", "tempera_letterspace",
		array("Default" ,"-0.05em" , "-0.04em", "-0.03em" , "-0.02em" , "-0.01em" , "0.01em", "0.02em" , "0.03em" , "0.04em" , "0.05em"),
		array( __("Default","tempera"),"-0.05em" , "-0.04em", "-0.03em" , "-0.02em" , "-0.01em" , "0.01em", "0.02em" , "0.03em" , "0.04em" , "0.05em")
	);
	echo "<div><small>".__("The space between <i>letters</i>. Leave 'Default' for normal settings (size value will be as set in the CSS).","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[textshadow]
function cryout_setting_paragraphspace_fn() {
	global $temperas;
	$items[]="0.0em"; for ($i=0.5;$i<=1.5;$i+=0.1) {  $items[] = number_format($i,1)."em";  }
	cryout_proto_field( $temperas, "select", "tempera_paragraphspace", $items, $items );
	echo "<div><small>".__("Select the spacing between the paragraphs.","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[headerindent]
function cryout_setting_uppercasetext_fn() {
	global $temperas;
	cryout_proto_field( $temperas, "select", "tempera_uppercasetext", array(0, 1),
		array( __("Default (disabled)","tempera"), __("Enable","tempera"))
	);
	echo "<div><small>".__("Enable uppercase text styling. All text in the site will be uppercase.","tempera")."</small></div>";
}

////////////////////////////////
//// APPEREANCE SETTINGS ///////
////////////////////////////////

function cryout_setting_sitebackground_fn() {
     echo "<a href=\"?page=custom-background\" class=\"button\" target=\"_blank\">".__('Define background image','tempera')."</a>";
} // cryout_setting_sitebackground_fn()

function  cryout_setting_generalcolors_fn() {
	global $temperas;
	echo '<h4>'.__('Background:','tempera').'</h4>';
	cryout_color_field('tempera_backcolorheader',__('Header Background','tempera'),$temperas['tempera_backcolorheader']);
	cryout_color_field('tempera_backcolormain',__('Main Site Background','tempera'),$temperas['tempera_backcolormain']);
	cryout_color_field('tempera_backcolorfooterw',__('Footer Widgets Area Background','tempera'),$temperas['tempera_backcolorfooterw']);
	cryout_color_field('tempera_backcolorfooter',__('Footer Background','tempera'),$temperas['tempera_backcolorfooter']);
	echo '<br class="colors-br" /><h4>'.__('Text:','tempera').'</h4>';
	cryout_color_field('tempera_contentcolortxt',__('General Text','tempera'),$temperas['tempera_contentcolortxt']);
	cryout_color_field('tempera_contentcolortxtlight',__('General Lighter Text','tempera'),$temperas['tempera_contentcolortxtlight']);
	cryout_color_field('tempera_footercolortxt',__('Footer Text','tempera'),$temperas['tempera_footercolortxt']);
	echo "<div><small>".__("The site background features 4 separately coloured areas.<br />The general text colour applies to all text on the website that is not controlled by any other option.","tempera")."</small></div>";
}

function  cryout_setting_accentcolors_fn() {
	global $temperas;
	cryout_color_field('tempera_accentcolora',__('Accent Color #1','tempera'),$temperas['tempera_accentcolora']);
	cryout_color_field('tempera_accentcolorb',__('Accent Color #2','tempera'),$temperas['tempera_accentcolorb']);
	cryout_color_field('tempera_accentcolorc',__('Accent Color #3','tempera'),$temperas['tempera_accentcolorc']);
	cryout_color_field('tempera_accentcolord',__('Accent Color #4','tempera'),$temperas['tempera_accentcolord']);
	cryout_color_field('tempera_accentcolore',__('Accent Color #5','tempera'),$temperas['tempera_accentcolore']);
	echo "<div><small>".__("Accents #1 and #2 should either be the same as the link colours or be separate from all other colours on the site.<br />
     Accent #5 is used for input fields and buttons backgrounds, borders and lines.<br />
     Accents #3 and #4 should be the lighter/darker than the content background colour, being used as borders/shades on elements where accent #5 is background colour.","tempera")."</small></div>";
}

function  cryout_setting_titlecolors_fn() {
	global $temperas;
	echo '<h4>'.__('Background:','tempera').'</h4>';
	cryout_color_field('tempera_descriptionbg',__('Site Description Background','tempera'),$temperas['tempera_descriptionbg']);
	echo '<br class="colors-br" /><h4>'.__('Text:','tempera').'</h4>';
	cryout_color_field('tempera_titlecolor',__('Site Title','tempera'),$temperas['tempera_titlecolor']);
	cryout_color_field('tempera_descriptioncolor',__('Site Description','tempera'),$temperas['tempera_descriptioncolor']);
//	echo "<div><small>".."</small></div>";
}

function  cryout_setting_menucolors_fn() {
	global $temperas;
	echo '<h4>'.__('Menu:','tempera').'</h4>';
	cryout_color_field('tempera_menucolorbgdefault',__('Menu Background','tempera'),$temperas['tempera_menucolorbgdefault']);
	cryout_color_field('tempera_menucolortxtdefault',__('Menu Text','tempera'),$temperas['tempera_menucolortxtdefault']);
	//cryout_color_field('tempera_menucolorbghover',__('Menu Item Background on Hover','tempera'),$temperas['tempera_menucolorbghover']);
	//cryout_color_field('tempera_menucolorbgactive',__('Active Menu Item Background','tempera'),$temperas['tempera_menucolorbgactive']);
	echo '<br class="colors-br" /><h4>'.__('Submenu:','tempera').'</h4>';
	cryout_color_field('tempera_submenucolorbgdefault',__('Submenu Background','tempera'),$temperas['tempera_submenucolorbgdefault']);
	cryout_color_field('tempera_submenucolortxtdefault',__('Submenu Text','tempera'),$temperas['tempera_submenucolortxtdefault']);
	cryout_color_field('tempera_submenucolorshadow',__('Submenu Shadow','tempera'),$temperas['tempera_submenucolorshadow']);
	//cryout_color_field('',__('','tempera'),$temperas[''],__("","tempera"));
	echo "<div><small>".__("These colours apply to the main site menu (and dropdown elements).","tempera")."</small></div>";
}

function  cryout_setting_topmenucolors_fn() {
	global $temperas;
	echo '<h4>'.__('Background:','tempera').'</h4>';
	cryout_color_field('tempera_topbarcolorbg',__('Top Bar Background','tempera'),$temperas['tempera_topbarcolorbg']);
	echo '<br class="colors-br" /><h4>'.__('Text:','tempera').'</h4>';
	cryout_color_field('tempera_topmenucolortxt',__('Top Bar Menu Link','tempera'),$temperas['tempera_topmenucolortxt']);
	cryout_color_field('tempera_topmenucolortxthover',__('Top Bar Menu Link Hover','tempera'),$temperas['tempera_topmenucolortxthover']);
	echo "<div><small>".__("These colours apply to the top bar menu.","tempera")."</small></div>";
}

function  cryout_setting_contentcolors_fn() {
	global $temperas;
	cryout_color_field('tempera_contentcolorbg',__('Content Background','tempera'),$temperas['tempera_contentcolorbg']);
	cryout_color_field('tempera_contentcolortxttitle',__('Page/Post Title','tempera'),$temperas['tempera_contentcolortxttitle']);
	cryout_color_field('tempera_contentcolortxttitlehover',__('Page/Post Title Hover','tempera'),$temperas['tempera_contentcolortxttitlehover']);
	cryout_color_field('tempera_contentcolortxtheadings',__('Content Headings','tempera'),$temperas['tempera_contentcolortxtheadings']);
	echo "<div><small>".__("Content colours apply to post and page areas of the site.","tempera")."</small></div>";
}

function  cryout_setting_frontpagecolors_fn(){
	global $temperas;
    cryout_color_field('tempera_fronttitlecolor',__('Titles Color','tempera'),$temperas['tempera_fronttitlecolor']);
	cryout_color_field('tempera_fpsliderbordercolor',__('Slider Border Color','tempera'),$temperas['tempera_fpsliderbordercolor']);
	cryout_color_field('tempera_fpslidercaptioncolor',__('Slider Caption Text Color','tempera'),$temperas['tempera_fpslidercaptioncolor']);
	cryout_color_field('tempera_fpslidercaptionbg',__('Slider Caption Background','tempera'),$temperas['tempera_fpslidercaptionbg']);
    echo "<div><small>".__("These colours apply to specific areas of the presentation page.","tempera")."</small></div>";
}

function  cryout_setting_sidecolors_fn() {
	global $temperas;
	echo '<h4>'.__('Background:','tempera').'</h4>';
	cryout_color_field('tempera_sidebg',__('Sidebars Background','tempera'),$temperas['tempera_sidebg']);
	cryout_color_field('tempera_sidetitlebg',__('Sidebars Widget Title Background','tempera'),$temperas['tempera_sidetitlebg']);
	echo '<br class="colors-br" /><h4>'.__('Text:','tempera').'</h4>';
	cryout_color_field('tempera_sidetxt',__('Sidebars Text','tempera'),$temperas['tempera_sidetxt']);
	cryout_color_field('tempera_sidetitletxt',__('Sidebars Widget Title Text','tempera'),$temperas['tempera_sidetitletxt']);
	echo "<div><small>".__("These colours apply to the widgets placed in either sidebar.","tempera")."</small></div>";
}


function  cryout_setting_widgetcolors_fn() {
	global $temperas;
	echo '<h4>'.__('Background:','tempera').'</h4>';
	cryout_color_field('tempera_widgetbg',__('Footer Widgets Background','tempera'),$temperas['tempera_widgetbg']);
	cryout_color_field('tempera_widgettitlebg',__('Footer Widgets Title Background','tempera'),$temperas['tempera_widgettitlebg']);
	echo '<br class="colors-br" /><h4>'.__('Text:','tempera').'</h4>';
	cryout_color_field('tempera_widgettxt',__('Footer Widget Text','tempera'),$temperas['tempera_widgettxt']);
	cryout_color_field('tempera_widgettitletxt',__('Footer Widgets Title Text','tempera'),$temperas['tempera_widgettitletxt']);
	echo "<div><small>".__("These colours apply to the widgets in the footer area.","tempera")."</small></div>";
}

function  cryout_setting_linkcolors_fn() {
	global $temperas;
	echo '<h4>'.__('General:','tempera').'</h4>';
	cryout_color_field('tempera_linkcolortext',__('General Links','tempera'),$temperas['tempera_linkcolortext']);
	cryout_color_field('tempera_linkcolorhover',__('General Links Hover','tempera'),$temperas['tempera_linkcolorhover']);
	echo '<br class="colors-br" /><h4>'.__('Sidebar Widgets:','tempera').'</h4>';
	cryout_color_field('tempera_linkcolorside',__('Sidebar Widgets Links','tempera'),$temperas['tempera_linkcolorside']);
	cryout_color_field('tempera_linkcolorsidehover',__('Sidebar Widgets Links Hover','tempera'),$temperas['tempera_linkcolorsidehover']);
	echo '<br class="colors-br" /><h4>'.__('Footer Widgets:','tempera').'</h4>';
	cryout_color_field('tempera_linkcolorwooter',__('Footer Widgets Links','tempera'),$temperas['tempera_linkcolorwooter']);
	cryout_color_field('tempera_linkcolorwooterhover',__('Footer Widgets Links Hover','tempera'),$temperas['tempera_linkcolorwooterhover']);
	echo '<br class="colors-br" /><h4>'.__('Footer:','tempera').'</h4>';
	cryout_color_field('tempera_linkcolorfooter',__('Footer Links','tempera'),$temperas['tempera_linkcolorfooter']);
	cryout_color_field('tempera_linkcolorfooterhover',__('Footer Links Hover','tempera'),$temperas['tempera_linkcolorfooterhover']);
	echo "<div><small>".__("Footer colours include the footer menu colours.","tempera")."</small></div>";
}

function  cryout_setting_metacolors_fn() {
	global $temperas;
	cryout_color_field('tempera_metacoloricons',__('Meta Icons','tempera'),$temperas['tempera_metacoloricons']);
	cryout_color_field('tempera_metacolorlinks',__('Meta Links','tempera'),$temperas['tempera_metacolorlinks']);
	cryout_color_field('tempera_metacolorlinkshover',__('Meta Links Hover','tempera'),$temperas['tempera_metacolorlinkshover']);
	echo "<div><small>".__("Colours for your meta area (post information).","tempera")."</small></div>";
}

function  cryout_setting_socialcolors_fn() {
	global $temperas;
	cryout_color_field('tempera_socialcolorbg',__('Social Icons Background','tempera'),$temperas['tempera_socialcolorbg']);
	cryout_color_field('tempera_socialcolorbghover',__('Social Icons Background Hover','tempera'),$temperas['tempera_socialcolorbghover']);
	echo "<div><small>".__("Background colours for your social icons.","tempera")."</small></div>";
}

function  cryout_setting_caption_fn() {
     global $temperas;
	$items = array ( "caption-light", "caption-dark","caption-simple" ,);
	$itemsare = array( __("Light","tempera"), __("Dark","tempera"),__("Simple","tempera"));
	echo "<select id='tempera_caption' name='tempera_settings[tempera_caption]'>";
     foreach($items as $id=>$item):
     	echo "<option value='$item'";
     	selected($temperas['tempera_caption'],$item);
     	echo ">$itemsare[$id]</option>";
     endforeach;
	echo "</select>";
	echo "<div><small>".__("This setting changes the look of your captions. Images that are not inserted through captions will not be affected.","tempera")."</small></div>";
}



////////////////////////////////
//// GRAPHICS SETTINGS /////////
////////////////////////////////

//CHECKBOX - Name: tempera_settings[breadcrumbs]
function cryout_setting_topbar_fn() {
	global $temperas;
	$items = array ("Normal" , "Fixed", "Hide");
	$itemsare = array( __("Normal","tempera"), __("Fixed","tempera"), __("Hide","tempera"));
	echo "<select id='tempera_topbar' name='tempera_settings[tempera_topbar]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_topbar'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";

	$items2 = array ("Site width" , "Full width");
	$itemsare2 = array( __("Site width","tempera"), __("Full width","tempera"));
	echo " - <select id='tempera_topbarwidth' name='tempera_settings[tempera_topbarwidth]'>";
foreach($items2 as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_topbarwidth'],$item);
	echo ">$itemsare2[$id]</option>";
}
	echo "</select>";

	echo "<div><small>".__("Show the topbar that can include social icons and the top menu.","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[breadcrumbs]
function cryout_setting_breadcrumbs_fn() {
	global $temperas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_breadcrumbs' name='tempera_settings[tempera_breadcrumbs]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_breadcrumbs'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Show breadcrumbs at the top of the content area. Breadcrumbs are a form of navigation that keeps track of your location withtin the site.","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[pagination]
function cryout_setting_pagination_fn() {
	global $temperas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_pagination' name='tempera_settings[tempera_pagination]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_pagination'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Show numbered pagination. Where there is more than one page, instead of the bottom <b>Older Posts</b> and <b>Newer posts</b> links you have a numbered pagination. ","tempera")."</small></div>";
}

function cryout_setting_menualign_fn() {
	global $temperas;
	$items = array ("left" , "center", "right", "rightmulti");
	$itemsare = array( __("Left","tempera"), __("Center","tempera"), __("Right", "tempera"), __("Right (multiline)", "tempera"));
	echo "<select id='tempera_menualign' name='tempera_settings[tempera_menualign]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_menualign'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Sets the desired menu items alignment.","tempera")."</small></div>";
}

function cryout_setting_searchbar_fn() {
	global $temperas;
	$items = array( "top", "main", "footer");
	$itemsare = array( __("Top Menu", "tempera"), __("Main Menu", "tempera"), __("Footer Menu", "tempera"));
	$i=0;
	foreach($items as $item):
		echo " <label id='$item' for='search$item' class='socialsdisplay'><input ";
		 checked($temperas['tempera_searchbar'][$item], 1);
		echo " value='1' id='search$item' name='tempera_settings[tempera_searchbar][$item]' type='checkbox' /> ".$itemsare[$i]."</label>";
		$i++;
	endforeach;

	echo "<div><small>".__("Select the menus where to add a search bar.","tempera")."</small></div>";
}


function  cryout_setting_contentmargins_fn() {
	global $temperas;
	echo __('Margin top: ','tempera');cryout_proto_field( $temperas, "input4str", "tempera_contentmargintop", $temperas['tempera_contentmargintop'], ' px ' );
	echo "<div><small>".__("The margin between the content and the menu. It can be set to 0px if you want the content area and menu to join.","tempera")."</small></div><br><br>";

	echo __('Padding left/right: ','tempera');cryout_proto_field( $temperas, "input4str", "tempera_contentpadding", $temperas['tempera_contentpadding'], ' px' );
	echo "<div><small>".__("The left/right padding around the content. Should be set to 10px or less for designs without a content color.","tempera")."</small></div>";
}

// RADIO-BUTTON - Name: tempera_settings[image]
function cryout_setting_image_fn() {
	global $temperas;
	$items = array("tempera-image-none", "tempera-image-one", "tempera-image-two", "tempera-image-three", "tempera-image-four","tempera-image-five");
	echo "<div>";
	foreach($items as $item) {
		$checkedClass = ($temperas['tempera_image_style']==$item) ? ' checkedClass' : '';
		echo " <label id='$item' for='$item$item' class='images $checkedClass'><input ";
			checked($temperas['tempera_image_style'],$item);
		echo " value='$item' id='$item$item' onClick=\"changeBorder('$item','images');\" name='tempera_settings[tempera_image_style]' type='radio' /><img class='$item'  src='".get_template_directory_uri()."/admin/images/testimg.jpg'/></label>";
	}
	echo "</div>";
	echo "<div><small>".__("The border style for your images. Only images inserted in your posts and pages will be affected. ","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[contentlist]
function cryout_setting_contentlist_fn() {
	global $temperas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","tempera"), __("Hide","tempera"));
	echo "<select id='tempera_contentlist' name='tempera_settings[tempera_contentlist]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_contentlist'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide or show bullets next to lists in your content area (posts, pages etc.).","tempera")."</small></div>";

}


//CHECKBOX - Name: tempera_settings[pagetitle]
function cryout_setting_pagetitle_fn() {
	global $temperas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","tempera"), __("Hide","tempera"));
	echo "<select id='tempera_pagetitle' name='tempera_settings[tempera_pagetitle]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_pagetitle'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide or show titles on pages.","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[categtitle]
function cryout_setting_categtitle_fn() {
	global $temperas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","tempera"), __("Hide","tempera"));
	echo "<select id='tempera_categtitle' name='tempera_settings[tempera_categtitle]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_categtitle'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide or show titles on Categories and Archives.","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[tables]
function cryout_setting_tables_fn() {
	global $temperas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_tables' name='tempera_settings[tempera_tables]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_tables'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide table borders and background color.","tempera")."</small></div>";
}


//CHECKBOX - Name: tempera_settings[backtop]
function cryout_setting_backtop_fn() {
	global $temperas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_backtop' name='tempera_settings[tempera_backtop]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_backtop'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Enable the Back to Top button. The button appears after scrolling the page down.","tempera")."</small></div>";
}


////////////////////////////////
//// POST SETTINGS /////////////
////////////////////////////////

function cryout_setting_metapos_fn() {
	global $temperas;
	$items = array ("Top","Bottom","Hide" );
	$itemsare = array(__("Top","tempera"), __("Bottom","tempera"), __("Hide","tempera"));
	echo "<select id='tempera_metapos' name='tempera_settings[tempera_metapos]'>";
     foreach($items as $id=>$item):
     	echo "<option value='$item'";
     	selected($temperas['tempera_metapos'],$item);
     	echo ">$itemsare[$id]</option>";
     endforeach;
	echo "</select>";
	echo "<div><small>".__("The position of your meta bar (author, date, category, tags and edit button) on category and other posts list sections.","tempera")."</small></div>";
}

// TEXTBOX - Name: tempera_settings[socialsdisplay]
function cryout_setting_metashowblog_fn() {
global $temperas;
$items = array( "author", "date", "time" , "category" ,"tag", "comments");
$itemsare = array( __("Author","tempera"), __("Date","tempera"),__("Time","tempera") , __("Category","tempera") ,__("Tag","tempera"), __("Comments","tempera"));
$i=0;
	foreach($items as $item):
		echo " <label id='$item' for='blog$item' class='socialsdisplay'><input ";
		 checked($temperas['tempera_blog_show'][$item],'1');
		echo " value='1' id='blog$item' name='tempera_settings[tempera_blog_show][$item]' type='checkbox' /> ".$itemsare[$i]."</label>";
	$i++;
	endforeach;

	echo "<div><small>".__("Choose the post metas you want to show on multiple post pages (home, blog, category, archive etc.)","tempera")."</small></div>";
}

// TEXTBOX - Name: tempera_settings[socialsdisplay]
function cryout_setting_metashowsingle_fn() {
global $temperas;
$items = array( "author", "date", "time" , "category" ,"tag", "bookmark");
$itemsare = array( __("Author","tempera"), __("Date","tempera"),__("Time","tempera") , __("Category","tempera") ,__("Tag","tempera"), __("Bookmark","tempera"));
$i=0;
foreach($items as $item):
		echo " <label id='$item' for='single$item' class='socialsdisplay'><input ";
		 checked($temperas['tempera_single_show'][$item],'1');
		echo " value='1' id='single$item' name='tempera_settings[tempera_single_show][$item]' type='checkbox' /> ".$itemsare[$i]."</label>";
	$i++;
	endforeach;

	echo "<div><small>".__("Choose the post metas you want to show on sigle post pages.","tempera")."</small></div>";
}



//CHECKBOX - Name: tempera_settings[comtext]
function cryout_setting_comtext_fn() {
	global $temperas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","tempera"), __("Hide","tempera"));
	echo "<select id='tempera_comtext' name='tempera_settings[tempera_comtext]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_comtext'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide the explanatory text under the comments form (starts with  <i>You may use these HTML tags and attributes:...</i>).","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[comclosed]
function cryout_setting_comclosed_fn() {
	global $temperas;
	$items = array ("Show" , "Hide in posts", "Hide in pages", "Hide everywhere");
	$itemsare = array( __("Show","tempera"), __("Hide in posts","tempera"), __("Hide in pages","tempera"), __("Hide everywhere","tempera"));
	echo "<select id='tempera_comclosed' name='tempera_settings[tempera_comclosed]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_comclosed'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide the <b>Comments are closed</b> text that by default shows up on pages or posts with comments disabled.","tempera")."</small></div>";
}


//CHECKBOX - Name: tempera_settings[comoff]
function cryout_setting_comoff_fn() {
	global $temperas;
	$items = array ("Show" , "Hide");
	$itemsare = array( __("Show","tempera"), __("Hide","tempera"));
	echo "<select id='tempera_comoff' name='tempera_settings[tempera_comoff]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_comoff'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Hide the <b>Comments off</b> text next to posts that have comments disabled.","tempera")."</small></div>";
}

////////////////////////////////
//// EXCERPT SETTINGS /////////////
////////////////////////////////


//CHECKBOX - Name: tempera_settings[excerpthome]
function cryout_setting_excerpthome_fn() {
	global $temperas;
	$items = array ("Excerpt" , "Full Post");
	$itemsare = array( __("Excerpt","tempera"), __("Full Post","tempera"));
	echo "<select id='tempera_excerpthome' name='tempera_settings[tempera_excerpthome]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_excerpthome'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Excerpts on the main page. Only standard posts will be affected. All other post formats (aside, image, chat, quote etc.) have their specific formating.","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[excerptsticky]
function cryout_setting_excerptsticky_fn() {
	global $temperas;
	$items = array ("Excerpt" , "Full Post");
	$itemsare = array( __("Excerpt","tempera"), __("Full Post","tempera"));
	echo "<select id='tempera_excerptsticky' name='tempera_settings[tempera_excerptsticky]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_excerptsticky'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Choose if you want the sticky posts on your home page to be visible in full or just the excerpts. ","tempera")."</small></div>";
}


//CHECKBOX - Name: tempera_settings[excerptarchive]
function cryout_setting_excerptarchive_fn() {
	global $temperas;
	$items = array ("Excerpt" , "Full Post");
	$itemsare = array( __("Excerpt","tempera"), __("Full Post","tempera"));
	echo "<select id='tempera_excerptarchive' name='tempera_settings[tempera_excerptarchive]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_excerptarchive'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Excerpts on archive, categroy and search pages. Same as above, only standard posts will be affected.","tempera")."</small></div>";
}


// TEXTBOX - Name: tempera_settings[excerptwords]
function cryout_setting_excerptwords_fn() {
	global $temperas;
	echo "<input id='tempera_excerptwords' name='tempera_settings[tempera_excerptwords]' size='6' type='text' value='".esc_attr( $temperas['tempera_excerptwords'] )."'  />";
	echo "<div><small>".__("The number of words for excerpts. When that number is reached the post will be interrupted by a <i>Continue reading</i> link that will take the reader to the full post page.","tempera")."</small></div>";
}

// TEXTBOX - Name: tempera_settings[excerptdots]
function cryout_setting_excerptdots_fn() {
	global $temperas;
	echo "<input id='tempera_excerptdots' name='tempera_settings[tempera_excerptdots]' size='40' type='text' value='".esc_attr( $temperas['tempera_excerptdots'] )."'  />";
	echo "<div><small>".__("Replaces the three dots ('[...])' that are appended automatically to excerpts.","tempera")."</small></div>";
}

// TEXTBOX - Name: tempera_settings[excerptcont]
function cryout_setting_excerptcont_fn() {
	global $temperas;
	echo "<input id='tempera_excerptcont' name='tempera_settings[tempera_excerptcont]' size='40' type='text' value='".esc_attr( $temperas['tempera_excerptcont'] )."'  />";
	echo "<div><small>".__("Edit the 'Continue Reading' link added to your post excerpts.","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[excerpttags]
function cryout_setting_excerpttags_fn() {
	global $temperas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable *UNSUPPORTED*","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_excerpttags' name='tempera_settings[tempera_excerpttags]'";
	if ($temperas['tempera_excerpttags'] != 'Enable') echo " disabled='disabled'"; 
	echo ">";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		if ('Enable' == $item) echo " disabled='disabled'"; 
		selected($temperas['tempera_excerpttags'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("By default WordPress excerpts remove all HTML tags (&lt;pre&gt;, &lt;a&gt;, &lt;b&gt and all others) and only clean text is left in the excerpt.
Enabling this option allows HTML tags to remain in excerpts so all your default formating will be kept.<br /> <b>Just a warning: </b>If HTML tags are enabled, you have to make sure
they are not left open. So if within your post you have an opened HTML tag but the except ends before that tag closes, the rest of the site will be contained in that HTML tag. -- Leave 'Disable' if unsure -- ","tempera").'<br><strong><em style="color:#880000;">'.__('This option is deprecated and no longer supported since Tempera v1.6.0','tempera')."</strong></em></small></div>";
}


////////////////////////////////
/// FEATURED IMAGE SETTINGS ////
////////////////////////////////


//CHECKBOX - Name: tempera_settings[fpost]
function cryout_setting_fpost_fn() {
	global $temperas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_fpost' name='tempera_settings[tempera_fpost]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_fpost'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	$checkedClass = ($temperas['tempera_fpostlink']=='1') ? ' checkedClass' : '';
	echo " <label style='border:none;margin-left:10px;' id='$items[0]' for='$items[0]$items[0]' class='socialsdisplay $checkedClass'><input type='hidden' name='tempera_settings[tempera_fpostlink]' value='0' /><input ";
		 checked($temperas['tempera_fpostlink'],'1');
	echo " value='1' id='$items[0]$items[0]'  name='tempera_settings[tempera_fpostlink]' type='checkbox' /> ".__("Link the thumbnail to the post","tempera")."</label>";
	echo "<div><small>".__("Show featured images as thumbnails on posts. The images must be selected for each post in the Featured Image section.","tempera")."</small></div>";
}

//CHECKBOX - Name: tempera_settings[fauto]
function cryout_setting_fauto_fn() {
	global $temperas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_fauto' name='tempera_settings[tempera_fauto]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_fauto'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Show the first image that you inserted in a post as a thumbnail. If there is a Featured Image selected for that post, it will have priority.","tempera")."</small></div>";
}


//CHECKBOX - Name: tempera_settings[falign]
function cryout_setting_falign_fn() {
	global $temperas;
	$items = array ("Left" , "Center", "Right");
	$itemsare = array( __("Left","tempera"), __("Center","tempera"), __("Right","tempera"));
	echo "<select id='tempera_falign' name='tempera_settings[tempera_falign]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_falign'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Thumbnail alignment.","tempera")."</small></div>";
}


// TEXTBOX - Name: tempera_settings[fwidth]
function cryout_setting_fsize_fn() {
	global $temperas;
	echo "<input id='tempera_fwidth' name='tempera_settings[tempera_fwidth]' size='4' type='text' value='".esc_attr( $temperas['tempera_fwidth'] )."'  />px ".__("(width)","tempera")." <b> X </b> ";
	echo "<input id='tempera_fheight' name='tempera_settings[tempera_fheight]' size='4' type='text' value='".esc_attr( $temperas['tempera_fheight'] )."'  />px ".__("(height)","tempera")."";

	$checkedClass = ($temperas['tempera_fcrop']=='1') ? ' checkedClass' : '';

		echo " <label id='fcrop' for='tempera_fcrop' class='socialsdisplay $checkedClass'><input  ";
		 checked($temperas['tempera_fcrop'],'1');
	echo "value='1' id='tempera_fcrop'  name='tempera_settings[tempera_fcrop]' type='checkbox' /> ".__("Crop images to exact size.","tempera")." </label>";


	echo "<div><small>".__("The size (in pixels) for your thumbnails. By default imges will be scaled with aspect ratio kept. Choose to crop the images if you want the exact size.","tempera")."</small></div>";
}


//CHECKBOX - Name: tempera_settings[fheader]
function cryout_setting_fheader_fn() {
	global $temperas;
	$items = array ("Enable" , "Disable");
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_fheader' name='tempera_settings[tempera_fheader]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_fheader'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<div><small>".__("Show featured images on headers. The header will be replaced with a featured image if you selected it as a Featured Image in the post and if it is bigger or at least equal to the current header size.","tempera")."</small></div>";
}


////////////////////////
/// SOCIAL SETTINGS ////
////////////////////////

function cryout_setting_social_master($i) {
	$cryout_special_keys = array('Mail', 'Skype');
	$cryout_social_small = array (
		'',__('Select your desired Social network from the left dropdown menu and insert your corresponding address in the right input field. (ex: <i>http://www.facebook.com/yourname</i> )','tempera'),
		'',__("You can also choose if you want the link to open in a new window and what title to display while hovering over the icon.",'tempera'),
		'',__("You can show up to 5 different social icons from over 35 social networks.",'tempera'),
		'',__("You can leave any number of inputs empty.",'tempera'),
		'',__("You can change the background for your social colors from the colors settings section.",'tempera')
		);
	$j=$i+1;
	global $temperas, $tempera_socialNetworks;
	echo "<select id='tempera_social$i' name='tempera_settings[tempera_social$i]'>";
	foreach($tempera_socialNetworks as $item) {
		echo "<option value='$item'";
		selected($temperas['tempera_social'.$i],$item);
		echo ">$item</option>";
	}
	echo "</select><span class='address_span'> &raquo; </span>";

	if (in_array($temperas['tempera_social'.$i],$cryout_special_keys)) :
		$cryout_current_social = esc_html( $temperas['tempera_social'.$j] );
	else :
		$cryout_current_social = esc_url( $temperas['tempera_social'.$j] );
	endif;
	// Social Link
	echo "<input id='tempera_social$j' placeholder='".__("Social Network Link","tempera")."' name='tempera_settings[tempera_social$j]' size='32' type='text'  value='$cryout_current_social' />";
	// Social Open in new window
	$checkedClass = ($temperas['tempera_social_target'.$i]=='1') ? ' checkedClass' : '';
	echo " <label id='tempera_social_target$i' for='tempera_social_target$i$i' class='$checkedClass'><input ";
	 checked($temperas['tempera_social_target'.$i],'1');
	echo " value='1' id='tempera_social_target$i$i' name='tempera_settings[tempera_social_target$i]' type='checkbox' /> ".__("Open in new window","tempera")." </label>";
	// Social Title
	echo "<input id='tempera_social_title$i$i' name='tempera_settings[tempera_social_title$i]' size='32' type='text' placeholder='".__("Social Network Title","tempera")."' value='".$temperas['tempera_social_title'.$i]."' />";

	echo "<div><small>".$cryout_social_small[$i]."</small></div>";
} // cryout_setting_social_master()


// TEXTBOX - Name: tempera_settings[socialX]
function cryout_setting_socials1_fn() {
	cryout_setting_social_master(1);
}

function cryout_setting_socials2_fn() {
	cryout_setting_social_master(3);
}

// TEXTBOX - Name: tempera_settings[social3]
function cryout_setting_socials3_fn() {
	cryout_setting_social_master(5);
}

// TEXTBOX - Name: tempera_settings[social4]
function cryout_setting_socials4_fn() {
	cryout_setting_social_master(7);
}

// TEXTBOX - Name: tempera_settings[social5]
function cryout_setting_socials5_fn() {
	cryout_setting_social_master(9);
}


// TEXTBOX - Name: tempera_settings[socialsdisplay]
function cryout_setting_socialsdisplay_fn() {
global $temperas;
		$items = array( "Header", "CLeft", "CRight" , "Footer" ,"SLeft", "SRight");


	echo " <label id='$items[0]' for='$items[0]$items[0]' class='socialsdisplay'><input ";
		 checked($temperas['tempera_socialsdisplay0'],'1');
	echo " value='1' id='$items[0]$items[0]' name='tempera_settings[tempera_socialsdisplay0]' type='checkbox' /> ".__("Top Bar","tempera")."</label>";

	echo " <label id='$items[3]' for='$items[3]$items[3]' class='socialsdisplay'><input ";
		 checked($temperas['tempera_socialsdisplay3'],'1');
	echo " value='1' id='$items[3]$items[3]' name='tempera_settings[tempera_socialsdisplay3]' type='checkbox' /> ".__("Footer","tempera")." </label>";

	echo " <label id='$items[4]' for='$items[4]$items[4]' class='socialsdisplay'><input ";
		 checked($temperas['tempera_socialsdisplay4'],'1');
	echo " value='1' id='$items[4]$items[4]' name='tempera_settings[tempera_socialsdisplay4]' type='checkbox' /> ".__("Left side","tempera")." </label>";

	echo " <label id='$items[5]' for='$items[5]$items[5]' class='socialsdisplay'><input ";
		 checked($temperas['tempera_socialsdisplay5'],'1');
	echo " value='1' id='$items[5]$items[5]' name='tempera_settings[tempera_socialsdisplay5]' type='checkbox' /> ".__("Right side","tempera")." </label>";

	echo "<br/>";

	echo " <label id='$items[1]' for='$items[1]$items[1]' class='socialsdisplay'><input ";
		 checked($temperas['tempera_socialsdisplay1'],'1');
	echo " value='1' id='$items[1]$items[1]' name='tempera_settings[tempera_socialsdisplay1]' type='checkbox' /> ".__("Left Sidebar","tempera")." </label>";

	echo " <label id='$items[2]' for='$items[2]$items[2]' class='socialsdisplay'><input ";
		 checked($temperas['tempera_socialsdisplay2'],'1');
	echo " value='1' id='$items[2]$items[2]' name='tempera_settings[tempera_socialsdisplay2]' type='checkbox' /> ".__("Right Sidebar","tempera")." </label>";

	echo "<div><small>".__("Choose the <b>areas</b> where to display the social icons.","tempera")."</small></div>";
}


////////////////////////
/// MISC SETTINGS ////
////////////////////////


// TEXTBOX - Name: tempera_settings[copyright]
function cryout_setting_copyright_fn() {
	global $temperas;
	echo "<textarea id='tempera_copyright' name='tempera_settings[tempera_copyright]' rows='3' cols='70' type='textarea' >".esc_textarea($temperas['tempera_copyright'])." </textarea>";
	echo "<div><small>".__("Insert custom text or HTML code that will appear in you footer. <br /> You can use HTML to insert links, images and special characters like &copy;.","tempera")."</small></div>";
}


// TEXTBOX - Name: tempera_settings[customcss]
function cryout_setting_customcss_fn() {
	global $temperas;
	echo "<textarea id='tempera_customcss' name='tempera_settings[tempera_customcss]' rows='8' cols='70' type='textarea' >".esc_textarea(htmlspecialchars_decode($temperas['tempera_customcss'], ENT_QUOTES))." </textarea>";
	echo "<div><small>".__("Insert your custom CSS here. Any CSS declarations made here will overwrite Tempera's (even the custom options specified right here in the Tempera Settings page). <br /> Your custom CSS will be preserved when updating the theme.","tempera")."</small></div>";
}

// TEXTBOX - Name: tempera_settings[customjs]
function cryout_setting_customjs_fn() {
	global $temperas;
	echo "<textarea id='tempera_customjs' name='tempera_settings[tempera_customjs]' rows='8' cols='70' type='textarea' >".esc_textarea(htmlspecialchars_decode($temperas['tempera_customjs']))." </textarea>";
	echo "<div><small>".__("Insert your custom Javascript code here. (Google Analytics and any other forms of Analytic software).","tempera")."</small></div>";
}
function cryout_setting_iecompat_fn() {
	global $temperas;
	$items = array (1, 0);
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_iecompat' name='tempera_settings[tempera_iecompat]'>";
foreach($items as $id=>$item) {
	echo "<option value='$item'";
	selected($temperas['tempera_iecompat'],$item);
	echo ">$itemsare[$id]</option>";
}
	echo "</select>";
	echo "<input type='hidden' name='tempera_settings[tempera_current_admin_menu]' id='tempera_current' value='". $temperas['tempera_current_admin_menu']."'>";
	echo "<input type='hidden' name='tempera_settings[tempera_postboxes]' id='tempera-postboxes' value='". $temperas['tempera_postboxes']."'>";
	echo "<div><small>".__("Enable extra compatibility tag for older Internet Explorer versions. Turning this option on will trigger W3C validation errors.","tempera")."</small></div>";
} // cryout_setting_iecompat_fn()

/*function cryout_setting_masonry_fn() {
	global $temperas;
	$items = array (1, 0);
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_masonry' name='tempera_settings[tempera_masonry]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_masonry'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Disable to troubleshoot compatibility with plugins that dynamically add content to post lists and change length.","tempera")."</small></div>";
}*/

function cryout_setting_fitvids_fn() {
	global $temperas;
	$items = array (1, 0);
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_fitvids' name='tempera_settings[tempera_fitvids]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_fitvids'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Disable to troubleshoot embedded video resize issues.","tempera")."</small></div>";
}

function cryout_setting_editorstyle_fn() {
	global $temperas;
	$items = array (1, 0);
	$itemsare = array( __("Enable","tempera"), __("Disable","tempera"));
	echo "<select id='tempera_editorstyle' name='tempera_settings[tempera_editorstyle]'>";
	foreach($items as $id=>$item) {
		echo "<option value='$item'";
		selected($temperas['tempera_editorstyle'],$item);
		echo ">$itemsare[$id]</option>";
	}
	echo "</select>";
	echo "<div><small>".__("Disable to turn off the theme's styling in the Visual Editor.","tempera")."</small></div>";
}

// FIN