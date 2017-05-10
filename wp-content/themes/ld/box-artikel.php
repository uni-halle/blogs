<div class="box">
    <div class="box-header with-border">
              <h3 class="box-title"><i class="ion ion-ios-pricetag-outline text-<?php echo (($fields['status'] == '1' && !$fields['entleiher']) ? 'green' : 'red') ?>" style="font-size: 140%; vertical-align:bottom; margin-right: 5px;"></i><b>
<?php
if ($fields['kategorie'] == 'Literatur') {
  echo $fields['titel'];
} else {
  echo $fields['bezeichnung'];
}
?>
            </b><small style="margin-left: 5px; text-transform: uppercase; letter-spacing: 1px;">#<?php echo $fields['artikelnummer']; ?><span class="text-red" style="margin-left: 5px;">
<?php
// Status
switch ($fields['status']) {
  case 1:
    // Entleiher
    if ($fields['entleiher']) {
      $entleiher = get_user_by('login', $fields['entleiher']);
      echo $entleiher->user_firstname . ' ' . $entleiher->user_lastname;
    }
    break;
  case 2:
    echo 'Reserviert';
    break;
  case 3:
    echo 'Gesperrt';
    break;
  case 4:
    echo 'Verloren';
    break;
}
?>
            </span></small></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php
              if ($fields['kategorie'] == 'Literatur') {
                // Autoren
                $autoren = array();
                foreach ($fields['autoren'] as $key => $value) { $autoren[] = $fields['autoren'][$key]['name']; }
                echo (count($autoren) > 1 ? 'Autoren' : 'Autor') . ' <b>' . implode(', ', $autoren) . '</b>';
                // Untertitel
                if ($fields['untertitel']) {
                  echo ' Untertitel <b>' . $fields['untertitel'] . '</b>';
                }
                // Erscheinungsjahr
                if ($fields['erscheinungsjahr']) {
                  echo ' Jahr <b>' . $fields['erscheinungsjahr'] . '</b>';
                }
                // Bibliografische Angaben
                foreach ($fields['bibliografischeangaben'] as $key => $value) {
                  echo ' ' . $fields['bibliografischeangaben'][$key]['attribut'] . ' <b>' . $fields['bibliografischeangaben'][$key]['wert'] . '</b>';
                };
              } else {
                // Zusatzinformationen
                foreach ($fields['zusatzinformationen'] as $key => $value) {
                  echo ' ' . $fields['zusatzinformationen'][$key]['attribut'] . ' <b>' . $fields['zusatzinformationen'][$key]['wert'] . '</b>';
                };
              }
              ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
              <div class="pull-right">
                <?php
                /*
                if (current_user_can('editor') || current_user_can('administrator')) {
                  echo '<a href="/wp-admin/post.php?post=' . get_the_ID() . '&action=edit" target="backend" class="btn btn-default btn-flat" role="button">Artikel bearbeiten</a>';
                }
                */
                if ($fields['status'] == '1') {
                  if (!$fields['entleiher']) {
                    echo '<a href="/checkout?i=' . $fields['artikelnummer'] . '&c=1" class="btn btn-default btn-flat" role="button">Ausleihen</a>';
                  } else {
                    $user = wp_get_current_user();
                    echo '<a href="/checkin?i=' . $fields['artikelnummer'] . '&c=1" class="btn btn-default btn-flat' . ($fields['entleiher'] == $user->user_login ? ' disabled' : '') . '" role="button">Zurücknehmen</a>';
                  }
                } else {
                  echo '<a href="/checkout?i=' . $fields['artikelnummer'] . '&c=1" class="btn btn-default btn-flat disabled" role="button">Ausleihen</a>';
                }
                ?>
              </div>
            </div>
          </div>
