<?php
  if ($instance['db_id']) {
    if ($details && $details->token && $details->token !== '' && $details->error_detected != 1) {
  ?>    
    <p>
      To configure this widget click on the <b>Configure Widget</b> button.
    </p>
  
    <input type="button" value="Configure Widget" onclick="openSetup<?php print $instance['db_id'] ?>();" class="simpleSetupButton button-primary" id="setupButton<?php print $instance['db_id'] ?>">
    
    <div id="hiddenFields<?php print $instance['db_id'] ?>" style="display: none;">
      <!-- default values -->
      <input type="hidden" name="title" 	       value="<?php print htmlspecialchars($details->settings['title']) ?>">
      <input type="hidden" name="user" 		       value="<?php print $details->settings['user'] ?>">
      <input type="hidden" name="username" 	     value="<?php print htmlspecialchars(	$details->settings['username'])?>">
      <input type="hidden" name="tag1" 		       value="<?php print htmlspecialchars(	$details->settings['tag1'])?>">
      <input type="hidden" name="tag2"		       value="<?php print htmlspecialchars(	$details->settings['tag2'])?>">
      <input type="hidden" name="tag3"		       value="<?php print htmlspecialchars(	$details->settings['tag3'])?>">
      <input type="hidden" name="tag4"		       value="<?php print htmlspecialchars(	$details->settings['tag4'])?>">
      <input type="hidden" name="width"		       value="<?php print htmlspecialchars(	$details->settings['width'])?>">
      <input type="hidden" name="height"	       value="<?php print htmlspecialchars(	$details->settings['height'])?>">
      <input type="hidden" name="delay"		       value="<?php print htmlspecialchars(	$details->settings['delay'])?>">
      <input type="hidden" name="method"	       value="<?php print $details->settings['method']?>">
      <input type="hidden" name="cols"		       value="<?php print $details->settings['cols']?>">
      <input type="hidden" name="rows"		       value="<?php print $details->settings['rows']?>">
      <input type="hidden" name="transition"	   value="<?php print $details->settings['transition']?>"> 
      <input type="hidden" name="cache_duration" value="<?php	print ($details->cache_timeout || $this->_defaultCacheTime()) ?>">
      <input type="hidden" name="responsive"	   value="<?php	print $details->settings['responsive']?>">
      <input type="hidden" name="sharing"	       value="<?php	print $details->settings['sharing']?>">
      <input type="hidden" name="verbose"	       value="<?php	print $details->settings['verbose']?>">
      <input type="hidden" name="celebPick"	       value="<?php	print $details->settings['celebPick']?>">
      <input type="hidden" name="tagCompare"     value="<?php print htmlspecialchars($details->settings['tagCompare']) ?>">
      <input type="hidden" name="location"       value="<?php print htmlspecialchars($details->settings['location']) ?>">
      <input type="hidden" name="latitude"       value="<?php print htmlspecialchars($details->settings['latitude']) ?>">
      <input type="hidden" name="longitude"      value="<?php print htmlspecialchars($details->settings['longitude']) ?>">
    </div>
    
    <?php
        $customTitle = 'Instagram Widget';
      
        if ($details->settings['display'] === 'self') {
          $customTitle = 'Your Instagram Photos';
        } else if ($details->settings['display'] === 'likes') {
          $customTitle = 'Your favourite Instagram Photos';
        } else if ($details->settings['display'] === 'feed') {
          $customTitle = 'Your Instagram feed';
        } else if ($details->settings['display'] === 'popular') {
          $customTitle = 'Popular on Instagram';
        } else if ($details->settings['display'] === 'celebs') {
          $customTitle = "Celebrity Instagrams"; 
        } else if ($details->settings['display'] === 'user') {
          $customTitle = '@' . $details->settings['username'] . " Instagrams"; 
        } else if ($details->settings['display'] === 'tags') {
          $customTitle = 'Instagrams tagged: ';
          
          $previous = 0;
          if ($details->settings['tag1'] && $details->settings['tag1'] !== '') {
            $customTitle .= $details->settings['tag1'];
            $previous = 1;
          } 
          if ($details->settings['tag2'] && $details->settings['tag2'] !== '') {
            if ($previous === 1) {
              $customTitle .= ', ';
            }
            $customTitle .= $details->settings['tag2'];
            $previous = 1;
          }
          if ($details->settings['tag3'] && $details->settings['tag3'] !== '') {
            if ($previous === 1) {
              $customTitle .= ', ';
            }
            $customTitle .= $details->settings['tag3'];
            $previous = 1;
          }
          if ($details->settings['tag4'] && $details->settings['tag4'] !== '') {
            if ($previous === 1) {
              $customTitle .= ', ';
            }
            $customTitle .= $details->settings['tag4'];
            $previous = 1;
          }
        }        
    ?>

    <div id="setupForm<?php print $instance['db_id'] ?>" style="display: none;">
      <div id="#setupFormInner<?php print $instance['db_id'] ?>">
        <?php require("formHeader.php") ?>
        <div class="widget-content instagram-widget-admin-form" id="form<?php print $instance['db_id'] ?>">
          <ul class="wp-tab-bar">
            <li data-tab="content" class="tabber active" id="contentTab">
              <a href="#" onclick="javascript:switchTab(this);" data-tab="content" class="tabber active">
                Content
              </a>
            </li>
            <li data-tab="display" class="tabber" id="displayTab">
              <a href="#" onclick="javascript:switchTab(this);" data-tab="display" class="tabber">
                Display
              </a>
            </li>
            <li data-tab="settings" class="tabber" id="settingsTab">
              <a href="#" onclick="javascript:switchTab(this);" data-tab="settings" class="tabber">
                Settings
              </a>
            </li>
            <li data-tab="help" class="tabber">
              <a href="#" onclick="javascript:switchTab(this);" data-tab="help" class="tabber">
                Help &amp; Support
              </a>
            </li>
          </ul>
          <div class="tabs-panel tabber active" data-tab="content">
            <p>
              <span class="errorMessage">
                <span class="block-arrow"></span>
                You must enter a title for your widget. If you do not want your widget title to be visible then you must customize your local CSS to hide it.
              </span>
            
              <label>
                Title
                <span class="help-icon dashicons dashicons-info">
                  <span class="block">
                    <span class="block-arrow"></span>
                    This title will appear above your Instagram widget.
                  </span>
                </span>
              </label>
              <input type="text" name="title" id="title" value="<?php print htmlspecialchars($details->settings['title']) ?>" class="widefat">
            </p>  
            <p>
              <label>
                Content type
                <span class="help-icon dashicons dashicons-info">
                  <span class="block">
                    <span class="block-arrow"></span>
                    Select the type of Instagram feed for your widget to show.
                  </span>
                </span>
              </label>
              <select class="widefat" name="display" id="display">
                <option value="self" 	    <?php if ($details->settings['display'] === 'self') 	   { echo "SELECTED"; } ?>>My Photos</option>
                <option value="likes" 	  <?php if ($details->settings['display'] === 'likes') 	   { echo "SELECTED"; } ?>>My Favourites</option>
                <option value="feed" 	    <?php if ($details->settings['display'] === 'feed') 	   { echo "SELECTED"; } ?>>My Feed</option>
                <option value="popular" 	<?php if ($details->settings['display'] === 'popular') 	 { echo "SELECTED"; } ?>>Popular Feed</option>
                <option value="user" 	    <?php if ($details->settings['display'] === 'user') 	   { echo "SELECTED"; } ?>>Another users photos</option>
                <option value="celebs"  <?php if ($details->settings['display'] === 'celebs') 	   { echo "SELECTED"; } ?>>Celebrities</option>
                <option value="tags" 	    <?php if ($details->settings['display'] === 'tags') 	   { echo "SELECTED"; } ?>>Tagged photos</option>
                <option value="location"  <?php if ($details->settings['display'] === 'location')  { echo "SELECTED"; } ?>>From a Location</option>
              </select>
            </p>
            <div id="location">
              <p>
                <span class="errorMessage">
                  <span class="block-arrow"></span>
                  You must search for and select a location if you want to display pictures.
                </span>  
                
                <label>
                  Location/Place name
                  <span class="help-icon dashicons dashicons-info">
                    <span class="block">
                      <span class="block-arrow"></span>
                      Search for a location/place name that you would like to display photos from then select one of the found location names in the drop down box.
                    </span>
                  </span>
                </label>
                <input type="hidden" name="latitude" id="latitude" value="<?php print $details->settings['latitude'] ?>">
                <input type="hidden" name="longitude" id="longitude" value="<?php print $details->settings['longitude'] ?>">
                <input class="widefat" type="text" name="location" id="locationEntry" placeholder="Start typing a location name to search" value="<?php print htmlspecialchars($details->settings['location']) ?>">
                <div class="wpinstagram_widget_loader"></div>
              </p>            
              <div id="locationResults"></div>
            </div>
            <div id="anotherUser">
              <p>
                <span class="errorMessage">
                  <span class="block-arrow"></span>
                  You must search for and select a user if you want to show their pictures.
                </span>
            
                <label>
                  User to Show
                  <span class="help-icon dashicons dashicons-info">
                    <span class="block">
                      <span class="block-arrow"></span>
                      Search for an Instagram user to display in your widget. Start typing the username and then select one of the users listed in the drop down box.
                    </span>
                  </span>
                </label>
                <input type="hidden" name="user" id="otherUserId" placeholder="Start typing a username to search" value="<?php print $details->settings['user'] ?>">                
                <input class="widefat" type="text" name="username" id="otherUser" placeholder="Start typing a username to search" value="<?php print htmlspecialchars($details->settings['username']) ?>">
                <div class="wpinstagram_widget_loader"></div>
              </p>
              <div id="otherUserResults"></div>
            </div>
	    <div id="celebs">
		<span class="block">
                     <span class="block-arrow"></span>
                </span>
                <label>
                     Pick a celebrity to display in the widget area:
                  <span class="help-icon dashicons dashicons-info">
                    <span class="block">
                      <span class="block-arrow"></span>
			These celebrities are the most popular Instagram users.
                    </span>
                  </span>
                </label>                
                <select name="celebPick" class="widefat">
                  <option value="18428658" <?php if ($details->settings['celebPick'] === '18428658') { echo "SELECTED"; } ?>>Kim Kardashian West</option>
                  <option value="11830955" <?php if ($details->settings['celebPick'] === '11830955') { echo "SELECTED"; } ?>>Taylor Swift</option>
                  <option value="208560325" <?php if ($details->settings['celebPick'] === '208560325') { echo "SELECTED"; } ?>>Khloe Kardashian</option>
                  <option value="451573056" <?php if ($details->settings['celebPick'] === '451573056') { echo "SELECTED"; } ?>>Nicki Minaj</option>
                  <option value="12281817" <?php if ($details->settings['celebPick'] === '12281817') { echo "SELECTED"; } ?>>Kylie Jenner</option>
                  <option value="6380930" <?php if ($details->settings['celebPick'] === '6380930') { echo "SELECTED"; } ?>>Kendall Jenner</option>
                  <option value="6860189" <?php if ($details->settings['celebPick'] === '6860189') { echo "SELECTED"; } ?>>Justin Bieber</option>
                  <option value="7719696" <?php if ($details->settings['celebPick'] === '7719696') { echo "SELECTED"; } ?>>Ariana Grande</option>
                  <option value="460563723" <?php if ($details->settings['celebPick'] === '460563723') { echo "SELECTED"; } ?>>Selena Gomez</option>
                  <option value="462151702" <?php if ($details->settings['celebPick'] === '462151702') { echo "SELECTED"; } ?>>Beyonce</option>
                </select>
            </div>
            <div id="tags">
              <p style="margin-bottom: 0px;">
                <span class="errorMessage">
                  <span class="block-arrow"></span>
                  You must enter atleast 1 tag.
                </span>
                
                <label>
                  Tags to show
                  <span class="help-icon dashicons dashicons-info">
                    <span class="block">
                      <span class="block-arrow"></span>
                      Enter up to 4 tags to display photos from. You can enter only 1 tag if you wish.
                    </span>
                  </span>
                </label>
                <input type="text" class="widefat half" name="tag1" value="<?php print htmlspecialchars($details->settings['tag1']) ?>">
                <input type="text" class="widefat half" name="tag2" value="<?php print htmlspecialchars($details->settings['tag2']) ?>">
                <input type="text" class="widefat half" name="tag3" value="<?php print htmlspecialchars($details->settings['tag3']) ?>">
                <input type="text" class="widefat half" name="tag4" value="<?php print htmlspecialchars($details->settings['tag4']) ?>">      
              </p>
              <p style="margin-top: 0px;">
                <label>
                  Search method
                  <span class="help-icon dashicons dashicons-info">
                    <span class="block">
                      <span class="block-arrow"></span>
                      Should the tag search method be cumulative or restrictive?
                    </span>
                  </span>
                </label>                
                <select name="tagCompare" class="widefat">
                  <option value="cumulative" <?php if ($details->settings['tagCompare'] === 'cumulative') { echo "SELECTED"; } ?>>Cumulative (OR)</option>
                  <option value="restrictive" <?php if ($details->settings['tagCompare'] === 'restrictive') { echo "SELECTED"; } ?>>Restrictive (AND)</option>
                </select>
              </p>
            </div>
        
            <input type="button" class="button button-primary widget-control-save right" onclick="savePlugin<?php print $instance['db_id'] ?>(true);" value="Save">
          </div>
          <div class="tabs-panel tabber" data-tab="display">
            <p>
              <span class="errorMessage">
                <span class="block-arrow"></span>
                You must enter a width and/or height. Values must be a whole number and must be greater than zero.
              </span>
              <label>
                Dimensions
                <span class="help-icon dashicons dashicons-info">
                  <span class="block">
                    <span class="block-arrow"></span>
                    Enter the static dimensions for the widget. If you want these dimensions to apply you need to turn off the responsive mode in the settings section.
                  </span>
                </span>
              </label>
              <input type="text" name="width" class="widefat short" 	value="<?php print htmlspecialchars($details->settings['width']) ?>">px by
              <input type="text" name="height" class="widefat short" 	value="<?php print htmlspecialchars($details->settings['height']) ?>">px
            </p>
            <p>
              <label>
                Display as
                <span class="help-icon dashicons dashicons-info">
                  <span class="block">
                    <span class="block-arrow"></span>
                    Choose to show your widget as a grid, a grid with paging or as a slideshow.
                  </span>
                </span>
              </label>
              <select name="method" class="widefat" id="method">
                <option value="grid"	<?php if ($details->settings['method'] === 'grid') 	{ echo "SELECTED"; } ?>>Grid</option>
                <option value="grid-page" <?php if ($details->settings['method'] === 'grid-page') { echo "SELECTED"; } ?>>Grid with paging</option>
                <option value="slideshow" <?php if ($details->settings['method'] === 'slideshow') { echo "SELECTED"; } ?>>Slideshow</option>
              </select>
            </p>
            <p id="gridOptions">
              <label>
                Grid Dimensions
                <span class="help-icon dashicons dashicons-info">
                  <span class="block">
                    <span class="block-arrow"></span>
                    Choose the number of rows and columns you wish to show in your grid. (max 33 pics). 
                  </span>
                </span>
              </label>
              <select name="cols" class="widefat short">
                <option value="1"	<?php if ($details->settings['cols'] === '1') { echo "SELECTED"; } ?>>1</option>
                <option value="2"	<?php if ($details->settings['cols'] === '2') { echo "SELECTED"; } ?>>2</option>
                <option value="3"	<?php if ($details->settings['cols'] === '3') { echo "SELECTED"; } ?>>3</option>
                <option value="4"	<?php if ($details->settings['cols'] === '4') { echo "SELECTED"; } ?>>4</option>
                <option value="5"	<?php if ($details->settings['cols'] === '5') { echo "SELECTED"; } ?>>5</option>
                <option value="6"	<?php if ($details->settings['cols'] === '6') { echo "SELECTED"; } ?>>6</option>
                <option value="7"	<?php if ($details->settings['cols'] === '7') { echo "SELECTED"; } ?>>7</option>
                <option value="8"	<?php if ($details->settings['cols'] === '8') { echo "SELECTED"; } ?>>8</option>
              </select>
               by 
              <select name="rows" class="widefat short">
                <option value="1"	<?php if ($details->settings['rows'] === '1') { echo "SELECTED"; } ?>>1</option>
                <option value="2"	<?php if ($details->settings['rows'] === '2') { echo "SELECTED"; } ?>>2</option>
                <option value="3"	<?php if ($details->settings['rows'] === '3') { echo "SELECTED"; } ?>>3</option>
                <option value="4"	<?php if ($details->settings['rows'] === '4') { echo "SELECTED"; } ?>>4</option>
                <option value="5"	<?php if ($details->settings['rows'] === '5') { echo "SELECTED"; } ?>>5</option>
                <option value="6"	<?php if ($details->settings['rows'] === '6') { echo "SELECTED"; } ?>>6</option>
                <option value="7"	<?php if ($details->settings['rows'] === '7') { echo "SELECTED"; } ?>>7</option>
                <option value="8"	<?php if ($details->settings['rows'] === '8') { echo "SELECTED"; } ?>>8</option>
              </select>
            </p>
            <p class="slideshowOptions">
              <label>
                Transition type
                <span class="help-icon dashicons dashicons-info">
                  <span class="block">
                    <span class="block-arrow"></span>
                    Select one of the transition types for your slideshow widget. Remember to set the transition delay to customise the transition experience.
                  </span>
                </span>
              </label>
              <select name="transition">
                <option value="vert"	<?php if ($details->settings['transition'] === 'vert') 		{ echo "SELECTED"; } ?>>Vertical slide</option>
                <option value="fade"	<?php if ($details->settings['transition'] === 'fade') 		{ echo "SELECTED"; } ?>>Fade in/out</option>
                <option value="horz"	<?php if ($details->settings['transition'] === 'horz') 		{ echo "SELECTED"; } ?>>Horizontal slide</option>
                <option value="shuffle"	<?php if ($details->settings['transition'] === 'shuffle') 	{ echo "SELECTED"; } ?>>Shuffle</option>
                <option value="zoom"	<?php if ($details->settings['transition'] === 'zoom') 		{ echo "SELECTED"; } ?>>Zoom</option>
                <option value="turndown"	<?php if ($details->settings['transition'] === 'turndown') 	{ echo "SELECTED"; } ?>>Turn down</option>
                <option value="fold"	<?php if ($details->settings['transition'] === 'fold') 		{ echo "SELECTED"; } ?>>Fold</option>
              </select>
            </p>
            <p class="slideshowOptions">
              <span class="errorMessage">
                <span class="block-arrow"></span>
                You must enter a transition delay as a whole number that is greater than zero.
              </span>
              <label>
                Transition delay
                <span class="help-icon dashicons dashicons-info">
                  <span class="block">
                    <span class="block-arrow"></span>
                    The transition delay specifies the amount of time, in seconds, between slides in the slideshow.
                  </span>
                </span>
              </label>
              <input type="text" name="delay" value="<?php print htmlspecialchars($details->settings['delay']) ?>" class="widefat short">
            </p>
  
            <input type="button" class="button button-primary widget-control-save right" onclick="savePlugin<?php print $instance['db_id'] ?>(true);" value="Save">
          </div>
          <div class="tabs-panel tabber" data-tab="settings">
            <p>
              <label>
                Enable social sharing icons
                <span class="help-icon dashicons dashicons-info">
                  <span class="block">
                    <span class="block-arrow"></span>
                    Enable or disable the social sharing icons on the widget.                                    
                  </span>
                </span>
              </label>
              <select name="sharing" class="widefat">
                <option value="yes" <?php if ($details->settings['sharing'] === 'yes') { echo "SELECTED"; } ?>>Yes</option>
                <option value="no" <?php if ($details->settings['sharing'] === 'no') { echo "SELECTED"; } ?>>No</option>              
              </select>
            </p>
            <p>
              <span class="errorMessage">
                <span class="block-arrow"></span>
                Your cache duration needs to be a whole number that is greater than zero. A good value is <b>300</b>.
              </span>
              <label>
                Cache Duration (seconds)
                <span class="help-icon dashicons dashicons-info">
                  <span class="block">
                    <span class="block-arrow"></span>
                    Enter the amount of time the system should cache the results from Instagram. Entering a higher cache timeout will help busy websites to handle more traffic on the pages with the widget embedded.
                  </span>
                </span>
              </label>
              <input type="text" name="cache_duration" value="<?php print htmlspecialchars($details->cache_timeout) ?>" class="widefat">
            </p>
            <p>
              <label>
                Responsive
                <span class="help-icon dashicons dashicons-info">
                  <span class="block">
                    <span class="block-arrow"></span>
                    Either enable or disable responsive mode to have the widget automatically resize itself.
                  </span>
                </span>
              </label>
              <select name="responsive" class="widefat">
                <option value="yes" <?php if ($details->settings['responsive'] === "yes") { echo "SELECTED"; } ?>>Yes</option>
                <option value="no" <?php if ($details->settings['responsive'] === "no") { echo "SELECTED"; } ?>>No</option>
              </select>
            </p>
            
            <p>
              <label>
                Show warnings
                <span class="help-icon dashicons dashicons-info">
                  <span class="block">
                    <span class="block-arrow"></span>
                    If you want the system to display warnings when something has gone wrong turn this on, if you want the plugin to be silent then leave this turned off.
                  </span>
                </span>
              </label>
              <select name="verbose" class="widefat">
                <option value="yes" <?php if ($details->settings['verbose'] === "yes") { echo "SELECTED"; } ?>>Yes</option>
                <option value="no" <?php if ($details->settings['verbose'] === "no") { echo "SELECTED"; } ?>>No</option>
              </select>
            </p>
            
            <input type="button" class="button button-primary widget-control-save right" onclick="savePlugin<?php print $instance['db_id'] ?>(true);" value="Save">
          </div>
          <div class="tabs-panel tabber" data-tab="help">
            <div class="linkBar">
              <b>More Help</b>
              
              <ul>
                <li>
                  <a href="http://wordpress.ink361.com/help/configuring" target="_blank">Configuration help &raquo;</a>
                </li>
                <li>
                  <a href="http://wordpress.ink361.com/help/customizing" target="_blank">Customization guide &raquo;</a>
                </li>
                <li>
                  <a href="http://wordpress.ink361.com/help/faq" target="_blank">FAQ &raquo;</a>
                </li>
                <li class="break"></li>
                <li>
                  <a href="http://wordpress.ink361.com" target="_blank">Visit main website &raquo;</a>
                </li>
                <li>
                  <a href="http://ink361.com" target="_blank">INK361.com</a>
                </li>
              </ul>
            </div>
            
            <div class="contactMessage">
              <p>
                <b>Got an issue?</b>
              </p>
            
              <p>
                The INK361 team is here to help!
              </p>
              
              <p>
                Let us know about your issue by contacting us <a href="mailto:support@ink361.com">via email</a>.
              </p>
              
              <p>
                Alternatively, you may find your answer in the links to the right.
              </p>
            </div>
          </div>
        </div>  
        <?php require("formFooter.php") ?>
      </div>
    </div>
  <?php } else { ?>
    <p>
      <?php
        if ($details->error_detected == 1) {
      ?>
        To re-authenticate your widget with Instagram click the <b>Connect To Instagram</b> button. During this process you will be redirected to Instagram to authenticate your widget with the Instagram API.
      <?php
        } else {
      ?>
        To start the Instagram authentication process please click the <b>Connect To Instagram</b> button. During this process you will be redirected to Instagram to authenticate your widget with the Instagram API.      
      <?php
        }
      ?>
    </p>
  
    <input type="button" value="Connect To Instagram" onclick="openTokenConnect<?php print $instance['db_id'] ?>();" class="simpleSetupButton button-primary" id="tokenButton<?php print $instance['db_id'] ?>">
    
    <input type="hidden" name="instance_token" id="instanceToken<?php print $instance['db_id'] ?>">    

    <?php include("message.php"); ?>
  <?php } ?>

  <script>
    function customiseTitle<?php print $instance['db_id'] ?>(title) {
      try {
        var elem  = jQuery('#setupButton<?php print $instance['db_id'] ?>');
        elem.parent().parent().parent().parent().find('h4').html(title);
      } catch(e) {
        
      }
    }
    
    function savePlugin<?php print $instance['db_id'] ?>(close) {            
      if (copyFields<?php print $instance['db_id'] ?>()) {
        jQuery('#setupButton<?php print $instance['db_id'] ?>').parent().parent().find('input[type=submit]').click();
        if (close) {
          jQuery('.lboxWrapper').remove();
        }
      }
    }

    function openTokenConnect<?php print $instance['db_id'] ?>() {   
      location.href='https://api.instagram.com/oauth/authorize/?client_id=fda05624fb064c7ba5d8d8f18e05e4ca&response_type=code&redirect_uri=' + encodeURIComponent('http://wordpress.ink361.com/setup?loc=' + (location.href.split('#')[0].split('?')[0] + '?widget=<?php print $instance['db_id'] ?>')) + '&scope=basic';
    }   

    function openSetup<?php print $instance['db_id'] ?>() {      
      lightbox({
        content : window.setup<?php print $instance['db_id'] ?>,
        frameCls : '',
        closeCallback: function() {
          if (confirm('Would you like to save your changes?')) {
            savePlugin<?php print $instance['db_id'] ?>();
          }
        }      
      });
      
      configureForm<?php print $instance['db_id'] ?>();
    
      jQuery('#form<?php print $instance['db_id'] ?> .instagram-widget-admin-form input, .instagram-widget-admin-form select').change(function() {
        configureForm<?php print $instance['db_id'] ?>();
      });

      //our dropdown search
      jQuery('#form<?php print $instance['db_id'] ?> #otherUser').keyup(function(event) {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(function() {
          searchUserHandler<?php print $instance['db_id'] ?>();
        }, 200);
      });
      
      jQuery('#form<?php print $instance['db_id'] ?> #otherUser').blur(function(event) {
        setTimeout(function() {
          jQuery('#form<?php print $instance['db_id'] ?> #otherUserResults').removeClass('visible');
        }, 250);
      });
      
      jQuery('#form<?php print $instance['db_id'] ?> #locationEntry').keyup(function(event) {
        clearTimeout(window.searchTimeout);
        window.searchTimeout = setTimeout(function() {
          searchLocationHandler<?php print $instance['db_id'] ?>();       
        }, 200);
      });
      
      jQuery('#form<?php print $instance['db_id'] ?> #locationEntry').blur(function(event) {
        setTimeout(function() {          
          jQuery('#form<?php print $instance['db_id'] ?> #locationResults').removeClass('visible');
          
          if (jQuery('#form<?php print $instance['db_id'] ?> #locationEntry').val().replace(' ', '') == '') {
            jQuery('#form<?php print $instance['db_id'] ?> #locationEntry').val("<?php print htmlspecialchars($details->settings['location']) ?>");
          }
        }, 250);
      });
    }
  
    function copyFields<?php print $instance['db_id'] ?>(self) {
      var error = false;
      var fields = [];
      var tabs = [];
      
      var data = {
        title		        : jQuery('#form<?php print $instance['db_id'] ?> input[name=title]').val(),
        display		      : jQuery('#form<?php print $instance['db_id'] ?> select[name=display]').val(),
        user		        : jQuery('#form<?php print $instance['db_id'] ?> input[name=user]').val(),
        tag1		        : jQuery('#form<?php print $instance['db_id'] ?> input[name=tag1]').val(),
        tag2		        : jQuery('#form<?php print $instance['db_id'] ?> input[name=tag2]').val(),
        tag3		        : jQuery('#form<?php print $instance['db_id'] ?> input[name=tag3]').val(),
        tag4		        : jQuery('#form<?php print $instance['db_id'] ?> input[name=tag4]').val(),
        width		        : jQuery('#form<?php print $instance['db_id'] ?> input[name=width]').val(),        
        celebPick	        : jQuery('#form<?php print $instance['db_id'] ?> input[name=celebPick]').val(),        
        height		      : jQuery('#form<?php print $instance['db_id'] ?> input[name=height]').val(),
        method		      : jQuery('#form<?php print $instance['db_id'] ?> select[name=method]').val(),
        cols		        : jQuery('#form<?php print $instance['db_id'] ?> select[name=cols]').val(),
        rows		        : jQuery('#form<?php print $instance['db_id'] ?> select[name=rows]').val(),
        transition	    : jQuery('#form<?php print $instance['db_id'] ?> select[name=transition]').val(),
        delay		        : jQuery('#form<?php print $instance['db_id'] ?> input[name=delay]').val(),
        sharing		      : jQuery('#form<?php print $instance['db_id'] ?> select[name=sharing]').val(),
        cache_duration	: jQuery('#form<?php print $instance['db_id'] ?> input[name=cache_duration]').val(),
        responsive	    : jQuery('#form<?php print $instance['db_id'] ?> select[name=responsive]').val(),
        verbose		      : jQuery('#form<?php print $instance['db_id'] ?> select[name=verbose]').val(),
        latitude        : jQuery('#form<?php print $instance['db_id'] ?> input[name=latitude]').val(),
        longitude       : jQuery('#form<?php print $instance['db_id'] ?> input[name=longitude]').val(),
      };                    
      
      //check always entered values
      if (data.title.replace(' ', '') == '') {
        error = true;
        fields.push(jQuery('#form<?php print $instance['db_id'] ?> input[name=title]'));
        tabs.push(jQuery('#form<?php print $instance['db_id'] ?> #contentTab'));
      }
      
      if (data.width.replace(' ', '') == '' || !(data.width === parseInt(data.width, 10) + '') || 0 >= parseInt(data.width, 10)) {
        error = true;
        fields.push(jQuery('#form<?php print $instance['db_id'] ?> input[name=width]'));
        tabs.push(jQuery('#form<?php print $instance['db_id'] ?> #displayTab'));
      }
      
      if (data.height.replace(' ', '') == '' || !(data.height === parseInt(data.height, 10) + '') || 0 >= parseInt(data.height, 10)) {
        error = true;
        fields.push(jQuery('#form<?php print $instance['db_id'] ?> input[name=height]'));
        tabs.push(jQuery('#form<?php print $instance['db_id'] ?> #displayTab'));        
      }
      
      if (data.cache_duration.replace(' ', '') == '' || !(data.cache_duration === parseInt(data.cache_duration, 10) + '') || 0 >= parseInt(data.cache_duration, 10)) {
        error = true;
        fields.push(jQuery('#form<?php print $instance['db_id'] ?> input[name=cache_duration]'));
        tabs.push(jQuery('#form<?php print $instance['db_id'] ?> #settingsTab'));
      }
      
      //now for specifics
      if (data.display == 'user') {
        //user needs to be a number
        if (data.user.replace(' ', '') == '') {
          error = true;
          fields.push(jQuery('#form<?php print $instance['db_id'] ?> input[name=user]'));
          tabs.push(jQuery('#form<?php print $instance['db_id'] ?> #contentTab'));
        }
      } else if (data.display == 'tags') {
        //atleast 1 tag
        if (data.tag1.replace(' ', '') == '' && data.tag2.replace(' ', '') == '' && data.tag3.replace(' ', '') == '' && data.tag4.replace(' ', '') == '') {
          error = true;
          fields.push(jQuery('#form<?php print $instance['db_id'] ?> input[name=tag1]'));
          tabs.push(jQuery('#form<?php print $instance['db_id'] ?> #contentTab'));      
        }
      } else if (data.display == 'location') {
        //need latitude and longitude
        if (data.latitude.replace(' ', '') == '' && data.longitude.replace(' ', '') == '') {
          error = true;
          fields.push(jQuery('#form<?php print $instance['db_id'] ?> input[name=location]'));
          tabs.push(jQuery('#form<?php print $instance['db_id'] ?> #contentTab'));
        }
      }
      
      if (data.method == 'slideshow') {
        //need a valid transition value
        if (data.delay.replace(' ', '') == '' || !(data.delay === parseInt(data.delay, 10) + '') || 0 >= parseInt(data.delay, 10)) {
          error = true;
          fields.push(jQuery('#form<?php print $instance['db_id'] ?> input[name=delay]'));
          tabs.push(jQuery('#form<?php print $instance['db_id'] ?> #displayTab'));
        }
      }

      //clear all formattings
      jQuery('#form<?php print $instance['db_id'] ?> p').removeClass('error');
      jQuery('#form<?php print $instance['db_id'] ?> .tabber').removeClass('error');
      
      if (error) {
        for (var i = 0; fields.length > i; i++) {
          jQuery(fields[i]).parent().addClass('error');
        }
        
        for (var i = 0; tabs.length > i; i++) {
          jQuery(tabs[i]).addClass('error');
        }
      } else {    
        jQuery('#hiddenFields<?php print $instance['db_id'] ?>').html('');
        
        var elems = jQuery('#form<?php print $instance['db_id'] ?>').find('input');
      
        for (var i = 0; elems.length > i; i++) {
          jQuery('#hiddenFields<?php print $instance['db_id'] ?>').append(elems[i]);
        }

        var elems = jQuery('#form<?php print $instance['db_id'] ?>').find('select');
    
        for (var i = 0; elems.length > i; i++) {
          jQuery('#hiddenFields<?php print $instance['db_id'] ?>').append(elems[i]);
        }
      }
      
      return !error;
    }
  
    jQuery(document).ready(function() {      
      if (!window.switchTab) {
        window.switchTab = function(obj) {
          var tab = jQuery(obj).attr('data-tab');
        
          jQuery('.tabber').removeClass('active');
        
          jQuery('[data-tab="' + tab + '"]').addClass('active');
        }
      }
    
      if (!window.configureForm<?php print $instance['db_id'] ?>) {
        window.configureForm<?php print $instance['db_id'] ?> = function() {
          //reset everything          
          jQuery('#form<?php print $instance['db_id'] ?> #anotherUser').hide();          
          jQuery('#form<?php print $instance['db_id'] ?> #location').hide();
          jQuery('#form<?php print $instance['db_id'] ?> #tags').hide();
          jQuery('#form<?php print $instance['db_id'] ?> #celebs').hide();
          jQuery('#form<?php print $instance['db_id'] ?> #gridOptions').hide();
          jQuery('#form<?php print $instance['db_id'] ?> .slideshowOptions').hide();
          
          if (jQuery('#form<?php print $instance['db_id'] ?> #display').val() == 'tags') {
            jQuery('#form<?php print $instance['db_id'] ?> #tags').show();
          }
          if (jQuery('#form<?php print $instance['db_id'] ?> #display').val() == 'celebs') {            
            jQuery('#form<?php print $instance['db_id'] ?> #celebs').show();
          }
          if (jQuery('#form<?php print $instance['db_id'] ?> #display').val() == 'user') {            
            jQuery('#form<?php print $instance['db_id'] ?> #anotherUser').show();
          }
          if (jQuery('#form<?php print $instance['db_id'] ?> #display').val() == 'location') {
            jQuery('#form<?php print $instance['db_id'] ?> #location').show();
          }          
          if (jQuery('#form<?php print $instance['db_id'] ?> #method').val() == 'grid' || jQuery('#form<?php print $instance['db_id'] ?> #method').val() == 'grid-page') {
            jQuery('#form<?php print $instance['db_id'] ?> #gridOptions').show();
          } else if (jQuery('#form<?php print $instance['db_id'] ?> #method').val() == 'slideshow') {
            jQuery('#form<?php print $instance['db_id'] ?> .slideshowOptions').show();
          }          
        }
      }
  
      window.setup<?php print $instance['db_id'] ?> = jQuery('#setupForm<?php print $instance['db_id'] ?>').html();
      
      jQuery('#setupForm<?php print $instance['db_id'] ?>').html('');
  
      var token = '<?php print $instance['db_id'] ?>';
      if (location.href.replace('widget=' + token, '') != location.href) {
        setTimeout(function() {
          var parts = location.search.replace('?', '').replace('#', '').split('&');
        
          for (var i = 0; parts.length > i; i++) {
            var p = parts[i].split('=');
          
            if (p[0] == 'token') {
              var access_token = p[1];
            
              jQuery('#instanceToken<?php print $instance['db_id'] ?>').val(access_token);            
              jQuery('#tokenButton<?php print $instance['db_id'] ?>').parent().parent().find('input[type=submit]').click();

              setTimeout(function() {
                location.href = location.href.split('#')[0].split('?')[0] + '?openWidget=' + this;
              }.bind('<?php print $this->id ?>'), 100);
            }
          }
        }, 100);
      }
    
      if (location.search != '' && location.search.replace('openWidget', '') != location.search) {      
        setTimeout(function() {
          var parts = location.search.replace('?', '').replace('#', '').split('&');
          for (var i = 0; parts.length > i; i++) {
            var p = parts[i].split('=');
            
            if (p[0] == 'openWidget') {
              var widgetId = p[1];
            
              var elems = jQuery('.widget');
              for (var j = 0; elems.length > j; j++) {
                if (jQuery(elems[j]).attr('id').indexOf(widgetId) > 0) {
                  jQuery(elems[j]).addClass('open');
                  jQuery(elems[j]).find('.widget-inside').show();
                }
              }
            }
          }
        }, 100);
      }
      
      if (!window.searchLocationHandler<?php print $instance['db_id'] ?>) {
        window.searchLocationHandler<?php print $instance['db_id'] ?> = function() {                    
          var keywords = jQuery('#form<?php print $instance['db_id'] ?> #locationEntry').val();
          
          if (keywords.length > 2) {
            //show the loader
            jQuery('#form<?php print $instance['db_id'] ?> #location').addClass('wploading');
            
            jQuery.ajax({
              url       : 'https://nominatim.openstreetmap.org/search/' + keywords,
              jsonp     : 'json_callback',
              dataType  : 'jsonp',
              data      : {
                format          : 'json', 
                addressdetails  : '1',
                email           : 'support@ink361.com',
                dedupe          : '1',
              },
              success   : function(response) {
                jQuery('#form<?php print $instance['db_id'] ?> #latitude').val('');
                jQuery('#form<?php print $instance['db_id'] ?> #longitude').val('');                
                var seen = [];
                
                if (response && response.length > 0) {
                  var html = '';
                  for (var i = 0; i < response.length; i++) {
                    var loc = response[i];                    
                    if (loc['class'] != 'waterway' && loc['class'] != 'boundary' && loc['class'] != 'aeroway') {
                      var parts = [];
                      
                      if (loc.address.city) {
                        parts.push(loc.address.city);                        
                      } else if (loc.address.town) {
                        parts.push(loc.address.town);
                      } else if (loc.address.village) {
                        parts.push(loc.address.village);
                      } else if (loc.address.hamlet) {
                        parts.push(loc.address.hamlet);
                      }
                      
                      if (loc.address.state_district && loc.address.state_district != parts[0]) {
                        parts.push(loc.address.state_district);
                      } else if (loc.address.state) {
                        parts.push(loc.address.state);
                      } else if (loc.address.count) {
                        parts.push(loc.address.count);
                      }
                      
                      if (loc.address.country) {
                        parts.push(loc.address.country);
                      }
                      
                      if (parts.length > 0) {
                        if (seen.indexOf(parts.join(', ')) == -1) {
                          html += '<div class="result" data-latitude="' + loc.lat + '" data-longitude="' + loc.lon + '" data-name="' + parts.join(', ') + '">' + parts.join(', ') + '</div>';
                          seen.push(parts.join(', '));
                        }  
                      }                                              
                    }                    
                  }
                  
                  jQuery('#form<?php print $instance['db_id'] ?> #locationResults').html(html);
                       
                  jQuery('#form<?php print $instance['db_id'] ?> #locationResults').find('.result').click(function() {
                    jQuery('#form<?php print $instance['db_id'] ?> #locationEntry').val(jQuery(this).attr('data-name'));
                    jQuery('#form<?php print $instance['db_id'] ?> #latitude').val(jQuery(this).attr('data-latitude'));
                    jQuery('#form<?php print $instance['db_id'] ?> #longitude').val(jQuery(this).attr('data-longitude'));
                  }); 
                  
                  jQuery('#form<?php print $instance['db_id'] ?> #locationResults').addClass('visible');
                } else {
                  noLocationsFound<?php print $instance['db_id'] ?>();
                }  
                
                jQuery('#form<?php print $instance['db_id'] ?> #location').removeClass('wploading');    
              }              
            });
          }        
        }
      }
    
      if (!window.searchUserHandler<?php print $instance['db_id'] ?>) {
        window.searchUserHandler<?php print $instance['db_id'] ?> = function() {
          var keywords = jQuery('#form<?php print $instance['db_id'] ?> #otherUser').val();                 
                 
          if (keywords.length > 2) {
            jQuery('#form<?php print $instance['db_id'] ?> #anotherUser').addClass('wploading');
            
            jQuery.ajax({
              url	: 'https://api.instagram.com/v1/users/search',
              jsonp 	: "callback",
              dataType	: "jsonp",
              data 	: {
                access_token : '<?php print $details->token ?>', 
                q : keywords
              },
              success	: function(response) {
                //reset the id
                jQuery('#form<?php print $instance['db_id'] ?> #otherUserId').val('');
                                   
                if (response && response.data && response.data.length > 0) {
                  var html = '';
                  for (var i = 0; i < response.data.length; i++) {
                    html += '<div class="result" data-id="' + response.data[i].id + '" data-name="' + response.data[i].username + '">' + response.data[i].username + '</div>';
                  }
                  
                  jQuery('#form<?php print $instance['db_id'] ?> #otherUserResults').html(html);
                       
                  jQuery('#form<?php print $instance['db_id'] ?> #otherUserResults').find('.result').click(function() {
                    jQuery('#form<?php print $instance['db_id'] ?> #otherUser').val(jQuery(this).attr('data-name'));
                    jQuery('#form<?php print $instance['db_id'] ?> #otherUserId').val(jQuery(this).attr('data-id'));
                  }); 
                  
                  jQuery('#form<?php print $instance['db_id'] ?> #otherUserResults').addClass('visible');
                } else {
                  //show no users
                  noUsersFound<?php print $instance['db_id'] ?>();
                }
               
                jQuery('#form<?php print $instance['db_id'] ?> #anotherUser').removeClass('wploading');         
              }
            });
          }    
        }
      }
    
      if (!window.noUsersFound<?php print $instance['db_id'] ?>) {
        window.noUsersFound<?php print $instance['db_id'] ?> = function() {
          jQuery('#form<?php print $instance['db_id'] ?> #otherUserResults').html('<div class="noResults">Nobody found</div>');
          jQuery('#form<?php print $instance['db_id'] ?> #otherUserResults').addClass('visible');                         
        }
      }
      
      if (!window.noLocationsFound<?php print $instance['db_id'] ?>) {
        window.noLocationsFound<?php print $instance['db_id'] ?> = function() {
          jQuery('#form<?php print $instance['db_id'] ?> #locationResults').html('<div class="noResults">No locations found</div>');
          jQuery('#form<?php print $instance['db_id'] ?> #locationResults').addClass('visible');                         
        }
      }   
      
      //customise with our title
      customiseTitle<?php print $instance['db_id'] ?>('<?php print $customTitle ?>');
    });
  </script>
<?php
  } else {
?>
  <p id="wpinstagram-widget-__i__" class="pDetect">
    Please wait while we create a database entry for this widget, your page may refresh during this process.
  </p>

  <script>
    jQuery(document).ready(function() {
      var elems = jQuery('.pDetect');
    
      for (var i = 0; i < elems.length; i++) {
        if (jQuery(elems[i]).attr('id').replace('__i__', '') == jQuery(elems[i]).attr('id')) {
          setTimeout(function() {
            location.href = location.href.split('#')[0].split('?')[0] + '?openWidget=' + jQuery(this).attr('id');
          }.bind(elems[i]), 1000);
        }
      }
    });
  </script>
<?php
  }
?>
