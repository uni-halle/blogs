/**
 * 
 * @author alexhermann
 *
 */
var AWD_facebook = function(){
	
	return {
		FbEventHandler : function ()
		{
			if(awd_fcbk.FBEventHandler.callbacks){
				jQuery.each(awd_fcbk.FBEventHandler.callbacks,function(index,value){
					var AWD_actions_callback = window[this];
					if(jQuery.isFunction(AWD_actions_callback))
						AWD_actions_callback(this);
				});
			}
	
			jQuery('.AWD_facebook_connect_button').live('click',function(e){
				e.preventDefault();
				var redirect_url = jQuery(this).data('redirect');
				AWD_facebook.connect(redirect_url);
			});
		},
		
		callbackLogin : function(response,redirect_url)
		{
			var redirect = '';
			if(response.authResponse){
				if(!redirect_url){
					window.location.href = awd_fcbk.loginUrl;
				}else{
					redirect = "?redirect_to="+redirect_url;
					window.location.href = awd_fcbk.loginUrl+redirect;
				}
			}
		},
		
		connect :function(redirect_url)
		{
			FB.login(
				function(response){
					AWD_facebook.fbConnected = true;
					AWD_facebook.callbackLogin(response,redirect_url);
				},
				{ 
					scope: awd_fcbk.scope
				}
			);
			return false;
		},
		
		logout : function(){
			window.location.href = awd_fcbk.logoutUrl;
		},
			
		isFbConnected : function(){
			FB.getLoginStatus(function(response) {
				if (response.status === 'connected') {
					AWD_facebook.fbConnected = true;
					AWD_facebook.access_token = response.authResponse.accessToken;
				}
			});
			return AWD_facebook.fbConnected;	
		}
	};
}();
