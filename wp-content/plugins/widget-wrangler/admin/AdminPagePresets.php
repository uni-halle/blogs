<?php

namespace WidgetWrangler;

/**
 * Class AdminPagePresets
 * @package WidgetWrangler
 */
class AdminPagePresets extends AdminPage {

	/**
	 * @see AdminPage::title()
	 */
	function title() {
		return __('Presets', 'widgetwrangler');
	}

	/**
	 * @see AdminPage::menuTitle()
	 */
	function menuTitle() {
		return __('Presets', 'widgetwrangler');
	}

	/**
	 * @see AdminPage::slug()
	 */
	function slug() {
		return 'presets';
	}

	/**
	 * @see AdminPage::description()
	 */
	function description() {
		return array(
			__('Presets are pre-configured sets of Widgets assigned to Corrals. This makes a collection of widgets reusable on many pages without having to edit them individually.', 'widgetwrangler'),
            __('If you edit a preset here, the updates will apply to all pages using the preset.', 'widgetwrangler'),
		);
	}

	/**
	 * @see AdminPage::actions()
	 */
	function actions() {
		return array(
			'create' => array( $this, 'actionCreate' ),
            'update_widgets' => array( $this, 'actionUpdateWidgets' ),
            'update_data' => array( $this, 'actionUpdateWidgetsPresetData' ),
            'delete' => array( $this, 'actionDelete' ),
		);
	}

	/**
	 * @see AdminPage::enqueue()
	 */
	function enqueue() {
	    parent::enqueue();

	    if ($this->onPage()) {
		    SortableWidgetsUi::js();
        }
    }

	/**
	 * Update a Widget Preset
	 */
	function actionUpdateWidgets() {
		if ( empty( $_POST['preset-id'] ) ) {
			return $this->error( __('Error: No preset id.', 'widgetwrangler') );
		}

		if ( empty( $_POST['preset-variety'] ) ) {
			return $this->error( __('Error: No preset variety.', 'widgetwrangler') );
		}

        $widgets = ( !empty( $_POST['ww-data'] ) && !empty( $_POST['ww-data']['widgets'] ) ) ? $_POST['ww-data']['widgets'] : array();
        $active_widgets = Admin::serializeWidgets( $widgets );
        $preset_id = intval( $_POST['preset-id'] );
        $preset_variety = $_POST['preset-variety'];
        $preset_widgets = (!empty($active_widgets)) ? $active_widgets : serialize(array());

        // update the ww_presets db
        $data = array(
            'widgets' => $preset_widgets,
        );

        $where = array(
            'id' => $preset_id,
            'type' => 'preset',
            'variety' => $preset_variety,
        );

        // save the widgets
        Extras::update($data, $where);

        return $this->result(__('Preset updated.', 'widgetwrangler'));
	}

	/**
     * Form to create a new preset.
     *
	 * @return string
	 */
    function formCreate(){
	    $varieties  = Presets::varieties();
	    unset($varieties['core']);
	    $options = array();

	    foreach ($varieties as $key => $variety) {
	        $options[$key] = $variety['title'];
        }

	    $form = new Form(array(
            'field_prefix' => 'create',
            'action' => $this->actionPath('create'),
            'fields' => array(
	            'variety' => array(
		            'type' => 'select',
		            'title' => __('Variety', 'widgetwrangler'),
		            'options' => $options,
	            ),
	            'save' => array(
		            'type' => 'submit',
		            'value' => __('Create New Preset', 'widgetwrangler'),
		            'class' => 'button button-primary button-large',
	            )
            ),
        ));

	    return $form->render();
    }

	/**
	 * Create a new Widget Preset
	 */
	function actionCreate() {
		if ( empty( $_POST['create'] ) || empty( $_POST['create']['variety'] ) ) {
			return $this->error( __('Error: Missing data.', 'widgetwrangler') );
		}

		switch( $_POST['create']['variety'] ) {
			case 'standard':
				$data = array(
					'type' => 'preset',
					'variety' => Utils::makeSlug( $_POST['create']['variety'] ),
					'data' => serialize(array('name' => ucfirst($_POST['create']['variety']))),
					'widgets' => serialize(array()),
				);

				$new_preset_id = Extras::insert($data);

				return $this->result( __('New Preset Created.', 'widgetwrangler'), $this->pageUrl().'&preset_id='.$new_preset_id );
				break;
		}

		return $this->error();
	}

	/**
     * Form to edit a preset's data.
     *
	 * @param $preset
	 *
	 * @return string
	 */
    function formEdit( $preset ) {
        $form = new Form(array(
            'style' => 'table',
            'action' => $this->actionPath('update_data'),
            'fields' => array(
                'submit' => array(
                    'type' => 'submit',
                    'value' => __('Update', 'widgetwrangler'),
                    'class' => 'ww-pull-right button button-primary button-large',
                ),
                'preset-id' => array(
                    'type' => 'hidden',
                    'value' => $preset->id,
                ),
                'preset-variety' => array(
                    'type' => 'hidden',
                    'value' => $preset->variety,
                ),
                'name' => array(
	                'type' => 'text',
	                'title' => __('Name', 'widgetwrangler'),
	                'name_prefix' => 'data',
	                'value' => $preset->data['name'],
                    'class' => 'regular-text',
                ),
                'slug' => array(
	                'type' => 'markup',
	                'title' => __('Type', 'widgetwrangler'),
	                'value' => $preset->variety,
                ),
            )
        ));

        return $form->render();
    }

