/**
 * Convert a single file-input element into a 'multiple' input list
 *
 * Usage:
 *
 *   1. Create a file input element (no name)
 *      eg. <input type="file" id="first_file_element">
 *
 *   2. Create a DIV for the output to be written to
 *      eg. <div id="files_list"></div>
 *
 *   3. Instantiate a MultiSelector object, passing in the DIV and an (optional) maximum number of files
 *      eg. var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 3 );
 *
 *   4. Add the first element
 *      eg. multi_selector.addElement( document.getElementById( 'first_file_element' ) );
 *
 *   5. That's it.
 *
 *   You might (will) want to play around with the addListRow() method to make the output prettier.
 *
 *   You might also want to change the line 
 *       element.name = 'file_' + this.count;
 *   ...to a naming convention that makes more sense to you.
 * 
 * Licence:
 *   Use this however/wherever you like, just don't blame me if it breaks anything.
 *
 * Credit:
 *   If you're nice, you'll leave this bit:
 *  
 *   Class by Stickman -- http://www.the-stickman.com
 *      with thanks to:
 *      [for Safari fixes]
 *         Luis Torrefranca -- http://www.law.pitt.edu
 *         and
 *         Shawn Parker & John Pennypacker -- http://www.fuzzycoconut.com
 *      [for duplicate name bug]
 *         'neal'
 */
function MultiSelector( list_target, max ){
	
	// Where to write the list
	this.list_target = list_target;
	// How many elements?
	this.count = 0;
	// How many elements?
	this.id = 0;
	// Is there a maximum?
	if( max ){this.max = max;}
	else {this.max = -1;};
	
	/**
	 * Add a new file input element
	 */
	this.addElement = function( element ){
	
		// Make sure it's a file input element
		if( element.tagName == 'INPUT' && element.type == 'file' ){	
			
			// Element name -- what number am I?
			element.name = 'file[]'; //_' + this.id++;
			element.className = "text";
			element.size = "35";
			
			// Add reference to this object
			element.multi_selector = this;

			// What to do when a file is selected
			element.onchange = function(){

				/* Set the Types Allowed */
				var types_allowed = ".gif, .jpg, .png";
				
				/* Get the form valu and strign length*/
				var form_value = element.value;
				var str_end = form_value.length;
				
				/* Find out the filetype of the selected file */
				var str_start = ((str_end/1) - 4);
				var file_type = form_value.substring(str_start, str_end).toLowerCase();;
				
				/* Replace Spaces for correct splitting */
				var arrFiles = types_allowed.replace(/ /g, "");
				arrFiles = arrFiles.split(",");
				
				var file_correct = 0;
				var i_max = arrFiles.length;
				for(var i = 0; i < i_max; i++)
					{
						if(file_type == arrFiles[i])
							{var file_correct = 1; break;}
					}
				if(file_correct == 0)
					{alert("Only the following files are allowed in this file input: "+types_allowed);}
				else
					{
						// New file input
						var new_element = document.createElement( 'input' );
						new_element.type = 'file';
		
						// Add new element
						this.parentNode.insertBefore( new_element, this );
		
						// Apply 'update' to element
						this.multi_selector.addElement( new_element );
		
						// Update list
						this.multi_selector.addListRow( this );
		
						// Hide this: we can't use display:none because Safari doesn't like it
						this.style.position = 'absolute';
						this.style.left = '-1000px';
					}

			};
			// If we've reached maximum number, disable input element
			if( this.max != -1 && this.count >= this.max ){
				element.disabled = true;
				alert("You have reached the maximum of "+ this.max +" uploads at a time.");
			};

			// File element counter
			this.count++;
			// Most recent element
			this.current_element = element;
			
		} else {
			// This can only be applied to file input elements!
			alert( 'Error: not a file input element' );
		};

	};

	/**************************************/
	/* Add a new row to the list of files */
    /**************************************/
	
	this.addListRow = function( element ){
		var file_List = document.getElementById("files_list").innerHTML;
		if(file_List == "You have not selected any images")
			{document.getElementById("files_list").innerHTML = "";}
		
		// Row div
		var new_row = document.createElement('div');
		new_row.style.padding = "6px;"; 
		new_row.className = "add_image";
		
		var new_row_button = document.createElement( 'a' );
		new_row_button.href = "javascript:;";
		new_row_button.innerHTML += " Remove"
		new_row_button.style.top = "-11px"
		
		// References
		new_row.element = element;

		// Delete function
		new_row_button.onclick= function(){

			// Remove element from form
			this.parentNode.element.parentNode.removeChild( this.parentNode.element );
			// Remove this row from the list
			this.parentNode.parentNode.removeChild( this.parentNode );
			// Decrement counter
			this.parentNode.element.multi_selector.count--;
			// Re-enable input element (if it's disabled)
			this.parentNode.element.multi_selector.current_element.disabled = false;
			// Appease Safari
			//    without it Safari wants to reload the browser window
			//    which nixes your already queued uploads
			return false;
		};

		// Set row value 
		// Loop through the full file path and remove all the text up to the actual file name.
		
		element_HTML = element.value.toString();
		var i_len = element_HTML.length;
		
		for(var i = 0; i < i_len; i++)
			{
				var element_HTML_findslash = element_HTML.toString().indexOf('\\');
				
				if(element_HTML_findslash == -1)
					{break}
				else
					{element_HTML = element_HTML.substring((element_HTML_findslash+1), i_len);}
			}
			
		new_row.innerHTML = element_HTML+" "; //element.value;

		// Add button
		new_row.appendChild( new_row_button );
				
		// Add it to the list
		this.list_target.appendChild( new_row );
		
	};

};