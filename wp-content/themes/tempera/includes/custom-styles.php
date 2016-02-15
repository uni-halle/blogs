<?php
////////// MASTER CUSTOM STYLE FUNCTION //////////

function tempera_body_classes($classes) {
	$temperas = tempera_get_theme_options();
	$classes[] = $temperas['tempera_image_style'];
	$classes[] = $temperas['tempera_caption'];

	if ($temperas['tempera_magazinelayout'] == "Enable" || (is_front_page() && $temperas['tempera_frontpage'] == "Enable" && $temperas['tempera_frontpostsperrow'] == '2') ) { $classes[] = 'magazine-layout'; }
	if ( is_front_page() && $temperas['tempera_frontpage'] == "Enable" && (get_option('show_on_front') == 'posts') ) {
		$classes[] = 'presentation-page';
		$classes[] = 'coldisplay'.$temperas['tempera_coldisplay']; 
	}

	switch ($temperas['tempera_menualign']):
		case "center": 
			$classes[] = 'tempera-menu-center';
			break;
		case "right": 
			$classes[] = 'tempera-menu-right';
			break;
		case "rightmulti": 
			$classes[] = 'tempera-menu-rightmulti';
			break;
		default: 
			$classes[] = 'tempera-menu-left';
			break;
	  endswitch;	
	
	return $classes;
}
add_filter('body_class','tempera_body_classes');

function tempera_custom_styles() {
	$temperas= tempera_get_theme_options();
	foreach ($temperas as $key => $value) { ${"$key"} = $value ; }
	$totalwidth= $tempera_sidewidth+$tempera_sidebar;
	$contentSize = $tempera_sidewidth;
	$sidebarSize= $tempera_sidebar;
	ob_start();

?>
<style type="text/css">
<?php
////////// LAYOUT DIMENSIONS. //////////
?>
#header, #main, #topbar-inner { <?php echo (($tempera_mobile == 'Enable') ? 'max-' : '');?>width: <?php echo ($totalwidth); ?>px; }
<?php
////////// COLUMNS //////////

$colPadding = 30;
$contentSize = $contentSize - 60;

?>
#container.one-column { }
#container.two-columns-right #secondary { width:<?php echo $sidebarSize; ?>px; float:right; }
#container.two-columns-right #content { width:<?php echo $contentSize-$colPadding; ?>px; float:left; } /*fallback*/
#container.two-columns-right #content { width:calc(100% - <?php echo $sidebarSize+$colPadding; ?>px); float:left; }
#container.two-columns-left #primary { width:<?php echo $sidebarSize; ?>px; float:left; }
#container.two-columns-left #content { width:<?php echo $contentSize-$colPadding; ?>px; float:right; } /*fallback*/
#container.two-columns-left #content { 	width:-moz-calc(100% - <?php echo $sidebarSize+$colPadding; ?>px); float:right; 
										width:-webkit-calc(100% - <?php echo $sidebarSize+$colPadding; ?>px); 
										width:calc(100% - <?php echo $sidebarSize+$colPadding; ?>px); }

#container.three-columns-right .sidey { width:<?php echo $sidebarSize/2; ?>px; float:left; }
#container.three-columns-right #primary { margin-left:<?php echo $colPadding; ?>px; margin-right:<?php echo $colPadding; ?>px; }
#container.three-columns-right #content { width:<?php echo $contentSize-$colPadding*2; ?>px; float:left; } /*fallback*/
#container.three-columns-right #content { 	width:-moz-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); float:left;
											width:-webkit-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px);
											width:calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px);}
											
#container.three-columns-left .sidey { width:<?php echo $sidebarSize/2; ?>px; float:left; }
#container.three-columns-left #secondary {margin-left:<?php echo $colPadding; ?>px; margin-right:<?php echo $colPadding; ?>px; }
#container.three-columns-left #content { width:<?php echo $contentSize-$colPadding*2; ?>px; float:right;} /*fallback*/
#container.three-columns-left #content { width:-moz-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); float:right;
										 width:-webkit-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px);
										 width:calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); }

