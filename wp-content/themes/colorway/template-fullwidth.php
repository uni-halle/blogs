<?php
/*
  Template Name: Fullwidth Template
 */
?>
<?php get_header(); 
$b = get_option('container-layout');
switch ($b) {
   case 'container':
       $container = 'container';
       break;
   case 'fullwidth-container':
       $container = 'container-fluid';
       break;
   default:
       $container = 'container-fluid';
}
?>
<div style="background-color:white;" class="<?php echo esc_attr($container); ?>">
 <!--Start Content Grid-->
<div class="row content">
    <div class="content-wrapper">
        
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <h2>
                    <?php the_title(); ?>
                </h2>
                <?php the_content(); ?>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>
<div class="clear"></div>
<!--End Content Grid-->
</div>
</div>
</div>
<!--End Container Div-->
<?php get_footer(); ?>
