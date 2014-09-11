<article <?php omega_attr( 'post' ); ?>>	

	<div class="entry-wrap">
		
		<?php do_action( 'omega_before_entry' ); ?>

		<div class="entry-content">		

			<?php do_action( 'omega_entry' ); ?>
			
		</div><!-- .entry-content -->

		<?php do_action( 'omega_after_entry' ); ?>

	</div><!-- .entry-wrap -->
	
</article><!-- #post-## -->