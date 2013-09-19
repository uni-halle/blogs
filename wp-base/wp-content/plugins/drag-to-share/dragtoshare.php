<?php
/*
Plugin Name: Drag to Share
Version: 1.0.0
Plugin URI: http://www.adrian-apan.com/blog/wordpress-plugin-drag-to-share
Description: "Drag to share" is the newest trend in social sharing. Just drag an image and drop it into any social website to share it in real time. This plugin is basically a similar effect release by Meebo.com and used by big players like Mashable.
Author: Adrian Apan
Author URI: http://www.adrian-apan.com
*/

// Pre-2.6 compatibility
if ( !defined('WP_CONTENT_URL') )
    define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if ( !defined('WP_CONTENT_DIR') )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
	

if (!class_exists("DragToShare")) {


	class DragToShare {
		function DragToShare() { //constructor
			
		}
		function addHeaderCode() {
			$DragToSharepath = WP_CONTENT_URL.'/plugins/'.plugin_basename(dirname(__FILE__)).'/';	// Path
			echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $DragToSharepath . 'dragToShare.css" />'."\n";	// Adds the CSS to the footer
			echo '<script type="text/javascript" src="' . $DragToSharepath . 'js/jquery-1.3.2.min.js"></script>'."\n";	// Adds the JavaScript to the footer
			echo '<script type="text/javascript" src="' . $DragToSharepath . 'js/jquery-ui-1.7.2.custom.min.js"></script>'."\n";	// Adds the JavaScript to the footer
			?>
			    <script type="text/javascript">
	  $(function() {
	    
		//cache selector
		var images = $("#content img"),
		  title = $("title").text() || document.title;
		
	    //make images draggable
	    images.draggable({
		  //create draggable helper
		  helper: function() {
		    return $("<div>").attr("id", "helper").html("<span>" + title + "</span><img id='thumb' src='" + $(this).attr("src") + "'>").appendTo("body");
		  },
		  cursor: "pointer",
		  cursorAt: { left: -10, top: 20 },
		  zIndex: 99999,
		  //show overlay and targets
		  start: function() {
		    $("<div>").attr("id", "overlay").css("opacity", 0.8).appendTo("body");
			$("#tip").remove();
			$(this).unbind("mouseenter");
			$("#targets").css("left", ($("body").width() / 2) - $("#targets").width() / 2).slideDown();
		  },
		  //remove targets and overlay
		  stop: function() {
		    $("#targets").slideUp();
			$(".share", "#targets").remove();
		    $("#overlay").remove();
			$(this).bind("mouseenter", createTip);
		  }
		});
		
		//make targets droppable
		$("#targets li").droppable({
		  tolerance: "pointer",
		  //show info when over target
		  over: function() {
		    $(".share", "#targets").remove();
		    $("<span>").addClass("share").text("Share on " + $(this).attr("id")).addClass("active").appendTo($(this)).fadeIn();
		  },
		  drop: function() {
		    var id = $(this).attr("id"),
			  currentUrl = window.location.href,
			  baseUrl = $(this).find("a").attr("href");

			if (id.indexOf("twitter") != -1) {
			  window.location.href = baseUrl + "/home?status=" + title + ": " + currentUrl;
			} else if (id.indexOf("delicious") != -1) {
			  window.location.href = baseUrl + "/save?url=" + currentUrl + "&title=" + title;
			} else if (id.indexOf("facebook") != -1) {
			  window.location.href = baseUrl + "/sharer.php?u=" + currentUrl + "&t=" + title;
			}
		  }		  
		});
	  
	    var createTip = function(e) {
		  //create tool tip if it doesn't exist
		  ($("#tip").length === 0) ? $("<div>").html("<span>Drag this image to share the page<\/span><span class='arrow'><\/span>").attr("id", "tip").css({ left:e.pageX + 30, top:e.pageY - 16 }).appendTo("body").fadeIn(500) : null;
		};
		
		images.bind("mouseenter", createTip);
		
		images.mousemove(function(e) {
		
		  //move tooltip
          $("#tip").css({ left:e.pageX + 30, top:e.pageY - 16 });
        });
	  
	    images.mouseleave(function() {
		
		  //remove tooltip
		  $("#tip").remove();
	    });
	  });
	</script>
     <ul id="targets">
      <li id="twitter"><a href="http://twitter.com"><!-- --></a></li>
      <li id="delicious"><a href="http://delicious.com"><!-- --></a></li>
      <li id="facebook"><a href="http://www.facebook.com"><!-- --></a></li>
    </ul>
			<?php
		}

		
	}

} //End Class DragToShare

if (class_exists("DragToShare")) {
	$dl_plugin = new DragToShare();
}
//Actions
if (isset($dl_plugin)) {
	//Add Action To Footer
	add_action('wp_footer', array(&$dl_plugin, 'addHeaderCode'));
}

?>