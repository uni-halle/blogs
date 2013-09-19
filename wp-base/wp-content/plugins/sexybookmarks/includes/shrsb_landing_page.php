<?php function shrsb_landing_page() { ?>

		<div class="wrap""><div class="icon32" id="icon-options-general"><br></div><h2>Shareaholic Dashboard</h2></div>

    <div id="shrsb-col-left" style="width:100%;">
		<ul id="shrsb-sortables">

        <li>
            <div class="page-header" style="margin-top:20px;">
						  <h1 class="grey_light">Enable Discovery:</h1>
						</div>

            <div id="rec_box" class="select_product">
							<img style="float:left; margin-left:-25px;margin-top:-19px;" src="<?php echo SHRSB_PLUGPATH."images/new_badge.png" ?>">
              <div class="shr-landing-product-icon"><img src="<?php echo SHRSB_PLUGPATH."images/thumbs.png" ?>"></div>
              <div class="shr-landing-product-name"><h2>Recommendations / Related Content</h2><span class="shr-landing-product-desc">Proven to drive more pageviews by helping your readers discover more of your amazing content.</span></div>
              <div class="shr-landing-product-configure"><a href="admin.php?page=shareaholic_recommendations.php" class="btn btn-large <?php global $shrsb_recommendations; echo ((@$shrsb_recommendations['recommendations'] == "1")? '' : "btn-primary");?>"><?php echo ((@$shrsb_recommendations['recommendations'] == "1")? '<i class="icon-cog" style="margin-top:2px;"></i> Settings' : "Enable");?></a></div>
            </div>
						
						<div class="page-header" style="margin-top:40px;"">
						  <h1 class="grey_light">Enable Sharing:</h1>
						</div>
						
            <div id="sb_box" class="select_product">
								<img style="float:left; margin-left:-25px;margin-top:-19px;visibility:hidden;" src="<?php echo SHRSB_PLUGPATH."images/new_badge.png" ?>">
                <div class="shr-landing-product-icon"><img src="<?php echo SHRSB_PLUGPATH."images/sbm.png" ?>"></div>
                <div class="shr-landing-product-name"><h2>SexyBookmarks</h2><span class="shr-landing-product-desc">Have your content shared more with the sexiest sharing buttons on the web.</span></div>
                <div class="shr-landing-product-configure"><a href="admin.php?page=shareaholic_sexybookmarks.php" class="btn btn-large <?php global $shrsb_plugopts; echo ((@$shrsb_plugopts['sexybookmark'] == "1")? '' : "btn-primary");?>"><?php echo ((@$shrsb_plugopts['sexybookmark'] == "1")? '<i class="icon-cog" style="margin-top:2px;"></i> Settings' : "Enable");?></a></div>
            </div>

            <div id="topbar_box" class="select_product">
							<img style="float:left; margin-left:-25px;margin-top:-19px;visibility:hidden;" src="<?php echo SHRSB_PLUGPATH."images/new_badge.png" ?>">
              <div class="shr-landing-product-icon"><img src="<?php echo SHRSB_PLUGPATH."images/tophat.jpg" ?>"></div>
              <div class="shr-landing-product-name"><h2>Top Bar</h2><span class="shr-landing-product-desc">Make sure your readers always have a share button nearby.</span></div>
              <div class="shr-landing-product-configure"><a href="admin.php?page=shareaholic_topbar.php" class="btn btn-large <?php global $shrsb_tb_plugopts; echo ((@$shrsb_tb_plugopts['topbar'] == "1")? '' : "btn-primary");?>"><?php echo ((@$shrsb_tb_plugopts['topbar'] == "1")? '<i class="icon-cog" style="margin-top:2px;"></i> Settings' : "Enable");?></a></div>
            </div>
            
            <div id="sb_box" class="select_product">
                <div class="shr-landing-product-icon"><img src="<?php echo SHRSB_PLUGPATH."images/cbm.png" ?>"></div>
                <div class="shr-landing-product-name"><h2>ClassicBookmarks</h2><span class="shr-landing-product-desc">Beautiful, elegant, classic styled sharing buttons.</span></div>
                <div class="shr-landing-product-configure"><a href="admin.php?page=shareaholic_classicbookmarks.php" class="btn btn-large <?php global $shrsb_cb; echo ((@$shrsb_cb['cb'] == "1")? '' : "btn-primary");?>"><?php echo ((@$shrsb_cb['cb'] == "1")? '<i class="icon-cog" style="margin-top:2px;"></i> Settings' : "Enable");?></a></div>
            </div>

						<div class="page-header" style="margin-top:40px;">
						  <h1 class="grey_light">Analyze:</h1>
						</div>
						
            <div id="soc_box" class="select_product">
							<img style="float:left; margin-left:-25px;margin-top:-19px;visibility:hidden;" src="<?php echo SHRSB_PLUGPATH."images/new_badge.png" ?>">
              <div class="shr-landing-product-icon"><img src="<?php echo SHRSB_PLUGPATH."images/chart.png" ?>"></div>
              <div class="shr-landing-product-name"><h2>Social Analytics</h2><span class="shr-landing-product-desc">Discover and connect with who is reading and sharing your content.</span></div>
              <div class="shr-landing-product-configure"><a href="admin.php?page=shareaholic_analytics.php" class="btn btn-large"><i class="icon-cog" style="margin-top:2px;"></i> Settings</a></div>
            </div>
		<div style="margin-top:45px;"></div>
		
	<?php echo shrsb_getfooter(); ?>
  </div>

<?php

//Right Side helpful links
echo shrsb_right_side_menu();
//Snap Engage
echo get_snapengage();

}//closing brace for function "shrsb_settings_page"

?>