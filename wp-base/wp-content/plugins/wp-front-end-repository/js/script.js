var current_page = 1;

jQuery(function() {

	// hiding all fileuploader but only page-1
	jQuery("ul#nmuploader-container li.nm-c-p-1").show();

	// hiding file detail
	jQuery(".file-detail").hide();

	
	/*
	 * creating directory
	 */
	jQuery("#nm-frm-directory").submit(function(){
		jQuery("#working-area-directory").show();
		
		var data = jQuery(this).serialize();
		//console.log(data);
		data = data + '&action=repo_createDirectory';
		jQuery.post(nm_repository_vars.ajaxurl, data, function(response){
			//console.log(response);
			if(nm_repository_vars.dircreated_message != ''){
				alert(nm_repository_vars.dircreated_message);
			}
			window.location.reload(true);
		});
	});
	
});

function setupUploader() {
	/*
	 * uploadify version 2.1.4
	*/
	
	// alert(nm_repository_vars.fileuploader_token);
	jQuery('#file_upload').uploadify(
			{
				'uploader' : nm_repository_vars.plugin_url + '/js/uploadify/uploadify.allglyphs.swf',
				'script' : nm_repository_vars.ajaxurl,
				'scriptData' : {
					'action' : 'repo_uploadFile',
					'username' : nm_repository_vars.current_user,
					'parentID'	: nm_repository_vars.parent_id
				},
				'cancelImg' : nm_repository_vars.plugin_url
						+ '/js/uploadify/cancel.png',
				'auto' : true,
				'buttonText' : 'Select File',
				'multi' : true,
				'fileExt' : getOption('nm_repository_files_allowed'),
				'fileDesc' : 'Select Files',
				'auto' : true,
				'sizeLimit' : getOption('nm_repository_size_limit'),
				'onComplete' : function(event, ID, fileObj, filename, data) {
					//alert(filename);
					oldVal = jQuery("#file-name").attr("value");
					if (oldVal != "")
						oldVal += ",";
					newVal = oldVal + filename;
					jQuery("#file-name").attr("value", newVal);
					jQuery("#upload-response").append("<br>" + filename	+ nm_repository_vars.fileuploaded_message).fadeIn(200);
				}
			// Put your options here
			});
	
}

/*
 * validate me
 */
function validate() {
	
	jQuery("#working-area").show();

	var file_name = jQuery("#file-name").val();

	var notices = jQuery("#error");
	notices.html('');

	var vFlag = false;

	if (file_name == '') {
		notices.append('Select any file first<br>');
		vFlag = true;
	}

	
	//metaFlag = validateMeta(notices, false);
	
	//if (vFlag || metaFlag) {
	if (vFlag) {
		jQuery("#working-area").hide();
	
	} else {
		saveFile();
	}
	
	return false;
}

/*
 * validate and prepare meta array
 */

function validateMeta(resp_error, isedit){
	
	fileMeta = jQuery.parseJSON(nm_repository_vars.file_meta);
	
	var vFlag = false;
	
	/* resetting the meta array */
	metaData = [];
		
	jQuery
			.each(
					fileMeta,
					function(i, item) {

						/* making element name */
						var elementName = item.label.toLowerCase();
						elementName = elementName.replace(' ', '-');
						
						if(isedit){
							elementName = elementName + '-e';
						}

						var metaEmpty = false;
						var data = new Object;
						
						/* copying meta structure to data struct */
						data = item;

						switch (item.type) {

						case 'text':

							data.data = jQuery('input:text[name^="' + elementName + '"]').val();
							
							if (data.data == '') {

								metaEmpty = true;
							}
							break;

						case 'checkbox':
							
							var checkedVals = [];
							jQuery('input:checkbox[name^="' + elementName + '"]:checked').each(function() {
								checkedVals.push(jQuery(this).val());
							});
							
							data.data = checkedVals;
							
						if (!jQuery('input:checkbox[name^="' + elementName + '"]').is(':checked')) {

								metaEmpty = true;
							}

							break;

						case 'select':
							
							data.data = jQuery('select[name^="' + elementName + '"]').val();

							if (data.data == '') {

								metaEmpty = true;
							}

							break;

						case 'textarea':
							
							data.data = jQuery('textarea[name^="' + elementName + '"]').val();

							if (data.data == '') {

								metaEmpty = true;
							}

							break;

						}

						if (item.required == 1 && metaEmpty) {

							resp_error.append(item.label + ' is required<br>');
							metaEmpty = false;
							vFlag = true;

						}
						
						//saving meta with data
						
						metaData.push(data);
					});
	
			
	return vFlag;
	
	
}

