<?php
/*
Template Name: Archiv
*/
?>

<?php get_header(); ?>

<div class="main">
	
	<?php include ('column-one.php'); ?>

		<div class="content">
			<div class="column two">
				<div class="edge-alt"></div>
				
<div class="entry-extended">

<h1 class="pagetitle">Blog Archiv</h1>

<h2>Archiv nach Monat:</h2>
	<ul>
		<?php wp_get_archives('type=monthly'); ?>
	</ul>

<h2>Archiv nach Kategorie:</h2>
	<ul>
		 <?php wp_list_categories('exclude=3&title_li='); ?>
	</ul>
</div>
	</div><!-- end column -->
</div><!-- end content -->
<?php get_footer(); ?>