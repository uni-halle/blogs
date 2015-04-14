<?php
/**
 * Provides validation for NextGen Basic Albums
 */
class A_NextGen_Basic_Album extends Mixin
{
    public function validation()
    {
        $ngglegacy_albums = array(NGG_BASIC_COMPACT_ALBUM, NGG_BASIC_EXTENDED_ALBUM);
        if (in_array($this->object->name, $ngglegacy_albums)) {
            $this->object->validates_presence_of('gallery_display_type');
            $this->object->validates_numericality_of('galleries_per_page');
        }
        return $this->call_parent('validation');
    }
    public function get_order()
    {
        return NGG_DISPLAY_PRIORITY_BASE + NGG_DISPLAY_PRIORITY_STEP;
    }
}
class A_NextGen_Basic_Album_Controller extends Mixin_NextGen_Basic_Pagination
{
    public $albums = array();
    /**
     * Renders the front-end for the NextGen Basic Album display type
     *
     * @param $displayed_gallery
     * @param bool $return
     */
    public function index_action($displayed_gallery, $return = FALSE)
    {
        $display_settings = $displayed_gallery->display_settings;
        // We need to fetch the album containers selected in the Attach
        // to Post interface. We need to do this, because once we fetch the
        // included entities, we need to iterate over each entity and assign it
        // a parent_id, which is the album that it belongs to. We need to do this
        // because the link to the gallery, is not /nggallery/gallery--id, but
        // /nggallery/album--id/gallery--id
        // Are we to display a gallery?
        if ($gallery = $gallery_slug = $this->param('gallery')) {
            // basic albums only support one per post
            if (isset($GLOBALS['nggShowGallery'])) {
                return;
            }
            $GLOBALS['nggShowGallery'] = TRUE;
            // Try finding the gallery by slug first. If nothing is found, we assume that
            // the user passed in a gallery id instead
            $mapper = C_Gallery_Mapper::get_instance();
            $result = reset($mapper->select()->where(array('slug = %s', $gallery))->limit(1)->run_query());
            if ($result) {
                $gallery = $result->{$result->id_field};
            }
            $renderer = C_Displayed_Gallery_Renderer::get_instance('inner');
            $gallery_params = array('source' => 'galleries', 'container_ids' => array($gallery), 'display_type' => $display_settings['gallery_display_type'], 'original_display_type' => $displayed_gallery->display_type, 'original_settings' => $display_settings);
            if (!empty($display_settings['gallery_display_template'])) {
                $gallery_params['template'] = $display_settings['gallery_display_template'];
            }
            return $renderer->display_images($gallery_params, $return);
        } else {
            if ($album = $this->param('album')) {
                $mapper = C_Album_Mapper::get_instance();
                $result = array_pop($mapper->select()->where(array('slug = %s', $album))->limit(1)->run_query());
                $album_sub = $result ? $result->{$result->id_field} : null;
                if ($album_sub != null) {
                    $album = $album_sub;
                }
                $displayed_gallery->entity_ids = array();
                $displayed_gallery->sortorder = array();
                $displayed_gallery->container_ids = ($album === '0' or $album === 'all') ? array() : array($album);
            }
        }
        // Get the albums
        // TODO: This should probably be moved to the elseif block above
        $this->albums = $displayed_gallery->get_albums();
        // None of the above: Display the main album. Get the settings required for display
        $current_page = (int) $this->param('nggpage', 1);
        $offset = $display_settings['galleries_per_page'] * ($current_page - 1);
        $entities = $displayed_gallery->get_included_entities($display_settings['galleries_per_page'], $offset);
        // If there are entities to be displayed
        if ($entities) {
            if (!empty($display_settings['template'])) {
                // Add additional parameters
                $pagination_result = $this->object->create_pagination($current_page, $displayed_gallery->get_entity_count(), $display_settings['galleries_per_page'], urldecode($this->object->param('ajax_pagination_referrer')));
                $this->object->remove_param('ajax_pagination_referrer');
                $display_settings['current_page'] = $current_page;
                $display_settings['entities'] =& $entities;
                $display_settings['pagination_prev'] = $pagination_result['prev'];
                $display_settings['pagination_next'] = $pagination_result['next'];
                $display_settings['pagination'] = $pagination_result['output'];
                // Render legacy template
                $this->object->add_mixin('Mixin_NextGen_Basic_Templates');
                $display_settings = $this->prepare_legacy_album_params($displayed_gallery->get_entity(), $display_settings);
                return $this->object->legacy_render($display_settings['template'], $display_settings, $return, 'album');
            } else {
                $params = $display_settings;
                $albums = $this->prepare_legacy_album_params($displayed_gallery->get_entity(), array('entities' => $entities));
                $params['image_gen_params'] = $albums['image_gen_params'];
                $params['galleries'] = $albums['galleries'];
                $params['displayed_gallery'] = $displayed_gallery;
                $params = $this->object->prepare_display_parameters($displayed_gallery, $params);
                switch ($displayed_gallery->display_type) {
                    case NGG_BASIC_COMPACT_ALBUM:
                        $template = 'compact';
                        break;
                    case NGG_BASIC_EXTENDED_ALBUM:
                        $template = 'extended';
                        break;
                }
                return $this->object->render_view("photocrati-nextgen_basic_album#{$template}", $params, $return);
            }
        } else {
            return $this->object->render_partial('photocrati-nextgen_gallery_display#no_images_found', array(), $return);
        }
    }
    /**
     * Gets the parent album for the entity being displayed
     * @param int $entity_id
     * @return stdClass (album)
     */
    public function get_parent_album_for($entity_id)
    {
        $retval = NULL;
        foreach ($this->albums as $album) {
            if (in_array($entity_id, $album->sortorder)) {
                $retval = $album;
                break;
            }
        }
        return $retval;
    }
    public function prepare_legacy_album_params($displayed_gallery, $params)
    {
        $image_mapper = C_Image_Mapper::get_instance();
        $storage = C_Gallery_Storage::get_instance();
        $image_gen = C_Dynamic_Thumbnails_Manager::get_instance();
        if (empty($displayed_gallery->display_settings['override_thumbnail_settings'])) {
            // legacy templates expect these dimensions
            $image_gen_params = array('width' => 91, 'height' => 68, 'crop' => TRUE);
        } else {
            // use settings requested by user
            $image_gen_params = array('width' => $displayed_gallery->display_settings['thumbnail_width'], 'height' => $displayed_gallery->display_settings['thumbnail_height'], 'quality' => isset($displayed_gallery->display_settings['thumbnail_quality']) ? $displayed_gallery->display_settings['thumbnail_quality'] : 100, 'crop' => isset($displayed_gallery->display_settings['thumbnail_crop']) ? $displayed_gallery->display_settings['thumbnail_crop'] : NULL, 'watermark' => isset($displayed_gallery->display_settings['thumbnail_watermark']) ? $displayed_gallery->display_settings['thumbnail_watermark'] : NULL);
        }
        // so user templates can know how big the images are expected to be
        $params['image_gen_params'] = $image_gen_params;
        // Transform entities
        $params['galleries'] = $params['entities'];
        unset($params['entities']);
        foreach ($params['galleries'] as &$gallery) {
            // Get the preview image url
            $gallery->previewurl = '';
            if ($gallery->previewpic && $gallery->previewpic > 0) {
                if ($image = $image_mapper->find(intval($gallery->previewpic))) {
                    $gallery->previewurl = $storage->get_image_url($image, $image_gen->get_size_name($image_gen_params), TRUE);
                    $gallery->previewname = $gallery->name;
                }
            }
            // Get the page link. If the entity is an album, then the url will
            // look like /nggallery/album--slug.
            $id_field = $gallery->id_field;
            if ($gallery->is_album) {
                if ($gallery->pageid > 0) {
                    $gallery->pagelink = @get_page_link($gallery->pageid);
                } else {
                    $gallery->pagelink = $this->object->set_param_for($this->object->get_routed_url(TRUE), 'album', $gallery->slug);
                }
            } else {
                if ($gallery->pageid > 0) {
                    $gallery->pagelink = @get_page_link($gallery->pageid);
                }
                if (empty($gallery->pagelink)) {
                    $pagelink = $this->object->get_routed_url(TRUE);
                    $parent_album = $this->object->get_parent_album_for($gallery->{$id_field});
                    if ($parent_album) {
                        $pagelink = $this->object->set_param_for($pagelink, 'album', $parent_album->slug);
                    } else {
                        if ($displayed_gallery->container_ids === array('0') || $displayed_gallery->container_ids === array('')) {
                            $pagelink = $this->object->set_param_for($pagelink, 'album', 'all');
                        } else {
                            $pagelink = $this->object->set_param_for($pagelink, 'album', 'album');
                        }
                    }
                    $gallery->pagelink = $this->object->set_param_for($pagelink, 'gallery', $gallery->slug);
                }
            }
            // Let plugins modify the gallery
            $gallery = apply_filters('ngg_album_galleryobject', $gallery);
        }
        $params['album'] = reset($this->albums);
        $params['albums'] = $this->albums;
        // Clean up
        unset($storage);
        unset($image_mapper);
        unset($image_gen);
        unset($image_gen_params);
        return $params;
    }
    public function _get_js_lib_url()
    {
        return $this->object->get_static_url('photocrati-nextgen_basic_album#init.js');
    }
    /**
     * Enqueues all static resources required by this display type
     *
     * @param C_Displayed_Gallery $displayed_gallery
     */
    public function enqueue_frontend_resources($displayed_gallery)
    {
        $this->call_parent('enqueue_frontend_resources', $displayed_gallery);
        wp_enqueue_style('nextgen_basic_album_style', $this->object->get_static_url('photocrati-nextgen_basic_album#nextgen_basic_album.css'));
        wp_enqueue_script('jquery.dotdotdot', $this->object->get_static_url('photocrati-nextgen_basic_album#jquery.dotdotdot-1.5.7-packed.js'), array('jquery'));
        $this->enqueue_ngg_styles();
    }
}
class A_NextGen_Basic_Album_Mapper extends Mixin
{
    public function set_defaults($entity)
    {
        $this->call_parent('set_defaults', $entity);
        if (isset($entity->name) && in_array($entity->name, array(NGG_BASIC_COMPACT_ALBUM, NGG_BASIC_EXTENDED_ALBUM))) {
            // Set defaults for both display (album) types
            $settings = C_NextGen_Settings::get_instance();
            $this->object->_set_default_value($entity, 'settings', 'galleries_per_page', $settings->galPagedGalleries);
            $this->object->_set_default_value($entity, 'settings', 'disable_pagination', 0);
            $this->object->_set_default_value($entity, 'settings', 'template', '');
            // Thumbnail dimensions -- only used by extended albums
            if ($entity->name == NGG_BASIC_EXTENDED_ALBUM) {
                $this->_set_default_value($entity, 'settings', 'override_thumbnail_settings', 0);
                $this->_set_default_value($entity, 'settings', 'thumbnail_width', $settings->thumbwidth);
                $this->_set_default_value($entity, 'settings', 'thumbnail_height', $settings->thumbheight);
                $this->_set_default_value($entity, 'settings', 'thumbnail_quality', $settings->thumbquality);
                $this->_set_default_value($entity, 'settings', 'thumbnail_crop', $settings->thumbfix);
                $this->_set_default_value($entity, 'settings', 'thumbnail_watermark', 0);
            }
            if (defined('NGG_BASIC_THUMBNAILS')) {
                $this->object->_set_default_value($entity, 'settings', 'gallery_display_type', NGG_BASIC_THUMBNAILS);
            }
            $this->object->_set_default_value($entity, 'settings', 'gallery_display_template', '');
            $this->object->_set_default_value($entity, 'settings', 'ngg_triggers_display', 'never');
        }
    }
}
class A_NextGen_Basic_Album_Routes extends Mixin
{
    public function render($displayed_gallery, $return = FALSE, $mode = NULL)
    {
        $do_rewrites = FALSE;
        $app = NULL;
        // Get display types
        $original_display_type = isset($displayed_gallery->display_settings['original_display_type']) ? $displayed_gallery->display_settings['original_display_type'] : '';
        $display_type = $displayed_gallery->display_type;
        // If we're viewing an album, rewrite the urls
        $regex = '/photocrati-nextgen_basic_\\w+_album/';
        if (preg_match($regex, $display_type)) {
            $do_rewrites = TRUE;
            // Get router
            $router = C_Router::get_instance();
            $app = $router->get_routed_app();
            $slug = '/' . C_NextGen_Settings::get_instance()->router_param_slug;
            $app->rewrite("{*}{$slug}/page/{\\d}{*}", "{1}{$slug}/nggpage--{2}{3}", FALSE, TRUE);
            $app->rewrite("{*}{$slug}/pid--{*}", "{1}{$slug}/pid--{2}", FALSE, TRUE);
            // avoid conflicts with imagebrowser
            $app->rewrite("{*}{$slug}/{\\w}/{\\w}/{\\w}{*}", "{1}{$slug}/album--{2}/gallery--{3}/{4}{5}", FALSE, TRUE);
            $app->rewrite("{*}{$slug}/{\\w}/{\\w}", "{1}{$slug}/album--{2}/gallery--{3}", FALSE, TRUE);
        } elseif (preg_match($regex, $original_display_type)) {
            $do_rewrites = TRUE;
            // Get router
            $router = C_Router::get_instance();
            $app = $router->get_routed_app();
            $slug = '/' . C_NextGen_Settings::get_instance()->router_param_slug;
            $app->rewrite("{*}{$slug}/album--{\\w}", "{1}{$slug}/{2}");
            $app->rewrite("{*}{$slug}/album--{\\w}/gallery--{\\w}", "{1}{$slug}/{2}/{3}");
            $app->rewrite("{*}{$slug}/album--{\\w}/gallery--{\\w}/{*}", "{1}{$slug}/{2}/{3}/{4}");
        }
        // Perform rewrites
        if ($do_rewrites && $app) {
            $app->do_rewrites();
        }
        return $this->call_parent('render', $displayed_gallery, $return, $mode);
    }
}
class A_NextGen_Basic_Album_Urls extends Mixin
{
    public function create_parameter_segment($key, $value, $id = NULL, $use_prefix = FALSE)
    {
        if ($key == 'nggpage') {
            return 'page/' . $value;
        } elseif ($key == 'album') {
            return $value;
        } elseif ($key == 'gallery') {
            return $value;
        } else {
            return $this->call_parent('create_parameter_segment', $key, $value, $id, $use_prefix);
        }
    }
    public function remove_parameter($key, $id = NULL, $url = FALSE)
    {
        $retval = $this->call_parent('remove_parameter', $key, $id, $url);
        $settings = C_NextGen_Settings::get_instance();
        $param_slug = preg_quote($settings->router_param_slug, '#');
        if (preg_match("#(/{$param_slug}/.*)album--#", $retval, $matches)) {
            $retval = str_replace($matches[0], $matches[1], $retval);
        }
        if (preg_match("#(/{$param_slug}/.*)gallery--#", $retval, $matches)) {
            $retval = str_replace($matches[0], $matches[1], $retval);
        }
        return $retval;
    }
}
class Mixin_NextGen_Basic_Album_Form extends Mixin_Display_Type_Form
{
    public function _get_field_names()
    {
        return array('nextgen_basic_album_gallery_display_type', 'nextgen_basic_templates_template');
    }
    /**
     * Renders the Gallery Display Type field
     * @param C_Display_Type $display_type
     */
    public function _render_nextgen_basic_album_gallery_display_type_field($display_type)
    {
        $mapper = C_Display_Type_Mapper::get_instance();
        return $this->render_partial('photocrati-nextgen_basic_album#nextgen_basic_album_gallery_display_type', array('display_type_name' => $display_type->name, 'gallery_display_type_label' => __('Display galleries as', 'nggallery'), 'gallery_display_type_help' => __('How would you like galleries to be displayed?', 'nggallery'), 'gallery_display_type' => $display_type->settings['gallery_display_type'], 'galleries_per_page_label' => __('Galleries per page', 'nggallery'), 'galleries_per_page' => $display_type->settings['galleries_per_page'], 'display_types' => $mapper->find_by_entity_type('image')), TRUE);
    }
    /**
     * Renders the Galleries Per Page field
     * @param C_Display_Type $display_type
     */
    public function _render_nextgen_basic_album_galleries_per_page_field($display_type)
    {
        return $this->render_partial('photocrati-nextgen_basic_album#nextgen_basic_album_galleries_per_page', array('display_type_name' => $display_type->name, 'galleries_per_page_label' => __('Items per page', 'nggallery'), 'galleries_per_page_help' => __('Maximum number of galleries or sub-albums to appear on a single page', 'nggallery'), 'galleries_per_page' => $display_type->settings['galleries_per_page']), TRUE);
    }
}
class A_NextGen_Basic_Extended_Album_Form extends Mixin_NextGen_Basic_Album_Form
{
    public function get_display_type_name()
    {
        return NGG_BASIC_EXTENDED_ALBUM;
    }
    /**
     * Returns a list of fields to render on the settings page
     */
    public function _get_field_names()
    {
        $fields = parent::_get_field_names();
        $fields[] = 'thumbnail_override_settings';
        return $fields;
    }
    /**
     * Enqueues static resources required by this form
     */
    public function enqueue_static_resources()
    {
        wp_enqueue_script('nextgen_basic_extended_albums_settings_script', $this->object->get_static_url('photocrati-nextgen_basic_album#extended_settings.js'), array('jquery.nextgen_radio_toggle'));
        $atp = C_Attach_Controller::get_instance();
        if ($atp != null) {
            $atp->mark_script('nextgen_basic_extended_albums_settings_script');
        }
    }
}
class A_NextGen_Basic_Compact_Album_Form extends Mixin_NextGen_Basic_Album_Form
{
    public function get_display_type_name()
    {
        return NGG_BASIC_COMPACT_ALBUM;
    }
    /**
     * Returns a list of fields to render on the settings page
     */
    public function _get_field_names()
    {
        $fields = parent::_get_field_names();
        $fields[] = 'thumbnail_override_settings';
        return $fields;
    }
    /**
     * Enqueues static resources required by this form
     */
    public function enqueue_static_resources()
    {
        wp_enqueue_script('nextgen_basic_compact_albums_settings_script', $this->object->get_static_url('photocrati-nextgen_basic_album#compact_settings.js'), array('jquery.nextgen_radio_toggle'));
        $atp = C_Attach_Controller::get_instance();
        if ($atp != null) {
            $atp->mark_script('nextgen_basic_compact_albums_settings_script');
        }
    }
}