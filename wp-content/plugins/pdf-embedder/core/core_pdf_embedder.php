<?php

class core_pdf_embedder {
	
	protected function useminified() {
		return true;
	}
	
	protected function __construct() {
		$this->add_actions();
		register_activation_hook($this->my_plugin_basename(), array( $this, 'pdfemb_activation_hook' ) );
	}
	
	// May be overridden in basic or premium
	public function pdfemb_activation_hook($network_wide) {
	}
	
	public function pdfemb_wp_enqueue_scripts() {
	}
	
	protected $inserted_scripts = false;
	protected function insert_scripts() {
		if (!$this->inserted_scripts) {
			$this->inserted_scripts = true;
			wp_enqueue_script( 'pdfemb_embed_pdf_js' );
			
			wp_enqueue_script( 'pdfemb_pdf_js' );
			
			wp_enqueue_style( 'pdfemb_embed_pdf_css', $this->my_plugin_url().'css/pdfemb-embed-pdf.css' );
		}
	}
	
	protected function get_translation_array() {
		return Array('worker_src' => $this->my_plugin_url().'js/pdfjs/pdf.worker'.($this->useminified() ? '.min' : '').'.js',
		             'cmap_url' => $this->my_plugin_url().'js/pdfjs/cmaps/');
	}
	
	protected function get_extra_js_name() {
		return '';
	}
		
	
	// SHORTCODES
	
	// Take over PDF type in media gallery
	public function pdfemb_upload_mimes($existing_mimes = array()) {
		$existing_mimes['pdf'] = 'application/pdf';
		return $existing_mimes;
	}
	
	public function pdfemb_post_mime_types($post_mime_types) {
		$post_mime_types['application/pdf'] = array( __( 'PDFs' ), __( 'Manage PDFs' ), _n_noop( 'PDF <span class="count">(%s)</span>', 'PDFs <span class="count">(%s)</span>' ) );
		return $post_mime_types;
	}
	
	// Embed PDF shortcode instead of link
	public function pdfemb_media_send_to_editor($html, $id, $attachment) {
		if (preg_match( "/\.pdf$/i", $attachment['url'])) {
			return '[pdf-embedder url="' . $attachment['url'] . '"]';
		} else {
			return $html;
		}
	}
	
	protected function modify_pdfurl($url) {
		return $url;
	}
	
	/* public function pdfemb_wp_get_attachment_link( $link, $id, $size, $permalink, $icon, $text ) {
		return $link;
	}*/

	public function pdfemb_shortcode_display_pdf($atts, $content=null) {
		if (!isset($atts['url'])) {
			return '<b>PDF Embedder requires a url attribute</b>';
		}
		$url = $atts['url'];
		
		$this->insert_scripts();
		
		$width = isset($atts['width']) ? $atts['width'] : 'max';
		$height = isset($atts['height']) ? $atts['height'] : 'auto';
		
		$extra_style = "";
		if (is_numeric($width)) {
			$extra_style .= "width: ".$width."px; ";
		}
		if (is_numeric($height)) {
			$extra_style .= "height: ".$height."px; ";
		}
		
		$toolbar = isset($atts['toolbar']) && in_array($atts['toolbar'], array('top', 'bottom', 'both')) ? $atts['toolbar'] : 'bottom';
		
		$toolbar_fixed = isset($atts['toolbarfixed']) && $atts['toolbarfixed'] == 'on' ? 'on' : 'off';
		
		$returnhtml = '<div class="pdfemb-viewer" data-pdf-url="'.esc_attr($this->modify_pdfurl($url)).'" style="'.esc_attr($extra_style).'" '
						.'data-width="'.esc_attr($width).'" data-height="'.esc_attr($height).'" ';
		
		$returnhtml .= $this->extra_shortcode_attrs($atts, $content);
						
		$returnhtml .= ' data-toolbar="'.$toolbar.'" data-toolbar-fixed="'.$toolbar_fixed.'"></div>';
		
		if (!is_null($content)) {
			$returnhtml .= do_shortcode($content);
		}
		return $returnhtml;
	}
	
	protected function extra_shortcode_attrs($atts, $content=null) {
		return '';
	}
	
	// ADMIN OPTIONS
	// *************
	
	protected function get_options_menuname() {
		return 'pdfemb_list_options';
	}
	
	protected function get_options_pagename() {
		return 'pdfemb_options';
	}
	
	protected function get_settings_url() {
		return is_multisite()
		? network_admin_url( 'settings.php?page='.$this->get_options_menuname() )
		: admin_url( 'options-general.php?page='.$this->get_options_menuname() );
	}
	
	public function pdfemb_admin_menu() {
		if (is_multisite()) {
			add_submenu_page( 'settings.php', 'PDF Embedder settings', 'PDF Embedder',
			'manage_network_options', $this->get_options_menuname(),
			array($this, 'pdfemb_options_do_page'));
		}
		else {
			add_options_page( 'PDF Embedder settings', 'PDF Embedder',
			'manage_options', $this->get_options_menuname(),
			array($this, 'pdfemb_options_do_page'));
		}
	}
	
	public function pdfemb_options_do_page() {
	
		$submit_page = is_multisite() ? 'edit.php?action='.$this->get_options_menuname() : 'options.php';
	
		if (is_multisite()) {
			$this->pdfemb_options_do_network_errors();
		}
		?>
			  
		<div>
		
		<h2>PDF Embedder setup</h2>
		
		<div id="pdfemb-tablewrapper">
		
		<?php $this->pdfemb_mainsection_text(); ?>

		<form action="<?php echo $submit_page; ?>" method="post" id="pdfemb_form">
		
		<?php 
		settings_fields($this->get_options_pagename());
		
		$this->pdfemb_options_submit();
		?>
				
		</form>
		</div>
		
		</div>  <?php
	}
	
