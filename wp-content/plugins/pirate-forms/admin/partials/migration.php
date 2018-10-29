<?php
/** @var $label */
/** @var $class */
/** @var $class_import */
?>
<div class="wrap">

	<div id="pf-migration">

		<div class="pf-migration-header">

			<img src="<?php echo esc_url( PIRATEFORMS_URL . 'admin/img/wpforms-migration.png'); ?>" alt="" width="300">

			<h1><?php esc_html_e( 'Migrating to WPForms is Quick & Easy', 'pirate-forms' ); ?></h1>

			<p><?php esc_html_e( 'There are just 2 steps. We’ll handle all of the heavy lifting behind the scenes.', 'pirate-forms' ); ?></p>

		</div>

		<div class="pf-migration-steps">

			<div class="pf-migration-step pf-migration-step-1">

				<div class="pf-migration-step-number">
					<span>1</span>
				</div>

				<div class="pf-migration-step-description">
					<h2><?php esc_html_e( 'Install & Activate WPForms', 'pirate-forms' ); ?></h2>

					<p><?php esc_html_e( 'Install WPForms Lite from the WordPress.org plugin repository. It’s trusted by over 1 million websites and has a 4.9 out of 5 star rating average.', 'pirate-forms' ); ?></p>

					<form method="post">
						<?php wp_nonce_field( 'pirateforms_migration', 'pirateforms_migration_nonce' ); ?>
						<button type="submit" class="button button-primary button-hero pf-migration-action <?php echo $class; ?>">
							<?php echo $label; ?>
						</button>

						<span class="pf-migration-step-message">
							<img src="<?php echo esc_url( PIRATEFORMS_URL . 'admin/img/loader.gif'); ?>" alt="">
						</span>
					</form>
				</div>
			</div>

			<div class="pf-migration-step pf-migration-step-2">

				<div class="pf-migration-step-number">
					<span>2</span>
				</div>

				<div class="pf-migration-step-description">
					<h2><?php esc_html_e( 'Migrate Pirate Forms', 'pirate-forms' ); ?></h2>

					<p>
						<?php
						printf(
							wp_kses(
								/* translate: %s - URL to WP Mail SMTP plugin on WordPress.org. */
								__( 'We’ll migrate your forms and settings automatically. If you have custom SMTP settings, our <a href="%s">WP Mail SMTP</a> plugin will also be installed.', 'pirate-forms' ),
								array(
									'a' => array(
										'href'   => array(),
										'rel'    => array(),
										'target' => array(),
									),
								)
							),
							'https://wordpress.org/plugins/wp-mail-smtp/'
						);
						?>
					</p>

					<?php
					// http://wpforms.am/wp-admin/admin.php?provider=pirate-forms&page=wpforms-tools&view=importer
					$migrate_url = add_query_arg(
						array(
							'provider' => PIRATEFORMS_SLUG,
							'page' => 'wpforms-tools',
							'view' => 'importer'
						),
						admin_url( 'admin.php' )
					);
					?>
					<a class="button button-secondary button-hero <?php echo $class_import; ?> js-pf-migration-import" href="<?php echo esc_url( $migrate_url ); ?>">
						<?php esc_html_e( 'Start Migration', 'pirate-forms' ); ?>
					</a>
				</div>
			</div>

		</div>

	</div>

</div><!-- .wrap -->
