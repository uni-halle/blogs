<?php

class WW_Admin_Sortable {

	private $all_corrals = array();
	private $all_widgets = array();

	function __construct( $page_widgets = array() ){
		add_action( 'widget_wrangler_form_top', array( $this, 'add_new_widget' ) );

		global $widget_wrangler;
		$this->all_corrals = $widget_wrangler->corrals;
		$this->all_widgets = $widget_wrangler->get_all_widgets(array('publish', 'draft'));
	}

	/**
	 * Create an admin interface for wrangling widgets
	 *
	 * @param $page_widgets
	 *
	 * @return string
	 */
	function box_wrapper( $page_widgets ){
		ob_start();
		// meta_box interior
		?>
		<div id="widget-wrangler-form-meta">
			<?php do_action('widget_wrangler_form_meta'); ?>
			<input value='true' type='hidden' name='widget-wrangler-edit' />
			<?php wp_nonce_field( 'widget-wrangler-sortable-list-box-save' , 'ww-sortable-list-box' ); ?>
		</div>

		<div id="widget-wrangler-form-wrapper">
			<div id='widget-wrangler-form' class='new-admin-panel'>
				<div class='outer'>
					<div id="widget_wrangler_form_top">
						<?php do_action('widget_wrangler_form_top'); ?>
					</div>
					<div id='ww-post-edit-message'>* <?php _e("Widget changes will not be updated until you save.", 'widgetwrangler'); ?>"</div>

					<?php
						print $this->theme_sortable_corrals( $page_widgets );
					?>
					<div id="widget_wrangler_form_bottom">
						<?php do_action('widget_wrangler_form_bottom'); ?>
					</div>

				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param $page_widgets
	 *
	 * @return string
	 */
	function theme_sortable_corrals( $page_widgets ){
		$output = '';

		foreach ( $this->all_corrals as $corral_slug => $corral_name ){
			// ensure an array exists
			if ( ! isset( $page_widgets[ $corral_slug ] ) ){
				$page_widgets[ $corral_slug ] = array();
			}

			$corral_widgets = $this->preprocess_sortable_corral( $page_widgets[ $corral_slug ], $corral_slug );

			$output.= $this->sortable_corral( $corral_widgets, $corral_slug  );
		}

		return $output;
	}

	/**
	 * Prepare the important widget details for sortable display
	 *
	 * @param $corral_widgets
	 * @param string $corral_slug
	 *
	 * @return array
	 */
	function preprocess_sortable_corral( $corral_widgets, $corral_slug = 'disabled' ){
		$sorted_widgets = array();

		foreach ( $corral_widgets as $i => $details ){
			if ( isset( $this->all_widgets[ $details['id'] ] ) ){
				$widget = $this->all_widgets[ $details['id'] ];

				$widget_details = array(
					'weight' => $details['weight'],
					'id' => $widget->ID,
					'title' => $widget->post_title,
					'slug' => $widget->post_name,
					'status' => $widget->post_status,
					'display_logic' => $widget->display_logic_enabled,
					'corral' => array(
						'title' => $this->all_corrals[ $corral_slug ],
						'slug' => $corral_slug,
					),
					'notes' => array(),
				);

				// additional text indicators of widget's status and display logic
				// indicate drafts
				if ( $widget_details['status'] == 'draft' ) {
					$widget_details['notes'][] = __('draft', 'widgetwrangler');
				}

				// indicate display logic enabled
				if ( $widget_details['display_logic'] ){
					$widget_details['notes'][] = __('display logic', 'widgetwrangler');
				}

				// fix widgets with no title
				if ( empty( $widget_details['title'] ) ){
					$widget_details['title'] = sprintf( __('(no title) - Slug: %1$s - ID: %2$s', 'widgetwrangler'), $widget->post_name, $widget->ID );
				}

				// fix empty or duplicate weights by moving widget to the bottom
				if ( ! isset( $widget_details['weight'] ) || isset( $sorted_widgets[ $widget_details['weight'] ] ) ) {
					$widget_details['weight'] = $i + count( $corral_widgets );
				}

				$sorted_widgets[ $widget_details['weight'] ] = $widget_details;
			}
		}

		ksort($sorted_widgets);

		return $sorted_widgets;
	}

	/**
	 * Sortable corral items
	 *
	 * @param $corral_widgets
	 * @param $corral_slug
	 *
	 * @return string
	 */
	function sortable_corral( $corral_widgets, $corral_slug ){
		// todo
		$corral_name = $this->all_corrals[ $corral_slug ];
		$no_widgets_style = count( $corral_widgets ) ? 'style="display:none"' : '';

		ob_start();
		?>
		<div id="ww-corral-<?php print $corral_slug; ?>-wrapper" class="ww-sortable-corral-wrapper">
			<h4 class="ww-sortable-widgets-corral-title"><?php print $corral_name; ?></h4>
			<ul name='<?php print $corral_slug; ?>' id='ww-corral-<?php print $corral_slug; ?>-items' class='inner ww-sortable' width='100%'>
				<?php
					foreach ( $corral_widgets as $i => $widget ){
						print $this->sortable_corral_item( $widget, $corral_slug.'-'.$i);
					}
				?>
				<li class='ww-no-widgets' <?php print $no_widgets_style; ?>><?php _e("No Widgets in this corral.", 'widgetwrangler'); ?></li>
			</ul>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Single sortable corral item
	 *
	 * @param $widget_details
	 * @param null $row_index
	 *
	 * @return string
	 */
	function sortable_corral_item($widget_details, $row_index = null) {
		if ( empty($row_index) ) { $row_index = $widget_details->ID; }
		$corral_slug = $widget_details['corral']['slug'];
		$weight = $widget_details['weight'];

		$notes = '';
		if ( ! empty( $widget_details['notes'] ) ) {
			$notes = ' : <em>(' . implode( '),(', $widget_details['notes'] ) . ')</em>';
		}

		// todo
		ob_start();
		?>
		<li class='ww-item <?php print $corral_slug; ?> nojs' width='100%'>
			<input  name='ww-data[widgets][<?php print $row_index; ?>][weight]' type='text' class='ww-widget-weight'  size='2' value='<?php print $weight; ?>' />
			<input  name='ww-data[widgets][<?php print $row_index; ?>][id]' type='hidden' class='ww-widget-id' value='<?php print $widget_details['id']; ?>' />
			<select name='ww-data[widgets][<?php print $row_index; ?>][sidebar]'>
				<option value='disabled'><?php _e('Remove', 'widgetwrangler'); ?></option>
				<?php foreach( $this->all_corrals as $this_corral_slug => $corral_name ){ ?>
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

	/**
	 * Implements widget_wrangler_form_bottom hook
	 *
	 * Allow user to add any widget to any corral
	 */
	function add_new_widget(){
		?>
		<div id="ww-add-new-widget">
			<h2><?php _e('Add Widget', 'widgetwrangler'); ?></h2>
			<div class="ww-inner">
				<select id="ww-add-new-widget-widget">
					<option value="0">-- <?php _e('Select a Widget', 'widgetwrangler'); ?> --</option>
					<?php foreach ( $this->all_widgets as $widget ){ ?>
						<option value="<?php print esc_attr( $widget->ID ); ?>"><?php print $widget->post_title; ?></option>
					<?php } ?>
				</select>
				<select id="ww-add-new-widget-corral">
					<option value="0">-- <?php _e('Select a Corral', 'widgetwrangler'); ?> --</option>
					<?php foreach($this->all_corrals as $corral_slug => $corral_name) { ?>
						<option value="<?php print esc_attr( $corral_slug ); ?>"><?php print $corral_name; ?></option>
					<?php } ?>
				</select>
				<span id="ww-add-new-widget-button" class="button button-large"><?php _e('Add Widget to Corral', 'widgetwrangler'); ?></span>
			</div>
			<p class="description ww-inner"><?php _e('Select a widget you would like to add, and the corral where you would like to add it. Click the button Add Widget to Corral.', 'widgetwrangler'); ?></p>

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

				print $this->sortable_corral_item( $tmpl_widget, '__ROW-INDEX__' );
				?>
			</script>
		</div>
		<?php
		//
	}
}