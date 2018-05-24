<?php
/**
 * Output stacks
 */
function graphene_stack( $stack, $args = array() ){
	$stack = str_replace( '-', '_', $stack );
	if ( function_exists( 'graphene_stack_' . $stack ) ) {
		do_action( 'graphene_before_stack_' . $stack );
		call_user_func( 'graphene_stack_' . $stack, apply_filters( 'graphene_stack_args', $args, $stack ) );
		do_action( 'graphene_after_stack_' . $stack );
	}
}


if ( ! function_exists( 'graphene_stack_mentions_bar' ) ) :
/**
 * Stack: Mentions Bar
 */
function graphene_stack_mentions_bar( $args = array() ){
	global $graphene_settings;
	
	$defaults = array(
		'title'			=> $graphene_settings['mentions_bar_title'],
		'description'	=> $graphene_settings['mentions_bar_desc'],
		'items'			=> $graphene_settings['brand_icons'],
		'new_tab'		=> $graphene_settings['mentions_bar_new_window'],
	);
	$args = wp_parse_args( $args, $defaults );
	extract( $args, EXTR_SKIP );
	
	if ( ! $items ) return;
	?>
	<div class="mentions-bar row">
		<?php graphene_container_wrapper( 'start' ); ?>

	    	<?php do_action( 'graphene_mentions_bar_top' ); ?>
	        <?php if ( $title ) : ?><h2 class="highlight-title"><?php echo $title; ?></h2><?php endif; ?>
	        <?php if ( $description ) echo '<div class="description">' . wpautop( $description ) . '</div>'; ?>
	        <ul class="mentions-bar-logo">
	        	<?php 
				foreach ( $items as $item ) : 
					$icon = wp_get_attachment_image_src( $item['image_id'], 'medium' ); 
					$icon_meta = wp_get_attachment_metadata( $item['image_id'] );
					$alt = ( isset( $icon_meta['image_meta']['title'] ) ) ? $icon_meta['image_meta']['title'] : '';
				?>
	            <li>
	            	<?php if ( $item['url'] ) : ?><a href="<?php echo esc_url( $item['url'] ); ?>" <?php if ( $new_tab ) echo 'target="_blank"'; ?>><?php endif; ?>
	                	<img src="<?php echo $icon[0]; ?>" width="<?php echo $icon[1]; ?>" height="<?php echo $icon[2]; ?>" alt="<?php echo $alt; ?>" />
	                <?php if ( $item['url'] ) : ?></a><?php endif; ?>
	            </li>
	            <?php endforeach; ?>
	        </ul>
	        <?php do_action( 'graphene_mentions_bar_bottom' ); ?>
	        
        <?php graphene_container_wrapper( 'end' ); ?>
    </div>
    <?php
}
endif;