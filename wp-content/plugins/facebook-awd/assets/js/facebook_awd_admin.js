/**
 * 
 * @author alexhermann
 *
 */
function AWDFacebookAdmin($){

	this.reloadAppInfos = function(button)
	{
		$(button).button('loading');
		$.post(ajaxurl,{action:'get_app_infos_content'},function(data){	
			if(data)
				jQuery('#awd_fcbk_app_infos_metabox.postbox .inside').html(data);
				
			$(button).button('reset');
		});
	};
	
	this.showUploadDialog = function(button)
	{
		var $button = $(button);
		var $data = $button.data();
		var post_id = $data.postId ? $data.postId : 0;
		var $field = $button.prev();
		var url = null;
		var formfieldName =  $field.attr('name');
		tb_show($data.title, 'media-upload.php?post_id='+post_id+'&type='+$data.type+'&amp;TB_iframe=true');
		window.send_to_editor = function(html){
			url = jQuery('img',html).attr('src');
			if(url == '' || typeof(url) == 'undefined'){
				url = jQuery(html).attr('href');
			}
			console.log("url: ",url)
			$field.val(url);
			tb_remove();
		}
		return false;
	};
	
	this.initToolTip = function()
	{
		$('.awd_tooltip_donate').popover({
			placement: 'top',
			title : function(){
				return $(".header_lightbox_donate_title").html();
			},
			content: function(){
				var html = $("#lightbox_donate").html();
				if(html == null){html = '...';}
				return '<div class="AWD_facebook_wrap">'+html+'</div>';
			},
			delay:{
				show:300,
				hide:500
			}
		});
		$('.awd_tooltip').popover({
			placement: 'right',
			title : function(){
				return $(".header_lightbox_help_title").html();
			},
			content: function(){
				var id = $(this).attr('id');
				console.log(id);
				var html = $("#lightbox_"+id).html();
				if(html == null){html = '...';}
				return '<div class="AWD_facebook_wrap">'+html+'</div>';
			},
			delay:{
				show:300,
				hide:500
			}
		});
	};
	
	this.initTab = function()
	{
		$('#plugins_menu li:eq(0) a').tab('show');
		$('#settings_menu li:eq(0) a').tab('show');
		$('#settings_ogp_menu li:eq(0) a').tab('show');
	};
	
	this.dependListener = function(master, depend, value)
	{
		$(master).change(function(){
			var curentValue = $(this).val();
			if($.inArray(curentValue, value) >= 0){
				$(depend).attr('disabled', false);
			}else{
				$(depend).attr('disabled', true);
			}
		});
		$(master).trigger('change');
	};
	
	this.bindEvents = function()
	{	
		var $awd = this;
		$awd.initToolTip();
		//Admin
		$(".alert").alert();
		$('#reload_app_infos').live('click',function(e){
			e.preventDefault();
			$awd.reloadAppInfos(this);
		});
		
		
		$('#toogle_list_pages').live('click',function(e){
			e.preventDefault();
			$('.toogle_fb_pages').slideToggle();
		});
		
		//Forms
		$awd.dependListener('#awd_fcbk_option_connect_enable','.depend_fb_connect', ['1']);
		$awd.dependListener('#awd_fcbk_option_like_button_on_pages','.depend_like_button_on_pages', ['1']);
		$awd.dependListener('#awd_fcbk_option_like_button_on_posts','.depend_like_button_on_posts', ['1']);
		$awd.dependListener('#awd_fcbk_option_like_button_on_custom_post_types','.depend_like_button_on_custom_post_types', ['1']);
		$awd.dependListener('.login_button_show_faces', '.depend_login_button_show_faces', ['1']);
		$awd.dependListener('.like_button_type', '.depend_like_button_type', ['xfbml', 'html5']);
		$awd.dependListener('.comments_box_place', '.depend_on_comments_box_place', ['replace']);
		
		
		$(".AWD_button_media").live('click',function(e){
			e.preventDefault();
			$awd.showUploadDialog(this);
		});
		$(".AWD_delete_media").live('click',function(e){
			e.preventDefault();
			$(this).parent().parent().slideUp().remove();
		});
		
		$('.awd_tooltip').live('click',function(e){ e.preventDefault();});
		
		$('#submit_settings').click(function(e){
			$(this).button('loading');
			e.preventDefault();
			$('#awd_fcbk_option_form_settings').submit();
		});
		$('#reset_settings').click(function(e){
			$(this).button('loading');
			e.preventDefault();  			
			$(".alert_reset_settings").slideDown();  
		});
		$('.reset_settings_dismiss').click(function(e){
			e.preventDefault();		
			$('#reset_settings').button('reset');
			$(".alert_reset_settings").slideUp();  
		});
		$('.reset_settings_confirm').click(function(e){
			e.preventDefault();  
			$(this).button('loading');
			$('#awd_fcbk_option_reset_settings').submit();
		});
		
		$('.get_permissions').live('click',function(e){
			e.preventDefault();
			var $this = $(this);
			var scope = $this.data('scope');
			FB.login(function(response)
			{
				if(response.authResponse) {
				    $('#awd_fcbk_option_form_settings').submit();
				}
			},{scope: scope});
		});
		
		//Open graph form
		$('.show_ogp_form').button('reset');
		
		$('#awd_fcbk_option_opengraph_object_link').live('change',function(){
			if($(this).val() == 'custom'){
				$('.opengraph_object_form').slideDown();
			}else{
				$('.opengraph_object_form').slideUp();
			}
		});
		$('#awd_fcbk_option_opengraph_object_link').trigger('change');

		
		$('#awd_fcbk_option_awd_ogp_type').live('change',function(){
			if($(this).val() == 'custom'){
				$('.depend_opengraph_custom_type').slideDown();
			}else{
				$('.depend_opengraph_custom_type').slideUp();
			}
		});
		$('#awd_fcbk_option_awd_ogp_type').trigger('change');
		
		
		$('.awd_add_media_field').live('click',function(e){
			e.preventDefault();
			var $button = $(this);
			var label = $button.data('label');
			var type = $button.data('type');
			var name = $button.data('name');
			var label2 = $button.data('label2');
			$button.button('loading');
			$.post(ajaxurl,{action:'get_media_field', label: label, type: type, name: name, label2: label2},function(data){	
				if(data)
					$('.awd_ogp_fields_'+type+'').append(data);
				$button.button('reset');
			});
		});
		
		//show an empty form
		$('.show_ogp_form').live('click',function(e){
			e.preventDefault();
			var $button = $(this);
			var object_id = '';
			$button.button('loading');
			$('.awd_ogp_form').slideUp();
			$.post(ajaxurl,'object_id='+object_id+'&action=get_open_graph_object_form',function(data){	
				$('.awd_ogp_form').html(data).slideDown();
				$awd.initToolTip();
				prettyPrint();
				$('#awd_fcbk_option_awd_ogp_type').trigger('change');
			});
		});
		
		//show an existing object form
		$('.awd_edit_opengraph_object').live('click',function(e){
			var $button = $(this);
			var object_id = $(this).parent().data('objectId');
			var copy = $(this).hasClass('copy');
			$button.button('loading');
			$('.show_ogp_form').button('loading');
			$('.awd_ogp_form').slideUp();
			$.post(ajaxurl,'object_id='+object_id+'&copy='+copy+'&action=get_open_graph_object_form',function(data){	
				$('.awd_ogp_form').html(data).slideDown();
				$awd.initToolTip();
				$button.button('reset');
				prettyPrint();
				$('#awd_fcbk_option_awd_ogp_type').trigger('change');
			});
		});
		
		//hide the form and reset it.
		$('.hide_ogp_form').live('click',function(e){
			e.preventDefault();
			$('.awd_ogp_form').slideUp().html('');
			$('.show_ogp_form').button('reset');
		});
		
		//delete an existing object
		$('.awd_delete_opengraph_object').live('click',function(e){
			var $button = $(this);
			var object_id = $(this).parent().data('objectId');
			var $tr = $(this).closest('tr');
			$button.button('loading');
			$.post(ajaxurl,'object_id='+object_id+'&action=delete_ogp_object',function(data){	
				if(data.success){
					$tr.slideUp().remove();
					if(data.count == 0){
						$('.awd_no_objects').slideDown();
					}
					$('.awd_ogp_links').html(data.links_form);
				}
				$button.button('reset');
			}, 'json');
		});
		
		
		//Pattern template vars in opengraph
		var id_focused="";
		$(":input").live('focus',function () {
			id_focused = $(this).attr('id');
		});
		$(".opengraph_placeholder button").live('click', function(e){
			e.preventDefault();
			var b = $(this);
			var value = $("#"+id_focused).val();
			$("#"+id_focused).val(value + b.text());
			
		});
		
		//submit the form to update/save the object
		$('#awd_fcbk_option_form_create_opengraph_object').live('submit',function(e){
			e.preventDefault();
			var $form = $(this);
			$('.awd_submit_ogp').button('loading');
			$.post(ajaxurl,$form.serialize()+'&action=save_ogp_object',function(data){	
				if(data.success){
					$('.awd_ogp_form').slideUp().html('');
					if($('.awd_object_item_'+data.item_id).length > 0){
						$('.awd_object_item_'+data.item_id).replaceWith($(data.item));
					}else{
						$('.awd_list_opengraph_object tbody.content').append(data.item);
					}
					$('.awd_no_objects').slideUp();
					$('.awd_ogp_links').html(data.links_form);
				}
				$('.awd_submit_ogp').button('reset');
				$('.show_ogp_form').button('reset');
			},'json');
		});
		
		$('#submit_ogp').live('click',function(e){
			e.preventDefault();
			$('#awd_fcbk_option_form_create_opengraph_object_links').submit();
		});
		$('#awd_fcbk_option_form_create_opengraph_object_links').live('submit',function(e){
			e.preventDefault();
			$('#submit_ogp').button('loading');
			$.post(ajaxurl,$(this).serialize()+'&action=save_ogp_object_links',function(data){
				$('#submit_ogp').button('reset');
			});
		});
		
		$('.awd_debug .page-header').each(function(){
			$(this).next().hide();
			$(this).css('cursor', 'pointer');
			$(this).click(function(){
				$(this).next().slideToggle();
			});
		});
		
	
	};
	
	
	this.init = function()
	{
		$.fn.button.defaults = {loadingText: 'Loading...'};
		$('.AWD_profile').wrap('<div class="well">');
		$('.AWD_logout a').addClass('btn btn-danger').css('marginTop','5px');
		$('.AWD_profile_image').addClass('pull-left').css('marginRight','10px');		
		$(".fadeOnload").delay(4000).fadeOut();	
		this.bindEvents();
		this.initTab();
		prettyPrint();
		$('.AWD_facebook').button();
	};
	this.init();
};
var AWD_facebook_admin;
jQuery(document).ready( function($){
	AWD_facebook_admin = new AWDFacebookAdmin($);
});

	
	