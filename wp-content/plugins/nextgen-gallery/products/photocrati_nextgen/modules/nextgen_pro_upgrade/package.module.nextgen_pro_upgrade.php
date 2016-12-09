<?php
class A_NextGen_Pro_Plus_Upgrade_Page extends Mixin
{
    public function setup()
    {
        // Using include() to retrieve the is_plugin_active() is apparently The WordPress Way(tm)..
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        // We shouldn't show the upgrade page if they already have the plugin and it's active
        $found = false;
        if (defined('NEXTGEN_GALLERY_PRO_PLUGIN_BASENAME')) {
            $found = 'NEXTGEN_GALLERY_PRO_PLUGIN_BASENAME';
        }
        if (defined('NGG_PRO_PLUGIN_BASENAME')) {
            $found = 'NGG_PRO_PLUGIN_BASENAME';
        }
        if (!($found && is_plugin_active(constant($found)))) {
            $this->object->add('ngg_pro_upgrade', array('adapter' => 'A_NextGen_Pro_Upgrade_Controller', 'parent' => NGGFOLDER));
        }
        return $this->call_parent('setup');
    }
}
class A_NextGen_Pro_Upgrade_Controller extends Mixin
{
    public function enqueue_backend_resources()
    {
        $this->call_parent('enqueue_backend_resources');
        wp_enqueue_style('nextgen_pro_upgrade_page', $this->get_static_url('photocrati-nextgen_pro_upgrade#style.css'), FALSE, NGG_SCRIPT_VERSION);
    }
    public function get_page_title()
    {
        return __('Upgrade to Pro', 'nggallery');
    }
    public function get_required_permission()
    {
        return 'NextGEN Change options';
    }
    public function get_i18n_strings()
    {
        $i18n = new stdClass();
        $i18n->plus_title = __('Upgrade to NextGEN Plus or Pro!', 'nggallery');
        $i18n->pro_title = __('Upgrade to NextGEN Pro!', 'nggallery');
        $i18n->plus_desc_first = __('Introducing the most powerful gallery system ever made for WordPress.', 'nggallery');
        $i18n->plus_desc_second = __('Take your galleries to the next level with the Plus and Pro extensions for NextGEN Gallery. ', 'nggallery');
        $i18n->pro_desc = __('NextGEN Pro now offers proofing and ecommerce! Extend NextGEN Gallery and NextGEN Plus with a complete solution for providing client galleries and for selling prints and digital downloads. Includes unlimited pricelists, PayPal and Stripe integration, and more.', 'nggallery');
        $i18n->plus_button = __('Learn More About NextGEN Plus', 'nggallery');
        $i18n->pro_button = __('Learn More About NextGEN Pro', 'nggallery');
        $i18n->click = __('CLICK TO LEARN MORE:', 'nggallery');
        return $i18n;
    }
    public function index_action()
    {
        $this->object->enqueue_backend_resources();
        $key = C_Photocrati_Transient_Manager::create_key('nextgen_pro_upgrade_page', 'html');
        if ($html = C_Photocrati_Transient_Manager::fetch($key, FALSE)) {
            echo $html;
        } else {
            // Get template and page content
            $template = 'photocrati-nextgen_pro_upgrade#plus';
            if (defined('NGG_PLUS_PLUGIN_BASENAME')) {
                $template = 'photocrati-nextgen_pro_upgrade#pro';
            }
            $html = $this->render_view($template, array('i18n' => $this->get_i18n_strings()), TRUE);
            // Cache it
            C_Photocrati_Transient_Manager::update($key, $html);
            // Render it
            echo $html;
        }
    }
}
class A_NextGen_Pro_Upgrade_Page extends Mixin
{
    public function setup()
    {
        // Using include() to retrieve the is_plugin_active() is apparently The WordPress Way(tm)..
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        // We shouldn't show the upgrade page if they already have the plugin and it's active
        $found = false;
        if (defined('NEXTGEN_GALLERY_PRO_PLUGIN_BASENAME')) {
            $found = 'NEXTGEN_GALLERY_PRO_PLUGIN_BASENAME';
        }
        if (defined('NGG_PRO_PLUGIN_BASENAME')) {
            $found = 'NGG_PRO_PLUGIN_BASENAME';
        }
        if (!($found && is_plugin_active(constant($found)))) {
            $this->object->add('ngg_pro_upgrade', array('adapter' => 'A_NextGen_Pro_Upgrade_Controller', 'parent' => NGGFOLDER));
        }
        return $this->call_parent('setup');
    }
}