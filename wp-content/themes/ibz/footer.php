<?php
/**
 * The template for displaying the footer.
 * Contains the closing of the id="wrapper" and id="container" div 
 * and it's loading Flex slider and Google map codes
 */
?>
        </div> <!--/wrapper--> 
</div>  
  </div> <!--/container-->      
    <div style="clear:both;"></div>
    <?php $maja_option = maja_get_global_options(); ?>
    
    <!-- responsive navigation -->
 	<script> 
	(function($) {
	$('div.viewhide').hide();
	$('.rooms_container > .leftcol > ul > li:first-child').css('color', '#9BC24B');
$('li.filter').click(function (e) 
{

var filter =$(this).attr('class').replace('filter ','') ;
if(filter=='DB')return;
var room=$(this).parent().parent().parent().attr('class').replace('rooms_container ','');

$('.' + room + ' > .leftcol > .view').hide();
$('.' + room + ' > .leftcol > .'+filter.replace('L','S')).show();
$('.' + room + ' > .leftcol > ul > li').css('color', '#000000'); 
$(this).css('color', '#9BC24B'); 


})

	
		jQuery(document).ready(function($) {
<?php		
$show=false;
		if (is_page( )) {
  $page = $post->ID;
  $children=wp_list_pages( 'echo=0&child_of=' . $page . '&title_li=' );
  if($children)
	$show=true;
  if ($post->post_parent!=5) {
  
    $show=true;	

  }}
  if($show==false)
 
  	echo "$('ul.children').hide();";
  ?>

	
			// Create the dropdown bases
			$("<select />").appendTo("#menu nav");
			
			// Create default option "Go to page..."
			$("<option />", {
			   "selected": "selected",
			   "value"   : "",
			   "text"    : "<?php echo $maja_option['maja_nav-title'] ?>"
			}).appendTo("nav select");
			
			// Populate dropdowns with the first menu items
			$("#menu_list li").each(function() {
				
				var depth   = $(this).parents('ul').length - 1;
				
				var dash = '';
				if( depth > 0 ) { dash = '- '; }
				if( depth > 1 ) { dash = '-- '; }
				
				var el = $(this).children('a');
				 $("<option />", {
					 "value"   : el.attr("href"),
					 "text"    : (dash + el.text())
				}).appendTo("nav select");
			});
		
			
			//make responsive dropdown menu actually work			
			$("#menu nav select").change(function() {
				window.location = $(this).find("option:selected").val();
			});
		})
		})( jQuery );
	</script>    
    <!-- /responsive navigation -->
    
    <!-- flex slider code -->    
 	<?php if ($maja_option['maja_slider'] =='1') { ?> 
        <script>  
		(function($) {
		jQuery(document).ready(function($) {
		  
			// FLEX SLIDER
			$('.flexslider').flexslider({
			  animation: "fade",
			  directionNav: true,
			  controlNav: true, 
			  animationDuration: 600,
			  slideshowSpeed: 7000,
		 	});
		})
		})( jQuery );
	    </script> 
    <?php } ?>  
    <!-- /flex slider code -->   
    
    <!-- google map code -->      
 	<?php if ($maja_option['maja_map-check'] =='1') { ?>          
        <script>
		(function($) {
          function initialize() {
            var myLatlng = new google.maps.LatLng(<?php echo $maja_option['maja_map-lat'] ?>,<?php echo $maja_option['maja_map-lng'] ?>);
			 
            var myOptions = {
              zoom: <?php echo $maja_option['maja_map-zoom'] ?>,
              center: myLatlng,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            }
            var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
			
            var marker = new google.maps.Marker({
                position: myLatlng, 
                map: map,
            }); 
			
			
          }
		  
    })( jQuery );
        </script> 
    <?php } ?> 
    <!-- /google map code --> 
             
    <?php wp_footer(); ?>
	
</body>
</html>