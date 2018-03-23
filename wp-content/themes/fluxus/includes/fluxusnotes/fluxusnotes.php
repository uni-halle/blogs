<?php
add_action( 'admin_menu', 'add_fluxusnotes' );

function add_fluxusnotes() {
	add_menu_page( 
		'fluxus Notizen',
		'fluxus Notizen',
		'read',
		'fluxus-notice',
		'create_fluxusnotes'
	);
}

function create_fluxusnotes() { ?>

	<div class='wrap'>

	<h2>Theme fluxus – Notizen</h2>
	
	<h3>Erweiterte Galerietypen</h3>
	<p>
		Wähle beim einfügen einer Galerie im Inhaltsbereich zwischen zwei Typen: <strong>masonry</strong> oder <strong>slider</strong>
	</p>

	<h3>Imagelightbox</h3>
	<p>
		Bilder im Inhaltsbereich, die zur eigenen Mediendatei verlinkt sind, öffnen sich in einer Lightbox.
	</p>

	<h3>Liste aus Unterseiten</h3>
	<p>
		Erzeuge eine automatische Liste von aktuellen Unterseiten durch den Shortcode: <strong>[childlist]</strong><br />
		Die Unterseiten einer anderen Seite können aufgelistet werden durch: <strong>[childlist parent="SEITENNAME"]</strong>
	</p>

	<h3>Breadcrump-Navigation</h3>
	<p>
		Gib eine breadcrump-Navigation aus – Shortcode: <strong>[breadcrump]</strong>
	</p>

	<h3>Accordion</h3>
	<p>
		Umrahme einen beliebigen Inhaltsbereich durch die Shortcodes <strong>[acc-start]</strong> und <strong>[acc-end]</strong>. Dieser Bereich wird dann als aufklappbarer Bereich angezeigt.  <br />
		Stelle sicher, dass gleich nach dem [acc-start] eine Überschrift kommt (Überschrift 1-6). Diese Überschrift wird dann der Schalter zum aufklappen.<br />
	</p>

	</div>


<?php } ?>