	// Override elsewhere
	protected function pdfemb_mainsection_text() {
		?>
		<p>There are no settings to configure in PDF Embedder.</p>
		<p>To use the plugin, just embed PDFs in the same way as you would normally embed images in your posts/pages - but try with a PDF file instead.</p>
		<p>From the post editor, click Add Media, and then drag-and-drop your PDF file into the media library. 
		When you insert the PDF into your post, it will automatically embed using the plugin's viewer.</p>
		<?php
	}
	
	protected function pdfemb_options_submit() {
	?>
		<p class="submit">
			<input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit">
		</p>
	<?php
	}
	
	public function pdfemb_options_validate($input) {
		$newinput = Array();
		$newinput['pdfemb_version'] = $this->PLUGIN_VERSION;
		return $newinput;
	}
	
	protected function get_error_string($fielderror) {
		return 'Unspecified error';
	}
	
	public function pdfemb_save_network_options() {
		check_admin_referer( $this->get_options_pagename().'-options' );
	
		if (isset($_POST[$this->get_options_name()]) && is_array($_POST[$this->get_options_name()])) {
			$inoptions = $_POST[$this->get_options_name()];
			
			$outoptions = $this->gdm_options_validate($inoptions);
			
			$error_code = Array();
			$error_setting = Array();
			foreach (get_settings_errors() as $e) {
				if (is_array($e) && isset($e['code']) && isset($e['setting'])) {
					$error_code[] = $e['code'];
					$error_setting[] = $e['setting'];
				}
			}
	
			update_site_option($this->get_options_name(), $outoptions);
				
			// redirect to settings page in network
			wp_redirect(
			add_query_arg(
			array( 'page' => $this->get_options_menuname(),
			'updated' => true,
			'error_setting' => $error_setting,
			'error_code' => $error_code ),
			network_admin_url( 'admin.php' )
			)
			);
			exit;
		}
	}
	
	protected function pdfemb_options_do_network_errors() {
		if (isset($_REQUEST['updated']) && $_REQUEST['updated']) {
			?>
					<div id="setting-error-settings_updated" class="updated settings-error">
					<p>
					<strong>Settings saved</strong>
					</p>
					</div>
				<?php
			}
	
			if (isset($_REQUEST['error_setting']) && is_array($_REQUEST['error_setting'])
				&& isset($_REQUEST['error_code']) && is_array($_REQUEST['error_code'])) {
				$error_code = $_REQUEST['error_code'];
				$error_setting = $_REQUEST['error_setting'];
				if (count($error_code) > 0 && count($error_code) == count($error_setting)) {
					for ($i=0; $i<count($error_code) ; ++$i) {
						?>
					<div id="setting-error-settings_<?php echo $i; ?>" class="error settings-error">
					<p>
					<strong><?php echo htmlentities2($this->get_error_string($error_setting[$i].'|'.$error_code[$i])); ?></strong>
					</p>
					</div>
						<?php
				}
			}
		}
	}
	
	// OPTIONS
	
	protected function get_default_options() {
		return Array('pdfemb_version' => $this->PLUGIN_VERSION);
	}
	
	protected $pdfemb_options = null;
	protected function get_option_dbe() {
		if ($this->pdfemb_options != null) {
			return $this->pdfemb_options;
		}
	
		$option = get_site_option($this->get_options_name(), Array());
	
		$default_options = $this->get_default_options();
		foreach ($default_options as $k => $v) {
			if (!isset($option[$k])) {
				$option[$k] = $v;
			}
		}
	
		$this->pdfemb_options = $option;
		return $this->pdfemb_options;
	}
	
	// ADMIN
	
	public function pdfemb_admin_init() {
		// Add PDF as a supported upload type to Media Gallery
		add_filter( 'upload_mimes', array($this, 'pdfemb_upload_mimes') );
		
		// Filter for PDFs in Media Gallery
		add_filter( 'post_mime_types', array($this, 'pdfemb_post_mime_types') );

		// Embed PDF shortcode instead of link
		add_filter( 'media_send_to_editor', array($this, 'pdfemb_media_send_to_editor'), 20, 3 );
		
		register_setting( $this->get_options_pagename(), $this->get_options_name(), Array($this, 'pdfemb_options_validate') );
	}
	
	// Override in Premium
	public function pdfemb_init() {
	}
	
	protected function add_actions() {

		add_action( 'init', array($this, 'pdfemb_init') );
		
		add_action( 'wp_enqueue_scripts', array($this, 'pdfemb_wp_enqueue_scripts'), 5, 0 );
		add_shortcode( 'pdf-embedder', Array($this, 'pdfemb_shortcode_display_pdf') );
		
		// When viewing attachment page, embded document instead of link
		// add_filter( 'wp_get_attachment_link', array($this, 'pdfemb_wp_get_attachment_link'), 20, 6 );
		
		if (is_admin()) {
			add_action( 'admin_init', array($this, 'pdfemb_admin_init'), 5, 0 );
			
			add_action(is_multisite() ? 'network_admin_menu' : 'admin_menu', array($this, 'pdfemb_admin_menu'));
			
			if (is_multisite()) {
				add_action('network_admin_edit_'.$this->get_options_menuname(), array($this, 'pdfemb_save_network_options'));
			}
		}
	}

}


?>