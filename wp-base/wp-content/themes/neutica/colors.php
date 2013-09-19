<?php
require_once( dirname(__FILE__) . '../../../../wp-config.php');
require_once( dirname(__FILE__) . '/functions.php');
header("Content-type: text/css");

global $options;

foreach ($options as $value) {
    if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); } }
?>

/* colors.css

 Colors
 --------------------------
 yellow				#F2E950
 black          	#241C0F
 highlight         	#FFFFA0

*/

/* BACKGROUNDS
------------------------ */
#wrapper,.post,.byuser p,#commentform, .sidebar li h3 a:hover{background:transparent;}
html, body, .edit, .tabmenu div, #menu-item-wrap, .shadetabs li a.selected, #menu ul li a:hover, div#featured .featured-post h2 a:hover, div#featured .featured-post h2 a:hover{background-color:<?php echo $neu_bodybackground_color; ?>}
#menu, ul.meta-list li a:hover, #nav-above, #nav-below, .tabmenu, .shadetabs li a, .entry-content a:hover, .post blockquote, div#featured .featured-post:hover, div#featured, .comment a:hover, .sidebar li ul li a:hover, .sidebar a:hover, #commentform #submit, #searchform #searchsubmit, #error404-searchsubmit, #noresults-searchsubmit {background-color:<?php echo $neu_bodyfontborder_color; ?>}
#commentform input, #commentform textarea, .input-text, textarea, #searchform #s, #error404-s, input {background-color:<?php echo $neu_formbackground_color; ?>;}
::selection {background-color:<?php echo $neu_formbackground_color; ?>}
::-moz-selection {background-color:<?php echo $neu_formbackground_color; ?>}
a::-moz-selection, a::selection {background-color:<?php echo $neu_formbackground_color; ?>}

