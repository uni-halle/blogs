<?php awpcp_print_messages() ?>

<div id="<?php echo $this->page ?>" class="<?php echo $this->page ?> awpcp-admin-page awpcp-page wrap">
	<div class="page-content">
		<h2 class="awpcp-page-header"><?php echo $this->title() ?></h2>

        <?php if ($this->sidebar) {
                echo $sidebar = awpcp_admin_sidebar();
            } else {
                $sidebar = '';
            } ?>

		<div class="awpcp-main-content <?php echo (empty($sidebar) ? 'without-sidebar' : 'with-sidebar') ?>">
            <div class="awpcp-inner-content">
            <?php echo $content ?>
            </div>
        </div>
    </div>
</div>
