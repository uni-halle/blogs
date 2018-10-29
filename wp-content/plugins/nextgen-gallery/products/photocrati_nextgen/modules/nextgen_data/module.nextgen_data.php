<?php

/***
{
Module: photocrati-nextgen-data,
Depends: { photocrati-datamapper }
}
 ***/

class M_NextGen_Data extends C_Base_Module
{
	function define($id = 'pope-module',
		$name = 'Pope Module',
		$description = '',
		$version = '',
		$uri = '',
		$author = '',
		$author_uri = '',
		$context = FALSE)
	{
		parent::define(
			'photocrati-nextgen-data',
			'NextGEN Data Tier',
			"Provides a data tier for NextGEN gallery based on the DataMapper module",
			'3.0.0',
			'https://www.imagely.com/wordpress-gallery-plugin/nextgen-gallery/',
			'Imagely',
			'https://www.imagely.com'
		);

		C_Photocrati_Installer::add_handler($this->module_id, 'C_NextGen_Data_Installer');
	}

	function _register_adapters()
	{
		$this->get_registry()->add_adapter('I_Component_Factory', 'A_NextGen_Data_Factory');
		#$this->get_registry()->add_adapter('I_CustomPost_DataMapper', 'A_Attachment_DataMapper', 'attachment');
		$this->get_registry()->add_adapter('I_Installer', 'A_NextGen_Data_Installer');
	}


	function _register_utilities()
	{
		$this->get_registry()->add_utility('I_Gallery_Mapper', 'C_Gallery_Mapper');
		$this->get_registry()->add_utility('I_Image_Mapper', 'C_Image_Mapper');
		$this->get_registry()->add_utility('I_Album_Mapper', 'C_Album_Mapper');
		$this->get_registry()->add_utility('I_Gallery_Storage', 'C_Gallery_Storage');
	}

	function get_type_list()
	{
		return array(
			'C_Exif_Writer_Wrapper' => 'class.exif_writer_wrapper.php',
			'A_Attachment_Datamapper' => 'adapter.attachment_datamapper.php',
			'A_Customtable_Sorting_Datamapper' => 'adapter.customtable_sorting_datamapper.php',
			'A_Nextgen_Data_Factory' => 'adapter.nextgen_data_factory.php',
			'C_NextGen_Data_Installer' => 'class.nextgen_data_installer.php',
			'A_Parse_Image_Metadata' => 'adapter.parse_image_metadata.php',
			'C_Album' => 'class.album.php',
			'C_Gallery' => 'class.gallery.php',
			'C_Image' => 'class.image.php',
			'C_Album_Mapper' => 'class.album_mapper.php',
			'C_Gallerystorage_Base' => 'class.gallerystorage_base.php',
			'C_Gallerystorage_Driver_Base' => 'class.gallerystorage_driver_base.php',
			'C_Gallery_Mapper' => 'class.gallery_mapper.php',
			'C_Gallery_Storage' => 'class.gallery_storage.php',
			'C_Image_Mapper' => 'class.image_mapper.php',
			'C_Image_Wrapper' => 'class.image_wrapper.php',
			'C_Image_Wrapper_Collection' => 'class.image_wrapper_collection.php',
			'C_Nextgen_Metadata' => 'class.nextgen_metadata.php',
			'Mixin_NextGen_Table_Extras'	=>	'mixin.nextgen_table_extras.php',
			'C_Ngglegacy_Gallerystorage_Driver' => 'class.ngglegacy_gallerystorage_driver.php',
			'C_Ngglegacy_Thumbnail' => 'class.ngglegacy_thumbnail.php',
			'C_Wordpress_Gallerystorage_Driver' => 'class.wordpress_gallerystorage_driver.php'
		);
	}


	function _register_hooks()
	{
		add_action('init', array(&$this, 'register_custom_post_types'));
		add_filter('posts_orderby', array($this, 'wp_query_order_by'), 10, 2);
	}

	function register_custom_post_types()
	{
		$types = array(
			'ngg_album'		=>	'NextGEN Gallery - Album',
			'ngg_gallery'	=>	'NextGEN Gallery - Gallery',
			'ngg_pictures'	=>	'NextGEN Gallery - Image',
		);

		foreach ($types as $type => $label) {
			register_post_type($type, array(
				'label'					=>	$label,
				'publicly_queryable'	=>	FALSE,
				'exclude_from_search'	=>	TRUE,
			));
		}
	}