#container.three-columns-sided .sidey { width:<?php echo $sidebarSize/2; ?>px; float:left; }
#container.three-columns-sided #secondary { float:right; }
#container.three-columns-sided #content { width:<?php echo $contentSize-$colPadding*2; ?>px; float:right; /*fallback*/
										  width:-moz-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); float:right;
										  width:-webkit-calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); float:right;
										  width:calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); float:right;
		                                  margin: 0 <?php echo ($sidebarSize/2)+$colPadding;?>px 0 <?php echo -($contentSize+$sidebarSize); ?>px; }
<?php
////////// FONTS //////////
$tempera_googlefont = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_googlefont));
$tempera_googlefonttitle = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_googlefonttitle));
$tempera_googlefontside = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_googlefontside));
$tempera_headingsgooglefont = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_headingsgooglefont));
$tempera_sitetitlegooglefont = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_sitetitlegooglefont));
$tempera_menugooglefont = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_menugooglefont));
?>
body { font-family: <?php echo ((!$tempera_googlefont)?$tempera_fontfamily:"\"$tempera_googlefont\""); ?>; }
#content h1.entry-title a, #content h2.entry-title a, #content h1.entry-title , #content h2.entry-title {
		font-family: <?php echo ((!$tempera_googlefonttitle)?(($tempera_fonttitle == 'General Font')?'inherit':$tempera_fonttitle):"\"$tempera_googlefonttitle\""); ?>; }
.widget-title, .widget-title a { line-height: normal;
		font-family: <?php echo ((!$tempera_googlefontside)?(($tempera_fontside == 'General Font')?'inherit':$tempera_fontside):"\"$tempera_googlefontside\"");  ?>;  }
.entry-content h1, .entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6, #comments #reply-title,
.nivo-caption h2, #front-text1 h1, #front-text2 h1, .column-header-image  {
		font-family: <?php echo ((!$tempera_headingsgooglefont)?(($tempera_headingsfont == 'General Font')?'inherit':$tempera_headingsfont):"\"$tempera_headingsgooglefont\"");  ?>; }
#site-title span a {
		font-family: <?php echo ((!$tempera_sitetitlegooglefont)?(($tempera_sitetitlefont == 'General Font')?'inherit':$tempera_sitetitlefont):"\"$tempera_sitetitlegooglefont\"");  ?>; }
#access ul li a, #access ul li a span {
		font-family: <?php echo ((!$tempera_menugooglefont)?(($tempera_menufont == 'General Font')?'inherit':$tempera_menufont):"\"$tempera_menugooglefont\"");  ?>; }

<?php
////////// COLORS //////////
?>
body { color: <?php echo $tempera_contentcolortxt; ?>; background-color: <?php echo $tempera_backcolormain; ?> }
a { color: <?php echo $tempera_linkcolortext; ?>; }
a:hover,.entry-meta span a:hover, .comments-link a:hover { color: <?php echo $tempera_linkcolorhover; ?>; }
#header { background-color: <?php echo $tempera_backcolorheader; ?>; }
#site-title span a { color:<?php echo $tempera_titlecolor; ?>; }
#site-description { color:<?php echo $tempera_descriptioncolor; ?>; <?php if(cryout_hex2rgb($tempera_descriptionbg)): ?>background-color: rgba(<?php echo cryout_hex2rgb($tempera_descriptionbg); ?>,0.3); padding-left: 6px; <?php endif; ?>}

