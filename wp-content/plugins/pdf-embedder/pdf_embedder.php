<?php

/**
 * Plugin Name: PDF Embedder
 * Plugin URI: http://wp-pdf.com/
 * Description: Embed PDFs straight into your posts and pages, with flexible width and height. No third-party services required. 
 * Version: 2.2.2
 * Author: Dan Lester
 * Author URI: http://wp-pdf.com/
 * License: GPL3
 */

require_once( plugin_dir_path(__FILE__).'/core/core_pdf_embedder.php' );

class pdfemb_basic_pdf_embedder extends core_pdf_embedder {

	protected $PLUGIN_VERSION = '2.2.2';
	
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
			wp_register_script( 'pdfemb_versionspecific_pdf_js', $this->my_plugin_url().'js/pdfemb-basic.js', array('jquery'));
			wp_register_script( 'pdfemb_grabtopan_js', $this->my_plugin_url().'js/grabtopan-basic.js', array('jquery'));
			wp_register_script( 'pdfemb_embed_pdf_js', $this->my_plugin_url().'js/pdfemb-embed-pdf.js', array('pdfemb_versionspecific_pdf_js', 'pdfemb_grabtopan_js', 'jquery') );
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
	
	protected function pdfemb_mobilesection_text()
    {
        ?>

        <h2>Mobile-friendly embedding using PDF Embedder Premium</h2>
        <p>This free version of the plugin should work on most mobile browsers, but it will be cumbersome for users with
            small screens - it is difficult to position
            the document entirely within the screen, and your users' fingers may catch the entire browser page when
            they're trying only to move about the document...</p>

        <p>Our <b>PDF Embedder Premium</b> plugin solves this problem with an intelligent 'full screen' mode.
            When the document is smaller than a certain width, the document displays only as a 'thumbnail' with a large
            'View in Full Screen' button for the
            user to click when they want to study your document.
            This opens up the document so it has the full focus of the mobile browser, and the user can move about the
            document without hitting other parts of
            the web page by mistake. Click Exit to return to the regular web page.
        </p>

        <p>See our website <a
                href="http://wp-pdf.com/premium/?utm_source=PDF%20Settings%20Premium&utm_medium=freemium&utm_campaign=Freemium">wp-pdf.com</a>
            for more
            details and purchase options.
        </p>

        <?php
    }

	protected function extra_plugin_action_links( $links ) {
        $secure_link = '<a href="http://wp-pdf.com/secure/?utm_source=Plugins%20Secure&utm_medium=freemium&utm_campaign=Freemium" target="_blank">Secure</a>';
        $mobile_link = '<a href="http://wp-pdf.com/premium/?utm_source=Plugins%20Premium&utm_medium=freemium&utm_campaign=Freemium" target="_blank">Mobile</a>';

        array_unshift( $links, $secure_link );
        array_unshift( $links, $mobile_link );

        return $links;
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