<?php get_header(); ?>
		<?php	
		$options = get_option("widget_sideFeature");
		$posts = get_option('uwc_number_posts');
		if (is_active_widget('widget_myFeature')) {
			$category = "showposts=".$posts."&cat=-".$options['category'];
		} else {
			$category = "showposts=".$posts;	
		}       	$x = 1;
		?>

        <?php query_posts($category); ?>
		<?php while (have_posts()) : the_post(); ?>
        <?php if($x == 1) { ?>
		<div class="post">
       		<h5>Neue Artikel</h5>
            <h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
			<div class="meta">
				Von <?php the_author() ?>
			</div>
			
            <div class="storycontent">
                <?php getImage('1'); ?>
                <?php theme_excerpt(get_option('uwc_excerpt_one')); ?>
            </div>

		
        <?php $x++; ?>
		<?php } elseif($x >= 2 && $x < 4) { ?>
        <?php if($x == 2) { $i=1; ?></div><div id="twocol"><?php } ?>
            <div class="twopost twopost<?php echo $i; $i++; ?>">
                <h1><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h1>
                <div class="meta">
                    Von <?php the_author() ?>
                </div>
    	
                <div class="storycontent">
					<?php getImage('1'); ?>
                	<?php theme_excerpt(get_option('uwc_excerpt_two')); ?>
                </div>
             </div>
        <?php $x++; ?>
   		<?php } else { ?>
        <?php if($x == 4) { $i=1; ?></div><hr class="mainhr" /><div id="threecol"><div id="threecol2"><?php } ?>
            <div class="threepost threepost<?php if($i==7) { $i = 4; } echo $i; $i++; ?>">
                <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                <div class="meta">
                    Von <?php the_author() ?>
                </div>
    
                <div class="storycontent">
					<?php getImage('1'); ?>
               		<?php theme_excerpt(get_option('uwc_excerpt_three')); ?>
                </div>
             </div>
        <?php $x++; } ?>
        <?php endwhile; ?>
		<?php if($x>4) { echo "</div>"; } ?>
        </div>
<?php get_footer(); ?>