.socials a { background-color: <?php echo $tempera_socialcolorbg; ?>; } 
.socials-hover { background-color: <?php echo $tempera_socialcolorbghover; ?>; }
/* Main menu top level */
#access a, #nav-toggle span { color: <?php echo $tempera_menucolortxtdefault; ?>; }
#access, #nav-toggle {background-color: <?php echo $tempera_menucolorbgdefault; ?>; }
#access > .menu > ul > li > a > span { border-color: <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>; 
-moz-box-shadow: 1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>; 
-webkit-box-shadow: 1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>; 
box-shadow: 1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>; }
#access a:hover {background-color: <?php echo cryout_hexadder($tempera_menucolorbgdefault,'13');?>; }
#access ul li.current_page_item > a, #access ul li.current-menu-item > a,
#access ul li.current_page_ancestor > a, #access ul li.current-menu-ancestor > a {
       background-color: <?php echo cryout_hexadder($tempera_menucolorbgdefault,'13');?>; }
/* Main menu Submenus */
#access > .menu > ul > li > ul:before {border-bottom-color:<?php echo $tempera_submenucolorbgdefault; ?>;}
#access ul ul ul:before { border-right-color:<?php echo $tempera_submenucolorbgdefault; ?>;}
#access ul ul li {
background-color:<?php echo $tempera_submenucolorbgdefault; ?>;
border-top-color:<?php echo cryout_hexadder($tempera_submenucolorbgdefault,'14');?>;
border-bottom-color:<?php echo cryout_hexadder($tempera_submenucolorbgdefault,'-11');?>
}
#access ul ul li a{color:<?php echo $tempera_submenucolortxtdefault; ?>}
#access ul ul li a:hover{background:<?php echo cryout_hexadder($tempera_submenucolorbgdefault,'14');?>}
#access ul ul li.current_page_item > a, #access ul ul li.current-menu-item > a,
#access ul ul li.current_page_ancestor > a, #access ul ul li.current-menu-ancestor > a  {
background-color:<?php echo cryout_hexadder($tempera_submenucolorbgdefault,'14');?>; }
<?php if (cryout_hex2rgb($tempera_submenucolorshadow)): ?>#access ul ul { box-shadow: 3px 3px 0 rgba(<?php echo cryout_hex2rgb($tempera_submenucolorshadow); ?>,0.3); }<?php endif; ?>

