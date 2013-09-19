<?php function shrsb_display_activation(){ ?>
	
<div class="clearbig"></div>
<form name="activation" id="shrsb-activation" action="" method="post">
	<div style="clear:both;height:10px;"></div>
	<img src="<?php echo SHRSB_PLUGPATH; ?>images/shareaholic-220.png" />
	<div style="clear:both;height:5px;"></div>
		
	<div id="shr-activation-header">
	  <div id="shr-activation-notice" style="padding-right:10px;">
	    <p style="font-size: 26px; color: #454B4C; text-shadow: 0pt 1px 0pt white;"><?php _e("Your Shareaholic Plugin is almost ready!", 'shrsb'); ?></p>
			<p style="font-size: 15px; line-height: 24px; color: #454B4C; text-shadow: 0pt 1px 0pt white;"><?php _e("Activate by clicking the green \"Enable\" button below. Once you’ve enabled Shareaholic, you will enjoy all the delightful goodness of Shareaholic.", 'shrsb'); ?></p>
		</div>
	</div>
				
	<div style="background: url('<?php echo SHRSB_PLUGPATH; ?>images/orange_arrow.gif') no-repeat;height: 70px;width: 50px; float:left;"></div>
		
	<input type="hidden" name="activate" value="1" />
	<?php wp_nonce_field('save-settings','shareaholic_nonce'); ?>
			 
  <div class="shrsbsubmit" style="margin: 38px 8px 10px!important;">
    <input type="submit" id="activate" value="<?php _e('Click here to enable Shareaholic now »', 'shrsb'); ?>" />
  </div>
</form>
    
<div style="clear:both;height:15px;"></div>
<div style="display:block; font-size: 11px; color: #777777; width: 960px;">
  <?php echo sprintf(__('<p>By enabling Shareaholic, you are accepting our %sTerms of Service%s and %sPrivacy Policy%s. Shareaholic is trusted by over 200,000 websites like yours and touches almost 300 million people each month.  Designed and built with all the love in the world in Boston, Massachusetts.</p>', 'shrsb'), '<a href="https://shareaholic.com/terms/" target="_new">', '</a>', '<a href="https://shareaholic.com/privacy/" target="_new">', '</a>'); ?>	
</div>

<?php } ?>