<?php awpcp_print_messages(); ?>

<div id="<?php echo esc_attr( $this->page ); ?>" class="<?php echo esc_attr( $this->page ); ?> awpcp-admin-page awpcp-page wrap">
	<div class="page-content">
        <?php
            $heading_params = array(
                'attributes' => array(
                    'class' => 'awpcp-page-header',
                ),
                'content' => $this->title(), // no need to escape; title() is allowed to output html
            );

            echo awpcp_html_admin_first_level_heading( $heading_params );
        ?>

        <?php $sidebar = $this->show_sidebar() ? awpcp_admin_sidebar() : ''; ?>
        <?php echo $sidebar; ?>

		<div class="awpcp-main-content <?php echo empty( $sidebar ) ? 'without-sidebar' : 'with-sidebar'; ?>">
            <div class="awpcp-inner-content">
            <?php echo $content; ?>
            </div>
        </div>
    </div>
</div>