/* FONT COLORS
------------------------ */
#menu ul li a, .back:link, .back:visited, .back:hover, .back:active, .back:focus, #nav-above, #nav-above a, #nav-below a, .meta-nav, .shadetabs li a, .shadetabs li a:link, .shadetabs li a:visited, .shadetabs li a:active, .shadetabs li a:hover, .entry-content a:hover, .post blockquote, ul.meta-list li a:hover, div#featured, div#featured .featured-post h2 a, div#featured .featured-post a, div#featured .featured-post a:hover, .comment a:hover, .sidebar li ul li a:hover, .sidebar a:hover, #commentform #submit, #searchform #searchsubmit, #error404-searchsubmit, #noresults-searchsubmit {color:<?php echo $neu_bodybackground_color; ?>}
a, a:hover, #menu ul li a:hover, strong, .green, #header a:link, #header a:visited, #header a:hover, #header a:active, #header a:focus, #countrytabs li a.selected, #countrytabs li a.selected:active, #countrytabs li a.selected:focus, #countrytabs li a.selected:link, #countrytabs li a.selected:hover, .entry-meta, .entry-meta a:hover, h2.entry-title a:hover, a.fn:hover, .fn, a.fn, .entry-meta h2, .post h2, .post h3, .post h4, .post h5, #search h2, .emptysearch, .error, div#featured .featured-post h2 a:hover, .pagenav a:link, .pagenav a:visited, .pagenav a:hover, .pagenav a:active, .pagenav a:focus, .prev h2 a:hover, .prev h2 a:active, .prev h2 a:focus, .next h2 a:hover, .next h2 a:active, .next h2 a:focus, .pagination, .pagination a:link, .pagination a:visited, .pagination a:hover, .pagination a:active, .pagination a:focus, .comment cite, .comment .moderation, #commentform a:link, #commentform a:visited, #commentform a:hover, #commentform a:active, #commentform a:focus, #comments h3, #commentform h3, #sidebar, .sidebar li h3 a:hover, #sidebar #sb-2 a:link, #sidebar #sb-2 a:visited, #sidebar a:link, #sidebar a:visited, #sidebar #sb-2 a:hover, #sidebar #sb-2 a:active, #sidebar #sb-2 a:focus, #sidebar a:hover, #sidebar a:active, #sidebar a:focus, #footer, #footer a:link, #footer a:visited, #footer a:hover, #footer a:active, #footer a:focus, #commentform input, #commentform textarea, .input-text, textarea, #searchform #s, #error404-s {color:<?php echo $neu_bodyfontborder_color; ?>}
.edit-link a, .edit-link a:visited, .edit-link a:link, .required {color:#F00}

/* BORDERS
------------------------ */
a, #nav-below a:hover, div#featured .featured-post a:hover, .post .thePic .attachment-thumbnail {border-bottom:5px solid <?php echo $neu_bodybackground_color; ?>;}
.shadetabs li a.selected{border-bottom: 1px solid <?php echo $neu_bodybackground_color; ?>;}

.post, .p1, .hentry, .tabmenu, #comments, #commentform #submit, #searchform #searchsubmit, #error404-searchsubmit, #noresults-searchsubmit {border-top:5px solid <?php echo $neu_bodyfontborder_color; ?>;}
ul#countrytabs {border-right:5px solid <?php echo $neu_bodyfontborder_color; ?>;}
a:hover,.entry-content ul#archives-page li h3, .post .thePic .attachment-thumbnail:hover, .sidebar h3, #commentform input, #commentform textarea, .input-text, textarea, #commentform #submit, #searchform #s, #searchform #searchsubmit, #error404-s, #error404-searchsubmit, #commentform input, #commentform textarea, .input-text, textarea, #searchform #s, #error404-s, input, #noresults-searchsubmit {border-bottom:5px solid <?php echo $neu_bodyfontborder_color; ?>;}
ul#countrytabs {border-left:5px solid <?php echo $neu_bodyfontborder_color; ?>}
.tabmenu div {border-color:<?php echo $neu_bodyfontborder_color; ?>;}

#menu-item-wrap, .entry-content ul li, .entry-content ol li, .entry ul li ul li, .entry-content ul li ul li, .entry ol li ol li, .entry-content ol li ol li, ul.meta-list, #comments-list ul, #commentform, .sidebar li ul li ul li {border-top:1px solid <?php echo $neu_bodyfontborder_color; ?>;}
.entry ul, .entry-content ul, .entry ol, .entry-content ol, ul.meta-list li, .comment-meta, .sidebar li ul li {border-bottom:1px solid <?php echo $neu_bodyfontborder_color; ?>;}
.comment-author-admin p{border-left:1px solid <?php echo $neu_bodyfontborder_color; ?>}

div.entry-content ul#archives-page, div.entry ul#archives-page, .entry-content ul#archives-page li.content-column, .comment, #commentform textarea, #commentform input, #searchform #s, #error404-s, #noresults-s, .shadetabs li {border-top:none;}
#commentform textarea, #commentform input, #commentform #submit, #searchform #s, #searchform #searchsubmit, #error404-s, #error404-searchsubmit, #noresults-s, #noresults-searchsubmit {border-right:none;}
#menu ul li a, #nav-below a, .shadetabs li a, .entry ul li ul li, .entry-content ul li ul li, .entry ol li ol li, .entry-content ol li ol li, .entry-content ul#archives-page, ul.meta-list, div#featured .featured-post a, div#featured .featured-post h2 a, div#featured .featured-post h2 a:hover, .sidebar li ul li ul li {border-bottom:none;}
#commentform textarea, #commentform input, #commentform #submit, #searchform #s, #searchform #searchsubmit, #error404-s, #error404-searchsubmit, #noresults-s, #noresults-searchsubmit {border-left:none;}

abbr, #menu-item-wrap, .entry-content a, ul.meta-list li a, .post img, .comment a, .sidebar li ul li a, .sidebar a, #footer {border:none}