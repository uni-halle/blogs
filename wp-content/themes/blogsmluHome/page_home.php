<?php 
/*
Template Name: Home
*/
get_header(); ?>

	<div id="content">
		<!--
		<div id="tweets">
			<h2>Gezwitscher <a class="follow twitter" href="https://twitter.com/mlublogs">Folge uns auf Twitter</a></h2>
		</div>
		
		<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>				
			
				<?php the_content(); ?>
				
		<?php endwhile; endif; ?>
		-->
		<h2>Blogs@MLU News</h2>
				
		<?php $posts = get_posts( "numberposts=5" ); ?>
		<?php if( $posts ) : ?>
		
		<ul class="newslist">
			
			<?php foreach( $posts as $post ) : setup_postdata( $post ); ?>
			
			<li>
				<?php post_images('1', '50', '50', '', false); ?>
				
				<span class="alignleft">
					<h3><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title();?></a></h3>
					<?php trim_excerpt(18); ?>
				</span>
			</li>
			
			<?php endforeach; ?>
			
			<li><a class="more-link button small" href="blog/">Alle News <span class="amp">&amp;</span> Anleitungen im Haupt-Blog</a></li>
		
		</ul>
		
		<?php endif; ?>
		
	</div><!-- end #content -->

	<div id="sidebar" class="actions">
		
		<div class="sidelist">
			<h2><a href="blog/" title="blogs@MLU Haupt-Blog">blogs@MLU Status &raquo;</a></h2>
			<ul id="status">
			
				<!--
				<li class="status-light aufbau"></li>
				<li class="status-message">Der Dienst befindet sich im Aufbau.</li>
				<li class="status-link"><a class="more-link" href="/blog" title="blogs@URZ News">blogs@URZ News</a></li>
				-->
				
				<li class="status-light green"></li>
				<li class="status-message">Alles im gr&uuml;nen Bereich.</li>
	
				<!--
				<li class="status-light yellow"></li>
				<li class="status-message">Eingeschr&auml;nkte Funktionen.</li>
				<li class="status-link"><a class="more-link" href="/blog" title="blogs@URZ News">blogs@URZ News</a></li>
				-->
	
				<!--
				<li class="status-light red"></li>
				<li class="status-message">Der Dienst ist nicht verf&uuml;gbar.</li>
				<li class="status-link"><a class="more-link" href="/blog" title="blogs@URZ News">blogs@URZ News</a></li>
				-->
		
			</ul>
			<p>Aktuelle Neuigkeiten zum Status des Blog-Dienstes erf&auml;hrst du im <a title="Blogs@MLU Hauptblog" rel="me" href="/blog">Hauptblog</a>.</p>
			<!--, &uuml;ber <a rel="me" href="https://twitter.com/mlublogs">Twitter</a> oder auf unserer <a rel="me" href="http://www.facebook.com/pages/blogsURZ/156259138175">Facebook Seite</a>-->
		</div>
		
		<div class="sidelist">
			<h2><a href="http://blogportal.urz.uni-halle.de/" title="blogs@MLU Portal">blogs@MLU Portal &raquo;</a></h2>
			<ul>
				<li>Beitr&auml;ge von <a href="http://blogportal.urz.uni-halle.de/alleblogs" title="Blogs aus dem Umfeld der Universit&auml;t">Blogs aus dem Umfeld der Universit&auml;t</a> werden im Portal eingebettet in die Universit&auml;ts-Homepage vom blogs@MLU-Team ausgew&auml;hlt und <a href="http://blogportal.urz.uni-halle.de/" title="blogs@MLU Portal">pr&auml;sentiert.</a></li>
				<li><a class="button small" href="http://blogportal.urz.uni-halle.de/" title="blogs@MLU Portal">blogs@MLU Portal</a></li>
			</ul>
		</div>
		
		<div class="sidelist tutorials">		
			<h2>Frische Anleitungen</h2>
			<ul>
		
			<?php $my_query = new WP_Query('category_name=Anleitungen&showposts=5'); ?>
		
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			
				<li><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title(); ?>"><?php the_title();?></a>				</li>
			
			<?php endwhile;?>
				<li><a href="/blog/category/anleitungen/" title="Alle blogs@MLU Anleitungen ansehen">[ Alle Anleitungen... ]</a></li>
			</ul>
			
		</div>		
		
	</div><!-- end #sidebar -->
		
		<div id="project" class="grey-box">
		
			<div class="column-left">
				
				<h3>Powered By WordPress</h3>

				<p>Der Blogs@MLU Dienst basiert auf der Multisite Variante der weit verbreiteten und leicht zu bedienenden Blog-Software <a title="Wordpress.org" href="http://wordpress.org">WordPress</a>.
				<a href="http://wordpress.org" title="WordPress"><img src="<?php bloginfo('template_url'); ?>/style/images/wordpress-logo.png" alt="wordpress-logo" width="220" height="64"/></a>
				</p>
				
			</div>

			<div class="column-mid">
			
				<h3>Features</h3>
				
				<p>Eine Übersicht zum Blog-Dienst der <acronym title="Martin-Luther-Universit&auml;t">MLU</acronym> und dessen Features findest du in der <a href="/dienst/" title="Blogs@MLU Dienst Features">Dienst&uuml;bersicht</a>.
				<a class="more-link button small" href="/dienst/" title="Blogs@MLU Dienst Features">Dienst</a>
				</p>
				
				<h3>Nutzungsbedingungen</h3>
				
				<p>Für die Nutzung der Blogs gilt die <a href="http://www.urz.uni-halle.de/ordnungen/benutzungsordnung_universitaetsre/" title="Benutzungsordnung des Universit&auml;tsrechenzentrums">Benutzungsordnung des Rechenzentrums</a>. Dar&uuml;ber hinaus gelten unsere zus&auml;tzlichen <a href="http://blogs.urz.uni-halle.de/dienst/nutzungsbedingungen/" title="Blogs@MLU Nutzungsbedingungen">Nutzungsbedingungen</a>.
				<a class="more-link button small" href="http://blogs.urz.uni-halle.de/dienst/nutzungsbedingungen/" title="Blogs@MLU Nutzungsbedingungen">Nutzungsbedingungen</a>
				</p>			
			</div>
		
			<div class="column-right">
				<h3>Kontakt</h3>
				<ul>
					<li><h4>blogs@MLU Team</h4></li>
					<li><a class="email" href="/kontakt" title="blogs@MLU-Team kontaktieren">Kontaktformular</a></li>
					<li><a class="email" href="mailto:blogs@urz.uni-halle.de" title="blogs@MLU-Team per E-Mail kontaktieren">blogs@urz.uni-halle.de</a></li>
					<!--<li><a class="twitter" href="https://twitter.com/mlublogs" title="Twitter" rel="me">Twitter</a></li>
					<li><a class="facebook" href="http://www.facebook.com/pages/blogsURZ/156259138175" title="Facebook Seite" rel="me">Facebook</a></li>-->
				</ul>
				<ul>
					<li><h4>Projektverantwortlicher</h4></li>
					<li>Robert J&auml;ckel</li>
					<li><a class="email" href="mailto:blogs@urz.uni-halle.de" title="blogs@LMU-Team kontaktieren">blogs@urz.uni-halle.de</a></li>
				</ul>
				<ul>
					<li><h4>Design</h4></li>
					<li>Matthias Kretschmann</li>
					<li><a class="url" href="http://kremalicious.com/blog" title="kremalicious Blog">Blog</a></li>
					<li><a class="twitter" href="https://twitter.com/kremalicious" title="Twitter">Twitter</a></li>
				</ul>
	
			</div>
	
		</div><!-- END #project -->

<?php get_footer(); ?>
