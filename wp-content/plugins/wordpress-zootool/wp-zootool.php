<?php
/*
    Plugin Name: Wordpress Zootool
    Description: This Plugin provides a Zootool Badge in form of a Wordpress Widget, "Zootool is about collecting, organizing and sharing your favorite images, videos, documents and links from all over the Internet. " Learn more on <a href="http://zootool.com/">zootool.com</a>. If your Theme does <strong>not</strong> support widgets just place the code &lt;?= wp_zootool::widgetContent() ?&gt; in your sidebar template. This Plugin is written and maintained by <a href="http://www.lautr.com">Johannes Lauter</a>
    Version: 1.11
    Author: Johannes lauter
    Author URI: http://www.lautr.com
*/
/*  Copyright 2010 Johannes Lauter <hannes@lautr.com>

    The  WP Zootool Plugin is distributed under the GNU General Public License, Version 2,
    June 1991. Copyright (C) 1989, 1991 Free Software Foundation, Inc., 51 Franklin
    St, Fifth Floor, Boston, MA 02110, USA

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
    ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
    WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
    DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
    ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
    (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
    ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
    (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
    SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

if('error_scrape' == $_GET['action']) {
  $error = get_option('wp_zootool_activation_error');
  if(is_array($error)){
  	  foreach($error as $errorMessage){
  	  	  print $errorMessage;
  	  }
  	  delete_option('myplugin_activate_error');
  	  die();
  }
}

class wp_zootool{

    public function __construct(){}

    /** public Methods ***/

    /**
     * actives admin control
     */
    public function init(){
        if (function_exists('add_options_page')) {
            add_options_page('WP Zootool','WP Zootool',9,basename(__FILE__), array('wp_zootool','adminControl'));
        }
    }

    /**
     * stored zootool options
     *
     * @param array $post
     */
    public function saveZootoolOptions($post){
        if(!(self::validateZootoolOptions($post))){
            $options['username']    = $post['wp-zootool-username'];
            $options['size']        = $post['wp-zootool-size'];
            $options['count']       = $post['wp-zootool-count'];
            $options['title']       = $post['wp-zootool-title'];
            $options['description'] = $post['wp-zootool-description'];
            $options['target']      = $post['wp-zootool-target'];
            $options['disablestyle']= $post['wp-zootool-disablestyle'];
            $options['ttl']         = $post['wp-zootool-ttl'];

            update_option('wp_zootool', $options);
        }
    }

    /**
     * Validates User Input, if not valid, shows Error Messages
     *
     * @param array $post
     * @return bool
     */
    public function validateZootoolOptions($post){
        if(!is_numeric($post['wp-zootool-size'])){             
            $errors[] = "Please use numeric Value for Thumbnail-Size.";
        }
        if(!is_numeric($post['wp-zootool-count'])){
            $errors[] = "Please use numeric Value for Thumbnail-Count.";
        }
        if(@(NULL == json_decode(file_get_contents('http://zootool.com/api/users/items/?username='.$post['wp-zootool-username'].'&apikey=d26306d6d220d64b28892e78efc28085')))){
            $errors[] = "Username unkown, please check.";
        }
        if(is_array($errors)){
            foreach($errors as $error){
                print "<p>".$error."</p>";
                if('Username unkown, please check.' == $error){
                	print "<p><strong>If you're sure the username is right, here is what happend:</strong><br/>";
                	print json_decode(file_get_contents('http://zootool.com/api/users/items/?username='.$post['wp-zootool-username'].'&apikey=d26306d6d220d64b28892e78efc28085'));
                	print "</p>";
                }
            }
            return true;
        }else{
            return false;
        }
    }

    /**
     * Provdies the Widget Frontend Content
     *
     * @return string
     */
    public function _widgetContent(){
        $options =  get_option('wp_zootool');

        $items =  json_decode(file_get_contents('http://zootool.com/api/users/items/?username='.$options['username'].'&apikey=d26306d6d220d64b28892e78efc28085&limit='.$options['count']));
        if('true' != $options['disablestyle']){
            $output .= "<style type='text/css'> ul.zootool-badge{overflow: hidden; line-height: 0px; margin: 0px; padding: 5px;} ul.zootool-badge li{background: none !important; float: left; list-style: none; margin: 5px !important; border: 1px solid #9e9e9e; padding: 1px !important; background: #fff;}</style>";
        }
        if(is_array($items)){
		$output .= "<ul class='zootool-badge'>";
		foreach($items as $item){
		    if('source' == $options['target']){
			$url = $item->url;
		    }else{
			$url = $item->permalink;
		    }
		    $output .= "<li><a target='_blank' href='".$url."'><img alt='".$item->title."' title='".$item->title."' src='".$item->thumbnail."' style='width: ".$options['size']."px'></a></li>";
		}
		$output .= "</ul>";
        }
        return $output;
    }

    /**
     * Iplements the Widget Frontend Content
     */
    public function widgetContent(){
        $options =  get_option('wp_zootool');

        if(function_exists('apc_store') && is_numeric($options['ttl'])){
            if(!$output = apc_fetch('wp_zootool')){
                $output = self::_widgetContent();
                apc_store('wp_zootool',$output,$options['ttl']);
            }
        }else{
            $output = self::_widgetContent();
        }
        print $output;
    }

    /** Wordpress Stuff **/

    /**
     * deactivation hook
     */ 
    public function deactivation(){
        $options['username']    = '';
        $options['size']        = '';
        $options['count']       = '';
        $options['target']      = '';
        $options['disablestyle']= '';
        $options['ttl']         = '';

        update_option('wp_zootool', $options);
    }

    /**
     * activation hook
     */
    public function activation(){
	// Some Checks if everything works out
	if(!function_exists('json_decode')){
	    $error[] = '<p>Your PHP Installation does not support json_decode [ <a target="_blank" href="http://php.net/manual/en/function.json-decode.php">http://php.net/manual/en/function.json-decode.php</a> ], please make sure to update your System.</p>';
	}
	if(!function_exists('file_get_contents')){
	    $error[] = '<p>Your PHP Installation does not support file_get_contents [ <a target="_blank" href="http://php.net/manual/en/function.file-get-contents.php">http://php.net/manual/en/function.file-get-contents.php</a> ], please make sure to update your System.</p>';
	}
	
	if(is_array($error)){
		add_option('wp_zootool_activation_error', $error);
		//trigger a fatal error
		deactivate_plugins(__FILE__);
		trigger_error('', E_USER_ERROR);
	}
	    	    
        $options =  get_option('wp_zootool');

        if(1 > $options['title']){
            $options['title']       = 'MyZoo';
        }

        $options['size']        = '50';
        $options['count']       = '24';
        $options['ttl']         = '360';
        $options['target']      = 'zootool';
        
        update_option('wp_zootool', $options);
    }

    /**
     * Implements the Widget in to the Theme
     *
     * @param <type> $args
     */
    public function widgetOutput($args){
        extract($args);
        $options =  get_option('wp_zootool');

        if(1 < strlen($options['username'])){
            echo $before_widget;
            echo $before_title . $options['title'] . $after_title;
            if(0 < strlen($options['title'])) { echo "<p>".$options['description']."<p>"; }
            echo self::widgetContent();
            echo $after_widget;
        }
    }

    /**
     * Option Blick for wordpress related options (widget title, desc and ttl)
     *
     * @param array $options
     */
    public function _optionControl($options){
        ?>
        <p>
            <label for="wp-zootool-title">Title: </label>
            <input name="wp-zootool-title" id="wp-zootool-username" value="<?php print $options['title']; ?>" type="text" class="widefat"/>
        </p>
        <p>
            <label for="wp-zootool-description">Description: </label>
            <input name="wp-zootool-description" value="<?php print $options['description']; ?>" type="text" class="widefat" id="wp-zootool-description"/>
        </p>
        <?php if(function_exists('apc_store')){ ?>
        <p>
            <label for="wp-zootool-ttl">Cache Lifetime (TTL) in Seconds</label>
            <input name="wp-zootool-ttl" id="wp-zootool-ttl" value="<?php print $options['ttl']; ?>" type="text" class="widefat"/>
        </p>
        <?php } ?>
        <p>
            <input type="checkbox" name="wp-zootool-disablestyle" id="wp-zootool-disablestyle" value="true" <?php if('true' == $options['disablestyle']){ print 'checked="checked"'; } ?>/>
            <label for="wp-zootool-disablestyle">Disable Default Style</label>
        </p>
        <?php
    }

    /**
     * Option Block for zootool settings
     *
     * @param array $options
     */
    public function _zootoolOptions($options){
        ?>
        <p>
            <input type="radio" name="wp-zootool-target" value="zootool" id="wp-zootool-target-zootool"<?php if('zootool' == $options['target']){ print 'checked="checked"'; } ?>/>
            <label for="wp-zootool-target-zootool">Use Zootool as Link Target</label>
        </p>
        <p>
            <input type="radio" name="wp-zootool-target" value="source" id="wp-zootool-target-source"<?php if('source' == $options['target']){ print 'checked="checked"'; } ?>/>
            <label for="wp-zootool-target-source">Use Source as Link Target</label>
        </p>
        <p>
            <label for="wp-zootool-username">* Username: </label>
            <input name="wp-zootool-username" id="wp-zootool-username" value="<?php print $options['username']; ?>" type="text" class="widefat"/>
        </p>
        <p>
            <label for="wp-zootool-size">* Thumbnail-Size: </label>
            <input name="wp-zootool-size" value="<?php print $options['size']; ?>" type="text" id="wp-zootool-size"/><span>px</span>
        </p>
        <p>
            <label for="wp-zootool-count">* Thumbnail-Count: </label>
            <input name="wp-zootool-count" value="<?php print $options['count']; ?>" type="text" class="widefat" id="wp-zootool-count"/>
        </p>
        <?php
    }

    /**
     * provides an admin Backend (for Users without a Widget Configuration possbility)
     */
    public function adminControl(){
      if(isset($_POST['wp-zootool-username'])){
            self::saveZootoolOptions($_POST);
        }
        $options =  get_option('wp_zootool');

        ?>
        <form action="" method="post">
            <div class="wrap" id="poststuff" style="overflow:hidden;">
                <div>
                    <h2 style='background: url("/wp-content/plugins/wp-zootool/zootool-32x32.png") no-repeat scroll 0pt 0pt transparent; padding-left: 35px;'>WP Zootool Widget</h2>
                    <small>by <a href="http://www.lautr.com">Johannes Lauter</a> </small>
                </div>
                    <div class="postbox">
                        <h3 class='hndle'><span>Wordpress Options</span></h3>
                        <div class="inside">
                            <?php self::_optionControl($options); ?>
                        </div>
                    </div>
                    <div class="postbox">
                        <h3 class='hndle'><span>Zootool Options</span></h3>
                        <div class="inside">
                            <?php self::_zootoolOptions($options); ?>
                        </div>
                    </div>
                <input type="submit" value="Save" class="button" name="savezootooloptions"/>
                <br /><small>* fields are mandatory</small>
            </div>
        </form>
        <?php
    }

    /**
     * Widget backend
     */
    public function widgetControl(){
        if(isset($_POST['wp-zootool-username'])){
            self::saveZootoolOptions($_POST);
        }
        $options =  get_option('wp_zootool');
        ?>
        <div>
            <strong>Wordpress options</strong>
            <?php self::_optionControl($options); ?>
        </div>
        <div>
            <strong>Zootool options</strong>
            <?php self::_zootoolOptions($options); ?>
        </div>
        <br /><small>* fields are mandatory</small>
        <?php
    }
}

/** even more Wordpress Stuff **/

register_sidebar_widget('Zootool',array('wp_zootool','widgetOutput'));
register_widget_control('Zootool', array('wp_zootool', 'widgetControl'));
register_deactivation_hook( __FILE__, array('wp_zootool', 'deactivation'));
register_activation_hook( __FILE__, array('wp_zootool', 'activation'));
add_action('admin_menu', array('wp_zootool','init'));