#topbar {
<?php if ($tempera_topbar == 'Hide'){ ?> display:none; <?php } 
else { ?>
	background-color:  <?php echo $tempera_topbarcolorbg; ?>;border-bottom-color:<?php echo cryout_hexadder($tempera_topbarcolorbg,'40');?>;
	box-shadow:3px 0 3px <?php echo cryout_hexadder($tempera_topbarcolorbg,'-40');?>; 
	<?php if ($tempera_topbar == 'Fixed'): ?>
		position:fixed;top:0;z-index:252;opacity:0.8;
	<?php endif; 
}?>
}
<?php if ($tempera_topbar == 'Fixed') {?> #header-full {margin-top:30px;} <?php } ?>
<?php if ($tempera_topbarwidth == 'Full width'){ ?> #topbar-inner {max-width:95%;} <?php } ?>
.topmenu ul li a { color: <?php echo $tempera_topmenucolortxt; ?>; }
.topmenu ul li a:hover { color: <?php echo $tempera_topmenucolortxthover; ?>; border-bottom-color: <?php echo $tempera_accentcolora; ?>; }

#main { background-color: <?php echo $tempera_contentcolorbg; ?>; }	
#author-info, #entry-author-info, .page-title { border-color: <?php echo $tempera_accentcolora; ?>; background: <?php echo $tempera_accentcolore; ?>; }
#entry-author-info #author-avatar, #author-info #author-avatar { border-color: <?php echo $tempera_accentcolorc; ?>; }

.sidey .widget-container { color: <?php echo $tempera_sidetxt; ?>; background-color: <?php echo $tempera_sidebg; ?>; }
.sidey .widget-title { color: <?php echo $tempera_sidetitletxt; ?>; background-color: <?php echo $tempera_sidetitlebg; ?>;border-color:<?php echo cryout_hexadder($tempera_sidetitlebg,'-40');?>;}
.sidey .widget-container a {color:<?php echo $tempera_linkcolorside;?>;}
.sidey .widget-container a:hover {color:<?php echo $tempera_linkcolorsidehover;?>;}

.entry-content h1, .entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6 {
     color: <?php echo $tempera_contentcolortxtheadings; ?>; }
 .sticky .entry-header {border-color:<?php echo $tempera_accentcolora; ?> }
.entry-title, .entry-title a { color: <?php echo $tempera_contentcolortxttitle; ?>; }
.entry-title a:hover { color: <?php echo $tempera_contentcolortxttitlehover; ?>; }
#content h3.entry-format { color: <?php echo $tempera_menucolortxtdefault; ?>; background-color: <?php echo $tempera_menucolorbgdefault; ?>; }

#footer { color: <?php echo $tempera_footercolortxt; ?>; background-color: <?php echo $tempera_backcolorfooterw; ?>; }
#footer2 { color: <?php echo $tempera_footercolortxt; ?>; background-color: <?php echo $tempera_backcolorfooter; ?>;  }
#footer a { color: <?php echo $tempera_linkcolorwooter; ?>; }
#footer a:hover { color: <?php echo $tempera_linkcolorwooterhover; ?>; }
#footer2 a, .footermenu ul li:after  { color: <?php echo $tempera_linkcolorfooter; ?>; }
#footer2 a:hover { color: <?php echo $tempera_linkcolorfooterhover; ?>; }
#footer .widget-container { color: <?php echo $tempera_widgettxt; ?>; background-color: <?php echo $tempera_widgetbg; ?>; }
#footer .widget-title { color: <?php echo $tempera_widgettitletxt; ?>; background-color: <?php echo $tempera_widgettitlebg; ?>;border-color:<?php echo cryout_hexadder($tempera_widgettitlebg,'-40');?> }

a.continue-reading-link, #cryout_ajax_more_trigger { color:<?php echo $tempera_menucolortxtdefault; ?> !important; background:<?php echo $tempera_menucolorbgdefault; ?>; border-bottom-color:<?php echo $tempera_accentcolora; ?>; }
a.continue-reading-link:after { background-color:<?php echo $tempera_accentcolorb; ?>; }
a.continue-reading-link i.icon-right-dir {color:<?php echo $tempera_accentcolora; ?>}
a.continue-reading-link:hover i.icon-right-dir {color:<?php echo $tempera_accentcolorb; ?>}
.page-link a, .page-link > span > em {border-color:<?php echo $tempera_accentcolord;?>}

.columnmore a {background:<?php echo $tempera_accentcolorb;?>;color:<?php echo $tempera_accentcolore; ?>}
.columnmore a:hover {background:<?php echo $tempera_accentcolora;?>;}

.file, .button, #respond .form-submit input#submit, input[type=submit], input[type=reset] {
	background-color: <?php echo $tempera_contentcolorbg; ?>;
	border-color: <?php echo $tempera_accentcolord; ?>;
    box-shadow: 0 -10px 10px 0 <?php echo $tempera_accentcolore; ?> inset; }
.file:hover, .button:hover, #respond .form-submit input#submit:hover {
	background-color: <?php echo $tempera_accentcolore; ?>; }
.entry-content tr th, .entry-content thead th {
	color: <?php echo $tempera_contentcolortxtheadings; ?>; }
.entry-content fieldset, #content tr td,#content tr th, #content thead th { border-color: <?php echo $tempera_accentcolord; ?>; }
 #content tr.even td { background-color: <?php echo $tempera_accentcolore; ?> !important; }
