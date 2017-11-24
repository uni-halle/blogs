<?php

namespace WidgetWrangler;

/**
 * Class AdminPageSidebars
 * @package WidgetWrangler
 */
class AdminPageSidebars extends AdminPage {

	/**
	 * @see AdminPage::title()
	 */
	function title() {
		return __('Theme Sidebars', 'widgetwrangler');
	}

	/**
	 * @see AdminPage::menuTitle()
	 */
	function menuTitle() {
		return __('Sidebars', 'widgetwrangler');
	}

	/**
	 * @see AdminPage::slug()
	 */
	function slug() {
		return 'sidebars';
	}

	/**
	 * @see AdminPage::description()
	 */
	function description() {
		return array(
            __('Modify the sidebar output registered by the current theme.', 'widgetwrangler'),
		);
	}

	/**
	 * @see AdminPage::actions()
	 */
	function actions() {
		return array(
			'update' => array( $this, 'actionUpdate' ),
		);
	}

	/**
	 * @see AdminPage::menu()
	 */
	function menu() {
		parent::menu();

		if ( !$this->settings['theme_compat']) {
			remove_submenu_page($this->parentSlug(), $this->slug());
		}
	}

	/**
     * Save the sidebars
     *
	 * @return array
	 */
	function actionUpdate() {
		if ( empty( $_POST['ww-data']['sidebars'] ) || !is_array( $_POST['ww-data']['sidebars'] ) ) {
			return $this->error( __('Error: Form data missing or malformed.', 'widgetwrangler') );
        }

        // clean up a little
        $sidebars = array();
        foreach ($_POST['ww-data']['sidebars'] as $slug => $sidebar){
            $sidebars[$slug] = array();
            foreach ($sidebar as $k => $v){
                $sidebars[$slug][$k] = str_replace("\\", '', $v);
            }
        }
        update_option('ww_alter_sidebars', $sidebars);

        return $this->result( __('Sidebars modified.', 'widgetwrangler') );
	}

	/**
	 * @see AdminPage::page()
	 */
	function page() {
		global $wp_registered_sidebars;
		$altered_sidebars = Utils::alteredSidebars(true);

		$form = new Form(array(
			'style' => 'table',
			'action' => $this->actionPath('update'),
			'field_prefix' => 'ww-data',
		));

		print $form->form_open();

		print $form->render_field(array(
            'type' => 'submit',
            'name' => 'save',
            'value' => __('Update Sidebars', 'widgetwrangler'),
            'class' => 'button button-large button-primary',
        ));

		foreach ($altered_sidebars as $slug => $sidebar)
		{
			$original = (isset($wp_registered_sidebars[$slug])) ? $wp_registered_sidebars[$slug] : FALSE;
			?>
            <div class="ww-box">
                <h3><?php print $sidebar['name']; ?> (<code><?php print $slug; ?></code>)</h3>
                <div>
                    <p class="description"><?php print $sidebar['description']; ?></p>
                    <?php
                    print $form->render_field(array(
                        'type' => 'hidden',
                        'name' => 'slug',
                        'name_prefix' => "[sidebars][{$slug}]",
                        'value' => $slug,
                    ));

                    print $form->form_open_table();

                    print $form->render_field(array(
                        'type' => 'checkbox',
                        'title' => __('Alter Sidebar', 'widgetwrangler'),
                        'name' => 'ww_alter',
                        'name_prefix' => "[sidebars][{$slug}]",
                        'value' => !empty($sidebar['ww_alter']),
                    ));
                    print $form->render_field(array(
                        'type' => 'text',
                        'title' => __('Before Widget', 'widgetwrangler'),
                        'name' => 'before_widget',
                        'name_prefix' => "[sidebars][{$slug}]",
                        'value' => htmlentities( $sidebar['before_widget'] ),
                        'class' => 'regular-text code',
                    ));
                    print $form->render_field(array(
                        'type' => 'text',
                        'title' => __('Before Title', 'widgetwrangler'),
                        'name' => 'before_title',
                        'name_prefix' => "[sidebars][{$slug}]",
                        'value' => htmlentities( $sidebar['before_title'] ),
                        'class' => 'regular-text code',
                    ));
                    print $form->render_field(array(
                        'type' => 'text',
                        'title' => __('After Title', 'widgetwrangler'),
                        'name' => 'after_title',
                        'name_prefix' => "[sidebars][{$slug}]",
                        'value' => htmlentities( $sidebar['after_title'] ),
                        'class' => 'regular-text code',
                    ));
                    print $form->render_field(array(
                        'type' => 'text',
                        'title' => __('After Widget', 'widgetwrangler'),
                        'name' => 'after_widget',
                        'name_prefix' => "[sidebars][{$slug}]",
                        'value' => htmlentities( $sidebar['after_widget'] ),
                        'class' => 'regular-text code',
                    ));

                    print $form->form_close_table();
                    ?>
                    <hr>
                    <a class="toggle-next-content"><?php _e('View Original', 'widgetwrangler'); ?></a>
                    <div class="togglable-content">
                        <pre class="code"><?php print htmlentities(print_r($original,1)); ?></pre>
                    </div>
                </div>
            </div>
			<?php
		}

		print $form->form_close();
	}

}
