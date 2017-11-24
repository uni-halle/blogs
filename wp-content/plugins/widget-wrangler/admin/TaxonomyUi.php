<?php

namespace WidgetWrangler;

/**
 * Class TaxonomiesUi
 * @package WidgetWrangler
 */
class TaxonomyUi extends AdminPage {

	/**
	 * Register hooks.
	 *
	 * @param $settings
	 *
	 * @return \WidgetWrangler\AdminPage
	 */
	public static function register( $settings ) {
		$plugin  = parent::register( $settings );

		// enabled taxonomy, but not term pages
		if ( empty( $_GET['tag_ID'] ) &&
		     !empty( $_GET['taxonomy'] ) &&
		     !empty( $settings['taxonomies'] ) &&
		     !empty( $settings['taxonomies'][ $_GET['taxonomy'] ] ) )
		{
			add_action( 'admin_enqueue_scripts', array( $plugin, 'enqueue' ));
			add_action( "after-{$_GET['taxonomy']}-table", array( $plugin, 'formTaxonomy' ) );
		}

		return $plugin;
	}

	/**
	 * @see AdminPage::slug()
	 */
	function slug() {
		return 'taxonomies';
	}

	/**
	 * @see AdminPage::actions()
	 */
	function actions() {
		return array(
			'save_taxonomy' => array( $this, 'actionSaveTaxonomy' ),
		);
	}

	/**
	 * @see AdminPage::enqueue()
	 */
	function enqueue() {
		parent::enqueue();
		SortableWidgetsUi::js();
	}

	/**
	 * Need a page route for saving taxonomy defaults that doesn't exist in the
	 * admin menu.
	 */
	function menu(){
		$this->page_hook = add_submenu_page(null, $this->title(), $this->menuTitle(), $this->capability(), $this->slug(), array( $this, 'route' ) );
	}

	/**
     * Widget form on taxonomy (term list) screen. On the taxonomy screen we
     * need a complete form w/ button any route -> action.
     *
	 * @param $taxonomy
	 */
	function formTaxonomy( $taxonomy ) {

		if (isset($this->settings['taxonomies'][$taxonomy]) &&
		    $taxonomies = get_taxonomies(array('name' => $taxonomy), 'objects'))
		{
			$where = array(
				'type' => 'taxonomy',
				'variety' => 'taxonomy',
				'extra_key' => $taxonomy,
			);

			// allow for presets
			if ($tax_data = Extras::get($where))
			{
				if (isset($tax_data->data['preset_id']) && $tax_data->data['preset_id'] != 0) {
					$preset = Presets::get($tax_data->data['preset_id']);
					$widgets = $preset->widgets;
				}
				else {
					$widgets = $tax_data->widgets;
				}
			}
			else {
				$preset = Presets::getCore('default');
				$widgets = $preset->widgets;
			}

			$page_widgets = $widgets;

			ob_start();
			    print $this->templateMessages();
			    SortableWidgetsUi::metaBox( $page_widgets );
			$sortable_widgets = ob_get_clean();

			$form = new Form(array(
				'action' => $this->actionPath('save_taxonomy'),
				'style' => 'box',
				'fields' => array(
					'opening' => array(
						'type' => 'markup',
						'title' => __('Widget Wrangler', 'widgetwrangler'),
						'description' => __('Here you can override the default widgets for all terms in this taxonomy.', 'widgetwrangler'),
					),
					'taxonomy' => array(
						'type' => 'hidden',
						'value' => $taxonomy,
					),
					'override_default' => array(
						'type' => 'checkbox',
						'title' => __('Set taxonomy default widgets', 'widgetwrangler'),
						'help' => __('Enable these widgets as the default widgets for terms in this taxonomy. If not checked, these widgets will never be displayed.', 'widgetwrangler'),
                        'value' => !empty( $tax_data->data['override_default'] ),
					),
					'save_widgets' => array(
						'type' => 'submit',
						'value' => __('Save Widgets', 'widgetwrangler'),
						'class' => 'button button-large button-primary',
					),
					'widgets' => array(
						'type' => 'markup',
						'value' => $sortable_widgets,
						'title' => __('Widgets', 'widgetwrangler'),
					),
				)
			));

			print $form->render();
		}
	}

	/**
	 * Save taxonomy widget data
	 *
	 * @return array
	 */
	function actionSaveTaxonomy() {
		if ( empty( $_POST['taxonomy'] ) ) {
			return $this->error( __('Missing taxonomy data.', 'widgetwrangler') );
		}

		$data = array(
			'override_default' => (int) !empty($_POST['override_default']),
		);

		Admin::saveTaxonomyWidgets( 'taxonomy', $_POST['taxonomy'], $data );

		return $this->result( __('Success.', 'widgetwrangler') );
	}
    
}
