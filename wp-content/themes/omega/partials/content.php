<?php
if ( have_posts() ) : 		

	do_action( 'omega_before_loop');	

	/* Start the Loop */ 
	while ( have_posts() ) : the_post(); 
	?>
		<article <?php omega_attr( 'post' ); ?>><div class="entry-wrap">
			<?php 
			do_action( 'omega_before_entry' ); 
			do_action( 'omega_entry' );
			do_action( 'omega_after_entry' ); 
			?>
		</div></article>				
	<?php
	endwhile; 
	
	do_action( 'omega_after_loop');			

else : 
		get_template_part( 'partials/no-results', 'archive' ); 
endif;	
?>