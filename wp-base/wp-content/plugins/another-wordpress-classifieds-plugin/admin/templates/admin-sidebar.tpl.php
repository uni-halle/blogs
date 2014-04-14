<div class="awpcp-postbox-container postbox-container" style="<?php echo $float; ?>; width: 20%; ">
    <div class="metabox-holder">
        <div class="meta-box-sortables">

            <div class="postbox">
                <h3 class="hndle1"><span><?php _e('Like this plugin?', 'AWPCP'); ?></span></h3>
                <div class="inside">
                <p><?php _e('Why not do any or all of the following:', 'AWPCP'); ?></p>
                    <ul>
                        <li class="li_link">
                            <a href="http://wordpress.org/extend/plugins/another-wordpress-classifieds-plugin/">
                                <?php _e('Give it a good rating on WordPress.org.', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a href="http://wordpress.org/extend/plugins/another-wordpress-classifieds-plugin/">
                                <?php _e('Let other people know that it works with your WordPress setup.', 'AWPCP'); ?>
                            </a></li>
                        <li class="li_link">
                            <a href="http://www.awpcp.com/premium-modules/?ref=panel"><?php _e('Buy a Premium Module', 'AWPCP'); ?></a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="postbox" style="background-color:#FFFFCF; border-color:#0EAD00; border-width:3px;">
                <h3 class="hndle1" style="color:#145200;">
                    <span class="red">
                        <strong><?php _e('Get a Premium Module!', 'AWPCP'); ?></strong>
                    </span>
                </h3>

                <div class="inside">
                    <ul>
                        <li class="li_link">
                            <img style="align:left" src="<?php echo $url; ?>/resources/images/new.gif"/>
                            <a style="color:#145200;" href="http://awpcp.com/premium-modules/comments-ratings-module/?ref=panel" target="_blank">
                                <?php _e('Comments/Ratings Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <img style="align:left" src="<?php echo $url; ?>/resources/images/new.gif"/>
                            <a style="color:#145200;" href="http://www.awpcp.com/premium-modules/authorizenet-payment-module/?ref=panel" target="_blank">
                                <?php _e('Authorize.Net Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <img style="align:left" src="<?php echo $url; ?>/resources/images/new.gif"/>
                            <a style="color:#145200;" href="http://www.awpcp.com/premium-modules/paypalpro-payment-module/?ref=panel" target="_blank">
                                <?php _e('PayPal Pro Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://www.awpcp.com/premium-modules/coupon-module/?ref=panel" target="_blank">
                                <?php _e('Coupon/Discount Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://www.awpcp.com/premium-modules/subscriptions-module/?ref=panel" target="_blank">
                                <?php _e('Subscriptions Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://www.awpcp.com/premium-modules/fee-per-category-module/?ref=panel" target="_blank">
                                <?php _e('Fee Per Category Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://www.awpcp.com/premium-modules/featured-ads-module/?ref=panel" target="_blank">
                                <?php _e('Featured Ads Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://www.awpcp.com/premium-modules/extra-fields-module/?ref=panel" target="_blank">
                                <?php _e('Extra Fields Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://www.awpcp.com/premium-modules/category-icons-module/?ref=panel" target="_blank">
                                <?php _e('Category Icons Premium Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://www.awpcp.com/premium-modules/regions-control-module/?ref=panel" target="_blank">
                                <?php _e('Regions Control Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://www.awpcp.com/premium-modules/google-checkout-module/?ref=panel" target="_blank">
                                <?php _e('Google Checkout Payment Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://www.awpcp.com/premium-modules/rss-module/?ref=panel" target="_blank">
                                <?php _e('RSS Module', 'AWPCP'); ?>
                            </a>
                        </li>
                        <li class="li_link">
                            <a style="color:#145200;" href="http://www.awpcp.com/donate/?ref=panel" target="_blank">
                                <?php _e('Donate to Support AWPCP', 'AWPCP'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="postbox">
                <h3 class="hndle1"><span><?php _e('Found a bug?', 'AWPCP'); ?> &nbsp; <?php _e('Need Support?', 'AWPCP'); ?></span></h3>
                <?php $tpl = '<a href="%s" target="_blank">%s</a>'; ?>
                <div class="inside">
                    <ul>
                        <?php $link = sprintf($tpl, 'http://www.awpcp.com/quick-start-guide', __('Quick Start Guide', 'AWPCP')); ?>
                        <li><?php echo sprintf(_x('Browse the %s.', 'Browse the <a>Quick Start Guide</a>', 'AWPCP'), $link); ?></li>
                        <?php $link = sprintf($tpl, 'http://awpcp.com/docs', __('Documentation', 'AWPCP')); ?>
                        <li><?php echo sprintf(_x('Read the full %s.', 'Read the full <a>Documentation</a>', 'AWPCP'), $link); ?></li>
                        <?php $link = sprintf($tpl, 'http://www.awpcp.com/forum', __('visit the forums!', 'AWPCP')); ?>
                        <li><?php echo sprintf(_x('Report bugs or get more help: %s.', 'Report bugs or get more help: <a>visit the forums!</a>', 'AWPCP'), $link); ?></li>
                    </ul>
                </div>
            </div>

            <div class="postbox">
                <h3 class="hndle1"><span><?php _e("Premium Modules","AWPCP"); ?></span></h3>
                <div class="inside">

                    <h4><?php _e("Installed", "AWPCP"); ?></h4>

                    <?php if (count($modules['premium']['installed']) == 0): ?>

                    <p><?php _e( 'No premium modules installed.', 'AWPCP' ); ?></p>

                    <?php else: ?>

                    <ul>
                    <?php foreach ($modules['premium']['installed'] as $module): ?>
                        <li><?php echo $module['name']; ?></li>
                    <?php endforeach; ?>
                    </ul>

                    <?php endif; ?>


                    <h4><?php _e("Not Installed", "AWPCP"); ?></h4>

                    <?php if (count($modules['premium']['not-installed']) == 0): ?>

                    <p><?php _e("All premium modules installed!", "AWPCP"); ?></p>

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
                <h3 class="hndle1"><span><?php __("Other Modules","AWPCP"); ?></span></h3>

                <div class="inside">

                    <h4><?php _e("Installed", "AWPCP"); ?><h4>

                    <?php if (count($modules['other']['installed']) == 0): ?>

                    <p><?php __("No other modules installed", "AWPCP"); ?></p>

                    <?php else: ?>

                    <ul>
                    <?php foreach ($modules['other']['installed'] as $module): ?>
                        <li><?php echo $module['name']; ?></li>
                    <?php endforeach; ?>
                    </ul>

                    <?php endif; ?>


                    <h4><?php _e("Not Installed", "AWPCP"); ?><h4>

                    <?php if (count($modules['other']['not-installed']) == 0): ?>

                    <p><?php __("All other modules installed!", "AWPCP"); ?></p>

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