hr { background-color: <?php echo $tempera_accentcolord; ?>; }
input[type="text"], input[type="password"], input[type="email"], input[type="file"], textarea, select,
input[type="color"],input[type="date"],input[type="datetime"],input[type="datetime-local"],input[type="month"],input[type="number"],input[type="range"],
input[type="search"],input[type="tel"],input[type="time"],input[type="url"],input[type="week"] {
	background-color: <?php echo $tempera_accentcolore; ?>;
    border-color: <?php echo $tempera_accentcolord; ?> <?php echo $tempera_accentcolorc; ?> <?php echo $tempera_accentcolorc; ?> <?php echo $tempera_accentcolord; ?>;
	color: <?php echo $tempera_contentcolortxt; ?>; }
input[type="submit"], input[type="reset"] {
	color: <?php echo $tempera_contentcolortxt; ?>;
	background-color: <?php echo $tempera_contentcolorbg; ?>;
	border-color: <?php echo $tempera_accentcolord; ?>;
	box-shadow: 0 -10px 10px 0 <?php echo $tempera_accentcolore; ?> inset; }
input[type="text"]:hover, input[type="password"]:hover, input[type="email"]:hover, textarea:hover,
input[type="color"]:hover, input[type="date"]:hover, input[type="datetime"]:hover, input[type="datetime-local"]:hover, input[type="month"]:hover, input[type="number"]:hover, input[type="range"]:hover,
input[type="search"]:hover, input[type="tel"]:hover, input[type="time"]:hover, input[type="url"]:hover, input[type="week"]:hover {
	<?php if(cryout_hex2rgb($tempera_accentcolore)): ?>background-color: rgba(<?php echo cryout_hex2rgb($tempera_accentcolore); ?>,0.4); <?php endif; ?> }
.entry-content code {
	border-color: <?php echo $tempera_accentcolord; ?>;
	border-bottom-color:<?php echo $tempera_accentcolora ;?>;}
.entry-content pre { border-color: <?php echo $tempera_accentcolord; ?>;
	background-color:<?php echo $tempera_accentcolore; ?>;}
.entry-content blockquote {
	border-color: <?php echo $tempera_accentcolorc; ?>; }
abbr, acronym { border-color: <?php echo $tempera_contentcolortxt; ?>; }
.comment-meta a { color: <?php echo $tempera_contentcolortxt; ?>; }
#respond .form-allowed-tags { color: <?php echo $tempera_contentcolortxtlight; ?>; }
.reply a{ background-color: <?php echo $tempera_accentcolore; ?>; border-color: <?php echo $tempera_accentcolorc; ?>; }
.reply a:hover { background-color: <?php echo $tempera_menucolorbgdefault; ?>;color: <?php echo $tempera_linkcolortext; ?>; }

.entry-meta .icon-metas:before {color:<?php echo $tempera_metacoloricons; ?>;}
.entry-meta span a, .comments-link a {color:<?php echo $tempera_metacolorlinks; ?>;}
.entry-meta span a:hover, .comments-link a:hover {color:<?php echo $tempera_metacolorlinkshover; ?>;}

.nav-next a:hover {}
.nav-previous a:hover {
}
.pagination { border-color:<?php echo cryout_hexadder($tempera_accentcolore,'-10');?>;}
.pagination span, .pagination a { 
	background:<?php echo $tempera_accentcolore;?>;
	border-left-color:<?php echo cryout_hexadder($tempera_accentcolore,'-26'); ?>;
	border-right-color:<?php echo cryout_hexadder($tempera_accentcolore,'16'); ?>;
}
.pagination a:hover { background: <?php echo cryout_hexadder($tempera_accentcolore,'8'); ?>; }

#searchform input[type="text"] {color:<?php echo $tempera_contentcolortxtlight; ?>;}

.caption-accented .wp-caption {<?php if(cryout_hex2rgb($tempera_accentcolora)):?> background-color:rgba(<?php echo cryout_hex2rgb($tempera_accentcolora);?>,0.8); <?php endif; ?>
	color:<?php echo $tempera_contentcolorbg;?>}

