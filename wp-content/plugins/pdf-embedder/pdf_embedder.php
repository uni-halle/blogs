<?php

/**
 * Plugin Name: PDF Embedder
 * Plugin URI: http://wp-pdf.com/
 * Description: Embed PDFs straight into your posts and pages, with flexible width and height. No third-party services required. 
 * Version: 1.2.1
 * Author: Dan Lester
 * Author URI: http://wp-pdf.com/
 * License: GPL3
 */

require_once( plugin_dir_path(__FILE__).'/core/core_pdf_embedder.php' );

class pdfemb_basic_pdf_embedder extends core_pdf_embedder {
	
	protected function useminified() {
		/* using-minified */ return true;
	}
	
	// Singleton
	private static $instance = null;
	
	public static function get_instance() {
		if (null == self::$instance) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	// Basic specific
	
	public function pdfemb_wp_enqueue_scripts() {
		if (!$this->useminified()) {
			wp_register_script( 'pdfemb_versionspecific_pdf_js', $this->my_plugin_url().'js/pdfemb-basic.js');
			wp_register_script( 'pdfemb_embed_pdf_js', $this->my_plugin_url().'js/pdfemb-embed-pdf.js', array('pdfemb_versionspecific_pdf_js', 'jquery') );
		}
		else {
			wp_register_script( 'pdfemb_embed_pdf_js', $this->my_plugin_url().'js/all-pdfemb-basic.min.js', array('jquery') );
		}
		
		wp_localize_script( 'pdfemb_embed_pdf_js', 'pdfemb_trans', $this->get_translation_array() );
	
		wp_register_script( 'pdfemb_compat_js', $this->my_plugin_url().'js/pdfjs/compatibility'.($this->useminified() ? '.min' : '').'.js');
		wp_register_script( 'pdfemb_pdf_js', $this->my_plugin_url().'js/pdfjs/pdf'.($this->useminified() ? '.min' : '').'.js', array('pdfemb_compat_js'));
	}
	
	protected function get_extra_js_name() {
		return 'basic';
	}
	
	// ADMIN
	
	protected function get_options_name() {
		return 'pdfemb_basic';
	}
	
	// Don't need a submit button here
	protected function pdfemb_options_submit() {
	}
	
	protected function pdfemb_mainsection_text() {
		parent::pdfemb_mainsection_text();
		?>
		<h2>Protect your PDFs using PDF Embedder Secure</h2>
		<p>Our premium PDF Embedder Secure plugin provides the same simple but elegant viewer for your website visitors, with the added protection that 
		it is difficult for them to download or print the original PDF document.</p>

		<p>This means that your PDF is unlikely to be shared outside your site where you have no control over who views, prints, or shares it.</p>

		<p>See our website <a href="http://wp-pdf.com/secure/?utm_source=PDF%20Settings&utm_medium=freemium&utm_campaign=Freemium">wp-pdf.com</a> for more 
		details and purchase options.
		</p>
		<?php
	}
	
	// AUX
	
	protected function my_plugin_basename() {
		$basename = plugin_basename(__FILE__);
		if ('/'.$basename == __FILE__) { // Maybe due to symlink
			$basename = basename(dirname(__FILE__)).'/'.basename(__FILE__);
		}
		return $basename;
	}
	
	protected function my_plugin_url() {
		$basename = plugin_basename(__FILE__);
		if ('/'.$basename == __FILE__) { // Maybe due to symlink
			return plugins_url().'/'.basename(dirname(__FILE__)).'/';
		}
		// Normal case (non symlink)
		return plugin_dir_url( __FILE__ );
	}
	
}

// Global accessor function to singleton
function pdfembPDFEmbedder() {
	return pdfemb_basic_pdf_embedder::get_instance();
}

// Initialise at least once
pdfembPDFEmbedder();

?>