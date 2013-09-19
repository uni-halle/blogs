<div id="sidebar" <?php if ( get_option('woo_mainright') ) echo 'class="fl"'; ?>>

    <!-- Add you sidebar manual code here to show above the widgets -->
    <div class="widgetized">
		<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(2) )  ?>	
    </div>	           
    <!-- Add you sidebar manual code here to show below the widgets -->	

    <!-- Sidebar Video -->    
    <?php if ( !get_option('woo_video') ) include ( TEMPLATEPATH . "/includes/video.php" ); ?>

    <!-- Sidebar Tabs -->
    <?php if ( !get_option('woo_tabs') ) include ( TEMPLATEPATH . "/includes/tabs.php" ); ?>
    
    <!-- Add you sidebar manual code here to show above the widgets -->
    <div class="widgetized">
		<?php if (function_exists('dynamic_sidebar') && dynamic_sidebar(1) )  ?>	
    </div>	           
    <!-- Add you sidebar manual code here to show below the widgets -->			
                
</div><!-- / #sidebar -->
			