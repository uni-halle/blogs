<?php awpcp_print_messages(); ?>

<div id="<?php echo esc_attr( $this->page ); ?>" class="<?php echo esc_attr( $this->page ); ?> awpcp-admin-page awpcp-page wrap">
	<div class="page-content">
		<h2 class="awpcp-page-header"><?php echo $this->title(); // title() is allowed to output html ?></h2>

        <?php $sidebar = $this->show_sidebar() ? awpcp_admin_sidebar() : ''; ?>
        <?php echo $sidebar; ?>

		<div class="awpcp-main-content <?php echo empty( $sidebar ) ? 'without-sidebar' : 'with-sidebar'; ?>">
            <div class="awpcp-inner-content">
            <?php echo $content; ?>
            </div>
        </div>
    </div>
</div>
