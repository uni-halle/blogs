<?php
// Standardrouten entfernen
remove_action('rest_api_init', 'create_initial_rest_routes', 99);
remove_action('rest_api_init', 'wp_oembed_register_route');

// Javascript für das Backend
function backendFormJavascript() {
?>
<script type="text/javascript">
// Internationale Standardbuchnummer prüfen
function isIsbnNumber (isbn) {
  var isbn = isbn.replace(/[^\dX]/gi, '');
	if (isbn.length == 10) {
		var chars = isbn.split('');
	  if(chars[9].toUpperCase() == 'X'){
	    chars[9] = 10;
	  }
	  var sum = 0;
	  for (var i = 0; i < chars.length; i++) {
	    sum += ((i+1) * parseInt(chars[i]));
	  };
		return ((sum % 11) == 0);
	} else if (isbn.length == 13) {
		var chars = isbn.split('');
	  var sum = 0;
	  for (var i = 0; i < chars.length; i++) {
			sum += ((i+1) % 2 == 0 ? (parseInt(chars[i]) * 3) : parseInt(chars[i]) );
	  };
		return ((sum % 10) == 0);
  } else {
		return false;
	}
};
(function($) {
  // Posttitel ausblenden
  $("body.post-type-post #titlediv").hide();
  // Vorauswahl für Ort treffen
  $( "#acf-field_58986d3f50e66" ).change(function() {
    if ($(this).val() == "Literatur") {
      $("#acf-field_589a1ddd19be0").val("Bibliothek");
    }
    if ($(this).val() == "Technik") {
      $("#acf-field_589a1ddd19be0").val("Technikerraum");
    }
    if ($(this).val() == "Sonstiges") {
      $("#acf-field_589a1ddd19be0").val("Sekretariat");
    }
  });
  // Bibliografische Angaben abrufen
  $( "#acf-field_58986f53bb913" ).change(function() {
    var isbn = $(this).val().replace(/[^\dX]/gi, '');
		if (isIsbnNumber(isbn)) {
      ($(this).val() != isbn ? $(this).val(isbn) : '');
      var api = "https://www.googleapis.com/books/v1/volumes?q=isbn:" + isbn;
      $.getJSON(api).done(function(data) {
        if (data.totalItems) {
          var book = data.items[0];
          if ( book["volumeInfo"]["title"] ) {
            if ( !$("#acf-field_58986e6250e67").val() ) {
              $("#acf-field_58986e6250e67").val(book["volumeInfo"]["title"]);
            }
          }
          if ( book["volumeInfo"]["subtitle"] ) {
            if ( !$("#acf-field_58986eaa50e68").val() ) {
              $("#acf-field_58986eaa50e68").val(book["volumeInfo"]["subtitle"]);
            }
          }
          if ( book["volumeInfo"]["authors"] ) {
            var fields = $('div[data-key="field_58986fa0217b0"] tr.acf-row td[data-key="field_58986ff3217b1"] input').not('div[data-key="field_58986fa0217b0"] tr.acf-clone td[data-key="field_58986ff3217b1"] input');
            for (i = fields.length; i < book["volumeInfo"]["authors"].length; i++) {
              $('div[data-key="field_58986fa0217b0"] a.acf-button.acf-repeater-add-row').trigger('click');
            }
            fields = $('div[data-key="field_58986fa0217b0"] tr.acf-row td[data-key="field_58986ff3217b1"] input').not('div[data-key="field_58986fa0217b0"] tr.acf-clone td[data-key="field_58986ff3217b1"] input');
            for (i = 0; i < fields.length; i++) {
              var id = fields[i].getAttribute('id');
              if ( !$('#' + id).val() ) {
                $('#' + id).val(book["volumeInfo"]["authors"][i]);
              }
            }
          }
          if ( book["volumeInfo"]["publishedDate"] ) {
            if ( !$("#acf-field_589a653a6dce2").val() ) {
              $("#acf-field_589a653a6dce2").val(book["volumeInfo"]["publishedDate"].substr(0,4));
            }
          }
        }
      });
    }
	});
})(jQuery);
</script>
<?php
}
add_action('acf/input/admin_footer', 'backendFormJavascript');

// Automatischer Posttitel
function autoPostTitle($title) {
  global $post;
  if (empty($_POST['post_title']) && get_post_type($post->ID) == 'post') {
    $title = '#' . postIdentifierToArticleNumber($post->ID);
  }
  return $title;
}
add_filter('title_save_pre','autoPostTitle');

// Artikelnummer speichern
function addArticleNumber(){
  global $post;
  $articleNumber = postIdentifierToArticleNumber($post->ID);
  update_field('field_58a4906b26cd5', $articleNumber, $post->ID);
}
add_action('save_post', 'addArticleNumber');

