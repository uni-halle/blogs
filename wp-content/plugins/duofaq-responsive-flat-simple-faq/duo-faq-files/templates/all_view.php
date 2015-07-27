<?php ob_start(); ?>
<?php //var_dump($cat); ?>
<h2 id="faq-top"><?php echo $title; ?></h2>
<div class="smart_all_accordion accod_parent">
    <ul class="faq-labels">
        <?php foreach($cat as $item) { ?>
        <li><a href="#<?php echo str_replace(' ', '_', trim($item->slug)) ?>"><?php echo $item->name ?></a></li>
        <?php } ?>
    </ul>
<?php
    foreach($cat as $item) {
        $args = array(
            'post_type' => 'faq',
            'faq_categories' => $item->slug,
            'posts_per_page' => -1
        );
        $posts = array();
        $temp_posts = get_posts($args);
        foreach( $temp_posts as $post ){
            $meta = get_post_meta( $post->ID, 'faq_order_no', true );
            $post->order_no = $meta;
            array_push($posts, $post);
        }
        usort($posts, array($this, 'cmp_post'));
        //var_dump($posts);
?>
    <div class="faq_wrap_all">
        <h4 class="faq-cat-title" id="<?php echo str_replace(' ', '_', trim($item->slug)) ?>">
            <?php echo $item->name; ?>
            <span><a href="#faq-top"><?php _e( 'Go To Top', 'df' ) ?></a> </span>
        </h4>
        <div class="smartItems">
            <?php foreach($posts as $post) { ?>
            <h3 class="accordion_title"><?php echo $post->post_title; ?></h3>
            <div class="smartItemsDetails">
                <?php echo wpautop( $post->post_content ); ?>
            </div>
            <?php } ?>
        </div>
    </div>
        <?php
    }
?>
</div>
<?php $html .= ob_get_clean();