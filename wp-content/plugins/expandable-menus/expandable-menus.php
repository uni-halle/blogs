<?php
/*
Plugin Name: Expandable Menus
Plugin URI: http://playforward.net
Description: Allows you to expand and collapse theme menus in WordPress admin.
Author: Dustin Dempsey
Version: 2.1
Author URI: http://playforward.net
*/


	// out function
	function EM_admin_head(){
	
		// echo css and javascript
		$plus_path = includes_url('/images/admin-bar-sprite.png');
		
		echo '
			<style>
				.expand_hidden {
					display: none;
				}
				.minimizing .menu-item-handle,
				.minimized .menu-item-handle,
				.hovering .menu-item-handle {
					overflow: visible !important;
				}
				.ie .minimizing .menu-item-handle,
				.ie .minimized .menu-item-handle,
				.ie .hovering .menu-item-handle {
					border-right: 2px solid #42c038;
				}
				.minimized dt.menu-item-handle:after {
					content: attr(data-content);
					display: inline-block;
					position: absolute;
					top: 0px;
					left: 417px;
					background: #298cba;
					color: #fff;
					padding-left: 10px;
					padding-right: 10px;
					text-shadow: none;
					-webkit-border-radius: 3px;
					border-radius: 3px;
					cursor: s-resize !important;
					min-width: 113px;
					text-align: center;
				}
				.hovering dt.menu-item-handle:after {
					content: "double-click to minimize";
					display: inline-block;
					position: absolute;
					top: 0px;
					left: 417px;
					background: #298cba;
					color: #fff;
					padding-left: 10px;
					padding-right: 10px;
					text-shadow: none;
					-webkit-border-radius: 3px;
					border-radius: 3px;
					cursor: s-resize !important;
					min-width: 143px;
					text-align: center;
				}
			</style>
			<!-- Expandable Menu Code -->
			<script type="text/javascript">
			
				function expand_set( c_name, value, exdays ) {
					var exdate = new Date();
					exdate.setDate( exdate.getDate() + exdays );
					var c_value = escape( value ) + ((exdays==null) ? "" : "; expires = "+exdate.toUTCString());
					document.cookie = c_name + "=" + c_value;
				}
				
				function expand_get( c_name ) {
					if ( document.cookie ) {
						var i,x,y,ARRcookies = document.cookie.split(";");
						for ( i=0; i<ARRcookies.length; i++ ) {
							x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
							y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
							x=x.replace(/^\s+|\s+$/g,"");
							if ( x == c_name ) {
								return unescape( y );
							}
						}
					} else {
						return false;
					}
				}
				
				jQuery(document).ready(function(){
				
					var cookie = expand_get( "expandable_menus" );
					
					// our defaults
					var expand_depth;
					var search_for 			= "menu-item-depth-";
					var expanding_status	= true;
					var expand_minimizing 	= true;
					var depth_to_block 		= 9999;
					
					
					// process element
					var process_expand_element = function( element, depth ) {
					
						var $this 		= element;
						var $next 		= $this.next();
						var $next_class	= $next.attr( "class" );
						var depth_next 	= 0;
						
						// next element depth
						if ( $next_class ) {
							var classes = $next_class.split(" ");
							jQuery.each( classes, function(index, value) {
								if ( value.substr( 0, 16 ) == search_for) {
									depth_next = parseInt( value.split( search_for )[1] );
									return depth_next;
								}
							});
						}
						
						// minimizing?
						if ( expand_minimizing ) {
						
							// hide everything, easy
							$this.addClass( "expand_hidden" );
						
						} else {
						
							// expanding
							
							// if the current depth is less than the blocked depth
							if ( depth < depth_to_block ) {
							
								// if element is minimized
								if ( $this.hasClass( "minimized" ) ) {
								
									// set new depth to block as it is already hidden
									depth_to_block = depth_next;
									$this.removeClass( "expand_hidden" );
								
								} else {
								
									// show element
									$this.removeClass( "expand_hidden" );
								}
							}
						}
						
						// if the next element is deeper than the depth we started at
						if ( depth_next > expand_depth ) {
						
							// process next element
							process_expand_element( $next, depth_next );
						
						} else {
						
							var to_cookie = "";
							
							// done processing, set minimizing to minimized
							$element = jQuery( "#menu-to-edit li.minimizing" );
							if ( $element ) {
								$element.addClass( "minimized" );
								$element.removeClass( "minimizing" );
							}
							
							// reset blocking depth
							depth_to_block = 9999;
							
							// set count
							jQuery("#menu-to-edit li.minimized").each(function(){
								var $this 	= jQuery(this);
								var id		= $this.attr( "id" );
								var count 	= $this.nextUntil(":not(.expand_hidden)").length;
								var term 	= "item";
								if ( count > 1 ) {
									var term = "items";
								}
								$this.find(".menu-item-handle").attr( "data-content", "minimized: "+count+" "+term+"" );
								
								// assemle cookie
								var item = id+"|";
								to_cookie = to_cookie+item;
								
							});
							
							// set cookie
							to_cookie = to_cookie.slice(0,to_cookie.length-1);
							expand_set( "expandable_menus", to_cookie, 365 );
						}
					}
					
					// on double click
					jQuery("#menu-to-edit li").hover( function(){
						
						var $this 		= jQuery(this);
						
						if ( !$this.hasClass("minimized") ) {
						
							var $next 		= $this.next();
							var depth 		= 0;
							var depth_next 	= 0;
							
							// get current depth
							var classes = $this.attr( "class" ).split(" ");
							jQuery.each( classes, function(index, value) {
								if ( value.substr( 0, 16 ) == search_for) {
									depth = parseInt( value.split( search_for )[1] );
									return depth;
								}
							});
							
							
							// get next depth
							var $next_class	= $next.attr( "class" );
							if ( $next_class ) {
								var classes = $next_class.split(" ");
								jQuery.each( classes, function(index, value) {
									if ( value.substr( 0, 16 ) == search_for) {
										depth_next = parseInt( value.split( search_for )[1] );
										return depth_next;
									}
								});
							}
							
							if ( depth_next > depth ) {
								jQuery(this).addClass("hovering");
							}
						}
						
					},function(){
						jQuery(this).removeClass("hovering");
					});
					
					// on double click
					jQuery("#menu-to-edit li").on( "dblclick", function(){
					
						var $this 		= jQuery(this);
						var $next 		= $this.next();
						var depth 		= 0;
						var depth_next 	= 0;
						
						// get current depth
						var classes = $this.attr( "class" ).split(" ");
						jQuery.each( classes, function(index, value) {
							if ( value.substr( 0, 16 ) == search_for) {
								depth = parseInt( value.split( search_for )[1] );
								return depth;
							}
						});
						
						// get next depth
						var $next_class	= $next.attr( "class" );
						if ( $next_class ) {
							var classes = $next_class.split(" ");
							jQuery.each( classes, function(index, value) {
								if ( value.substr( 0, 16 ) == search_for) {
									depth_next = parseInt( value.split( search_for )[1] );
									return depth_next;
								}
							});
						}
						
						// set depth we are working with
						expand_depth = depth;
						
						if ( $this.hasClass( "minimized" ) ) {
						
							// expanding
							expand_minimizing = false;
							$this.removeClass( "minimized" );
						
						} else {
						
							// minimizing
							expand_minimizing = true;
							$this.addClass( "minimizing" );
						}
						
						// if next element is a child
						if ( depth_next > depth ) {
						
							// process next element
							process_expand_element( $next, depth_next );
						
						} else {
						
							// last element in row
							$this.removeClass( "minimizing" );
							expand_minimizing = true;
						}
					});
					
					
					var process_cookie = function() {
					
						if ( cookies ) {
						
							// if we still have items in the array to process
							if ( cookies.length > 0 ) {
							
								// trigger double click on item
								var current_item = cookies[0];
								jQuery("#"+current_item).trigger( "dblclick" );
								
								// remove item from array
								cookies.splice( 0, 1 );
								
								// process again
								process_cookie();
							}
						}
					}
					
					if ( cookie ) {
					
						// get minimized items and revrse array to work backwards
						var cookies = cookie.split("|");
						
						if ( cookies ) {
						
							cookies = cookies.reverse();
							
							// process cookie items
							process_cookie();
						}
					}
				});
			</script>
			<!-- END expanadable menu code -->
			';
	}
	
	// add action to admin head
	add_action( 'admin_head', 'EM_admin_head' );

?>