/*
 * save file
 */

function saveFile() {

	var file_name = jQuery("#file-name").val();
	var file_detail = jQuery('textarea[name="nm-file-detail"]').val();

	var post_data = {
		'action' : 'repo_saveFile',
		'filename' : file_name,
		'filedetail' : file_detail,
		'parentID': nm_repository_vars.parent_id
	};

	jQuery.post(nm_repository_vars.ajaxurl, post_data, function(data) {
		//console.log(data);
		// clearing all input
		jQuery("#frm_upload").find('input:text, input:hidden, input:file, select').val('');
		jQuery("#frm_upload").find('input:checkbox').removeAttr('checked')
				.removeAttr('selected');
		jQuery("#upload-response").html('');

		if(nm_repository_vars.filesaved_message != ''){
			alert(nm_repository_vars.filesaved_message);
		}
		window.location.reload(true);
	});
}

/*
 * loading file detail
 */

function loadFileDetail(id) {

	jQuery("#meta-all-" + id).html(
			'<img src=' + nm_repository_vars.loading_image_url + '>');

	var post_data = {
		'action'		: 'repo_detail',
		'fid' 			: id,
		'currentuser'	: nm_repository_vars.current_user
	};

	jQuery.post(nm_repository_vars.ajaxurl, post_data, function(data) {
		// alert(data);
		// resetting all meta/detail of already loaded files
		resetFileDetail(id);

		// hiding file smart meta
		jQuery("#meta-smart-" + id).hide(200);

		// loading detail to its container
		jQuery("#meta-all-" + id).html(data);

		// applying some css to loaded table
		jQuery("#file-meta-container tr:odd").addClass("odd");

		jQuery("#file-meta-container-meta tr:odd").addClass("odd-meta");
		jQuery("#file-meta-container-meta tr:even").removeClass("add")
				.addClass("even-meta");
		
		/*
		 * hiding/showing file update/edit button
		 * based on settings
		 */
		
		if(settings.allowedit == "false"){
			
			jQuery("#nm-update-file-button").hide();
		}
	});
}

function resetFileDetail(fid) {

	if (nm_repository_vars.current_file_detail != '') {

		jQuery("#meta-smart-" + nm_repository_vars.current_file_detail)
				.show(200);
		jQuery("#meta-all-" + nm_repository_vars.current_file_detail).html('');
	}

	nm_repository_vars.current_file_detail = fid;

}

/*
 * updating file for sharing and filemeta
 */

function updateFile(fid) {

	jQuery("#update-response-here").html('<img src=' + nm_repository_vars.loading_image_url + '>');
	
	/*
	 * this existing meta id/meta template
	 * it is neccessary to keep save
	 */
	var meta_id = jQuery("input[name^='template-meta-id']").val();

	/*
	 * =========== sharing options block =============
	 */

	var sharing = jQuery('input:radio[name^="file-sharing-e"]:checked').val();

	var wp_roles = new Array();
	jQuery("input[name='roles-e[]']:checked").each(function() {
		wp_roles.push(jQuery(this).val());
	});

	var wp_users = new Array();
	jQuery("input[name='users-e[]']:checked").each(function() {
		wp_users.push(jQuery(this).val());
	});
	
	/* meta validation/edit section */
	error_resp = jQuery("#error-meta-edit");
	error_resp.html('');
	metaFlag = validateMeta(error_resp, true);
	if(metaFlag){
		error_resp.show();
		jQuery("#update-response-here").html('');
		
	}else{
	
	error_resp.hide();

		var post_data = {
			'action' 	: 'repo_update',
			'filemeta' 	: metaData,
			'sharing' 	: sharing,
			'roles' 	: wp_roles,
			'users' 	: wp_users,
			'fid' 		: fid,
			'metaid'	: meta_id
		};
	
		jQuery.post(nm_repository_vars.ajaxurl, post_data, function(data) {
			//alert(data);
			jQuery("#update-response-here").html(data).css('color','green');
	
		});
	}

}

