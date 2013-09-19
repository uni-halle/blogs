<?php
/*
*
* Help settings Admin AWD FCBK
* (C) 2011 AH WEB DEV
* Hermann.alexandre@ahwebdev.fr
*
*/
/**
* Help app id
*/
?>
<div class="header_lightbox_help_title hidden"><img style="vertical-align:middle;" src="<?php echo $this->plugin_url_images; ?>facebook-mini.png" alt="facebook logo"/> <?php _e('Help',$this->ptd); ?></div>

<div id="lightbox_help_debug_enable" class="hidden">
	<p><?php
	_e("Help you debugging Facebook AWD object.",$this->ptd); 
	?></p>
</div>
<div id="lightbox_help_app_id" class="hidden">
	<p>
	<?php
	printf(__("This is the id of your application on Facebook. You can find this info on your application dashboard on Facebook developers platform. If you have not yet create an application, you can create one %shere%s.",$this->ptd),'<a class="btn btn-mini" href="https://www.facebook.com/developers/createapp.php" target="_blank">','</a>'); 
	?>
	</p>
</div>
<div id="lightbox_help_admins" class="hidden">
	<p>
	<?php
	_e("This is Your facebook user id.",$this->ptd);
	?>
	</p>
</div>
<div id="lightbox_help_app_secret_key" class="hidden">
	<p>
	<?php
	printf(__("This is the secret key of your application on Facebook. You can find this info on your application dashboard on Facebook developers platform. If you have not yet create an application, you can create one %shere%s.",$this->ptd),'<a class="btn btn-mini" href="https://www.facebook.com/developers/createapp.php" target="_blank">','</a>'); 
	?>
	</p>
</div>
<div id="lightbox_help_locale" class="hidden">
	<p>
	<?php
	_e("Lang to use with Facebook (default to en_US, or equal to WPLANG Constant if set in wp-config.php)",$this->ptd);
	?>
	</p>
</div>
<div id="lightbox_help_open_graph_enable" class="hidden">
	<p>
	<?php
	printf(__("The Open Graph Protocol enables you to integrate your Web pages into the social graph. It is currently designed for Web pages representing profiles of real-world things — things like movies, sports teams, celebrities, and restaurants. Including Open Graph tags on your Web page, makes your page equivalent to a Facebook Page. This means when a user clicks a Like button on your page, a connection is made between your page and the user. Your page will appear in the ”Likes and Interests” section of the user’s profile, and you have the ability to publish updates to the user. Your page will show up in the same places that Facebook pages show up around the site (e.g. search), and you can target ads to people who like your content. The structured data you provide via the Open Graph Protocol defines how your page will be represented on Facebook. %sRead more%s",$this->ptd),'<a class="btn btn-mini" href="https://developers.facebook.com/docs/opengraph/" target="_blank">','</a>'); 
	?>
	</p>
</div>
<div id="lightbox_help_connect_enable" class="hidden">
	<p>
	<?php
	printf(__("Facebook helps you simplify and enhance user registration and sign-in by using Facebook as your login system. Users no longer need to fill in yet another registration form or remember another username and password to use your site. As long as the user is signed into Facebook, they are automatically signed into your site as well. Using Facebook for login provides you with all the information you need to create a social, personalized experience from the moment the user visits your site in their browser. %sRead more%s",$this->ptd),'<a class="btn btn-mini" href="https://developers.facebook.com/docs/guides/web/#login" target="_blank">','</a>'); 
	?>
	</p>
</div>

<div id="lightbox_help_perms" class="hidden">
	<p>
	<?php
	printf(__('Facebook Connect permissions (example: publish_stream,user_photos,user_status) %sSee the list of permissions%s',$this->ptd),'<a class="btn btn-mini" href="https://developers.facebook.com/docs/authentication/permissions/" target="_blank">',"</a>");
	?>
	</p>
</div>
<div id="lightbox_help_timeout" class="hidden">
	<p>
	<?php _e('This value represents the maximum time in seconds to connect to the Facebook API. If your server is slow, increase this value, then try again',$this->ptd); ?>
	</p>
</div>
<div id="lightbox_help_connect_fbavatar" class="hidden">
	<p><?php
	_e("This will add the Facebook Avatar to the avatar function of Wordpress, You will be able to activate it on the discussion settings.",$this->ptd); 
	?></p>
</div>


<div id="lightbox_help_publish_to_pages" class="hidden">
	<p><?php
	_e("If YES the checkbox to publish on facebook in post editor will be always checked.",$this->ptd); 
	?></p>
</div>    
<div id="lightbox_help_publish_to_profile" class="hidden">
	<p><?php
	_e("If YES the checkbox to publish on facebook in post editor will be always checked.",$this->ptd); 
	?></p>
</div>      
<div id="lightbox_help_publish_read_more_text" class="hidden">
	<p><?php
	_e("You can redefine the default text use for the action link. This setting can be redefined in the post editor.",$this->ptd); 
	?></p>
</div>


<div class="header_lightbox_donate_title hidden"><img style="vertical-align:middle;" src="<?php echo $this->plugin_url_images; ?>facebook-mini.png" alt="facebook logo"/> <?php _e('I am sure you love it...',$this->ptd); ?></div>
<div id="lightbox_donate" class="hidden">
	<p><?php
	_e("I am sure you love it, you can help me to continue support this plugin...Click!",$this->ptd); 
	?></p>
</div>
