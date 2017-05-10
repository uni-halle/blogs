<?php get_header(); ?>

<?php
global $wpdb;
$item = $_GET['i'];
$confirm = $_GET['c'];
$user = wp_get_current_user();

if ($item) {
  // Der Artikel hat eine fehlerfreie Artikelnummer
  if (isValidArticleNumber($item)) {
    // Der Artikel existiert in der Datenbank
    if (get_post_type(articleNumberToPostIdentifier($item)) == 'post') {
      $args = array (
    		'posts_per_page' => -1,
    		'include' => articleNumberToPostIdentifier($item)
    		);
    	$posts = get_posts( $args );
    	foreach ( $posts as $post ) {
    		setup_postdata ( $post );
    		$fields = get_fields();
        // Der Artikel ist momentan ausgeliehen
        if ($fields['entleiher']) {
          // Der Artikel wird von jemand anderem zurückgenommen
          if ($fields['entleiher'] != $user->user_login) {
            $entleiher = get_user_by('login', $fields['entleiher']);
            // Die Bestätigung ist nicht mehr nötig
            if (!$confirm) {
            // Der Artikel wurde erfolgreich zurückgenommen
            if (update_field('field_58a3372e29a41', '')) {
              $text = 'Du hast den Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> von <b>';
              $text .= $entleiher->user_firstname . ' ' . $entleiher->user_lastname;
              $text .= '</b> zurückgenommen.';
              $text .= '<div><a href="/checkout?i=' . $fields['artikelnummer']. '&c=1" class="btn btn-default btn-flat" role="button" style="color: #333333; text-decoration: none">Selbst ausleihen</a></div>';
              $feedback = array(
                'flavor' => 'success',
                'callout' => 'Erfolgreich',
                'text' => $text
              );
              // Den Verlauf aktualisieren
              if (have_rows('field_589a259b3130a')) {
                $verlauf = $fields['verlauf'];
              } else {
                $verlauf = array();
              }
              $verlauf[] = array(
                'field_589a25d33130b'	=> 'checkin',
                'field_589a26073130c'	=> time(),
                'field_589a26813130d'	=> $user->user_login
              );
              if (!update_field('field_589a259b3130a', $verlauf)) {
                $text = 'Du hast den Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> von <b>';
                $text .= $entleiher->user_firstname . ' ' . $entleiher->user_lastname;
                $text .= '</b> zurückgenommen, aber der Verlauf konnte nicht aktualisiert werden.';
                $feedback = array(
                  'flavor' => 'warning',
                  'callout' => 'Warnung',
                  'text' => $text
                );
              }
            } else {
              $text = 'Der Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> wurde noch nicht zurückgebucht, da ein technischer Fehler aufgetreten ist. Der Artikel ist weiterhin von <b>';
              $text .= $entleiher->user_firstname . ' ' . $entleiher->user_lastname;
              $text .= '</b> ausgeliehen.';
              $feedback = array(
                'flavor' => 'danger',
                'callout' => 'Fehler',
                'text' => $text
              );
            }
            } else {
              $text = 'Nimmst du den Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> von <b>';
              $text .= $entleiher->user_firstname . ' ' . $entleiher->user_lastname;
              $text .= '</b> zurück?';
              $text .= '<div><a href="/checkin?i=' . $fields['artikelnummer']. '" class="btn btn-default btn-flat" role="button" style="color: #333333; text-decoration: none">Bestätigen</a></div>';
              $feedback = array(
                'flavor' => 'info',
                'callout' => 'Rückfrage',
                'text' => $text
              );
            }
          } else {
            $text = 'Du kannst den von dir ausgeliehenen Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> nicht selbst zurückgeben. Ein anderer Benutzer muss diesen Artikel von dir zurückzunehmen.';
            $feedback = array(
              'flavor' => 'danger',
              'callout' => 'Fehler',
              'text' => $text
            );
          }
        } else {
          $text = 'Der Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> wurde noch nicht ausgeliehen, du kannst ihn deshalb nicht zurücknehmen.';
          $feedback = array(
            'flavor' => 'danger',
            'callout' => 'Fehler',
            'text' => $text
          );
        }
      }
    	wp_reset_postdata();
    } else {
      $text = 'Die Artikelnummer <b>' . $item . '</b> ist unbekannt.';
      $feedback = array(
        'flavor' => 'danger',
        'callout' => 'Fehler',
        'text' => $text
      );
    }
  } else {
    $text = 'Die Artikelnummer <b>' . $item . '</b> ist falsch.';
    $feedback = array(
      'flavor' => 'danger',
      'callout' => 'Fehler',
      'text' => $text
    );
  }
}
$result = $wpdb->get_results(
"
  SELECT COUNT(a.id) AS meine
  FROM $wpdb->posts a, $wpdb->postmeta b
  WHERE a.id = b.post_id
  AND a.post_type = 'post'
  AND a.post_status = 'publish'
  AND b.meta_key = 'entleiher'
  AND b.meta_value = '" . $user->user_login . "'
"
);
$statistics['meine'] = $result[0]->meine;
?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Rücknahme
  </h1>
</section>

<!-- Main content -->
<section class="content">

<?php
if ($feedback) {
  echo '<div class="callout callout-' . $feedback['flavor'] . '">';
  echo '<h4>' . $feedback['callout'] . '</h4>';
  echo '<p>' . $feedback['text'] . '</p>';
  echo '</div>';
}
?>

<div class="row">
    <div class="col-md-4 col-xs-12">
              <div class="info-box">
                <a href="/meine"><span class="info-box-icon bg-light-blue"><i class="ion ion-ios-person-outline"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text text-light-blue">Von mir ausgeliehen</span>
                    <span class="info-box-number text-light-blue" style="font-size: 350%; line-height: 60px; font-weight: 100;"><?php echo $statistics['meine']; ?></span>
                  </div>
                </a>
                <!-- /.info-box-content -->
              </div>
              <!-- /.info-box -->
            </div>

            <div class="col-md-4 col-xs-12">
                      <div class="info-box">
                        <form role="form" method="get" action="/checkin">
                        <a href="/checkin"><span class="info-box-icon bg-light-blue"><i class="ion ion-ios-box-outline"></i></span></a>
                          <div class="info-box-content">
                            <span class="info-box-text text-light-blue">Rücknahme</span>
                            <div class="input-group">
                              <input type="text" name="i" placeholder="Nummer" class="form-control" style="padding: 0; border: 0; border-bottom: 1px solid #3c8dbc; height: auto; font-size:30px; font-weight: 100;">
                              <input type="hidden" name="c" value="1">
                            </div>
                          </div>
                        <!-- /.info-box-content -->
                      </form>
                      </div>
                      <!-- /.info-box -->
                    </div>
          </div>
<p class="text-muted">Du kannst einen von dir ausgeliehenen Artikel nicht selbst zurückgeben. Ein anderer Benutzer muss deinen Artikel zurückzunehmen.</p>
</section>
<!-- /.content -->

<?php get_footer(); ?>

<script>
$(function () {
/* Navigationsmenü */
$("ul.sidebar-menu li#checkin").addClass("active");
});
</script>
