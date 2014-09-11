// JavaScript Document
(function() {
	tinymce.create('tinymce.plugins.advanced_wp_columns', {
		init: function(ed, url){ 			
			var isEditMode = function(){
                if(jQuery(ed.selection.getNode()).parents('.csRow:first').length !== 0)
                {
                    return true;
                } else {
                    return false;
                }
            };
			
			ed.addButton('advanced_wp_columns', {
                title : 'Advanced WP Columns',
                onclick: function() {
					tb_show('Advanced WP Columns', '../wp-content/plugins/advanced-wp-columns/assets/js/plugins/views/columns.html?TB_iframe=1&width=960');
				}
			});
			
			//tinymce version checker
			switch(tinymce.majorVersion){
				case "3":
				{
					//WP 3.8 and older versions
					ed.onClick.add(function(ed) {
						if(isEditMode() === true)
						{
							tinyMCE.activeEditor.controlManager.setActive('advanced_wp_columns', true);
						}else{   
							tinyMCE.activeEditor.controlManager.setActive('advanced_wp_columns', false);
						}
					});
				};
				break;
				case "4":
				{
					//WP 3.9 and newer versions
					ed.on('click',function(){
						if(isEditMode() === true)
						{
							tinyMCE.activeEditor.controlManager.setActive('advanced_wp_columns', true);
						}else{   
							tinyMCE.activeEditor.controlManager.setActive('advanced_wp_columns', false);
						}
					});
				};
				break;
			}						
		},
        createControl: function(n, cm) {
			switch (n) {
                case 'advanced_wp_columns':			
                    var c = cm.createButton('advanced_wp_columns', {
                        title: 'Advanced WP Columns',
                        onclick: function() {
                            tb_show('Advanced WP Columns', '../wp-content/plugins/advanced-wp-columns/assets/js/plugins/views/columns.html?TB_iframe=1&width=960');
                        }
                    });
					
                    // Return the new advanced wp columns instance
                    return c;
            }
            return null;
        }
    });
    tinymce.PluginManager.add('advanced_wp_columns', tinymce.plugins.advanced_wp_columns);
})();