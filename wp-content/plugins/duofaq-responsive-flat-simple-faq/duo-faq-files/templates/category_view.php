<?php ob_start(); ?>

    <?php if( trim( $title ) != "" ) { ?>
    <h2><?php echo $title; ?></h2>
    <?php } ?>

<?php
    $args = array(
        'post_type' => 'faq',
        'faq_categories' => $cat->slug,
        'posts_per_page' => -1
    );
    //$posts = get_posts($args);
    $posts = array();
    $temp_posts = get_posts($args);
    foreach( $temp_posts as $post ){
        $meta = get_post_meta( $post->ID, 'faq_order_no', true );
        $post->order_no = $meta;
        array_push($posts, $post);
    }
    usort($posts, array($this, 'cmp_post'));
?>
<div class="smart_accordion accod_parent faq_wrap_all smartItems">
    <?php foreach($posts as $post) { ?>

    <h3 class="accordion_title"><?php echo $post->post_title; ?></h3>
    <div class="smartItemsDetails">
        <?php echo wpautop( $post->post_content ); ?>
    </div>

    <?php } ?>
</div>
<?php $html .= ob_get_clean();