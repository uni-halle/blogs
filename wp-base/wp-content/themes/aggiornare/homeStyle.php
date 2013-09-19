<?php
/**
 * @package WordPress
 * @subpackage Aggiornare
 */
/*
Template Name: Home
*/

get_header(); ?>
<?php
      // Load up theme options
      $bannerImage = get_option('aggiornare_homepage_image');
      $bannerHeadline = get_option('aggiornare_homepage_headline');
      $intro = get_option('aggiornare_homepage_intro');
      $linkText = get_option('aggiornare_homepage_linkText');
      $linkPage = get_option('aggiornare_homepage_linkPage');
?>

<div class="introBanner">
					<?php if($bannerImage) { ?>
						<div class="bannerImage">&nbsp;</div>
					<?php } ?>
						<h2><?php echo $bannerHeadline; ?></h2>
				</div>
				
				<p class="large"><?php echo $intro; ?></p>
				<p class="highlight"><a href="<?php echo $linkPage; ?>"><?php echo $linkText; ?></a></p>
				
			</div>

	
<?php get_sidebar(); ?>

<?php get_footer(); ?>
