<?php

namespace WidgetWrangler;

/**
 * Class SortableWidgetsUi
 * @package WidgetWrangler
 */
class SortableWidgetsUi {

	private $all_widgets = array();

	/**
	 * SortableWidgetsUi constructor.
	 */
	function __construct(){
		add_action( 'widget_wrangler_form_top', array( $this, 'formTop' ) );

		$this->all_widgets = Widgets::all(array('publish', 'draft'));
	}

	/**
	 * Javascript drag and drop for sorting
	 */
	public static function js(){
	    wp_enqueue_style('ww-admin');
		wp_enqueue_script('ww-sortable-widgets');

		$data = array(
            'allWidgets' => Widgets::all(array('publish', 'draft')),
            'context' => Context::context(),
        );
		wp_localize_script( 'ww-sortable-widgets', 'WidgetWranglerData', $data );
	}

	/**
     * Meta box hook callback.
     *
	 * @param $post
	 */
	public static function postMetaBox( $post ) {
	    self::metaBox();
    }

	/**
	 * Output the sortable wrangler meta box.
	 *
	 * @param null $widgets
	 */
	public static function metaBox( $widgets = null ) {
	    if ( !$widgets ){
		    $widgets = Context::pageWidgets();
	    }

		$sortable = new self();

		print $sortable->form( $widgets );
    }