	function wp_query_order_by($order_by, $wp_query)
	{
		if ($wp_query->get('datamapper_attachment'))
		{
			$order_parts = explode(' ', $order_by);
			$order_name = array_shift($order_parts);

			$order_by = 'ABS(' . $order_name . ') ' . implode(' ', $order_parts) . ', ' . $order_by;
		}

		return $order_by;
	}

	static function strip_html($data, $just_scripts=FALSE)
	{
		$retval = $data;

		if (!$just_scripts)
		{
			// Remove *ALL* HTML and tag contents
			$retval = wp_strip_all_tags($retval, TRUE);
		}
		else {
			// Allows HTML to remain but we strip nearly all attributes, strip all
			// <script> tags, and sanitize hrefs to prevent javascript.
			//
			// This can generate a *lot* of warnings when given improper texts
			libxml_use_internal_errors(true);
			libxml_clear_errors();

			$allowed_attributes = array(
			    '*' => array('id', 'class', 'href', 'name', 'title', 'rel', 'style'),
                'img' => array('src', 'alt', 'title')
            );

			if (is_object($data))
			{
				// First... recurse to the deepest elements and work back & upwards
				if ($data->hasChildNodes())
				{
					foreach (range($data->childNodes->length - 1, 0) as $i) {
						self::strip_html($data->childNodes->item($i), TRUE);
					}
				}

				// Remove disallowed elements and content
				if ($data instanceof DOMElement) {
					foreach ($data->getElementsByTagName('script') as $deleteme) {
						$data->removeChild($deleteme);
					}
				}

				// Strip (nearly) all attributes
				if (!empty($data->attributes))
				{
					// DOMDocument reindexes as soon as any changes are made so we
					// must loop through attributes backwards
					for ($i = $data->attributes->length - 1; $i >= 0; --$i) {
						$item = $data->attributes->item($i);
						$name = $item->nodeName;

						$allowed = FALSE;
						foreach ($allowed_attributes as $element_type => $attributes) {
                            if (($data->tagName == $element_type || $element_type == '*')
                            &&  in_array($name, $attributes)) {
                                    $allowed = TRUE;
                            }
                        }

                        if (!$allowed)
							$data->removeAttribute($name);

						// DO NOT EVER allow href="javascript:...."
						if (strpos($item->nodeValue, 'javascript:') === 0)
							$item->nodeValue = '#';
					}
				}
			}
			else {
				$dom = new DOMDocument();

				if (!empty($data))
				{
					// Because DOMDocument wraps saveHTML() with HTML headers & tags we use
					// this placeholder to retrieve *just* the original given text
					$id = 'ngg_data_strip_html_placeholder';
					$start = "<div id=\"{$id}\">";
					$end = '</div>';
					$start_length = strlen($start);
					$end_length = strlen($end);

					// Prevent attempted work-arounds using &lt; and &gt; or other html entities
					$data = html_entity_decode($data);

					// This forces DOMDocument to treat the HTML as UTF-8
					$meta = '<meta http-equiv="Content-Type" content="charset=utf-8"/>';
					$data = $meta . $start . $data . $end;

					$dom->loadHTML($data);

					// Invoke the actual work
					self::strip_html($dom->documentElement, TRUE);

					// Export back to text
					//
					// TODO: When PHP 5.2 support is dropped we can use the target parameter
					// of the following saveHTML and rid ourselves of some of the nonsense
					// workarounds to the fact that DOMDocument used to force the output to
					// include full HTML/XML doctype and root elements.
					$retval = $dom->saveXML();

					// saveXML includes the full doctype and <html><body></body></html> wrappers
					// so we first drop everything generated up to our wrapper and chop off the
					// added end wrappers
					$position = strpos($retval, $start);
					$retval  = substr($retval, $position, -15);

					// Lastly remove our wrapper
					$retval = substr($retval, $start_length, -$end_length);
				}
				else {
					$retval = '';
				}
			}
		}

		return $retval;
	}
}
new M_NextGen_Data();