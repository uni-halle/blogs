<?php
/**
 * The left sidebar.
 * 
 * @package bootstrap-basic4
 */
$bsb4Sidebars = new \BootstrapBasic4\Bsb4Sidebars();
// @Anke: hier die Klassen eintragen
$sidebarContainerClasses = 'col-md-4 col-xl-3';

?>
<?php if ($bsb4Sidebars->shouldShowFaelle($post->ID)) { ?>
  <div id="sidebar-left" class="<?php echo $sidebarContainerClasses; ?>">
    <aside id="sidebar-faelle">
      <div id="suchmaske">
        <?php //echo do_shortcode('[searchandfilter id="2103"]'); ?>
        <?php echo get_search_form(false); ?>
      </div>
    
      <div id="accordion" class="accordion">
        <div class="card">
          <div class="card-header" id="headingTags">
            <h1 class="mb-0 collapsed widget-title"  data-toggle="collapse" data-target="#collapseTags" aria-expanded="false" aria-controls="collapseTags">
              Schlagwörter
            </h1>
          </div>

          <div id="collapseTags" class="collapse" aria-labelledby="headingTags" data-parent="#accordion">
            <div class="card-body">
              <?php
              $widget = new WPCTC_Widget;
              $args = array(
                  'before_widget' => '',
                  'before_title' => '',
                  'after_title' => '',
                  'widget_id' => 'wpctc_post_' . get_the_ID(),
                  'after_widget' => '',
              );
              $instance = $widget->update(null, null);
              $instance['title'] = '';
              $instance['format'] = 'array';
              $instance['height'] = 250;
              $instance['radiusx'] = 1;
              $instance['radiusy'] = 1;
              $instance['radiusz'] = 1;
              $instance['smallest'] = 60;
              $instance['largest'] = 130;
              $instance['number'] = 30;
              $widget->widget($args, $instance);
              ?>

            </div>
            <div class="card-body">
              <?php echo do_shortcode('[searchandfilter id="2268"]'); ?>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header" id="headingFilter">
            <h1 class="mb-0 collapsed widget-title" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">Filter</h1>
          </div>
          <div id="collapseFilter" class="collapse" aria-labelledby="headingFilter" data-parent="#accordion">
            <div class="card-body">
              <?php echo do_shortcode('[searchandfilter id="2096"]'); ?>
              <div id="accordionFilter" class="accordion">   
                <div class="card">
                  <div class="card-header" id="headingArbeitsfelder">
                    <h2 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseArbeitsfelder" aria-expanded="false" aria-controls="collapseArbeitsfelder">filtern nach Arbeitsfeldern</h2>
                  </div>
                  <div id="collapseArbeitsfelder" class="collapse" aria-labelledby="headingArbeitsfelder" data-parent="#accordionFilter">
                    <div class="card-body">
                      <?php echo do_shortcode('[searchandfilter id="2097"]'); ?>
                      <div id="accordionArbeitsfelder" class="accordion">
                        <!-- Schule - Unterricht START -->
                        <div class="card">
                          <div class="card-header" id="headingSchuleUnterricht">
                            <h3 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseSchuleUnterricht" aria-expanded="false" aria-controls="collapseSchuleUnterricht">
                              Schule - Unterricht
                            </h3>
                          </div>
                          <div id="collapseSchuleUnterricht" class="collapse" aria-labelledby="headingSchuleUnterricht" data-parent="#accordionArbeitsfelder">
                            <div class="card-body">
                              <?php echo do_shortcode('[searchandfilter id="2016"]'); ?>
                            </div>
                          </div>
                        </div>
                        <!-- Schule - Unterricht END -->
                        <!-- Schule - außerunterrichtlicher Bereich START -->
                        <div class="card">
                          <div class="card-header" id="headingSchuleAusserUnterricht">
                            <h3 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseSchuleAusserUnterricht" aria-expanded="false" aria-controls="collapseSchuleAusserUnterricht">
                              Schule - außerunterrichtlicher Bereich
                            </h3>
                          </div>
                          <div id="collapseSchuleAusserUnterricht" class="collapse" aria-labelledby="headingSchuleAusserUnterricht" data-parent="#accordionArbeitsfelder">
                            <div class="card-body">
                              <?php echo do_shortcode('[searchandfilter id="2035"]'); ?>
                            </div>
                          </div>
                        </div>
                        <!-- Schule - außerunterrichtlicher Bereich END -->
                        <!-- außerschulische Kinder-/Jugendarbeit START -->
                        <div class="card">
                          <div class="card-header" id="headingAusserschulisch">
                            <h3 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseAusserschulisch" aria-expanded="false" aria-controls="collapseAusserschulisch">
                              außerschulische Kinder-/Jugendarbeit
                            </h3>
                          </div>
                          <div id="collapseAusserschulisch" class="collapse" aria-labelledby="headingAusserschulisch" data-parent="#accordionArbeitsfelder">
                            <div class="card-body">
                              <?php echo do_shortcode('[searchandfilter id="2045"]'); ?>
                            </div>
                          </div>
                        </div>
                        <!-- außerschulische Kinder-/Jugendarbeit END -->
                        <!-- Erwachsenenbildung START -->
                        <div class="card">
                          <div class="card-header" id="headingErwachsenenbildung">
                            <h3 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseErwachsenenbildung" aria-expanded="false" aria-controls="collapseErwachsenenbildung">
                              Lehrer*innenbildung
                            </h3>
                          </div>
                          <div id="collapseErwachsenenbildung" class="collapse" aria-labelledby="headingErwachsenenbildung" data-parent="#accordionArbeitsfelder">
                            <div class="card-body">
                              <?php echo do_shortcode('[searchandfilter id="2046"]'); ?>
                            </div>
                          </div>
                        </div>
                        <!-- Erwachsenenbildung END -->

                      </div>
                    </div>
                  </div>
                </div>
                <!-- + -->
                <div class="card">
                  <div class="card-header" id="headingThemen">
                    <h2 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseThemen" aria-expanded="false" aria-controls="collapseThemen">filtern nach Themenschwerpunkten</h2>
                  </div>
                  <div id="collapseThemen" class="collapse" aria-labelledby="headingThemen" data-parent="#accordionFilter">
                    <div class="card-body">
                      <?php echo do_shortcode('[searchandfilter id="2300"]'); ?>
                      <div id="accordionThemen" class="accordion">
                        <!-- Unterrichtseinstieg START -->
                        <div class="card">
                          <div class="card-header" id="headingUnterrichtseinstieg">
                            <h3 class="mb-0 collapsed">
                              Unterrichtseinstieg
                            </h3>
                          </div>
                          <div id="collapseUnterrichtseinstieg" class="collapse" aria-labelledby="headingUnterrichtseinstieg" data-parent="#accordionThemen">
                            <div class="card-body">
                              <?php echo do_shortcode('[searchandfilter id="2309"]'); ?>
                            </div>
                          </div>
                        </div>
                        <!-- Unterrichtseinstieg END -->
						<!-- Interaktion START -->
                        <div class="card">
                          <div class="card-header" id="headingInteraktion">
                            <h3 class="mb-0 collapsed">
                              Schüler*in-Schüler*in-Interaktion
                            </h3>
                          </div>
                          <div id="collapseInteraktion" class="collapse" aria-labelledby="headingInteraktion" data-parent="#accordionThemen">
                            <div class="card-body">
                              <?php echo do_shortcode('[searchandfilter id="2313"]'); ?>
                            </div>
                          </div>
                        </div>
                        <!-- Interaktion END -->
                        <!-- Nähe-Distanz START -->
                        <div class="card">
                          <div class="card-header" id="headingNaehedistanz">
                            <h3 class="mb-0 collapsed">
                              Nähe-Distanz
                            </h3>
                          </div>
                          <div id="collapseNaehedistanz" class="collapse" aria-labelledby="headingNaehedistanz" data-parent="#accordionThemen">
                            <div class="card-body">
                              <?php echo do_shortcode('[searchandfilter id="2312"]'); ?>
                            </div>
                          </div>
                        </div>
                        <!-- Nähe-Distanz END -->
                        <!-- Unterrichtsstörung START -->
                        <div class="card">
                          <div class="card-header" id="headingUnterrichtsstoerung">
                            <h3 class="mb-0 collapsed">
                              Unterrichtsstörung
                            </h3>
                          </div>
                          <div id="collapseUnterrichtsstoerung" aria-labelledby="headingUnterrichtsstoerung" data-parent="#accordionThemen">
                            <div class="card-body">
                              <?php echo do_shortcode('[searchandfilter id="2304"]'); ?>
                            </div>
                          </div>
                        </div>
                        <!-- Unterrichtsstoerung END -->
                        <!-- Feedback START -->
                        <div class="card">
                          <div class="card-header" id="headingFeedbackUndBewertung">
                            <h3 class="mb-0 collapsed">
                              Feedback und Bewertung
                            </h3>
                          </div>
                          <div id="collapseFeedback" class="collapse" aria-labelledby="headingFeedbackUndBewertung" data-parent="#accordionThemen">
                            <div class="card-body">
                              <?php echo do_shortcode('[searchandfilter id="2311"]'); ?>
                            </div>
                          </div>
                        </div>
                        <!-- Feedback END -->
                        <!-- Methoden START -->
                        <div class="card">
                          <div class="card-header" id="headingMethoden">
                            <h3 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseMethoden" aria-expanded="false" aria-controls="collapseMethoden">
                              spezifische Unterrichtsmethoden
                            </h3>
                          </div>
                          <div id="collapseMethoden" class="collapse" aria-labelledby="headingMethoden" data-parent="#accordionThemen">
                            <div class="card-body">
                              <?php echo do_shortcode('[searchandfilter id="2274"]'); ?>
                            </div>
                          </div>
                        </div>
                        <!-- Methoden END -->

                      </div>
                    </div>
                  </div>
                </div>

                <!-- + -->


                <div class="card">
                  <div class="card-header" id="headingWeitereFilter">
                    <h2 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseWeitereFilter" aria-expanded="false" aria-controls="collapseWeitereFilter">
                      weitere Filter
                    </h2>
                  </div>
                  <div id="collapseWeitereFilter" class="collapse" aria-labelledby="headingWeitereFilter" data-parent="#accordionFilter">
                    <?php echo do_shortcode('[searchandfilter id="2047"]'); ?>

                  </div>
                </div>


              </div>
            </div>
          </div>
          <!-- Interpretationen START -->
          <div class="card">
            <div class="card-header" id="headingInterpretationen">
              <h1 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseInterpretationen" aria-expanded="false" aria-controls="collapseInterpretationen">
                Interpretationen
              </h1>
            </div>
            <div id="collapseInterpretationen" class="collapse" aria-labelledby="headingInterpretationen" data-parent="#accordion">
              <div class="card-body">
                <?php
                echo do_shortcode('[searchandfilter id="2048"]');
                ?>
              </div>
            </div>
          </div>
          <!-- Interpretationen END -->
          <!-- Mitmachen START -->
          <div class="card">
            <div class="card-header" id="headingInterpretationen">
              <h1 class="mb-0 collapsed" data-toggle="collapse" data-target="#collapseMitmachen" aria-expanded="false" aria-controls="collapseMitmachen">
                Mitmachen
              </h1>
            </div>
            <div id="collapseMitmachen" class="collapse" aria-labelledby="headingMitmachen" data-parent="#accordion">
              <div class="card-body">
                <?php
                wp_nav_menu(array(
                    'menu' => 265 // Menu 'einreichen'
                ));
                ?>
              </div>
            </div>
          </div>
          <!-- Mitmachen END -->
          <div class="hidden">
            <?php echo do_shortcode('[searchandfilter id="2215"]'); ?>
          </div>
          </aside>
        </div>
      <?php } ?>

