<?php
defined('WYSIJANLP') or die('Restricted access');
/**
 * This class help extending the subscribers admin page with various hooks and filters
 */
class WYSIJANLP_help_extend_subscribers extends WYSIJA_help{

    function __construct(){
        if (method_exists('WYSIJA_help', 'WYSIJA_help')) parent::WYSIJA_help();
        else parent::__construct();
    }

    /**
     * DEPRECATED since 2.7; Will be remove in 3.0
     * @param type $htmlContent
     * @param type $data
     * @return string
     */
    function profile_edit_view_stats($htmlContent,$data){
        $mConfig=WYSIJA::get('config','model');
        $htmlContent='';
        if($mConfig->getValue('premium_key')){
            if(count($data['charts']['stats'])>0 ):
            $htmlContent.='<div id="wysistats">
                <div id="wysistats1" class="left">
                    <div id="statscontainer"></div><h3>'.sprintf(__('%1$s emails received.',WYSIJA),$data['user']['emails']).'</h3>
                </div>
                <div id="wysistats2" class="left">
                    <ul>';

                        foreach($data['charts']['stats'] as $stats){
                            $htmlContent.='<li>'.$stats['name'].": ".$stats['number'].'</li>';
                        }
                            $htmlContent.='<li>'.__('Added',WYSIJA).": ".$this->fieldListHTML_created_at($data['user']['details']['created_at']).'</li>';
                    $htmlContent.='
                    </ul>
                </div>
                <!--div id="wysistats3" class="left">
                    <p class="title">'. sprintf(__('Total of %1$s clicks:',WYSIJA),count($data['clicks'])).'</p>
                    <ol>';
                        $validation_helper =WYSIJA::get('validation','helper');
                        $css_class = 'stats-url-link';
                        foreach($data['clicks'] as $click){
                            $label = preg_replace('/^http[s]?:\/\//','',$click['url']); //remove http://, https://
                            if($validation_helper->isUrl($click['url'])){
                                $label = '<a href="'.$click['url'].'" target="_blank" class="'.$css_class.'">'.$label.'</a>';
                            }
                            $htmlContent.='<li><em>'.$click['name'].'</em> : <strong >'.sprintf(_n('%1$s click', '%1$s clicks', $click['number_clicked'],WYSIJA), $click['number_clicked']).'</strong> - '.$label.'</li>';
                        }
                        $htmlContent.='
                    </ol>
                </div-->
                <div class="clear"></div>
            </div>';

            endif;
        }
        return $htmlContent;
    }

     /**
     *
     * @param type $val
     * @param type $format
     * @return type
     */
    function fieldListHTML_created_at($val,$format=''){
        if(!$val) return '---';
        if($format) return date_i18n($format,$val);
        else return date_i18n(get_option('date_format'),$val);
    }
}