// Artikelnummer generieren
function postIdentifierToArticleNumber ($postIdentifier) {
  $postIdentifier = preg_replace('/[^\d]/', '', $postIdentifier);
  $chars = str_split($postIdentifier, 1);
  $sum = 0;
  for ($i = 0; $i < count($chars); $i++) {
    $sum += (($i + 3) * $chars[$i]);
  }
  $check = (10 - ($sum % 10)) % 10;
  return $postIdentifier . $check;
}

// Artikelnummer prüfen
function isValidArticleNumber ($articleNumber) {
  $articleNumber = preg_replace('/[^\d]/', '', $articleNumber);
  $chars = str_split($articleNumber, 1);
  $digit = $chars[count($chars) - 1];
  $sum = 0;
  for ($i = 0; $i < (count($chars) - 1); $i++) {
    $sum += (($i + 3) * $chars[$i]);
  }
  $check = (10 - ($sum % 10)) % 10;
  return ($check == $digit ? true : false);
}

// Artikelnummer auflösen
function articleNumberToPostIdentifier ($articleNumber) {
  $articleNumber = preg_replace('/[^\d]/', '', $articleNumber);
  if (isValidArticleNumber($articleNumber)) {
    return substr($articleNumber, 0, -1);
  }
}

// Beiträge in Artikel umbenennen
function renamePostLabels() {
    global $wp_post_types;
    $labels = &$wp_post_types['post']->labels;
    $labels->name = 'Artikel';
    $labels->singular_name = 'Artikel';
		$labels->menu_name = 'Artikel';
    $labels->name_admin_bar = 'Artikel';
    $labels->add_new = 'Erstellen';
    $labels->add_new_item = 'Neuen Artikel erstellen';
    $labels->edit_item = 'Artikel bearbeiten';
    $labels->new_item = 'Neuer Artikel';
    $labels->view_item = 'Artikel anzeigen';
    $labels->search_items = 'Artikel durchsuchen';
    $labels->not_found = 'Keine Artikel gefunden.';
    $labels->not_found_in_trash = 'Keine Artikel im Papierkorb gefunden.';
    $labels->all_items = 'Alle Artikel';
}
add_action( 'init', 'renamePostLabels' );

// Aktionen aus Artikelliste entfernen
function removeListActions( $actions ) {
	unset($actions['inline hide-if-no-js']);
	unset($actions['edit_as_new_draft']);
	return $actions;
}
add_filter( 'post_row_actions' , 'removeListActions' , 10, 1);

// Überflüssige Tabellenspalten im Backend entfernen
function removeDefaultColumns($defaults) {
  unset($defaults['categories']);
  unset($defaults['comments']);
  unset($defaults['tags']);
  unset($defaults['date']);
  return $defaults;
}
add_filter('manage_post_posts_columns', 'removeDefaultColumns');

// Neue Spalten anlegen
function newColumnHeads($defaults) {
    $defaults['kategorie'] = 'Kategorie';
    $defaults['bezeichnung'] = 'Bezeichnung';
    $defaults['titel'] = 'Titel';
    $defaults['untertitel'] = 'Untertitel';
    return $defaults;
}
add_filter('manage_posts_columns', 'newColumnHeads');

// Die neuen Spalten auch mit Inhalt füllen
function newColumnContent($column, $postID) {
  if ($column == 'kategorie') {
    $kategorie = get_post_meta($postID, 'kategorie', 1);
    if ($kategorie) {
      echo $kategorie;
    }
  }
  if ($column == 'bezeichnung') {
    $bezeichnung = get_post_meta($postID, 'bezeichnung', 1);
    if ($bezeichnung) {
      echo $bezeichnung;
    }
  }
  if ($column == 'titel') {
    $titel = get_post_meta($postID, 'titel', 1);
    if ($titel) {
      echo $titel;
    }
  }
  if ($column == 'untertitel') {
    $untertitel = get_post_meta($postID, 'untertitel', 1);
    if ($untertitel) {
      echo $untertitel;
    }
  }
}
add_action('manage_posts_custom_column', 'newColumnContent', 10, 2);

// Einige der neuen Spalten als sortierbar kennzeichnen
function makeColumnsSortable($sortable_columns) {
  $sortable_columns['kategorie'] = 'kategorie';
    return $sortable_columns;
}
add_filter('manage_edit-post_sortable_columns', 'makeColumnsSortable');

// Die als "sortierbar" gekennzeichneten Spalten sortierbar machen
function sortColumns($query) {
  if ($query->is_main_query() && ($orderby = $query->get('orderby'))) {
    switch($orderby) {
      case 'kategorie':
        $query->set( 'meta_key', 'kategorie' );
        $query->set( 'orderby', 'meta_value' );
        break;
    }
  }
}
add_action('pre_get_posts', 'sortColumns', 1);

?>