	/**
	 * Update the preset name.
	 *
	 * @return array
	 */
	function actionUpdateWidgetsPresetData() {
		if ( empty( $_POST['preset-id'] ) ) {
			return $this->error( __('Error: No preset id.', 'widgetwrangler') );
		}

		if ( empty( $_POST['preset-variety'] ) ) {
			return $this->error( __('Error: No preset variety.', 'widgetwrangler') );
		}

		if ( empty( $_POST['data'] ) || empty( $_POST['data']['name'] ) ) {
			return $this->error( __('Error: Missing data.', 'widgetwrangler') );
		}

		$where = array(
			'id' => $_POST['preset-id'],
			'variety' => $_POST['preset-variety']
		);

		$preset = Extras::get($where);

		if ( $preset ) {
			$preset->data['name'] = sanitize_text_field($_POST['data']['name']);
			Extras::update(array( 'data' => $preset->data ), $where);

			return $this->result(__('Updated preset name to ', 'widgetwrangler') . $preset->data['name']);
		}

		return $this->error( __('Error: Preset not found', 'widgetwrangler') );
	}

	/**
     * Form to delete a preset.
     *
	 * @param $preset
	 *
	 * @return string
	 */
    function formDelete($preset) {

	    $form = new Form(array(
		    'style' => 'table',
		    'action' => $this->actionPath('delete'),
		    'fields' => array(
			    'submit' => array(
				    'type' => 'submit',
				    'value' => __('Delete', 'widgetwrangler'),
				    'class' => 'button button-small disabled',
				    'attributes' => array(
					    'data-confirm' => __('Are you sure you want to delete this preset?', 'widgetwrangler'),
				    ),
			    ),
			    'preset-id' => array(
				    'type' => 'hidden',
				    'value' => $preset->id,
			    ),
			    'preset-variety' => array(
				    'type' => 'hidden',
				    'value' => $preset->variety,
			    ),
		    )
	    ));

	    return $form->render();
    }

	/**
	 * Delete a Widget Preset
	 */
	function actionDelete(){
		if ( empty( $_POST['preset-id'] ) ) {
			return $this->error( __('Error: No preset id.', 'widgetwrangler') );
		}

		if ( empty( $_POST['preset-variety'] ) ) {
			return $this->error( __('Error: No preset variety.', 'widgetwrangler') );
		}

		if ( $_POST['preset-variety'] == 'core' ) {
			return $this->error( __('Error: You cannot delete a plugin provided preset.', 'widgetwrangler') );
		}

		Extras::delete(array(
			'type' => 'preset',
			'variety' => $_POST['preset-variety'],
			'id' => $_POST['preset-id'],
		));

		return $this->result( __('Preset deleted.', 'widgetwrangler'), $this->pageUrl() );
	}

	/**
	 * Admin Manage presets form
	 */
	function page() {
		$all = Presets::all();
		$preset_id = 1;
		$preset = Presets::getCore('default');
        $context = Context::context();
		$sortable = new SortableWidgetsUi();

		if ( $context['preset'] ) {
			$preset = $context['preset'];
			$preset_id = $preset->id;
		}
		ob_start();
		?>
        <div class="ww-columns">
            <div class="ww-column col-25">
                <div class="ww-box">
                    <h3><?php _e('Presets', 'widgetwrangler'); ?></h3>
                    <ul class="ww-list-links">
                        <?php foreach($all as $item) {
                            $classes = ($preset_id == $item->id) ? 'active' : '';
                            ?>
                            <li class="<?php print $classes; ?>">
                                <a href="<?php print $this->pagePath(); ?>&preset_id=<?php print $item->id; ?>"><?php print $item->data['name']; ?><?php print ($item->variety == 'core') ? ' <small>('.$item->variety.')</small>' : ''; ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="ww-box">
                    <h3><?php _e('Create New', 'widgetwrangler'); ?></h3>
                    <?php print $this->formCreate(); ?>
                </div>
            </div>
            <div class="ww-column col-75">
                <?php if ( $preset->variety == 'core') { ?>
                    <div class="ww-box">
                        <h3><?php print $preset->data['name']; ?></h3>
                    </div>
                <?php } else { ?>
                    <div class="ww-box ww-box-toggle">
                        <h3><?php print $preset->data['name']; ?></h3>
                        <div class="ww-box-toggle-content">
                            <?php
                            print $this->formEdit($preset);
                            print $this->formDelete($preset);
                            ?>
                        </div>
                    </div>
                <?php } ?>

                <div class="ww-box">
                    <h3><?php _e('Widgets', 'widgetwrangler'); ?></h3>
                    <?php
                    $form = new Form(array(
	                    'action' => $this->actionPath('update_widgets'),
	                    'fields' => array(
		                    'submit' => array(
			                    'type' => 'submit',
			                    'value' => __('Save Preset', 'widgetwrangler'),
			                    'class' => 'ww-pull-right button button-primary button-large',
		                    ),
		                    'preset-id' => array(
			                    'type' => 'hidden',
			                    'value' => $preset->id,
		                    ),
		                    'preset-variety' => array(
			                    'type' => 'hidden',
			                    'value' => $preset->variety,
		                    ),
		                    'wrangler' => array(
			                    'type' => 'markup',
			                    'value' => $sortable->form( $preset->widgets ),
                            ),
	                    )
                    ));

                    print $form->render();
                    ?>
                </div>
            </div>
        </div>
		<?php
	}

}
