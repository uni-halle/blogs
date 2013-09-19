<div id="banner">
    <h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a></h1>
    <p id="description"><?php bloginfo('description'); ?></p>
</div>
<div id="searchpanel">
    <div class="feed">
        <a href="<?php bloginfo('rss2_url'); ?>" target="_blank"><span>Feed</span></a>
    </div>
    <div class="search">
        <form action="<?php bloginfo('home'); ?>/" method="post" id="searchform">
            <input type="text" value="" name="s" id="s" />
        </form>
    </div>
    <div class="clear"></div>
</div>