<div id="subcontent">

<div id="globalnav" class="box">
        <h3>Pages</h3>
        <ul class="link-list">
    <?php wp_list_pages('sort_column=menu_order&title_li=&depth=-1'); ?> 
        </ul>
</div>
    

<div id="cats" class="box">
        <h3>Categories</h3>
        <ul class="link-list">
			<?php wp_list_categories('show_count=0&title_li=&hierarchical=0'); ?>
        </ul>
    </div>
    
<div id="feeds" class="box">
        <h3>RSS Feeds</h3>
        <ul class="link-list">
          <li><a href="<?php bloginfo('rss2_url'); ?>">Posts</a></li>
          <li><a href="<?php bloginfo('comments_rss2_url'); ?>">Comments</a></li>
        </ul>
    </div>
    
<div id="blogroll" class="box">
        <h3>Blogroll</h3>
        <ul class="link-list">
        <?php wp_list_bookmarks('categorize=0&title_li=&orderby=rand&limit=10'); ?>
        </ul>
    </div>

</div>
