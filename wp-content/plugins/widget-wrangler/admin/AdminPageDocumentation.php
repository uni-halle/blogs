<?php

namespace WidgetWrangler;

class AdminPageDocumentation extends AdminPage {

	/**
	 * @see AdminPage::title()
	 */
	function title() {
		return __('Widget Wrangler Documentation', 'widgetwrangler');
	}

	/**
	 * @see AdminPage::menuTitle()
	 */
	function menuTitle() {
		return __('Documentation', 'widgetwrangler');
	}

	/**
	 * @see AdminPage::slug()
	 */
	function slug() {
		return 'documentation';
	}

	/**
	 * @see AdminPage::description()
	 */
	function description() {
		return array();
	}

	function page() {
		foreach ( $this->sections() as $key => $section ) {
		    $section['key'] = $key;
			print $this->templateSection( $section );
		}
	}

	function imgUrl( $name ) {
	    return WW_PLUGIN_URL . "/admin/images/docs/{$name}.png";
    }

	/**
     * Wrap section in a shared template.
     *
	 * @param $section
	 *
	 * @return string
	 */
	function templateSection( $section ) {
		ob_start();
		?>
        <a id="<?php print $section['key']; ?>" class="anchor"></a>
		<div class="ww-columns ww-docs">
			<div class="ww-column col-25">
				<h2><?php print $section['title']; ?></h2>
				<p><?php print $section['description']; ?></p>
			</div>
			<div class="ww-column col-75">
                <div class="ww-box">
				    <?php if ( is_callable( $section['callback'] ) ) call_user_func( $section['callback'] ); ?>
                </div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @return array
	 */
	function sections() {
		return array(
			'toc' => array(
				'title' => __('Table of Contents', 'widgetwrangler'),
				'description' => __('', 'widgetwrangler'),
				'callback' => array( $this, 'sectionToc' ),
			),
			'getting-started' => array(
				'title' => __('Getting Started', 'widgetwrangler'),
				'description' => __('General overview of Widget Wrangler.', 'widgetwrangler'),
				'callback' => array( $this, 'sectionGettingStarted' ),
			),
			'setup' => array(
				'title' => __('Setup', 'widgetwrangler'),
				'description' => __('First steps for using Widget Wrangler.', 'widgetwrangler'),
				'callback' => array( $this, 'sectionSetup' ),
			),
			'widget-posttype' => array(
				'title' => __('Widgets', 'widgetwrangler'),
				'description' => __('The new widget post_type.', 'widgetwrangler'),
				'callback' => array( $this, 'sectionWidgetPostType' ),
			),
			'corrals' => array(
				'title' => __('Corrals', 'widgetwrangler'),
				'description' => __('Arbitrary groups of widgets.', 'widgetwrangler'),
				'callback' => array( $this, 'sectionCorrals' ),
			),
			'presets' => array(
				'title' => __('Presets', 'widgetwrangler'),
				'description' => __('Reusable set of widgets in corrals.', 'widgetwrangler'),
				'callback' => array( $this, 'sectionPresets' ),
			),
			'theme-compatibility' => array(
				'title' => __('Theme Compatibility', 'widgetwrangler'),
				'description' => __('Attempt to render widgets using the Sidebar HTML.', 'widgetwrangler'),
				'callback' => array( $this, 'sectionThemeCompatibility' ),
			),
			'widget-templates' => array(
				'title' => __('Widget Templates', 'widgetwrangler'),
				'description' => __('', 'widgetwrangler'),
				'callback' => array( $this, 'sectionWidgetTemplates' ),
			),
			'sidebar-alters' => array(
				'title' => __('Altered Sidebars', 'widgetwrangler'),
				'description' => __('', 'widgetwrangler'),
				'callback' => array( $this, 'sectionSidebarAlters' ),
			),
		);
	}

	/**
	 * Table of Contents
	 */
	function sectionToc() {
	    $sections = $this->sections();
	    array_shift( $sections );
	    ?>
        <ul>
        <?php foreach( $sections as $key => $section ) { ?>
            <li><a href="#<?php print $key; ?>"><?php print $section['title']; ?></a></li>
        <?php } ?>
        </ul>
        <?php
    }

	/**
	 * Getting started overview.
	 */
	function sectionGettingStarted() {
		?>
        <h3><?php _e('Overview', 'widgetwrangler'); ?></h3>
        <p><?php _e('It\'s important to understand that Widget Wrangler creates a new Post Type in WordPress of the post_type "widget". Since WordPress already has a concept called "widgets" this can be confusing. To counter this confusion, the widgets that come with WordPress should always be called "WordPress widgets", while Widget Wrangler widgets will just be called "widgets".', 'widgetwrangler'); ?></p>

        <h3><?php _e('Terminology', 'widgetwrangler'); ?></h3>
        <dl>
            <dt><?php _e('Widget', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('A Post in WordPress of the post_type <code>widget</code>', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('Corral', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('A group of Widgets.', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('WordPress Widget', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('A class widget in the WordPress system that you can manage by visiting to following location on the administrator dashboard: Appearance > Widgets', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('Sidebar', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('A group of WordPress widgets. Sidebars are defined by the WordPress theme.', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('Preset', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('A saved set of corrals with widgets in them. Presets are reusable throughout the site.', 'widgetwrangler'); ?></p>
            </dd>
        </dl>

        <p class="toggle-next-content"><a><?php _e('Introduction Video', 'widgetwrangler'); ?></a></p>
        <div class="togglable-content">
            <p class="description"><?php _e('This video is over 4 years old, but the steps are the same.', 'widgetwrangler'); ?></p>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/oW2NgtwUuHE" frameborder="0" allowfullscreen></iframe>
        </div>
		<?php
	}

	/**
	 * Setup
	 */
	function sectionSetup() {
		?>
        <h3><?php _e('Theme Setup', 'widgetwrangler'); ?></h3>
        <p><?php _e('The easiest and most common way to setup Widget Wrangler is to create a Corral within each of your theme Sidebars. Since this setup is so common, there is a button on the plugin Settings page that will do this for you automatically.', 'widgetwrangler'); ?></p>
        <p><a href="edit.php?post_type=widget&page=settings#setup-theme-tool"><?php _e('Single click setup', 'widgetwrangler'); ?></a></p>

        <p><?php _e('To this manually, you would perform the following things for each of your theme Sidebars:', 'widgetwrangler'); ?></p>

        <ol>
            <li><?php _e('Visit <a href="edit.php?post_type=widget&page=corrals">Widget Wrangler > Corrals</a>, and create a new Corral.', 'widgetwrangler'); ?></li>
            <li><?php _e('Create a new Corral.', 'widgetwrangler'); ?></li>
            <li><?php _e('Visit Appearance > Widgets', 'widgetwrangler'); ?></li>
            <li><?php _e('Add a new <span class="ww-highlight">Widget Wrangler - Corral</span> widget to one of your theme Sidebars. In the settings for that WordPress Widget, select the Corral you just created.', 'widgetwrangler'); ?></li>
        </ol>

        <p><?php _e('For example, if your theme has two Sidebars named "Blog Sidebar" and "Footer 1", you would do the following things:', 'widgetwrangler'); ?></p>

        <ol>
            <li><?php _e('Create a new Corral named "Right Sidebar".', 'widgetwrangler'); ?></li>
            <li><?php _e('Create a new Corral named "Footer 1".', 'widgetwrangler'); ?></li>
            <li><?php _e('On Appearance > Widgets, add a <span class="ww-highlight">Widget Wrangler - Corral</span> WordPress Widget to the "Blog Sidebar" and in its settings choose the Corral named "Right Sidebar"', 'widgetwrangler'); ?></li>
            <li><?php _e('On Appearance > Widgets, add a <span class="ww-highlight">Widget Wrangler - Corral</span> WordPress Widget to the "Footer 1" and in its settings choose the Corral named "Footer 1"', 'widgetwrangler'); ?></li>
        </ol>

        <p><?php _e('The results should look like this', 'widgetwrangler'); ?>:</p>
        <p><img src="<?php print $this->imgUrl('corral-widgets-in-sidebar'); ?>" alt="<?php _e('Corral WordPress Widgets in Sidebars', 'widgetwrangler'); ?>"></p>
		<?php
	}

	/**
	 * Widget Post Type.
	 */
	function sectionWidgetPostType() {
		?>
        <h3><?php _e('Post Type - Widget', 'widgetwrangler'); ?></h3>
        <p><?php _e('Widget Wrangler widgets are just like Posts and Pages in WordPress. This means your widgets have a WYSIWYG editor, file attachments, any other feature a Post might have.', 'widgetwrangler'); ?></p>
        <p><?php _e('Actually, there are two types of Widget Wrangler widgets:', 'widgetwrangler'); ?></p>
        <dl>
            <dt><?php _e('Standard', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('Standard widgets are normal content. They have titles, the WYSIWYG editor, featured image, file attachements, and any other feature a Post might have.', 'widgetwrangler'); ?></p>
                <p><?php _e('To create a Standard widget, visit <a href="post-new.php?post_type=widget">Widget Wrangler > Add New Widget</a> on the administrator dashboard.', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('Clone', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('A Clone widget is an instance of a WordPress Widget that is being controlled by Widget Wrangler. It has the same settings the original WordPress Widget has.', 'widgetwrangler'); ?></p>
                <p><?php _e('To create a Clone widget, visit <a href="edit.php?post_type=widget&page=clone">Widget Wrangler > Copy WP Widget</a> on the administrator dashboard. Then configure the widget you would like to copy and save it.', 'widgetwrangler'); ?></p>
            </dd>
        </dl>
        <h3><?php _e('Widget Features', 'widgetwrangler'); ?></h3>
        <p><?php _e('Both standard and clone widgets have to following features and settings.', 'widgetwrangler'); ?></p>
        <dl>
            <dt><?php _e('Shortcodes', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('Any widget can be placed on a Post or Page by using its shortcode. The Shortcodes can be used with either the widget ID or slug. Example: <code>[ww_widget slug="my-widget-slug"]</code>', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('Hide widget title', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('Disable the widget title when it is shown to users.', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('Hide widget from Wrangler', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('Disable the widget from appearing on the form when placing widgets. This is useful for widgets that are only meant to be used as shortcodes.', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('Display Logic', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('You can use custom PHP code to determine if a widget should be shown on a page.', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('Custom Template Suggestion', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('Provide the name of a widget template that will be used to render the widget.', 'widgetwrangler'); ?></p>
            </dd>
        </dl>
        <p><?php _e('Standard widgets have the following additional settings.', 'widgetwrangler'); ?></p>
        <dl>
            <dt><?php _e('Automatic Paragraphs', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('Automatically add paragraph HTML to the widget content when it is rendered. This makes it act more like Posts and Pages.', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('Advanced Parsing', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('Replace the content of the widget with custom PHP code.', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('Template Advanced Parsing', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('Allow the custom PHP from the Advanced parsing area to be rendered within the normal widget template. This requires that the PHP in the Advanced Parsing area to return an array with "title" and "content" keys.', 'widgetwrangler'); ?></p>
            </dd>
        </dl>
        <p><?php _e('More help is available for each feature within meta boxes on the Widget edit page.', 'widgetwrangler'); ?></p>
		<?php
	}

	/**
	 * Corrals.
	 */
	function sectionCorrals() {
		?>
        <h3><?php _e('Corrals', 'widgetwrangler'); ?></h3>
        <p><?php _e('A Corral is a container for widgets. On their own, they don\'t do anything special.', 'widgetwrangler'); ?></p>
        <p><?php _e('When editing a WordPress Page you can chose which widgets are assigned each corral. Then when the corral is shown, the widgets assigned to the corral for the Page being viewed will be displayed.', 'widgetwrangler'); ?></p>
        <p><?php _e('The most common way to use Corrals is to place one within each WordPress Sidebar, but there is nothing stopping you from using more Corrals within a Sidebar if you want or need to.', 'widgetwrangler'); ?></p>
		<?php
	}

	/**
	 * Presets.
	 */
	function sectionPresets() {
		?>
		<?php _e('', 'widgetwrangler'); ?>
        <h3><?php _e('Presets - Saved Set of Widgets', 'widgetwrangler'); ?></h3>
        <p><?php _e('A Preset is a saved configuration of widgets within Corrals that are reusable anywhere in Widget Wrangler. You can create new Presets by visiting <a href="edit.php?post_type=widget&page=presets">Widget Wrangler > Presets</a> and clicking the <span class="ww-highlight">Create New Preset</span> button.', 'widgetwrangler'); ?></p>
        <p><?php _e('There are two types of Presets:', 'widgetwrangler'); ?></p>
        <dl>
            <dt><?php _e('Core', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('Core presets come pre-installed with Widget Wrangler and cannot be deleted.', 'widgetwrangler'); ?></p>
                <p><?php _e('The <span class="ww-highlight">Default</span> preset is used to determine the widgets to be shown by default on any given page. When widgets are not configured specifically for a page, the Default preset is shown.', 'widgetwrangler'); ?></p>
                <p><?php _e('The <span class="ww-highlight">Posts Page</span> preset is used to determine the widgets to be shown on the WordPress Blog page.', 'widgetwrangler'); ?></p>
            </dd>
            <dt><?php _e('Standard', 'widgetwrangler'); ?></dt>
            <dd>
                <p><?php _e('Standard Presets can be created and deleted as desired by the site administrator.', 'widgetwrangler'); ?></p>
            </dd>
        </dl>
        <p><?php _e('When you change the widgets withing a Preset, all Pages using that Preset will get updated.', 'widgetwrangler'); ?></p>
        <p><?php _e('Generally speaking, you can manage all your site widgets with Presets, and not need to change widgets on every Page. This is the ideal way to use Widget Wrangler as it is much easier to maintain and there is less room for error.', 'widgetwrangler'); ?></p>
		<?php
	}

	/**
	 * Theme compatibility
	 */
	function sectionThemeCompatibility() {
		?>
        <h3><?php _e('Setting - Theme Compatibility', 'widgetwrangler'); ?></h3>
        <p><?php _e('Enabled by default on new installations.', 'widgetwrangler'); ?></p>
        <p><?php _e('With theme compatibility enabled Widget Wrangler will attempt to wrap widgets within the appropriate Sidebar HTML. This occurs when a widget appears within a WordPress Sidebar through use of the <span class="ww-highlight">Widget Wrangler - Corral</span> and <span class="ww-highlight">Widget Wrangler - Widget</span> WordPress Widgets.', 'widgetwrangler'); ?></p>
        <p><?php _e('Sidebar HTML is defined when the sidebar is registered by the theme. If you are a theme developer, you will recognize these as: <code>$before_widget</code>, <code>$before_title</code>, <code>$after_title</code>, <code>$after_widget</code>.', 'widgetwrangler'); ?></p>
        <p><?php _e('If you are a theme developer and want to control widget output with templates in your theme, this setting is not necessary. You will still need to register Sidebars in your theme, but the Side HTML will not ever be used.', 'widgetwrangler'); ?></p>
		<?php
	}

	/**
	 * Sidebar Altering
	 */
	function sectionSidebarAlters() {
		?>
        <h3><?php _e('Alter Sidebar HTML', 'widgetwrangler'); ?></h3>
        <p><?php _e('This utility allows you to override the HTML for the theme WordPress Sidebar. It uses the same variables as the Sidebar uses when being registered: <code>$before_widget</code>, <code>$before_title</code>, <code>$after_title</code>, <code>$after_widget</code> ', 'widgetwrangler'); ?></p>
        <p><?php _e('This feature is only available when the Theme Compatibility setting is enabled. You can access it by visting <a href="edit.php?post_type=widget&page=sidebars">Widget Wrangler > Sidebars</a>', 'widgetwrangler'); ?></p>
		<?php
	}

	function sectionWidgetTemplates() {
		?>
        <h3><?php _e('Templates', 'widgetwrangler'); ?></h3>
        <p><?php _e('Widget Wrangler uses a custom templating system that allows for highly granular control over the HTML for widgets. When a widget is being displayed Widget Wrangler searches for the appropriate template to use in order of priority.', 'widgetwrangler'); ?></p>
        <p><?php _e('It first looks within the current active theme folder for template files matching any suggestions for the widget, and if no suggestion is found, it will default to the widget.php template provided by the plugin.', 'widgetwrangler'); ?></p>
        <p><?php _e('The default <span class="ww-highlight">template suggestions</span> are as follows:', 'widgetwrangler'); ?></p>
        <ul class="list">
            <li><code>widget--corral_{corral_slug}--{widget_id}.php</code></li>
            <li><code>widget--corral_{corral_slug}--{post_name}.php</code></li>
            <li><code>widget--{widget_id}.php</code></li>
            <li><code>widget--{post_name}.php</code></li>
            <li><code>widget--corral_{corral_slug}--type_{widget_type}.php</code></li>
            <li><code>widget--type_{widget_type}.php</code></li>
            <li><code>widget--corral_{corral_slug}.php</code></li>
            <li><code>widget.php</code></li>
        </ul>
        <p><?php _e('The first template found for a widget is the one used to render the widget. This means that more specific the template name will take precedence over the more general ones.', 'widgetwrangler'); ?></p>
        <h3><?php _e('Creating Templates', 'widgetwrangler'); ?></h3>
        <p><?php _e('You can create templates in your theme by following these steps:', 'widgetwrangler'); ?></p>
        <ol>
            <li><?php _e('Locate the default template within the plugin files. Generally this can be located at <code>wp-content/plugins/widget-wrangler/templates/widget-1x.php</code> relative to your WordPress installation.', 'widgetwrangler'); ?></li>
            <li>
                <p><?php _e('Copy the contents of that template to a new file within your theme. The new file should follow the naming convention seen in the above list of template suggestions.', 'widgetwrangler'); ?></p>
                <p><?php _e('For example, if you have a corral named "Footer" and you give the new template the name of <code>widget--corral_footer.php</code>, that template will be used to render any widget within the "Footer" Corral.', 'widgetwrangler'); ?></p>
            </li>
        </ol>
        <p><?php _e('You can also provide each widget with an arbitrary <span class="ww-highlight">custom template suggestion</span>. For example, say you want to make it very easy to remove all wrapping HTML from a widget:', 'widgetwrangler'); ?></p>
        <ol>
            <li>
                <?php _e('Create a new widget template named <code>widget-nowrapper.php</code> with the contents of:', 'widgetwrangler'); ?><br><code>&lt;?php print $widget->post_content; ?&gt;</code>.
            </li>
            <li><?php _e('Then edit the widget you would like to use that template for and type in <code>nowrapper</code> as the <span class="ww-highlight">Custom Template Suggestion</span> setting.', 'widgetwrangler'); ?></li>
        </ol>

        <h3><?php _e('Starter Widget Template', 'widgetwrangler'); ?></h3>
        <p><?php _e('This template is a great place to get started when creating a new widget template. This is an exact copy of the template found at <code>wp-content/plugins/widget-wrangler/templates/widget-1x.php</code>.', 'widgetwrangler'); ?></p>
        <pre class="code"><?php print htmlentities( file_get_contents( WW_PLUGIN_DIR.'/templates/widget-1x.php' ) ); ?></pre>
		<?php
	}

}
