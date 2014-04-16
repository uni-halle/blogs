<?php
	$imagepath =  get_template_directory_uri() . '/images/';
	
	$aadya_slider_img_1 = of_get_option('slider_img_1'); if(($aadya_slider_img_1 == "")) unset($aadya_slider_img_1);
	$aadya_slider_img_2 = of_get_option('slider_img_2'); if(($aadya_slider_img_2 == "")) unset($aadya_slider_img_2);
	$aadya_slider_img_3 = of_get_option('slider_img_3'); if(($aadya_slider_img_3 == "")) unset($aadya_slider_img_3);
	$aadya_slider_img_4 = of_get_option('slider_img_4'); if(($aadya_slider_img_4 == "")) unset($aadya_slider_img_4);	
	
	$aadya_heading_1 = trim(of_get_option('slider_image_heading_1')); if(($aadya_heading_1 == "")) unset($aadya_heading_1);
	$aadya_heading_2 = trim(of_get_option('slider_image_heading_2')); if(($aadya_heading_2 == "")) unset($aadya_heading_2);
	$aadya_heading_3 = trim(of_get_option('slider_image_heading_3')); if(($aadya_heading_3 == "")) unset($aadya_heading_3);
	$aadya_heading_4 = trim(of_get_option('slider_image_heading_4'));	if(($aadya_heading_4 == "")) unset($aadya_heading_4);	
	
	$aadya_caption_1 = trim(of_get_option('slider_image_caption_1')); if(($aadya_caption_1 == "")) unset($aadya_caption_1);
	$aadya_caption_2 = trim(of_get_option('slider_image_caption_2')); if(($aadya_caption_2 == "")) unset($aadya_caption_2);
	$aadya_caption_3 = trim(of_get_option('slider_image_caption_3')); if(($aadya_caption_3 == "")) unset($aadya_caption_3);
	$aadya_caption_4 = trim(of_get_option('slider_image_caption_4')); if(($aadya_caption_4 == "")) unset($aadya_caption_4);	
	
	$aadya_button_1 = trim(of_get_option('slider_image_button_1')); if(($aadya_button_1 == "")) unset($aadya_button_1);
	$aadya_button_2 = trim(of_get_option('slider_image_button_2')); if(($aadya_button_2 == "")) unset($aadya_button_2);
	$aadya_button_3 = trim(of_get_option('slider_image_button_3')); if(($aadya_button_3 == "")) unset($aadya_button_3);
	$aadya_button_4 = trim(of_get_option('slider_image_button_4')); if(($aadya_button_4 == "")) unset($aadya_button_4);		
	
	$aadya_link_1 = trim(of_get_option('slider_image_button_1_link')); if(($aadya_link_1 == "")) unset($aadya_link_1);
	$aadya_link_2 = trim(of_get_option('slider_image_button_2_link')); if(($aadya_link_2 == "")) unset($aadya_link_2);
	$aadya_link_3 = trim(of_get_option('slider_image_button_3_link')); if(($aadya_link_3 == "")) unset($aadya_link_3);
	$aadya_link_4 = trim(of_get_option('slider_image_button_4_link')); if(($aadya_link_4 == "")) unset($aadya_link_4);	
	
	$display_slider = of_get_option('display_slider');
	
