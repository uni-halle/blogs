<?php echo _x( 'Hello,', 'listing media uploaded notification', 'another-wordpress-classifieds-plugin' ) . PHP_EOL ?>
<?php if ( $other_attachments ): ?>

<?php
    $message = _x( 'The following media files were recently uploaded to listing "<listing-title>":', 'listing media uploaded notification', 'another-wordpress-classifieds-plugin' );
    echo str_replace( '<listing-title>', $listing_title, $message ) . PHP_EOL;
?>

<?php foreach ( $other_attachments as $attachment ): ?>
- <?php echo $attachment->name . PHP_EOL; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php if ( $attachments_awaiting_approval ): ?>

<?php
    $message = _x( 'The following media files were recently uploaded to listing "<listing-title>" and are awaiting approval:', 'listing media uploaded notification', 'another-wordpress-classifieds-plugin' );
    echo str_replace( '<listing-title>', $listing_title, $message ) . PHP_EOL;
?>

<?php foreach ( $attachments_awaiting_approval as $attachment ): ?>
- <?php echo $attachment->name . PHP_EOL; ?>
<?php endforeach; ?>
<?php endif; ?>

<?php
    $message = _x( 'Click here to manage media uploaded to the listing: <manage-listing-media-url>.', 'listing media uploaded notification', 'another-wordpress-classifieds-plugin' );
    echo str_replace( '<manage-listing-media-url>', $manage_listing_media_url, $message ) . PHP_EOL;
?>

<?php
    $message = _x( 'Click here to view the listing: <view-listing-url>.', 'listing media uploaded notification', 'another-wordpress-classifieds-plugin' );
    echo str_replace( '<view-listing-url>', $view_listing_url, $message ) . PHP_EOL;
?>

<?php echo awpcp_get_blog_name() . PHP_EOL; ?>
<?php echo home_url() . PHP_EOL; ?>