function hideFileDetail(fid) {

	jQuery("#meta-smart-" + fid).show(200);
	jQuery("#meta-all-" + fid).html('');

}

function confirmFirst(fid, type) {
	var a = confirm('Are you sure to delete this file?');
	if (a) {
		
		deleteFile(fid, type);
	}
}

/*
 * deleting file
 */
function deleteFile(fid, type) {
	jQuery("#del-file-" + fid).attr("src", nm_repository_vars.loading_image_url);

	if(type == '1dir'){
		
		var post_data = {

				'action' : 'repo_deleteDir',
				'fid' : fid,
				'parentID'	: nm_repository_vars.parent_id
			};
	}else{
		var post_data = {

				'action' : 'repo_deleteFile',
				'fid' : fid,
				'parentID'	: nm_repository_vars.parent_id
			};	
		
	}
	

	jQuery.post(nm_repository_vars.ajaxurl, post_data, function(reponse) {
		//console.log(reponse);
		jQuery("#nmuploader-" + fid).fadeOut(1000);
	});
}

/* 
 * generating downloading link 
 * */
function generateDownloadLink(filename) {
	
	jQuery("#send-email-here").hide();
	jQuery("#download-link-here").html(
			'<img src=' + nm_repository_vars.loading_image_url + '>');
	
	var data = {
		'action' : 'repo_download',
		'filename' : filename
	};

	jQuery.post(nm_repository_vars.ajaxurl, data, function(response) {
		
		jQuery("#download-link-here").html(response);

	});
	
}

/*
 * just showing email area
 */

function showSendFile(){
	jQuery("#send-email-here").toggle(200);
	jQuery("#download-link-here").html('');
}
/*
 * sending file in email
 */
function sendDownloadLink(filename){
	
	jQuery("#email-sending-resp").html('<img src=' + nm_repository_vars.loading_image_url + '>');
	
	
	var data = {
		'action' : 'repo_sendlink',
		'filename' : filename,
		'to'		: jQuery('input[name^="file-email-to"]').val(),
		'subject'	: jQuery('input[name^="file-email-subject"]').val(),
		'message'	: jQuery('textarea[name^="file-email-message"]').val()
	};

	jQuery.post(nm_repository_vars.ajaxurl, data, function(response) {
		
		if(response.status == 'success'){			
			jQuery("#email-sending-resp").html(response.message).css("color", "green");
		}else{
			jQuery("#email-sending-resp").html(response.message).css("color", "red");
		}
	

	}, 'json');
}

function showContainers(area){
	
	jQuery("#container-"+area).slideToggle();
}


/* pagination */
function loadUploaderPageNext()
{
	jQuery("ul#nmuploader-container li.nm-c-p-"+current_page++).hide();
	jQuery("ul#nmuploader-container li.nm-c-p-"+current_page).show();
	setUploaderPagination();
}

function loadUploaderPagePrev()
{
	jQuery("ul#nmuploader-container li.nm-c-p-"+current_page--).hide();
	jQuery("ul#nmuploader-container li.nm-c-p-"+current_page).show();
	setUploaderPagination();
}



function setUploaderPagination()
{
	if(total_pages == 1)
	{
		jQuery("#prev-page a").hide();
		jQuery("#next-page a").hide();
	}
	else if(total_pages == current_page)
	{
		jQuery("#next-page a").hide();
		jQuery("#prev-page a").show();
	}
	else if(total_pages > current_page && current_page > 1)
	{
		jQuery("#prev-page a").show();
	}
	else
	{
		jQuery("#prev-page a").hide();
		jQuery("#next-page a").show();
	}	
	
	//setting page couner lable
	jQuery("#page-count").html(current_page+" of "+total_pages);
	
}


function getOption(key){
	
	var req_option = '';
	
	jQuery.each(nm_repository_vars.repo_settings, function(k, option){
		
		//console.log(key);
		
		if (k == key)
			req_option = option;		
	});
	
	//console.log(req_option);
	return req_option;
	
}

/* ====== Pagination ========== */