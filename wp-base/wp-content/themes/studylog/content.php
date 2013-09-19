<?php

/***************************FLASH VIEW*********************************/

if(!isset($_REQUEST['tb_content']) && !isset($_REQUEST['nofl'])){ ?> 
<?php get_sidebar(); ?>	
 	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
			id="flashui" width="100%" height="100%"
			codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">
			<param name="movie" value="<?php bloginfo('template_directory'); ?>/flash/study_log.swf" />
			<param name="quality" value="high" />
			<param name="bgcolor" value="#ffffff" />
			<param name="wmode" value="transparent" />
			<param name="width" value="100%" />			
			<param name="height" value="100%" />
			<param name="align" value="top" />
			<param name="FlashVars" value="postId=<?php print $_REQUEST['p']; ?>&blogUrl=<?php bloginfo('url'); ?>&diagonalMaxLogEntryDistanceFromNeighbour=50&verticalMaxLogEntryDistanceFromNeighbour=10&verticalMiddleScreenMaxLogEntryDistanceFromNeighbour=80&thickboxWidth=500&randomAberrationPixelInListPresentation=10&circleDistanceFromCenterY=-10&circleDistanceFromCenterX=-50&circleRadiusProportionalToWidth=5&textIconWidth=25&dropShadowDistance=2.5&latestEntriesAmount=15&lognameCharLength=55&thickbox=true&thickboxHeight=550&motionSpeed=18&thumbWidth=50&applicationType=wordpress&workingDirectory=<?php bloginfo('template_directory'); ?>/&commsyXml=data/example_commsy.xml" />
			<param name="allowScriptAccess" value="sameDomain" />
			<embed src="<?php bloginfo('template_directory'); ?>/flash/study_log.swf" quality="high" bgcolor="#ffffff"
				width="100%" height="100%" name="flashui" align="top"
				play="true"
				FlashVars="postId=<?php print $_REQUEST['p']; ?>&blogUrl=<?php bloginfo('url'); ?>&diagonalMaxLogEntryDistanceFromNeighbour=50&verticalMaxLogEntryDistanceFromNeighbour=10&verticalMiddleScreenMaxLogEntryDistanceFromNeighbour=80&thickboxWidth=500&randomAberrationPixelInListPresentation=10&circleDistanceFromCenterY=-10&circleDistanceFromCenterX=-50&circleRadiusProportionalToWidth=5&textIconWidth=25&dropShadowDistance=2.5&latestEntriesAmount=15&lognameCharLength=55&thickbox=true&thickboxHeight=550&motionSpeed=18&thumbWidth=50&applicationType=wordpress&workingDirectory=<?php bloginfo('template_directory'); ?>/&commsyXml=data/example_commsy.xml"
				loop="false"
				quality="high"
				wmode="transparent"
				allowScriptAccess="sameDomain"
				type="application/x-shockwave-flash"
				pluginspage="http://www.adobe.com/go/getflashplayer">
			</embed>
	</object>
	
<?php
} ?>

<?php if(isset($_REQUEST['p'])) print('<div id="wrapper">') ;?>

<?php
/***************************HTML VIEW*********************************/

if(isset($_REQUEST['nofl'])&&!isset($_REQUEST['p'])&&!isset($_REQUEST['m'])&&!isset($_REQUEST['tag'])) { ?>
<?php get_sidebar(); ?>	
<div id="htmlcontent">
	<?php if(!empty($_REQUEST['s'])) print ('<h1><span class="grey">Suche: </span>'.$s.'</h1>'); ?>	
	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>
					<div class="post" id="post-<?php the_ID(); ?>">
						<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', ''), the_title_attribute('echo=0')); ?>"><?php the_title(); ?></a></h2>

						<div class="postmetadata"><?php the_time(get_option('date_format'))  ?>, <?php the_author(); ?>	</div>

						<div class="entry">
							 <?php the_excerpt(); ?> 
							<?php /* the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); */?>	
						</div>
			<div class="tagscomments"><?php comments_number('Keine Kommentare','Ein Kommentar','% Kommentare'); ?><br /><?php the_tags(__('Tags:', '') . ' ', ', ', '<br />'); ?><?php edit_post_link(__('Bearbeiten', ''), '&rarr; ', ' '); ?>  </div>

					</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft" id="skip"><?php next_posts_link('&laquo; Ältere') ?></div>
			<div class="alignright" id="skip"><?php previous_posts_link('Neuere &raquo;') ?></div>
		</div>

	<?php else : ?>

	<h1>Kein Eintrag.</h1>

	<?php endif; ?>

	</div>
<?php } ?>

