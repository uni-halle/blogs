<?php get_header() ?> 
<div class="container containercontent">

    <div class='span-11 prepend-1'>
    	<div class="content">
			<h3 class="posttitle">Oops... No Items Found</h3>
            <p>Would you like to return to the <a href="<?php bloginfo('url') ?>">homepage</a><br /> or do a search below?</p>
			<?php get_search_form(); ?>
        </div>
    </div>
    
    <div class='span-11 append-1 last'>
		<?php get_sidebar() ?>
    </div>
    
<?php get_footer() ?>