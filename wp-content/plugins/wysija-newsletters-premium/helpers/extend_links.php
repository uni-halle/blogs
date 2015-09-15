<?php
defined('WYSIJANLP') or die('Restricted access');
/**
 * This class help extending the subscribers admin page with various hooks and filters
 */
class WYSIJANLP_help_extend_links extends WYSIJA_object{

    protected $file_extensions;

    public function __construct(){
        if (method_exists('WYSIJA_object', 'WYSIJA_object')) parent::WYSIJA_object();
        else parent::__construct();
        $this->file_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'mp3', 'mp4', 'ppt', 'pptx', 'rtf', 'odt');
    }
    /**
     * Render a typical link
     * @param type $html_content
     * @param type $link
     * @param string $max_link_length if the link exceeds to max_link_length, cut it off
     * @param string $sub_string_length if the link exceeds to max_link_length, cut it off and keep a substring of $sub_string_length characters
     * @param boolean $is_full_link if true, show full link, if no, show truncated link
     * @param string $default_html if this is defined, we will use this value instead.
     * @return < a > rendered tag
     */
    public function render_link($html_content, $link, $max_link_length = 50, $sub_string_length = 15, $is_full_link = false, $default_html = NULL){
	$default_html = $default_html; // in free version only
        $model_config=WYSIJA::get('config','model');
        if(!$model_config->getValue('premium_key'))
            return $html_content;

        $validation_helper =WYSIJA::get('validation','helper');
        // remove http://, https://
        $html_content = preg_replace('/^http[s]?:\/\//','',$link);

        // if this is a link to a file, display filename as a label
        $file_ext = strrpos($html_content, '.',-1) !== false ? substr($html_content, strrpos($html_content, '.',-1)+1) : '';
        $html_content = in_array($file_ext, $this->file_extensions) ? substr($html_content,strrpos($html_content,'/')+1) : $html_content;

        // if label is too long, cut it off
        if (strlen($html_content) > $max_link_length && !$is_full_link) // in case label is too long
            $html_content = substr($html_content, 0,$sub_string_length).'...'.substr($html_content,strlen($html_content)-$sub_string_length);

        // if link is a real link, add href attribute and <a> tag
        if($validation_helper->isUrl($link)){
            $html_content = '<a href="'.  esc_attr($link).'" target="_blank" class="wysija-link">'.esc_url($html_content).'</a>';
        }
        else {
            // if this is a special link ([unsubscribe_link], [view_in_browser_link]...), convert in read-able style
            // in which:
            // + remove [, ]
            // + Replace _ with a space
            // + Make the first letter uppercase
            $count = 0;
            preg_replace('/\[[^\[\]]*\]/', '', $html_content, -1, $count);
            if ($count > 0) {
                $html_content = ucfirst(str_replace(array('[',']', '_'), array('','', ' '),$html_content));
            }
        }

        return $html_content;
    }
}
