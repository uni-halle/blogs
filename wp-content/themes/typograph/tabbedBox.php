<div id="tabbed_box_1" class="tabbed_box">  

<!-- TABBED BOX MOOTOOLS from http://www.cssmenubuilder.com/tutorial-files/mootools-tabs/example -->

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/mootools/1.11/mootools-yui-compressed.js"></script>
<script type="text/javascript">

	window.addEvent('domready', function() {
		initTabs();
	});

	function initTabs() {
		$ES('a','tabMenu').each(function(el) {
			el.addEvent('click',function(e) {
				var ev = new Event(e).stop();
				tabState(el);
			});
		});
	}

	function tabState(ael) {
		$ES('a','tabMenu').each(function(el) {
			if(el.hasClass('active')) {
				el.removeClass('active');
			}
		});
		ael.addClass('active');
		$$('#tabContent div.content').each(function(el) {
			if(el.hasClass('active')) {
				el.removeClass('active');
			}
		});

		var ac = ael.getProperty('href');
		$(ac).addClass('active');
	}
</script>
 

<!-- END MOOTOOLS -->


<!-- MooTools tabbed box -->

	<div id="tabContainer">
		<div id="tabMenu">
			<ul>
				<li><a href="menu" class="active">Menu</a></li>
				<li><a href="categories">Categories</a></li>
				<li><a href="about">About</a></li>
				<li><a href="rss">RSS</a></li>
				<li><a href="search">Search</a></li>
			</ul>
		</div>
		<div id="tabContent">
			<div id="menu" class="content active">
				<ul>  
					<li><a href="<?php bloginfo('url'); ?>">Home</a></li>
					<?php wp_list_pages('title_li='); ?>
             	</ul>
			</div>
			<div id="categories" class="content">
				<ul>
					<?php list_cats(); ?>
				</ul>
			</div>
			<div id="about" class="content">
				<h4>Typograph WordPress Theme</h4>
				<p><a href="http://blog.pinkandyellow.com/free-wordpress-themes/typograph/" title="Typograph theme home">Typograph</a> is a standards compliant theme with a JQuery powered tabbed sidebar box and an ad under the first post on the index page. This theme has no images and is purely based on CSS elements and typography. Ideal for future customization. Typograph was styled "from the ground up" on a highly customized version of the <a href="http://wordpress.org/extend/themes/sandbox#post-35">Sandbox theme</a>.</p>
    			<p>Designed by Morten Rand-Hendriksen - designer, information philosopher and author based out of Burnaby, BC.</p> 
				<p>You can change the contents of the tabbed box by editing the tabbedBox.php file located in the Typograph theme directory.</p>
			</div>
			<div id="rss" class="content">
				<h1><a href="<?php bloginfo('url'); ?>/rss" title="Subscribe to the <?php bloginfo('name') ?> RSS Feed" rel="rss">Subscribe to my feed</a></h1>				
			</div>
			<div id="search" class="content">
				<form id="searchBox" method="get" action="<?php bloginfo('home') ?>">
					<div>
						<input id="s" name="s" type="text" value="<?php echo wp_specialchars(stripslashes($_GET['s']), true) ?>" size="44" tabindex="8" />
						<input id="searchsubmit" name="searchsubmit" type="submit" value="<?php _e('Find') ?>" tabindex="9" />
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="clear"></div> 

<!-- END MooTools tabbed box -->

 </div>  

