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
        // Der Artikel ist ausleihbar
        if ($fields['status'] == '1') {
          // Der Artikel ist verfügbar
          if (!$fields['entleiher']) {
            // Die Bestätigung ist nicht mehr nötig
            if (!$confirm) {
              // Der Artikel wurde erfolgreich ausgeliehen
              if (update_field('field_58a3372e29a41', $user->user_login)) {
                $text = 'Du hast den Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> jetzt ausgeliehen.';
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
                  'field_589a25d33130b'	=> 'checkout',
                  'field_589a26073130c'	=> time(),
                  'field_589a26813130d'	=> $user->user_login
                );
                if (!update_field('field_589a259b3130a', $verlauf)) {
                  $text = 'Du hast den Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> ausgeliehen, aber der Verlauf konnte nicht aktualisiert werden.';
                  $feedback = array(
                    'flavor' => 'warning',
                    'callout' => 'Warnung',
                    'text' => $text
                  );
                }
              } else {
                $text = 'Du hast den Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> noch nicht ausgeliehen, da ein technischer Fehler aufgetreten ist.';
                $feedback = array(
                  'flavor' => 'danger',
                  'callout' => 'Fehler',
                  'text' => $text
                );
              }
            } else {
              $text = 'Möchtest du den Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> ausleihen?';
              $text .= '<div><a href="/checkout?i=' . $fields['artikelnummer']. '" class="btn btn-default btn-flat" role="button" style="color: #333333; text-decoration: none">Bestätigen</a></div>';
              $feedback = array(
                'flavor' => 'info',
                'callout' => 'Rückfrage',
                'text' => $text
              );
            }
          } else {
            $text = 'Der Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> wurde bereits von ';
            if ($fields['entleiher'] == $user->user_login) {
              $text .= 'dir';
            } else {
              $entleiher = get_user_by('login', $fields['entleiher']);
              $text .= $entleiher->user_firstname . ' ' . $entleiher->user_lastname;
            }
            $text .= ' ausgeliehen.';
            $feedback = array(
              'flavor' => 'danger',
              'callout' => 'Fehler',
              'text' => $text
            );
          }
        } else {
          $text = 'Der Artikel <b>' . ($fields['kategorie'] == 'Literatur' ? $fields['titel'] : $fields['bezeichnung']) . '</b> wurde ';
          switch ($fields['status']) {
            case 2:
              $text .= 'reserviert';
              break;
            case 3:
              $text .= 'gesperrt';
              break;
            case 4:
              $text .= 'verloren';
              break;
          }
          $text .= '.';
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
    Ausleihe
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
                <form role="form" method="get" action="/checkout">
                <a href="/checkout"><span class="info-box-icon bg-light-blue"><i class="ion ion-ios-paperplane-outline"></i></span></a>
                  <div class="info-box-content">
                    <span class="info-box-text text-light-blue">Ausleihe</span>
                    <div class="input-group">
                      <input type="text" name="i" placeholder="Nummer" class="form-control" style="padding: 0; border:0; border-bottom: 1px solid #3c8dbc; height: auto; font-size:30px; font-weight: 100;">
                      <input type="hidden" name="c" value="1">
                    </div>
                  </div>
                <!-- /.info-box-content -->
              </form>
              </div>
              <!-- /.info-box -->
            </div>

          </div>
</section>
<!-- /.content -->

<?php get_footer(); ?>

<script>
$(function () {
/* Navigationsmenü */
$("ul.sidebar-menu li#checkout").addClass("active");
});
</script>
