<form role="search" method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>">
    <div>
        <input type="text" onfocus="if (this.value == 'Search') {
                    this.value = '';
                }" onblur="if (this.value == '') {
                            this.value = '<?php esc_attr_e('Search', 'colorway'); ?>';
                        }"  value="<?php esc_attr_e('Search', 'colorway'); ?>" name="s" id="s" />
        <input type="submit" id="searchsubmit" value="<?php esc_attr_e('Search', 'colorway'); ?>" />
    </div>
</form>
<div class="clear"></div>