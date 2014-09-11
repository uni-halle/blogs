<?php
if(!isset($_REQUEST['nofl'])){
?>   
  <div id="sidebar">
		<ul>
		
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

			<!-- Author information is disabled per default. Uncomment and fill in your details if you want to use it.
			<li><h2>Author</h2>
			<p>A little something about you, the author. Nothing lengthy, just an overview.</p>
			</li>
			-->

		<?php endif; ?>
		<li class="widget widget_search">
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		</li>

		<li class="widget widget_history"><h2><a href="javascript:callStudyLogSortChronological()">Verlauf</a></h2>
		<div>
			<ul>
			<?php wp_get_archives("type=yearly"); ?>
			</ul>
			<select name="archive-dropdown" onChange='callStudyLogSortChronological(this.options[this.selectedIndex].value.substring((this.options[this.selectedIndex].value.indexOf("?m=")+3)));'> 
				<option value="" selected><?php echo attribute_escape(__('Monat')); ?></option>
				<?php wp_get_archives("type=monthly&format=option"); ?> 
			</select>	
		</div>
		</li>
		
		<li class="widget widget_alphabet"><h2><a href="javascript:callStudyLogSortAlphabetical()">Alphabet</a></h2>
		<div>
			<?php 
			foreach (range("a", "z") as $buchstabe) 
			{
			    echo ('<a class="alphabet" href="javascript:callStudyLogSortAlphabetical(\''.$buchstabe.'\')">'.$buchstabe.'</a>');
			}?>
		</div>
		</li>		
		<li class="widget widget_tag_cloud"><h2><a href="javascript:callStudyLogTagList()">Tags</a></h2>
		<div>
		<?php wp_tag_cloud("smallest=8&largest=16&number=45"); ?>
	
		</div>
		</li>
		<li class="widget widget_toggle_view">
		<div>
           <h2>Docuverser&nbsp;|&nbsp;<a href="?nofl=yes">Einfaches HTML</a></h2>
        </div>
		</li>


		</ul>

	
<?php
} ?>	
<?php
if(isset($_REQUEST['nofl'])){
?> 	
	
	<div id="sidebar">
		<ul>
		
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>

			<!-- Author information is disabled per default. Uncomment and fill in your details if you want to use it.
			<li><h2>Author</h2>
			<p>A little something about you, the author. Nothing lengthy, just an overview.</p>
			</li>
			-->

		<?php endif; ?>
		<li class="widget widget_search">
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
		</li>


		<li class="widget widget_history"><h2><a href="javascript:callStudyLogSortChronological()">Verlauf</a></h2>
		<div>
			<ul>
			<?php wp_get_archives("type=yearly"); ?>
			</ul>
			<select name="archive-dropdown" onChange="document.location.href=this.options[this.selectedIndex].value+'&nofl=yes';"> 
				<option value="" selected><?php echo attribute_escape(__('Monat')); ?></option>
				<?php wp_get_archives("type=monthly&format=option"); ?> 
			</select>	
		</div>
		</li>
		
		<li class="widget widget_alphabet"><h2><a href="<?php bloginfo('home'); ?>?orderby=title&order=asc">Alphabetisch</a></h2>
		<!--<div>
			<?php 
			foreach (range("a", "z") as $buchstabe) 
			{
			    echo ('<a class="alphabet" href="javascript:callStudyLogSortAlphabetical(\''.$buchstabe.'\')">'.$buchstabe.'</a>');
			}?>
		</div>-->
		</li>		
		<li class="widget widget_tag_cloud"><h2><a href="javascript:callStudyLogSortByTagName()">Tags</h2>
		<div>
		<?php wp_tag_cloud("smallest=8&largest=16"); ?>
		

		</div>
		</li>
		<li class="widget widget_toggle_view">
		<div>
           <h2><a href="?fl=yes">Docuverser</a>&nbsp;|&nbsp;Einfaches HTML</h2>
        </div>
		</li>

		</ul>
		
	
<?php
} ?>
	<div id="credits"><a href="http://wordpress.org/">WordPress</a> und *mms <a href="http:/mms.uni-hamburg.de/blogs/studyblog">study.log</a> Theme<br /><a href="<?php bloginfo('rss2_url'); ?>" title="<?php _e('Syndicate this site using RSS'); ?>"><?php _e('<abbr title="Really Simple Syndication">RSS</abbr>'); ?></a>&nbsp;|&nbsp;<a href="<?php bloginfo('comments_rss2_url'); ?>" title="<?php _e('The latest comments to all posts in RSS'); ?>"><?php _e('Comments <abbr title="Really Simple Syndication">RSS</abbr>'); ?></a><br />&raquo; <?php wp_loginout(); ?></div>

</div>