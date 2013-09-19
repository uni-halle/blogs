<?php
// Build main site navigation from Organic Options
$title1 = substr(get_option('organic_theme_page_one_title'),0,17);
$desc1 = substr(get_option('organic_theme_page_one_desc'),0,35);
$title2 = substr(get_option('organic_theme_page_two_title'),0,17);
$desc2 = substr(get_option('organic_theme_page_two_desc'),0,35);
$title3 = substr(get_option('organic_theme_page_three_title'),0,17);
$desc3 = substr(get_option('organic_theme_page_three_desc'),0,35);
$title4 = substr(get_option('organic_theme_page_four_title'),0,17);
$desc4 = substr(get_option('organic_theme_page_four_desc'),0,35);
$title5 = substr(get_option('organic_theme_page_five_title'),0,17);
$desc5 = substr(get_option('organic_theme_page_five_desc'),0,35);
// fetches permalinks
$page1 = get_page_by_title(get_option('organic_theme_page_one_title')); $link1 = get_permalink($page1->ID);
$page2 = get_page_by_title(get_option('organic_theme_page_two_title')); $link2 = get_permalink($page2->ID);
$page3 = get_page_by_title(get_option('organic_theme_page_three_title')); $link3 = get_permalink($page3->ID);
$page4 = get_page_by_title(get_option('organic_theme_page_four_title')); $link4 = get_permalink($page4->ID);
$page5 = get_page_by_title(get_option('organic_theme_page_five_title')); $link5 = get_permalink($page5->ID);
?>
<ul id="nav">
<?php if ($desc1 != "") { ?>
<li class="first">
	<h5><a href="<?php echo $link1; ?>"><?php echo ucwords($title1); ?></a></h5>
	<p><?php echo stripslashes($desc1); ?></p>
</li>
<?php } ?>
<?php if ($desc2 != "") { ?>
<li>
	<h5><a href="<?php echo $link2; ?>"><?php echo ucwords($title2); ?></a></h5>
	<p><?php echo stripslashes($desc2); ?></p>
</li>
<?php } ?>
<?php if ($desc3 != "") { ?>
<li>
	<h5><a href="<?php echo $link3; ?>"><?php echo ucwords($title3); ?></a></h5>
	<p><?php echo stripslashes($desc3); ?></p>
</li>
<?php } ?>
<?php if ($desc4 != "") { ?>
<li>
	<h5><a href="<?php echo $link4; ?>"><?php echo ucwords($title4); ?></a></h5>
	<p><?php echo stripslashes($desc4); ?></p>
</li>
<?php } ?>
<?php if ($desc5 != "") { ?>
<li>
	<h5><a href="<?php echo $link5; ?>"><?php echo ucwords($title5); ?></a></h5>
	<p><?php echo stripslashes($desc5); ?></p>
</li>
<?php } ?>
</ul>