	/**
	 * Create an admin interface for wrangling widgets
	 *
	 * @param $widgets
	 *
	 * @return string
	 */
	function form( $widgets ) {
		$context = Context::context();

		ob_start();
		?>
        <div id='widget-wrangler-form'>
            <div class="form-meta">
                <?php do_action('widget_wrangler_form_meta'); ?>
                <?php wp_nonce_field( 'widget-wrangler-sortable-list-box-save' , 'ww-sortable-list-box' ); ?>
            </div>

            <div id="widget-wrangler-form-content">
                <div class="form-top">
                    <?php do_action('widget_wrangler_form_top', $context); ?>
                </div>
                <div id='ww-edited-message'>
                    <p><em>* <?php _e("Widget changes will not be updated until you save.", 'widgetwrangler'); ?></em></p>
                </div>
                <?php
                    print $this->corrals( $widgets );
                ?>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Implements widget_wrangler_form_top hook
	 *
	 * Allow user to select preset and add any widget to any corral.
	 */
	function formTop() {
		$context = Context::context();
		$form = new Form(array( 'style' => 'inline' ));
		$preset_id = 0;
		$preset_message = __('No preset selected. This page is wrangling widgets on its own.', 'widgetwrangler');
		$adminPreset = new AdminPagePresets(array());

		if ( !empty( $context['preset'] ) ) {
			$preset_id = $context['preset']->id;
			$preset_message = __('This page is currently using the Preset: ', 'widgetwrangler') .
                "<a href='{$adminPreset->pagePath()}&preset_id={$preset_id}'>{$context['preset']->data['name']}</a>";
		}

		// do not show preset selection on the presets admin page
		if ( empty( $_GET['page'] ) || $_GET['page'] != 'presets' ) {
			print $form->render_field(array(
				'type' => 'markup',
				'name' => 'message',
				'value' => "<p id='ww-post-preset-message'>{$preset_message}</p>",
			));
			print $form->render_field(array(
				'type' => 'select',
				'name' => 'ww-preset-id-new',
				'title' => __('Preset', 'widgetwrangler') .'<span class="ajax-working spinner"></span>',
				'help' => __('Select the Preset you would like to control widgets on this page, or select "- No Preset -" to allow this page to control its own widgets.', 'widgetwrangler'),
				'options' => array( 0 => __('- No Preset -', 'widgetwrangler') ) + Presets::asOptions(),
				'value' => $preset_id,
			));
		}
		print $form->render_field(array(
			'type' => 'select',
			'title' => __('Add Widget', 'widgetwrangler'),
			'name' => 'ww-add-new-widget-widget',
			'options' => array( 0 => __('- Select a Widget -', 'widgetwrangler') ) + Widgets::asOptions( $this->all_widgets ),
		));
		print $form->render_field(array(
			'type' => 'select',
			'name' => 'ww-add-new-widget-corral',
			'options' => array( 0 => __('- Select a Corral -', 'widgetwrangler') ) + Corrals::all(),
		));
		?>
        <span id="ww-add-new-widget-button" class="button button-large"><?php _e('Add', 'widgetwrangler'); ?></span>
        <p class="description"><?php _e('Select a widget you would like to add, and the corral where you would like to add it then click the "Add" button.', 'widgetwrangler'); ?></p>

        <script type="text/html" id="tmpl-add-widget">
			<?php
			$tmpl_widget = array(
				'weight' => '__widget-weight__',
				'id' => '__widget-ID__',
				'title' => '__widget-post_title__',
				'corral' => array(
					'slug' => '__widget-corral_slug__',
				),
			);

			print $this->templateWidget( $tmpl_widget, '__ROW-INDEX__' );
			?>
        </script>
		<?php
	}

	/**
     * Build all sortable corrals.
     *
	 * @param $widgets
	 *
	 * @return string
	 */
	function corrals( $widgets ){
	    ob_start();
		foreach ( Corrals::all() as $corral_slug => $corral_name ){
			// ensure an array exists
			if ( ! isset( $widgets[ $corral_slug ] ) ){
				$widgets[ $corral_slug ] = array();
			}

			$corral_widgets = $this->processCorral( $widgets[ $corral_slug ], $corral_slug );

			print $this->templateCorral( $corral_widgets, $corral_slug  );
		}

		return ob_get_clean();
	}

	/**
	 * Prepare the important widget details for sortable display
	 *
	 * @param $corral_widgets
	 * @param string $corral_slug
	 *
	 * @return array
	 */
	function processCorral( $corral_widgets, $corral_slug = 'disabled' ){
		$sorted_widgets = array();

		foreach ( $corral_widgets as $i => $details ){
			if ( isset( $this->all_widgets[ $details['id'] ] ) ){
				$widget = $this->all_widgets[ $details['id'] ];

				$processed = array(
					'weight' => $details['weight'],
					'id' => $widget->ID,
					'title' => $widget->post_title,
					'slug' => $widget->post_name,
					'status' => $widget->post_status,
					'display_logic' => $widget->display_logic_enabled,
					'corral' => array(
						'title' => Corrals::get( $corral_slug ),
						'slug' => $corral_slug,
					),
					'notes' => array(),
				);

				// additional text indicators of widget's status and display logic
				// indicate drafts
				if ( $processed['status'] == 'draft' ) {
					$processed['notes'][] = __('draft', 'widgetwrangler');
				}

				// indicate display logic enabled
				if ( $processed['display_logic'] ){
					$processed['notes'][] = __('display logic', 'widgetwrangler');
				}

				// fix widgets with no title
				if ( empty( $processed['title'] ) ){
					$processed['title'] = sprintf( __('(no title) - Slug: %1$s - ID: %2$s', 'widgetwrangler'), $widget->post_name, $widget->ID );
				}

				// fix empty or duplicate weights by moving widget to the bottom
				if ( ! isset( $processed['weight'] ) || isset( $sorted_widgets[ $processed['weight'] ] ) ) {
					$processed['weight'] = $i + count( $corral_widgets );
				}

				$sorted_widgets[ $processed['weight'] ] = $processed;
			}
		}

		ksort($sorted_widgets);

		return $sorted_widgets;
	}

	/**
	 * Template a single corral of sortable widgets.
	 *
	 * @param $corral_widgets
	 * @param $corral_slug
	 *
	 * @return string
	 */
	function templateCorral( $corral_widgets, $corral_slug ){
		$corral_name = Corrals::get( $corral_slug );
		$no_widgets_style = count( $corral_widgets ) ? 'style="display:none"' : '';

		ob_start();
		?>
		<div class="ww-corral-wrapper">
			<h3><?php print $corral_name; ?></h3>
			<ul data-corralslug='<?php print $corral_slug; ?>' id='ww-corral-<?php print $corral_slug; ?>-items' class='inner ww-sortable'>
				<?php
					foreach ( $corral_widgets as $i => $widget ){
						print $this->templateWidget( $widget, $corral_slug . '-' . $i);
					}
				?>
				<li class='ww-no-widgets' <?php print $no_widgets_style; ?>>
                    <p><?php _e("No Widgets in this corral.", 'widgetwrangler'); ?></p>
                </li>
			</ul>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Single sortable widget.
	 *
	 * @param $widget_details
	 * @param null $row_index
	 *
	 * @return string
	 */
	function templateWidget($widget_details, $row_index = null) {
		if ( empty($row_index) ) { $row_index = $widget_details->ID; }
		$corral_slug = $widget_details['corral']['slug'];
		$weight = $widget_details['weight'];

		$notes = '';
		if ( ! empty( $widget_details['notes'] ) ) {
			$notes = ' : <em>(' . implode( '),(', $widget_details['notes'] ) . ')</em>';
		}

		ob_start();
		?>
		<li class='ww-item <?php print $corral_slug; ?>'>
			<input  name='ww-data[widgets][<?php print $row_index; ?>][weight]' type='text' class='ww-widget-weight'  size='2' value='<?php print $weight; ?>' />
			<input  name='ww-data[widgets][<?php print $row_index; ?>][id]' type='hidden' class='ww-widget-id' value='<?php print $widget_details['id']; ?>' />
			<select name='ww-data[widgets][<?php print $row_index; ?>][sidebar]'>
				<option value='disabled'><?php _e('Remove', 'widgetwrangler'); ?></option>
				<?php foreach( Corrals::all() as $this_corral_slug => $corral_name ) { ?>
					<option name='<?php print $this_corral_slug; ?>'
					        value='<?php print $this_corral_slug; ?>'
						    <?php selected( $this_corral_slug, $corral_slug ); ?>>
						<?php print $corral_name; ?>
					</option>
				<?php } ?>
			</select>
			<?php print $widget_details['title'] . $notes ?>
		</li>
		<?php
		return ob_get_clean();
	}

}
