jQuery(document).ready(function($) {
 
 $(window).load(function() {	
	$('.player-container').css('visibility', 'visible').show();
 

	 /*Text Version*/
	 
	  	$("div[id^=haiku-text-player]").each(function() {
			var num = this.id.match(/haiku-text-player(\d+)/)[1];
			var ctrlId = "div#text-player-container" + num + " ul#player-buttons" + num;
			var audioFile = $(ctrlId + " li.play a").attr("href");
			 $(this).jPlayer({
				ready: function () {
					this.element.jPlayer("setFile", audioFile);
				},
				customCssIds: true,
				swfPath: jplayerswf,
				errorAlerts:true,
				nativeSuport:false
			})
			$(ctrlId + " li.play").click(function() {
				stopAllPlayers();
				$("div#haiku-text-player" + num).jPlayer("play");
				showStopBtn();
				return false;
			});
			$(ctrlId + " li.stop").click(function() {
				$("div#haiku-text-player" + num).jPlayer("stop");
				showPlayBtn();
				return false;
			});
			function stopAllPlayers() {
				$("div.haiku-text-player").jPlayer("stop");
				$("div.text-player-container ul.player-buttons li.stop").hide();					$("div.text-player-container ul.player-buttons li.play").show();
			}
			function showStopBtn() {
				$(ctrlId + " li.play").fadeOut(100, function(){
					$(ctrlId + " li.stop").css("display","inline").fadeIn(100);});
			}
			function showPlayBtn() {
				$(ctrlId + " li.stop").fadeOut(100, function(){
					$(ctrlId + " li.play").fadeIn(10);});
			}

		});

	/*Graphical version*/

		$("div[id^=haiku-player]").each(function() {
		
			var num = this.id.match(/haiku-player(\d+)/)[1];
			var buttonId = "div#haiku-button" + num;
			var jpPlayTime = $("#jplayer_play_time" + num);
			var jpTotalTime = $("#jplayer_total_time" + num);
			var audioFile = $(buttonId + " a").attr("href");
			currentPlayer = 0;
			
			$(this).jPlayer({
				ready: function () {
					this.element.jPlayer("setFile", audioFile);
				},
				customCssIds: true,
				swfPath: jplayerswf,
				errorAlerts:true,
				nativeSuport:false
			})
			
			.jPlayer("onSoundComplete", function() {
			stopPreviousPlayer();
			})
			
			.jPlayer("onProgressChange", function(lp,ppr,ppa,pt,tt) {
	 		var lpInt = parseInt(lp);
	 		var ppaInt = parseInt(ppa);
	 		global_lp = lpInt;
	 		jpPlayTime.text($.jPlayer.convertTime(pt) + " / ");
			jpTotalTime.text($.jPlayer.convertTime(tt));

	 		$('#sliderPlayback' + num).slider('option', 'value', ppaInt);
			});
	 
			// Slider
			$('#sliderPlayback' + num).slider({
				max: 100,
				range: 'min',
				animate: true,
				slide: function(event, ui) {
					$('#haiku-player' + num).jPlayer("playHead", ui.value*(100.0/global_lp));
					$(buttonId + " li.play").hide();
					$(buttonId + " li.pause").fadeIn(10);
				}
			});
		
			$(buttonId + " a.play").click(function() {
				stopPreviousPlayer();
				$(buttonId + " a img.listen").hide();
				$(buttonId + " li.play").hide();
				$("ul#controls" + num).fadeIn('fast');
				$("ul#info" + num).fadeIn('fast');
				$("div#haiku-player" + num).jPlayer("play");
				currentPlayer = num;
				return false;
			});	
		
			$(buttonId + " li.pause").click(function() {
				$("div#haiku-player" + num).jPlayer("pause");
				showPlayBtn();
				return false;
			});
			
			$(buttonId + " li.play").click(function() {
				$("div#haiku-player" + num).jPlayer("play");
				showPauseBtn();
				return false;
			});
			
			$(buttonId + " li.stop").click(function() {
				$("div#haiku-player" + num).jPlayer("stop");
				showDefault();
				return false;
			});
			
			function stopPreviousPlayer() {
				$("div#haiku-player" + currentPlayer).jPlayer("stop");
				showPauseBtn();
				$("ul#controls" + currentPlayer).hide();	
				$("ul#info" + currentPlayer).hide();	
				$("div#haiku-button" + currentPlayer + " a img.listen").fadeIn('fast');
			}
			
			function showPlayBtn() {
			$(buttonId + " li.pause").hide();
			$(buttonId + " li.play").fadeIn(10);
			}
			
			function showPauseBtn() {
			$(buttonId + " li.play").hide();
			$(buttonId + " li.pause").fadeIn(10);
			}
				
			function showDefault() {
				$("ul#controls" + num).hide();
				$("ul#info" + num).hide();
				showPauseBtn();
				$(buttonId + " a img.listen").fadeIn(10);
			}
			
		});
	});	
});
 