.tempera-image-one .entry-content img[class*='align'],.tempera-image-one .entry-summary img[class*='align'],
.tempera-image-two .entry-content img[class*='align'],.tempera-image-two .entry-summary img[class*='align'] {
	border-color:<?php echo $tempera_accentcolora; ?>;} 
<?php
////////// LAYOUT //////////
?>
#content p, #content ul, #content ol, #content, #frontpage blockquote { text-align:<?php echo $tempera_textalign;  ?> ; }
#content p, #content ul, #content ol, .widget-area, .widget-area a, table, table td {
                                font-size:<?php echo $tempera_fontsize ?>;
								word-spacing:<?php echo $tempera_wordspace ?>; letter-spacing:<?php echo $tempera_letterspace ?>; }
#content p, #content ul, #content ol, .widget-area, .widget-area a { line-height:<?php echo $tempera_lineheight ?>; } 
<?php if ($tempera_uppercasetext==1): ?> #site-title a, #site-description, #access a, .topmenu ul li a, .footermenu a, .entry-meta span a, .entry-utility span a, #content h3.entry-format,
span.edit-link, h3#comments-title, h3#reply-title, .comment-author cite, .reply a, .widget-title, #site-info a, .nivo-caption h2, a.continue-reading-link,
.column-image h3, #front-columns h3.column-header-noimage, .tinynav , .entry-title, .breadcrumbs, .page-link{ text-transform: uppercase; }<?php endif; ?>
<?php if ($tempera_hcenter): ?> #bg_image {display:block;margin:0 auto;} <?php endif; ?>
#content h1.entry-title, #content h2.entry-title { font-size:<?php echo $tempera_headfontsize; ?> ;}
.widget-title, .widget-title a { font-size:<?php echo $tempera_sidefontsize; ?> ;} 
<?php $font_root = 36;
for($i=1;$i<=6;$i++):
	echo "#content .entry-content h$i { font-size: ";
	echo round(($font_root-(4*$i))*(preg_replace("/[^\d]/","",$tempera_headingsfontsize)/100),0);
	echo "px;} ";
endfor; ?>
#site-title span a { font-size:<?php echo $tempera_sitetitlesize; ?> ;}
#access ul li a { font-size:<?php echo $tempera_menufontsize; ?> ;}
#access ul ul ul a {font-size:<?php echo (absint($tempera_menufontsize)-2); ?>px;}
<?php /*if ($tempera_postseparator == "Show") { ?> article.post, article.page { padding-bottom: 10px; border-bottom: 3px solid #EEE; } <?php }*/ ?>
<?php if ($tempera_contentlist == "Hide") { ?> #content ul li { background-image: none; padding-left: 0; } <?php } ?>
<?php if ($tempera_comtext == "Hide") { ?> #respond .form-allowed-tags { display:none;} <?php } ?>
<?php switch ($tempera_comclosed) {
	case "Hide in posts": ?> .nocomments { display:none;} <?php break;
	case "Hide in pages": ?> .nocomments2 {display:none;} <?php break;
	case "Hide everywhere": ?> .nocomments, .nocomments2 {display:none;} <?php break;
};//switch ?>
<?php if ($tempera_comoff == "Hide") { ?> .comments-link span { display:none;} <?php } ?>
<?php if ($tempera_tables == "Enable") { ?>
		#content table {border:none;} #content tr {background:none;} #content table {border:none;}
		#content tr th, #content thead th {background:none;} #content tr th, #content tr td {border:none;}
<?php } ?>
<?php if ($tempera_headingsindent == "Enable") { ?>
		#content h1, #content h2, #content h3, #content h4, #content h5, #content h6 { margin-left:20px;}
		.sticky hgroup { padding-left: 15px;}
<?php } ?>
#header-container > div { margin:<?php echo $tempera_headermargintop; ?>px 0 0 <?php echo $tempera_headermarginleft; ?>px;}
<?php if ($tempera_pagetitle == "Hide") { ?> .page h1.entry-title, .home .page h2.entry-title { display:none; } <?php } ?>
<?php if ($tempera_categtitle == "Hide") { ?> header.page-header, .archive h1.page-title { display:none; }  <?php } ?>
#content p, #content ul, #content ol, #content dd, #content pre, #content hr { margin-bottom: <?php echo $tempera_paragraphspace;?>; }
<?php if ($tempera_parindent != "0px") { ?> #content p { text-indent:<?php echo $tempera_parindent;?>;} <?php } ?>

<?php if ($tempera_metapos == 'Top') { ?> footer.entry-meta {background-image:none !important;padding-top:0;} <?php } ?>
	
<?php switch ($tempera_menualign): 
		case "center": ?> #access > .menu > ul { border-left: 1px solid <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>; 
										-moz-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>; 
										-webkit-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>; 
										box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>; } <?php 
		break;
		case "right": ?> #access > .menu > ul > li > a > span { border-left:1px solid <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>; 
							-moz-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>; 
							-webkit-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>; 
							box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>; 
							border-right: 0; } <?php
		break;
		case "rightmulti": ?> #access > .menu > ul > li > a > span { border-left:1px solid <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>; 
							-moz-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>; 
							-webkit-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>; 
							box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>;
							border-right:0;	} <?php 
		break;
		default: 
		break; 
	  endswitch; ?>
