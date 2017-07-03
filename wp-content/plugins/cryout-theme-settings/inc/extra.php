	<div id="latest-themes" class="postbox">
		<h3 class="hndle"> Our latest free themes </h3>
		<div id="slider">
			<a href="#" class="control_next">></a>
			<a href="#" class="control_prev"><</a>
			<ul>
				<li><a href="https://www.cryoutcreations.eu/wordpress-themes/anima" target="_blank"><span>Anima WordPress Theme</span><img src="<?php echo $url ?>/anima.jpg"></a></li>
				<li><a href="https://www.cryoutcreations.eu/wordpress-themes/septera" target="_blank"><span>Septera WordPress Theme</span><img src="<?php echo $url ?>/septera.jpg"></a></li>
				<li><a href="https://www.cryoutcreations.eu/wordpress-themes/verbosa" target="_blank"><span>Verbosa WordPress Theme</span><img src="<?php echo $url ?>/verbosa.jpg"></a></li>
				<li><a href="https://www.cryoutcreations.eu/wordpress-themes/fluida" target="_blank"><span>Fluida WordPress Theme</span><img src="<?php echo $url ?>/fluida.jpg"></a></li>
			</ul>
		</div>
	</div>
	
	<div id="priority-support" class="postbox priority-support">
		<div title="Click to toggle" class="handlediv"><br /></div>
		<h3 class="hndle"> Need help? </h3>
		<div class="inside">
			<a href="https://www.cryoutcreations.eu/pricing" target="_blank"><img src="<?php echo $url ?>/priority-support.jpg' ?>"></a>
		</div><!--inside-->
	</div>
	
	<style type="text/css">
		.priority-support.postbox .inside {
			margin: 0;
			padding: 5px 0 0 0;
		}

		#slider {
			position: relative;
			overflow: hidden;
			margin: 5px auto 0;
		}

		#slider ul {
			position: relative;
			margin: 0;
			padding: 0;
			height: 375px;
			list-style: none;
			overflow: hidden;
		}

		#slider ul li {
			position: relative;
			display: block;
			float: left;
			margin: 0;
			padding: 0;
			width: 500px;
			height: 375px;
			background: #ccc;
			text-align: center;
		}

		#slider a span {
			display: inline-block;
			position: absolute;
			left: 0;
			right: 0;
			bottom: 0;
			margin: auto;
			opacity: 0;
			background: rgba(0,0,0,.5);
			max-width: 200px;
			height: 40px;
			line-height: 40px;
			font-size: 1.2em;
			color: #FFF;
			-webkit-transition: .3s ease-out all;
			transition: .3s ease-out all;
		}

		#slider:hover a span {
			opacity: 1;
		}

		a.control_prev,
		a.control_next {
			position: absolute;
			top: 50%;
			-webkit-transform:translateY(-50%);
			transform:translateY(-50%);
			z-index: 999;
			display: block;
			padding: 4% 3%;
			width: auto;
			height: auto;
			background: #2a2a2a;
			color: #fff;
			text-decoration: none;
			font-weight: 600;
			font-size: 18px;
			opacity: 0.3;
			cursor: pointer;
		}

		a.control_prev:hover,
		a.control_next:hover {
			opacity: .8;
			-webkit-transition: all 0.2s ease;
			transition: all 0.2s ease;
		}

		a.control_prev {
			border-radius: 0 2px 2px 0;
		}

		a.control_next {
			right: 0;
			border-radius: 2px 0 0 2px;
		}

		.no-js #slider img {
			width: 100%;
		}

		.no-js #slider ul li {
			width: 50%;
			height: 50%;
		}

		.no-js .control_next,
		.no-js .control_prev {
			display: none;
		}

		.no-js #slider ul li:hover a span {
			background: rgba(0,0,0,.8);
		}	
	</style>
	
	<script type="text/javascript">
		jQuery(document).ready(function ($) {
			interval = 25000;
			// autoplay
			setInterval(function () {
				moveRight();
			}, interval);


			var slideCount = $('#slider ul li').length;
			var slideWidth = $('#slider ul li').width();
			var slideHeight = $('#slider ul li').height();
			var sliderUlWidth = slideCount * slideWidth;

			$('#slider').css({ width: slideWidth, height: slideHeight });

			$('#slider ul').css({ width: sliderUlWidth, marginLeft: - slideWidth });

			$('#slider ul li:last-child').prependTo('#slider ul');

			function moveLeft() {
				$('#slider ul').animate({
					left: + slideWidth
				}, 500, function () {
					$('#slider ul li:last-child').prependTo('#slider ul');
					$('#slider ul').css('left', '');
				});
			};

			function moveRight() {
				$('#slider ul').animate({
					left: - slideWidth
				}, 500, function () {
					$('#slider ul li:first-child').appendTo('#slider ul');
					$('#slider ul').css('left', '');
				});
			};

			$('a.control_prev').click(function ( e ) {
				e.preventDefault();
				moveLeft();
			});

			$('a.control_next').click(function ( e ) {
				e.preventDefault();
				moveRight();
			});

		});	
	</script>