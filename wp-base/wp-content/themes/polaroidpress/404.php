<?php get_header(); ?>

<div id="content">
   	<h1>Whoops!</h1>
    <p>You were looking for something that wasn't there... Try this instead:</p>
    
	<?php /* run the url as a query */
    query_posts('s='. $search_term_q );
    ?>
    <?php if ( have_posts() ) :
    /* check to see if there are posts, before telling people something that isn't true */ ?>
    <ul>

    <?php /* start the loop */
		while ( have_posts() ) : the_post(); ?>
		<li><strong><a href="<?php the_permalink() ?>" title="<?php printf(__('Permalink to %s', 'sandbox'), wp_specialchars(get_the_title(), 1)) ?>" rel="bookmark"><?php the_title() ?></a></strong><br />
		<abbr title="<?php the_time('Y-m-d\TH:i:sO'); ?>"><?php unset($previousday); printf(__('%1$s', 'sandbox'), the_date('', '', '', false), get_the_time()) ?></abbr><br />
		<em>
		<?php printf(__('Posted in %s', 'sandbox'), get_the_category_list(', ')) ?>
		. &nbsp;
		<?php comments_popup_link(__('Comments (0)', 'sandbox'), __('Comments (1)', 'sandbox'), __('Comments (%)', 'sandbox')) ?>
		</em>
		</li>
		<?php /* end the loop */
		endwhile; ?>
    <?php /* close the list before closing the ifelse */ ?>
    <?php else : endif; ?>
    </ul>

</div>

<?php get_sidebar() ?>
<?php get_footer(); ?>