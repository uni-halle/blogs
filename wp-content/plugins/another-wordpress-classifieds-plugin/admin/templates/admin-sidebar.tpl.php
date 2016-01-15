<div class="awpcp-admin-sidebar awpcp-postbox-container postbox-container" style="<?php echo $float; ?>">
    <div class="metabox-holder">
        <div class="meta-box-sortables">

            <div class="postbox">
                <?php echo awpcp_html_postbox_handle( array( 'content' => __( 'Like this plugin?', 'another-wordpress-classifieds-plugin' ) ) ); ?>
                <div class="inside">
                <p><?php _e('Why not do any or all of the following:', 'another-wordpress-classifieds-plugin'); ?></p>
                    <ul>
                        <li class="li_link">
                            <a href="http://wordpress.org/extend/plugins/another-wordpress-classifieds-plugin/">
                                <?php _e('Give it a good rating on WordPress.org.', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a href="http://wordpress.org/extend/plugins/another-wordpress-classifieds-plugin/">
                                <?php _e('Let other people know that it works with your WordPress setup.', 'another-wordpress-classifieds-plugin'); ?>
                            </a></li>
                        <li class="li_link">
                            <a href="http://www.awpcp.com/premium-modules/?ref=panel"><?php _e('Buy a Premium Module', 'another-wordpress-classifieds-plugin'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="awpcp-get-a-premium-module postbox" style="background-color:#FFFFCF; border-color:#0EAD00; border-width:3px;">
                <?php
                    $params = array(
                        'heading_attributes' => array(
                            'style' => 'color:#145200',
                        ),
                        'span_attributes' => array(
                            'class' => 'red',
                        ),
                        'content' => '<strong>' . __( 'Get a Premium Module!', 'another-wordpress-classifieds-plugin' ) . '</strong>',
                    );

                    echo awpcp_html_postbox_handle( $params );
                ?>

                <div class="inside">
                    <ul>
                        <li class="li_link">
                            <img style="align:left" src="<?php echo $url; ?>/resources/images/new.gif"/>
                            <a style="color:#145200;" href="http://awpcp.com/premium-modules/campaign-manager-module/?ref=panel" target="_blank">
                                <?php _e( 'Campaign Manager', 'another-wordpress-classifieds-plugin' ); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <img style="align:left" src="<?php echo $url; ?>/resources/images/new.gif"/>
                            <a style="color:#145200;" href="http://awpcp.com/downloads/mark-as-sold-module/?ref=panel" target="_blank">
                                <?php _e( 'Mark as Sold', 'another-wordpress-classifieds-plugin' ); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <img style="align:left" src="<?php echo $url; ?>/resources/images/new.gif"/>
                            <a style="color:#145200;" href="http://awpcp.com/premium-modules/restricted-categories-module/?ref=panel" target="_blank">
                                <?php _e( 'Restricted Categories', 'another-wordpress-classifieds-plugin' ); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/buddypress-module/?ref=panel" target="_blank">
                                <?php _e( 'BuddyPress Listings', 'another-wordpress-classifieds-plugin' ); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/comments-ratings-module/?ref=panel" target="_blank">
                                <?php _e('Comments/Ratings', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/attachments-module/?ref=panel" target="_blank">
                                <?php _e('Attachments', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/authorizenet-module/?ref=panel" target="_blank">
                                <?php _e('Authorize.Net', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/paypal-pro-module/?ref=panel" target="_blank">
                                <?php _e('PayPal Pro', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/coupons-module/?ref=panel" target="_blank">
                                <?php _e('Coupon/Discount', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/subscriptions-module/?ref=panel" target="_blank">
                                <?php _e('Subscriptions', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/fee-category-module/?ref=panel" target="_blank">
                                <?php _e('Fee Per Category', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/featured-ads-module/?ref=panel" target="_blank">
                                <?php _e('Featured Ads', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/extra-fields-module/?ref=panel" target="_blank">
                                <?php _e('Extra Fields', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/category-icons-module/?ref=panel" target="_blank">
                                <?php _e('Category Icons', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/regions-module/?ref=panel" target="_blank">
                                <?php _e('Regions Control', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://awpcp.com/downloads/rss-feeds-module/?ref=panel" target="_blank">
                                <?php _e('RSS', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://www.awpcp.com/donate/?ref=panel" target="_blank">
                                <?php _e('Donate to Support AWPCP', 'another-wordpress-classifieds-plugin'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="postbox">
                <?php echo awpcp_html_postbox_handle( array( 'content' => __( 'Found a bug?', 'another-wordpress-classifieds-plugin' ) . '&nbsp;' . __( 'Need Support?', 'another-wordpress-classifieds-plugin' ) ) ); ?>
                <?php $tpl = '<a href="%s" target="_blank">%s</a>'; ?>
                <div class="inside">
                    <ul>
                        <?php $link = sprintf($tpl, 'http://www.awpcp.com/quick-start-guide', __('Quick Start Guide', 'another-wordpress-classifieds-plugin')); ?>
                        <li><?php echo sprintf(_x('Browse the %s.', 'Browse the <a>Quick Start Guide</a>', 'another-wordpress-classifieds-plugin'), $link); ?></li>
                        <?php $link = sprintf($tpl, 'http://awpcp.com/docs', __('Documentation', 'another-wordpress-classifieds-plugin')); ?>
                        <li><?php echo sprintf(_x('Read the full %s.', 'Read the full <a>Documentation</a>', 'another-wordpress-classifieds-plugin'), $link); ?></li>
                        <?php $link = sprintf($tpl, 'http://www.awpcp.com/forum', __('visit the forums!', 'another-wordpress-classifieds-plugin')); ?>
                        <li><?php echo sprintf(_x('Report bugs or get more help: %s.', 'Report bugs or get more help: <a>visit the forums!</a>', 'another-wordpress-classifieds-plugin'), $link); ?></li>
                    </ul>
                </div>
            </div>

            <div class="postbox">
                <?php echo awpcp_html_postbox_handle( array( 'content' => __( 'Premium Modules','another-wordpress-classifieds-plugin' ) ) ); ?>
                <div class="inside">

                    <h4><?php _e("Installed", 'another-wordpress-classifieds-plugin'); ?></h4>

                    <?php if (count($modules['premium']['installed']) == 0): ?>

                    <p><?php _e( 'No premium modules installed.', 'another-wordpress-classifieds-plugin' ); ?></p>

                    <?php else: ?>

                    <ul>
                    <?php foreach ($modules['premium']['installed'] as $module): ?>
                        <li><?php echo $module['name']; ?></li>
                    <?php endforeach; ?>
                    </ul>

                    <?php endif; ?>


                    <h4><?php _e("Not Installed", 'another-wordpress-classifieds-plugin'); ?></h4>

                    <?php if (count($modules['premium']['not-installed']) == 0): ?>

                    <p><?php _e("All premium modules installed!", 'another-wordpress-classifieds-plugin'); ?></p>

                    <?php else: ?>

                    <ul>
                    <?php foreach ($modules['premium']['not-installed'] as $module): ?>
                        <li><a href="<?php echo $module['url']; ?>"><?php echo $module['name']; ?></a></li>
                    <?php endforeach; ?>
                    </ul>

                    <?php endif; ?>

                </div>
            </div>

            <!-- <div class="postbox">
                <?php echo awpcp_html_postbox_handle( array( 'content' => __( 'Other Modules','another-wordpress-classifieds-plugin' ) ) ); ?>

                <div class="inside">

                    <h4><?php _e("Installed", 'another-wordpress-classifieds-plugin'); ?><h4>

                    <?php if (count($modules['other']['installed']) == 0): ?>

                    <p><?php __("No other modules installed", 'another-wordpress-classifieds-plugin'); ?></p>

                    <?php else: ?>

                    <ul>
                    <?php foreach ($modules['other']['installed'] as $module): ?>
                        <li><?php echo $module['name']; ?></li>
                    <?php endforeach; ?>
                    </ul>

                    <?php endif; ?>


                    <h4><?php _e("Not Installed", 'another-wordpress-classifieds-plugin'); ?><h4>

                    <?php if (count($modules['other']['not-installed']) == 0): ?>

                    <p><?php __("All other modules installed!", 'another-wordpress-classifieds-plugin'); ?></p>

                    <?php else: ?>

                    <ul>
                    <?php foreach ($modules['other']['not-installed'] as $module): ?>
                        <li><a href="<?php echo $module['url']; ?>"><?php echo $module['name']; ?></a></li>
                    <?php endforeach; ?>
                    </ul>

                    <?php endif; ?>

                </div>
            </div> -->

        </div>
    </div>
</div>
