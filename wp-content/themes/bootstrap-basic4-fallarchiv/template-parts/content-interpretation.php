<?php
/**
 * Display the post content in "generic" or "standard" format.
 * This will be use in the loop and full page display.
 * 
 * @package bootstrap-basic4
 */


$Bsb4Design = new \BootstrapBasic4\Bsb4Design();
?> 
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<!--
    <div class="entry-meta"> 
    	<span style="float:right">      
	        <div class="klein"><?php if($format = get_the_term_list( $post->ID, 'format', ' ', ', ' )):?><?=$format?> <?php endif ?></div>
	    </span>
   </div> 
       <br>
-->
    <header class="entry-header"> 
    <h1 class="entry-title">
	       <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a> 
	    </h1>
        <?php if ('post' == get_post_type()) { ?> 
        <?php if( !post_password_required( $post )): ?>
        	<div class="entry-meta">
<!--             <?php $Bsb4Design->postOn(); ?>  -->
<?php
                /* translators: used between list items, there is a space after the comma */
                $categories_list = get_the_category_list(__(', ', 'bootstrap-basic4'));
                if (!empty($categories_list)) {
            ?> 
            <span class="cat-links">
            <?php if( !post_password_required( $post )): ?>
                <?php $Bsb4Design->categoriesList($categories_list); ?>
			<?php endif ?>
            </span>
            <?php } // End if categories ?> 
			<div class="klein">
								
			</div>
        </div><!-- .entry-meta -->
        <?php endif ?>
        <hr />
        <?php } //endif; ?> 
    </header><!-- .entry-header -->

    <?php if (is_search()) { // Only display Excerpts for Search ?> 
    <div class="entry-summary">
        <?php the_excerpt(); ?> 
        <div class="clearfix"></div>
    </div><!-- .entry-summary -->
    <?php } else { ?> 
    <div class="entry-content">
        <?php the_content($Bsb4Design->continueReading(true)); ?> 
        <div class="clearfix"></div>
        <?php 
        /**
         * This wp_link_pages option adapt to use bootstrap pagination style.
         */
        wp_link_pages(array(
            'before' => '<div class="page-links">' . __('Pages:', 'bootstrap-basic4') . ' <ul class="pagination">',
            'after'  => '</ul></div>',
            'separator' => ''
        ));
        ?> 
    </div><!-- .entry-content -->
    <?php } //endif; ?> 

    <footer>
        <?php if ('post' == get_post_type()) { // Hide category and tag text for pages on Search ?> 
        <div class="entry-meta">        
			<span style="float:right">  
                <?php $hat_interpretation = get_field( 'hat_interpretation' );?>
                <span>
					<?php if( $hat_interpretation == true ) {?><i class="fa fa-flag" aria-hidden="true" title="Interpretation vorhanden"></i> <?php }?> 
                </span>
<!--
				<?php if (! post_password_required() && (comments_open() || '0' != get_comments_number())) { ?> 
                    <span><?php $Bsb4Design->commentsLink(); ?></span>
				<?php } //endif; ?>
-->
			</span>
        </div><!--.entry-meta-category-tag-->
        <?php } // End if 'post' == get_post_type() ?> 
        <?php $Bsb4Design->editPostLink(); ?>

    </footer><!-- .entry-meta -->
</article><!-- #post-## -->
<?php unset($Bsb4Design); ?> 