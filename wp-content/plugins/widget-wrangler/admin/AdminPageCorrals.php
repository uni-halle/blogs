<?php
namespace WidgetWrangler;

/**
 * Class AdminPageCorrals
 * @package WidgetWrangler
 */
class AdminPageCorrals extends AdminPage {

	/**
	 * @see AdminPage::title()
	 */
    function title() {
        return __('Widget Corrals', 'widgetwrangler');
    }

	/**
	 * @see AdminPage::menuTitle()
	 */
    function menuTitle() {
	    return __('Corrals', 'widgetwrangler');
    }

	/**
	 * @see AdminPage::slug()
	 */
    function slug() {
        return 'corrals';
    }

	/**
	 * @see AdminPage::description()
	 */
	function description() {
		return array(
            __("A Corral is container for widgets. They are separate from sidebars to provide greater flexibility. For example you could have one or many Corrals within a theme sidebar.", 'widgetwrangler'),
            __("In general, you probably want at least one corral per theme sidebar.", 'widgetwrangler'),
		);
	}

	/**
	 * @see AdminPage::actions()
	 */
	function actions() {
        return array(
            'create' => array( $this, 'actionCreate' ),
            'delete' => array( $this, 'actionDelete' ),
            'update' => array( $this, 'actionUpdate' ),
            'sort' => array( $this, 'actionReorder' ),
        );
    }

	/**
     * Create corral form.
     *
	 * @return string
	 */
	function formCreate() {
	    $form = new Form(array(
            'style' => 'table',
            'action' => $this->actionPath('create'),
            'fields' => array(
                'submit' => array(
                    'type' => 'submit',
                    'value' => __('Create Corral', 'widgetwrangler'),
                    'class' => 'ww-pull-right button button-primary button-large',
                ),
                'ww-new-corral' => array(
                    'type' => 'text',
                    'title' => __('Corral Name', 'widgetwrangler'),
                    'class' => 'regular-text',
                )
            )
        ));

	    return $form->render();
    }

	/**
	 * Create a new corral.
	 *
	 * @return array
	 */
	function actionCreate() {
		if ( empty( $_POST['ww-new-corral'] ) ) {
			return $this->error( __('Error: No corral name given.', 'widgetwrangler') );
        }

        Corrals::add( $_POST['ww-new-corral'] );

        return $this->result(__('New corral created: ', 'widgetwrangler') . $_POST['ww-new-corral'] );
	}

	/**
	 * Sortable corrals form.
     *
	 * @param $corrals
	 *
	 * @return string
	 */
	function formReorder($corrals) {
	    ob_start();
	        ?><ul id='ww-corrals-sort-list'><?php
            $weight = 1;
            foreach( $corrals as $slug => $name) {
                ?>
                <li class='ww-box ww-sortable-corral'>
                    <h4><?php print $name; ?> (<code><?php print $slug; ?></code>)</h4>
                    <input type='hidden' name='weight[<?php print $weight; ?>]' value='<?php print $slug?>' />
                </li>
                <?php
                $weight += 1;
            }
            ?></ul><?php
	    $items = ob_get_clean();

        $form = new Form(array(
            'action' => $this->actionPath('sort'),
            'fields' => array(
                'submit' => array(
                    'type' => 'submit',
                    'value' => __('Save order', 'widgetwrangler'),
                    'class' => 'ww-pull-right button button-primary button-large',
                ),
                'items' => array(
                    'type' => 'markup',
                    'value' => $items,
                )
            )
        ));

        return $form->render();
    }

	/**
	 * Reorder the corrals.
	 *
	 * @return array
	 */
	function actionReorder() {
	    if ( empty($_POST['weight']) || !is_array($_POST['weight']) ) {
		    return $this->error();
        }

        Corrals::reorder($_POST['weight']);

        return $this->result(__('Corrals have been reordered.', 'widgetwrangler'));
	}

