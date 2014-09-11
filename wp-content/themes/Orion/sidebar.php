<div class="five columns ">

<!-- Sponsoren weg, Auskommentierung löschen und php ohne Leerzeichen -->
<!-- <div id="right">
< !-- 125px banners -- >	
 < ? php include (TEMPLATEPATH . '/sponsors.php');  ? >	
->->
<!-- Sidebar widgets -->
<div class="sidebar">
<?php 
	get_post_custom($post->ID);
	$karte = get_post_meta($post->ID, 'kartenlink', true);
	$kartezeigen = get_post_meta($post->ID, 'karte_zeigen', true);
	$kontakte = get_post_meta($post->ID, 'kontakt', true);
	$ansprech = get_post_meta($post->ID, 'ansprechpartner', true);
	$comm_link = get_post_meta($post->ID, 'gemeinde_link', true);
	
/* Karte anzeigen, füllen? */	
	if($kartezeigen === "ja") {
          if (!empty($karte)){
                echo "<h3 class='k-heading'>Karte:</h3>";
                echo $karte;
                }
                
          else {
                echo "<h3 class='k-heading'>Karte:</h3>";
                echo "<p><a href='http://maps.uni-halle.de/index_religion.php' target='_self'><img src='https://glauben.uni-halle.de/files/2013/03/karte.png' alt='' /><br /> <span style='font-size: small'>Zur Übersicht aller Standorte</span></a></p>";
                }
        }

    if(!empty($comm_link)) {
	    echo "<h3 class='k-heading'>Finde&nbsp;deine&nbsp;Gemeinde</h3>";
	    echo "<div class='kontakt'>$comm_link</div>";
    }
       

/* Kontakt und Ansprechpartner anzeigen */
    

	if (!empty($kontakte)){
              echo "<h3 class='k-heading'>Kontakt</h3>
              <div class='kontakt'>$kontakte</div>";    
                 }
                 
    if (!empty($ansprech)){
	          echo "<h3 class='k-heading'>Ansprechpartner</h3>
	          <div class='kontakt'>$ansprech</div>";
          }
?>
<ul>
	<?php if ( function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar') ) : else : ?>
	<?php endif; ?>
</ul>
</div>
</div>

</div>