#toTop {background:<?php echo $tempera_contentcolorbg; ?>;margin-left:<?php echo $totalwidth+150 ?>px;} 		  
<?php if (is_rtl() ) { ?> #toTop {margin-right:<?php echo $totalwidth+150 ?>px;-moz-border-radius:10px 0 0 10px;-webkit-border-radius:10px 0 0 10px;border-radius:10px 0 0 10px;}		<?php } ?>	
#toTop:hover .icon-back2top:before {color:<?php echo $tempera_accentcolorb;?>;}  

#main {margin-top:<?php echo $tempera_contentmargintop;?>px; }
#forbottom {margin-left: <?php echo $tempera_contentpadding;?>px; margin-right: <?php echo $tempera_contentpadding;?>px;}
#header-widget-area { width: <?php echo $tempera_headerwidgetwidth; ?>; }
<?php
////////// HEADER IMAGE //////////
?>
#branding { height:<?php echo HEADER_IMAGE_HEIGHT; ?>px; }
<?php if ($tempera_hratio) { ?> @media (max-width: 1920px) {#branding, #bg_image { height:auto; max-width:100%; min-height:inherit !important; } } <?php } ?>
</style>
<?php
	$tempera_custom_styling = ob_get_contents();
	ob_end_clean();
	return $tempera_custom_styling;
} // tempera_custom_styles()

// Tempera function for inserting the Custom CSS into the header
function tempera_customcss() {
	$temperas= tempera_get_theme_options();
	foreach ($temperas as $key => $value) { ${"$key"} = $value ; }
	if ($tempera_customcss != "") {
		echo '<style type="text/css">'.htmlspecialchars_decode($tempera_customcss, ENT_QUOTES).'</style>';
	}
} // tempera_customcss()

// Tempera function for inseting the Custom JS into the header
function tempera_customjs() {
	$temperas= tempera_get_theme_options();
	foreach ($temperas as $key => $value) { ${"$key"} = $value; }
	echo '<script type="text/javascript">';
	echo 'var cryout_global_content_width = '.$tempera_sidewidth.';';
	echo 'var cryout_toTop_offset = '.($tempera_sidewidth+$tempera_sidebar).';' ;
	if (is_rtl())  echo 'var cryout_toTop_offset =  '.($tempera_sidewidth+$tempera_sidebar).';';
	if ($tempera_customjs != "") {
		echo PHP_EOL.htmlspecialchars_decode($tempera_customjs, ENT_QUOTES);
	}
	echo '</script>';
} // tempera_customjs()

////////// FIN //////////
?>