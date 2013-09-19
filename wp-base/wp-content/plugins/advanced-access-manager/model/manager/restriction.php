<?php

/*
  Copyright (C) <2011>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Metabox & Widget Manager
 *
 * @package AAM
 * @subpackage Model
 */
class mvb_Model_Manager_Restriction {

    /**
     *
     * @global array $submenu
     * @param string $tmpl
     * @param mvb_Model_Manager $parent
     * @return string
     */
    public static function render($tmpl, $parent) {

        return mvb_Model_Template::replaceSub('POST_INFORMATION', '', $tmpl);
    }

    public static function renderInfo($id, $type, $parent, $tmpl) {
        global $wp_post_statuses, $wp_post_types;

        switch ($type) {
            case 'post':
                //get information about page or post
                $post = get_post($id);
                if ($post->ID) {
                    $tmpl = mvb_Model_Template::retrieveSub('POST', $tmpl);
                    $tmpl = phpQuery::newDocument($tmpl);
                    $data = $parent->getConfig()->getRestriction('post', $id);
                    foreach($data as $key => $value){
                        $tmpl['#' . $key]->attr('checked', 'checked');
                    }
                    if ($parent->getCurrentUser()){
                        $tmpl['.save-postinfo-all']->attr('disabled', 'disabled');
                    }
                    $tmpl['.category-title']->html(mvb_Model_Helper::editPostLink($post));
                    //check what type of post is it and render exclude if page
                    if (isset($wp_post_types[$post->post_type])) {
                        if ($wp_post_types[$post->post_type]->capability_type != 'page'){
                            $tmpl['#exclude']->remove();
                        }
                    }
                    $tmpl = $tmpl->htmlOuter();
                }
                break;

            case 'taxonomy':
                //get information about category
                $taxonomy = mvb_Model_Helper::getTaxonomyByTerm($id);
                $term = get_term($id, $taxonomy);
                if ($term->term_id) {
                    $tmpl = mvb_Model_Template::retrieveSub('CATEGORY', $tmpl);
                    $tmpl = phpQuery::newDocument($tmpl);
                    $data = $parent->getConfig()->getRestriction('taxonomy', $id);
                    foreach($data as $key => $value){
                        $tmpl['#' . $key]->attr('checked', 'checked');
                    }
                    if ($parent->getCurrentUser()){
                        $tmpl['.save-postinfo-all']->attr('disabled', 'disabled');
                    }
                    $tmpl['.category-title']->html(
                            str_replace('&', '&amp;', mvb_Model_Helper::editTermLink($term))
                    );
                    $tmpl['.subposts']->html(sprintf(mvb_Model_Label::get('LABEL_178'), $term->name));
                    if (mvb_Model_Helper::isPremium()){
                        $tmpl['.premium']->removeClass('premium');
                        $tmpl['#premium-ind']->html('&nbsp;');
                    }
                    $tmpl = $tmpl->htmlOuter();
                }
                break;

            default:
                $tmpl = '';
                break;
        }
        $tmpl = mvb_Model_Label::clearLabels($tmpl);

        $result = array(
            'status' => 'success',
            'html' => mvb_Model_Template::clearTemplate($tmpl)
        );

        return $result;
    }

}

?>