?>    
<?php if(isset($display_slider) && $display_slider==true):?>
<?php if(isset($aadya_slider_img_1) || isset($aadya_slider_img_2) || isset($aadya_slider_img_3) || isset($aadya_slider_img_4)) :?>	
	<!-- Carousel
    ================================================== -->
	<div class="slider">
    
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
	
	  <!-- Indicators -->
      <ol class="carousel-indicators">
	  <?php if(isset($aadya_slider_img_1)): ?>
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
	  <?php endif;?>	
	  <?php if(isset($aadya_slider_img_2)): ?>
        <li data-target="#myCarousel" data-slide-to="1"></li>
	  <?php endif;?>
	  <?php if(isset($aadya_slider_img_3)): ?>
        <li data-target="#myCarousel" data-slide-to="2"></li>
	  <?php endif;?>
	  <?php if(isset($aadya_slider_img_4)): ?>
        <li data-target="#myCarousel" data-slide-to="3"></li>
	  <?php endif;?>
      </ol>

      <div class="carousel-inner">	  
		<?php if(isset($aadya_slider_img_1)): ?>
        <div class="item active">
          <img src="<?php echo esc_url($aadya_slider_img_1);?>" alt="" class="img-responsive">
          <div class="container">
            <div class="carousel-caption">
			  <?php if(isset($aadya_heading_1)): ?>	
              <h1><?php echo $aadya_heading_1;?></h1>
			  <?php endif;?>
			  <?php if(isset($aadya_caption_1)): ?>	
              <p><?php echo $aadya_caption_1; ?></p>
			  <?php endif;?>
			  <?php if(isset($aadya_link_1)): ?>
              <p class="hidden-xs hidden-sm"><a class="btn btn-large btn-primary" href="<?php echo get_permalink( $aadya_link_1); ?>"><?php echo $aadya_button_1; ?></a></p>
			  <p class="visible-xs visible-sm"><a class="btn btn-xs btn-primary" href="<?php echo get_permalink( $aadya_link_1); ?>"><?php echo $aadya_button_1; ?></a></p>
			  <?php endif;?>
            </div>
          </div>
        </div>
		<?php endif;?>
		
		<?php if(isset($aadya_slider_img_2)): ?>
        <div class="item">
          <img src="<?php echo esc_url($aadya_slider_img_2);?>" alt="" class="img-responsive">
          <div class="container">
            <div class="carousel-caption">
			  <?php if(isset($aadya_heading_2)): ?>	
              <h1><?php echo $aadya_heading_2;?></h1>
			  <?php endif;?>
			  <?php if(isset($aadya_caption_2)): ?>	
              <p><?php echo $aadya_caption_2; ?></p>
			  <?php endif;?>
			  <?php if(isset($aadya_link_2)): ?>
              <p class="hidden-xs hidden-sm"><a class="btn btn-large btn-primary" href="<?php echo get_permalink( $aadya_link_2); ?>"><?php echo $aadya_button_2; ?></a></p>
			  <p class="visible-xs visible-sm"><a class="btn btn-xs btn-primary" href="<?php echo get_permalink( $aadya_link_2); ?>"><?php echo $aadya_button_2; ?></a></p>
			  <?php endif;?>
            </div>
          </div>
        </div>
		<?php endif;?>

		<?php if(isset($aadya_slider_img_3)): ?>
        <div class="item">
          <img src="<?php echo esc_url($aadya_slider_img_3);?>" alt="" class="img-responsive">
          <div class="container">
            <div class="carousel-caption">
			  <?php if(isset($aadya_heading_3)): ?>	
              <h1><?php echo $aadya_heading_3;?></h1>
			  <?php endif;?>
			  <?php if(isset($aadya_caption_3)): ?>	
              <p><?php echo $aadya_caption_3; ?></p>
			  <?php endif;?>
			  <?php if(isset($aadya_link_3)): ?>
              <p class="hidden-xs hidden-sm"><a class="btn btn-large btn-primary" href="<?php echo get_permalink( $aadya_link_3); ?>"><?php echo $aadya_button_3; ?></a></p>
			  <p class="visible-xs visible-sm"><a class="btn btn-xs btn-primary" href="<?php echo get_permalink( $aadya_link_3); ?>"><?php echo $aadya_button_3; ?></a></p>
			  <?php endif;?>
            </div>
          </div>
        </div>
		<?php endif;?>

		<?php if(isset($aadya_slider_img_4)): ?>
        <div class="item">
          <img src="<?php echo esc_url($aadya_slider_img_4);?>" alt="" class="img-responsive">
          <div class="container">
            <div class="carousel-caption">
			  <?php if(isset($aadya_heading_4)): ?>	
              <h1><?php echo $aadya_heading_4;?></h1>
			  <?php endif;?>
			  <?php if(isset($aadya_caption_4)): ?>	
              <p><?php echo $aadya_caption_4; ?></p>
			  <?php endif;?>
			  <?php if(isset($aadya_link_4)): ?>
              <p class="hidden-xs hidden-sm"><a class="btn btn-large btn-primary" href="<?php echo get_permalink( $aadya_link_4); ?>"><?php echo $aadya_button_4; ?></a></p>
			  <p class="visible-xs visible-sm"><a class="btn btn-xs btn-primary" href="<?php echo get_permalink( $aadya_link_4); ?>"><?php echo $aadya_button_4; ?></a></p>
			  <?php endif;?>
            </div>
          </div>
        </div>
		<?php endif;?>				
      </div>	  
      <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
    </div><!-- /.carousel -->
	</div><!-- /.slider -->
<?php endif;?>	
<?php endif;?>	

<?php
	$display_blurb = of_get_option('display_blurb');
	$display_blurb_button = of_get_option('display_blurb_button');
	
	if($display_blurb_button !=1) {
		$blurb_col = 12;
	} else {
		$blurb_col = 9;
	}
?>

<?php if($display_blurb == '1'): ?>	
<div class="blurb">
    <div class="container">
        <div class="row">
            <div class="col-md-<?php echo $blurb_col; ?>">
                <span><?php echo of_get_option('blurb_heading'); ?></span>
                <p><?php echo of_get_option('blurb_text'); ?></p>
            </div>        
			<?php if($display_blurb_button == '1'): ?>	
            <div class="col-md-3">
                <a href="<?php echo get_permalink( of_get_option('blurb_button_link_page')); ?>" class="btn-buy hover-effect"><?php echo of_get_option('blurb_button_title'); ?></a>            
            </div>
			<?php endif; ?>	
        </div>
    </div>
</div>
<?php endif; ?>	