	/**
     * Corral edit form.
     *
	 * @param $name
	 * @param $slug
     *
     * @return string
	 */
    function formUpdate($name, $slug) {
	    $form = new Form(array(
            'style' => 'table',
		    'action' => $this->actionPath('update'),
            'fields' => array(
                'ww-update-submit' => array(
	                'type' => 'submit',
	                'value' => __('Update', 'widgetwrangler'),
	                'class' => 'ww-pull-right button button-primary button-large',
                ),
                'ww-update-old-slug' => array(
	                'type' => 'hidden',
	                'value' => $slug,
	                'class' => 'ww-pull-right button button-primary button-large',
                ),
                'ww-update-corral' => array(
	                'type' => 'text',
	                'title' => __('Name', 'widgetwrangler'),
	                'value' => $name,
	                'class' => 'regular-text',
                ),
                'ww-update-slug' => array(
	                'type' => 'text',
	                'title' => __('Slug', 'widgetwrangler'),
	                'value' => $slug,
	                'class' => 'regular-text disabled',
                )
            ),
	    ));

	    return $form->render();
    }

	/**
	 * Update an existing corral.
	 *
	 * @return array
	 */
	function actionUpdate() {
	    if ( empty( $_POST['ww-update-old-slug'] ) ) {
		    return $this->error( __('Previous corral name not found.', 'widgetwrangler') );
        }

	    if ( empty( $_POST['ww-update-corral'] ) ) {
		    return $this->error( __('No new corral name found.', 'widgetwrangler') );
        }

	    if ( empty( $_POST['ww-update-slug'] ) ) {
		    return $this->error( __('No corral slug found.', 'widgetwrangler') );
        }

        Corrals::update( $_POST['ww-update-old-slug'],  $_POST['ww-update-corral'], $_POST['ww-update-slug']);

        return $this->result( __('New corral created: ') . $_POST['ww-update-corral']);
	}

	/**
	 * @param $slug
	 *
	 * @return string
	 */
    function formDelete($slug) {
	    $form = new Form(array(
		    'action' => $this->actionPath('delete'),
            'fields' => array(
                'ww-delete-slug' => array(
	                'type' => 'hidden',
	                'value' => $slug,
                ),
                'ww-delete-submit' => array(
	                'type' => 'submit',
	                'value' => __('Delete', 'widgetwrangler'),
	                'class' => 'disabled button button-small',
                    'attributes' => array(
                        'data-confirm' => __('Are you sure you want to delete this corral?', 'widgetwrangler'),
                    ),
                ),
            ),
	    ));

	    return $form->render();
    }

	/**
	 * Delete an existing corral.
	 *
	 * @return array
	 */
	function actionDelete() {
		if ( empty( $_POST['ww-delete-slug'] ) ) {
			return $this->error( __('Corral data missing.', 'widgetwrangler') );
		}

        Corrals::remove( $_POST['ww-delete-slug'] );

        return $this->result(__('Corral deleted: ', 'widgetwrangler') . $_POST['ww-delete-slug']);
	}

	/**
	 * Build the various forms
	 *
	 * @see \AdminPage::page()
	 */
	function page() {
		$corrals = Corrals::all();
		?>
        <div class="ww-columns">
            <div class="ww-column">
                <div class="ww-box">
                    <h3><?php _e("Create New Corral", 'widgetwrangler'); ?></h3>
                    <div>
                        <?php print $this->formCreate(); ?>
                    </div>
                </div>

                <?php if ( !empty( $corrals ) ) : ?>
                <div class="ww-box">
                    <h3><?php _e("Sort Corrals", 'widgetwrangler'); ?></h3>
                    <div>
                        <?php print $this->formReorder($corrals); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="ww-column">
                <?php if ( !empty( $corrals ) ) : ?>
                <div class="ww-box">
                    <h3><?php _e("Edit existing Corrals", 'widgetwrangler'); ?></h3>
                    <div class='description ww-danger'>
                        <?php _e("Warning! If you change a corral's slug, widgets currently assigned to that corral will need to be reassigned.", 'widgetwrangler'); ?>
                    </div>
                    <ul id='ww-corrals-list'>
                        <?php
                            foreach ($corrals as $slug => $name) {
                                ?>
                                <li class='ww-box ww-box-toggle'>
                                <h3><?php print $name; ?> (<code><?php print $slug; ?></code>)</h3>
                                <div class="ww-box-toggle-content">
                                    <?php
                                        print $this->formUpdate($name, $slug);
                                        print $this->formDelete($slug);
                                    ?>
                                </div>
                                </li>
                                <?php
                            }
                        ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
        </div>
		<?php
	}

}
