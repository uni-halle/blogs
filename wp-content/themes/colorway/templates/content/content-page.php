<div class="content-wrap">
    
    <div class="sl">
        <?php if (is_front_page()) { ?>
            <h2>
                <?php the_title(); ?>
            </h2>
        <?php } else { ?>
            <h1>
                <?php the_title(); ?>
            </h1>
        <?php } ?>
        <?php the_content(); ?>
    </div>
    <div class="folio-page-info">
    </div>
</div>
<div class="clear"></div>
