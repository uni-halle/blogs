
(function($) {

	//jQuery UI 1.5.2 doesn't support "option" as  getter/setter, so we have to apply a hotfix instead 
	var needs_jquery_hotfix = (($.ui.version === undefined) || (parseInt($.ui.version.split('.')[1]) < 6));
	if (needs_jquery_hotfix) {
		$.extend($.ui.draggable.prototype, {
			option: function(key, value) {
				if (value === undefined) {
					return this.options[key];
				}
				if (key == 'containment') {
					this.containment = value;
				}
				else{
					this.options[key] = value;
				}
			}
		});
		$.extend($.ui.draggable, {
			getter : 'option'
		});
	}

	//extend jQuery with pageColumnist object
	$.fn.extend({
		pageColumnist: function() {
			var self = $(this);
			
			self.box = function(elem) {
				var box = $(elem).offset();
				box.width = $(elem).width();
				box.height = $(elem).height();
				return box;
			}

			self.update_info = function(i, elem) {
				var b = self.box(self.columns[i]);
				var o = self.box(self);
				$(elem).css({top: b.top+'px', left: b.left+3+'px', width: '50px'});
				//changed to extended data info
				$(elem).find('b').html(Math.round(parseFloat($(self.columns[i]).attr('data')) * 100.0) / 100.0);
				$(elem).find('span').html(b.width);
			}
			
			self.update_spacer = function(i, elem) {
				var b1 = self.box(self.columns[i]);
				var b2 = self.box(self.columns[i+1]);
				$(elem).css({top: b1.top+'px', left: b1.left+b1.width+'px', width: b2.left - (b1.left+b1.width)+'px', height: b1.height+'px' });
			}
			
			self.recalc_containments = function(i, elem) {
				var b1 = self.box(self.columns[i]);
				var b2 = self.box(self.columns[i+1]);
				var body = self.box($('body'));
				self.containments[i] = [b1.left+50, 0, b2.left+b2.width-50-self.box(elem).width, body.height]
			}
			
			self.adjust_columns = function(spacer) {
				var idx = $(spacer).draggable('option', 'colidx');
				var perc = $(spacer).draggable('option', 'initial_perc');
				var s = self.box(spacer);
				var b1 = self.box(self.columns[idx]);
				var b2 = self.box(self.columns[idx+1]);
				var main = self.box(self);
				//reset affected columns
				var w = '0px';
				$(self.columns[idx]).css({width: w});
				$(self.columns[idx+1]).css({width: w});
				//calculate new width
				w = Math.round((s.left - b1.left) * 100.0) / main.width;
				$(self.columns[idx]).css({ width: w + '%'}).attr('data', w);
				b1 = self.box(self.columns[idx]);
				w = perc - w;
				$(self.columns[idx+1]).css({ width: w + '%'}).attr('data', w);
				self.make_equal_height();
			}
			
			self.make_equal_height = function() {
				var h = 0;
				$(self.columns).each(function(i, elem) { h = Math.max(h, $(elem).height()); });
				$('#cscp_ghost').css({'height' : h+'px'});
				$('.cspc-assist-spacer').css({'height' : h+'px'});
			}
			
			self.columns = $('.cspc-column', self);
			self.containments = [];
			
			$('body').prepend('<div id="cscp_ghost" class="cspc-assist-col"></div>');
			$('#cscp_ghost').css(self.box(self)).hide();
			for (var i=0; i<self.columns.length; i++) {
				$('body').append('<div class="cspc_assist_col_info"><b></b>&nbsp;%<br/>(<span></span>&nbsp;px)</div>');
				if (i > 0) {
					$('body').append('<div class="cspc-assist-spacer"></div>');
					self.containments.push([0,0,0,0]);
				}
			}
			$('.cspc_assist_col_info, .cspc-assist-spacer').css({position: 'absolute', 'z-index': 1001}).hide();
			self.spacer = $('.cspc-assist-spacer');
			$('.cspc-assist-spacer').each(self.recalc_containments);
			
			
			$('div.cspc_assist_col_info').css({ position: 'absolute', 'z-index': 1000, overflow:'hidden', color: '#000', 'background-color': '#fff', border: 'solid 1px black', padding: '3px', 'margin-top': '3px'}).each(self.update_info);
			$('div.cspc-assist-spacer').each(self.update_spacer).each(function(i,e) {
				var b = self.box(self);
				var x = needs_jquery_hotfix ? {} : { containment : self.containments[i] };
				//TODO: containment is not always valid, to be changed in later versions
				$(e).draggable($.extend(x,{
					axis: 'x', 
					colidx: i,
					/* containment: [b.left+20, b.top, b.left+b.width-20, b.top+b.height],  //didn't work for jquery.ui < 1.7.0, subject of later workarrounds */
					start: function(event, ui){
						try{
							var idx = $(event.target).draggable('option', 'colidx');
							var b1 = self.box(self.columns[idx]);
							var b2 = self.box(self.columns[idx+1]);
							$(self.spacer).each(function(i, elem) { 
								if(i!=idx) 
									$(elem).draggable('disable').css('z-index', 999); 
								else
									$(elem).addClass('cspc-assist-spacer-active');
							});
							if(needs_jquery_hotfix) $(event.target).draggable('option', 'containment', self.containments[idx]);
							$(event.target).draggable('option', 'initial_perc', parseFloat($(self.columns[idx]).attr('data')) + parseFloat($(self.columns[idx+1]).attr('data')));
							//$(event.target).draggable('option', 'initial_perc', parseFloat($(self.columns[idx]).css('width')) + parseFloat($(self.columns[idx+1]).css('width')));
						}
						catch(e) {
						}
					},
					drag: function(event, ui) {
						try{
							self.adjust_columns(event.target);
							$('div.cspc_assist_col_info').each(self.update_info);
						}catch(e) {
						}
					},
					stop: function(event, ui){
						$(self.spacer).each(function(j, elem) { $(elem).draggable('enable').css('z-index', 1001).removeClass('cspc-assist-spacer-active'); });
						try{
							self.adjust_columns(event.target);
							$('div.cspc_assist_col_info').each(self.update_info);
						}catch(e) {
						}
						$(self.spacer).each(self.recalc_containments);
					}
				}));
			});
		
			//keep tracking the resize of window
			$(window).resize(function() {
				$('#cscp_ghost').css(self.box(self))
				$('div.cspc_assist_col_info').each(self.update_info);
				$('div.cspc-assist-spacer').each(self.update_spacer);
				self.make_equal_height();
			});

			$('#cspc-col-spacing').change(function() {
				if (self.columns.length == 0) return;
				var space = parseFloat($(this).val());
				var base = 100.0;
				var perc = ((base - (self.columns.length - 1) * space ) / self.columns.length);
				if ($.browser.msie) {
					$(self.columns).each(function(i, elem) {
						$(elem).css({ width: '0.001%'});
					});
					space = space * 0.66 ; //because IE is unable to calculate the sums correctly, we have to trick it little bit
				}
				for (var i=0; i<self.columns.length; i++) {
					$(self.columns[i]).css({ width: perc+'%'}).attr('data', perc);
					if (i != 0) $(self.columns[i]).css({ marginLeft: space+'%'});
				}
				$(window).trigger('resize');
				return false;
			});
			
			self.make_equal_height();
			return $(this);
		}
	});

	$(document).ready(function(){
		//show always firefox scroll bars 
		$('body').css({'overflow' : 'scroll' });
		
		var wpadmbar = $('#wpadminbar').height();
		var assistbar = {
			show: (0 + wpadmbar),
			hide: (-36 + wpadmbar)
		};
		
		//assistance toolbar toggle
		$('#cspc-assist-bar-expander').click(function(){
			$('#cspc-assist-bar').animate({ top: (parseInt($('#cspc-assist-bar').css('top')) == assistbar.show ? assistbar.hide+'px' : assistbar.show+'px') });
		});
		$('#cspc-assist-bar').css({ top: assistbar.hide+'px'});

		//assistance toolbar spinner init
		$('#cspc-col-spacing').spin({max:10,min:0,imageBasePath: cspc_page_columnist_l10n.imageBasePath, interval: 0.5 });

		//columnizer setup
		$('#cspc-content').pageColumnist();
		
		//columnized area ghosting toggle
		$('#cspc-columns-sizing').click(function() {
			var checked = $(this).attr('checked');
			$('#cscp_ghost').toggle(checked);
			$('.cspc_assist_col_info, .cspc-assist-spacer').toggle(checked);
		});

		//saving all changes and reload
		$('#cspc-save-changes').click(function() {
			$(this).blur();
			var cols = [];
			$('#cspc-content .cspc-column').each(function(i, elem) {
				cols.push(Math.round(parseFloat($(elem).attr('data'))*100)/100.0);
				//cols.push(Math.round(parseFloat($(elem).css('width'))*100)/100.0);
			});
			params = {
				type: 'POST', 
				url: cspc_page_columnist_l10n.adminUrl+'admin-ajax.php',
				data: {
					action: 'cspc_save_changes',
					page_id: cspc_page_columnist_l10n.pageId,
					spacing: $('#cspc-col-spacing').val(),
					distribution: cols.join('|'),
					default_spacing: $('#cspc-default-spacing').attr('checked')
				},
				success: function(data, textStatus) {
					window.setTimeout("location.reload(true)", 500);
				},
				error: function(xhr, textStatus, error) {
					alert(xhr.responseText);
				}
			};
			$.ajax(params);
		});
		
	});	

})(jQuery);
