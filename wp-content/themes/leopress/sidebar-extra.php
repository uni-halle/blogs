<div id="extracontent">
<div id="about" class="box">
    <img src="<?php bloginfo('template_directory'); ?>/img/profile.jpg" alt="" class="photo" />
        <h3>About</h3>
        <p><?php the_author_description(); ?></p>
</div>

<div id="recent" class="box">
        <h3>Recent Posts</h3>
        <ul class="link-list">
        <?php
$lastposts = get_posts('numberposts=15');
foreach($lastposts as $post) :
    setup_postdata($post);
    ?>
        <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
<?php endforeach; ?>
        </ul>
    </div>
    
</div>
