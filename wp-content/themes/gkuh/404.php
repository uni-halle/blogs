<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="maincontainer">

						<main id="main" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<article class="custom404"> 
								<header class="article-header">

									<h1 class="page-title" itemprop="headline">
                                    404 - Kuhlossal verlaufen
                                    </h1>  

								</header> 

								<section class="entry-content cf" itemprop="articleBody">
                                   
                                       <img alt="Kuh Icon" class="img404" src="<?php echo get_template_directory_uri() ;?>/library/images/404cow.svg">
                                        
                                        <h2>Wirlich kuhrios: die gesuchte Seite ist nicht verfügbar. </h2>
                                        <p>Bestimmt hat Betty sie gegessen. Das tut uns sehr leid. Bitte schauen Sie sich doch mal im Menü oben um, ob sie dort nicht etwas Passendes finden. Auch unsere <a href="<?php echo home_url(); ?>">Startseite</a> ist immer einen Besuch wert.</p>

								</section> 

								<footer class="article-footer cf">
                                    <p><small>Icon by <a href="http://www.freepik.com" title="Freepik">Freepik</a> from <a href="http://www.flaticon.com" title="Flaticon">www.flaticon.com</a>, licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></small></p>
								</footer>

								<?php 
                                if( is_user_logged_in() ) { 
                                    comments_template();
                                }
                                ?>
                                

							</article>

						</main>
                    
				</div>

			</div>

<?php get_footer( nofooter ); ?>
