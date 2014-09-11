// visual editor buttons
(function() {  
    tinymce.create('tinymce.plugins.quote', {  	 
        init : function(ed, url) { 
		
			//one fourth column
			/*ed.addButton('maja_one_fourth', {  
				title : 'One fourth column',  
                image : url+'/../../images/one_fourth_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[one_fourth]' + ed.selection.getContent() + '[/one_fourth]');  
					ed.undoManager.add();             	
				}  
        	}); 		
		*/
			//one third column
			ed.addButton('maja_one_third', {  
				title : 'One third column',  
                image : url+'/../../images/one_third_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[one_third]' + ed.selection.getContent() + '[/one_third]');  
					ed.undoManager.add();             	
				}  
        	}); 
			
			//one half column
			ed.addButton('maja_one_half', {  
				title : 'One half column',  
                image : url+'/../../images/one_half_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[one_half]' + ed.selection.getContent() + '[/one_half]');
					ed.undoManager.add(); 					  
            	}  
        	});	
			
			//two thirds column
			ed.addButton('maja_two_thirds', {  
				title : 'Two thirds column',  
                image : url+'/../../images/two_third_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[two_thirds]' + ed.selection.getContent() + '[/two_thirds]');  
					ed.undoManager.add();             	
				}  
        	}); 
			
			//three fourths column
			/*ed.addButton('maja_three_fourths', {  
				title : 'Three fourths column',  
                image : url+'/../../images/three_fourths_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[three_fourths]' + ed.selection.getContent() + '[/three_fourths]');  
					ed.undoManager.add();             	
				}  
        	}); 			
				*/		
			//one column
			ed.addButton('maja_one', {  
				title : 'One column',  
                image : url+'/../../images/one_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[one]' + ed.selection.getContent() + '[/one]');
					ed.undoManager.add(); 
            	}  
        	});
			
			//bullet unorderd list
		/*	ed.addButton('maja_lists_bullet', {  
				title : 'Bullet list',  
                image : url+'/../../images/bullet_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[lists type="bullet"]' + ed.selection.getContent() + '[/lists]');
					ed.undoManager.add(); 
            	}  
        	});		
			*/
			//check unorderd list
			/*ed.addButton('maja_lists_check', {  
				title : 'Check list',  
                image : url+'/../../images/check_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[lists type="check"]' + ed.selection.getContent() + '[/lists]');
					ed.undoManager.add(); 
            	}  
        	});
			*/
			//arrow unorderd list
			/*ed.addButton('maja_lists_arrow', {  
				title : 'Arrow list',  
                image : url+'/../../images/arrow_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[lists type="arrow"]' + ed.selection.getContent() + '[/lists]');
					ed.undoManager.add(); 
            	}  
        	});					
			*/
			//button
			/*ed.addButton('maja_button', {  
				title : 'Add a button',  
                image : url+'/../../images/button_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[button link=""]' + ed.selection.getContent() + '[/button]');
					ed.undoManager.add(); 
            	}  
        	});								
		*/
			//clear
			/*ed.addButton('maja_clear', {  
				title : 'Clear floats',  
                image : url+'/../../images/clear_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[clear]');  
					ed.undoManager.add(); 
				}  
        	}); 
			*/
			//separator
			/*ed.addButton('maja_separator', {  
				title : 'Add line separator',  
                image : url+'/../../images/sep_btn.png',  
				onclick : function() {  
                    ed.selection.setContent('[separator]');  
					ed.undoManager.add(); 
				}  
        	}); */
											
        },  
        createControl : function(n, cm) {  
            return null;  
        },  
    });  
    tinymce.PluginManager.add('quote', tinymce.plugins.quote);
})();