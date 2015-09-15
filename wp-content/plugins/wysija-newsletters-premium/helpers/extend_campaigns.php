<?php
defined('WYSIJANLP') or die('Restricted access');
/**
 * This class help extending the campaigns pages with various hooks and filters
 */
class WYSIJANLP_help_extend_campaigns extends WYSIJA_help{

    function __construct(){
        if (method_exists('WYSIJA_help', 'WYSIJA_help')) parent::WYSIJA_help();
        else parent::__construct();
    }

    /**
     * manual send of the newsletter
     */
    function manual_send(){
        $modelQ=WYSIJA::get('queue','model');
        $config=WYSIJA::get('config','model');
        $premium=$config->getValue('premium_key');
        if($premium){

            if($modelQ->count()>0){
                $helperQ=WYSIJA::get('queue','helper');
                $emailid=false;
                if($_REQUEST['emailid']){
                    $emailid=$_REQUEST['emailid'];
                }
                $helperQ->process($emailid);
            }else{
                echo '<strong>'.__('Queue is empty!',WYSIJA).'</strong>';
            }
        }else{
            echo '<strong>'.__('Go premium, you cannot send anymore!',WYSIJA).'</strong>';
        }
    }

    /**
     * premium check to make sure that this is possible to send
     * @param boolean $filter
     * @return boolean
     */
    function can_site_send($filter){
       if(!$filter){
            $config=WYSIJA::get('config','model');
            $filter=true;
            if($config->getValue('premium_key')) return $filter;
       }
       return $filter;
    }

    /**
     * link statistics module
     * @param type $result
     * @param type $data
     * @return string
     */
    function link_stats($result,$data){
        $mConfig=WYSIJA::get('config','model');
        if($mConfig->getValue('premium_key')){
            $result='<ol>';
            $validation_helper =WYSIJA::get('validation','helper');
            foreach($data['clicks'] as $click){
                $css_class = 'stats-url-link';
                if(!empty($_REQUEST['url_id']) && $_REQUEST['url_id']==$click['url_id']) $css_class .= ' select';
                $filter_link = 'admin.php?page=wysija_campaigns&action=viewstats&id='.$_REQUEST['id'].'&url_id='.$click['url_id'];
                $label = preg_replace('/^http[s]?:\/\//','',$click['url']); //remove http://, https://
                if($validation_helper->isUrl($click['url'])){
                    $label = '<a href="'.$click['url'].'" target="_blank" class="'.$css_class.'">'.$label.'</a>';
                }
                $result.='<li><a href="'.$filter_link.'" class="'.$css_class.'">'.$click['name'].'</a> : '.$label.'</li>';
            }
            $result.='</ol>';
        }
        return $result;
    }

    /**
     * extend the step3 fields based on the premium status
     * @param type $fields
     * @return type
     */
    function extend_step3($fields){
        $config=WYSIJA::get('config','model');
        if($config->getValue('premium_key')){
            $fields['googletrackingcode']=array(
                'type'=>'input',
                'isparams' => 'params',
                'class'=>'',
                'label'=>__('Google Analytics Campaign',WYSIJA),
                'desc'=>__('For example, "Spring email". [link]Read the guide.[/link]',WYSIJA),
                'link'=>'<a href="http://support.wysija.com/knowledgebase/track-your-newsletters-visitors-in-google-analytics/?utm_source=wpadmin&utm_campaign=step3" target="_blank"> ');

            if(isset($_REQUEST['wysija']['email']['params']['googletrackingcode'])) {
                $data['email']['params']['googletrackingcode']=$fields['googletrackingcode']['default']=$_REQUEST['wysija']['email']['params']['googletrackingcode'];
            }
        }
        return $fields;
    }

    /**
     * how spammy button that appear only for premiums
     * @return string
     */
    function how_spammy(){
        $config = WYSIJA::get('config','model');
        if(!$config->getValue('premium_key')) return;

        $html = '<p>';
        $html .= '<a href="javascript:;" id="wysija-send-spamtest" class="button wysija">'.__('How spammy is this newsletter?',WYSIJA).'</a>';
        $html .= '<a id="wysija-spam-results" class="marginl hide" target="_blank">'.__('View your score', WYSIJA).'</a></p>';

        return $html;
    }
}
