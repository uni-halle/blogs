<?php /* Start add our footer credits */ ?>
<?php $shortname = 'drcms'; ?>
<?php global $themeOptions; ?>
	<?php if( $themeOptions[ $shortname .'_footer_text'] ) : ?>
		<div id="footer-credit">
			<?php echo stripslashes( $themeOptions[$shortname .'_footer_text'] ); ?>
		</div>
	<?php endif; ?>
<?php /* End footer credit */ ?>
