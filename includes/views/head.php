<?php
	$screen = get_current_screen();
	$adp = ADP_Plugin::instance();
	$screens = array(
		'edit-' . $adp->get_post_type(),
		'adpublisher_page_adp_modules',
		'adpublisher'
	)
?>
<?php if ( in_array( $screen->id, $screens ) ) : ?>
    <div class="notice adp-notice">
		<div class="adp-header">
		<div class="adp-header__logo">adpublisher</div>
		<button href="<?php echo admin_url( 'post-new.php?post_type=adpublisher' ); ?>" class="adp-button adp-add-new-ads"><?php _e( 'Add New Ads', 'ad-publisher' ) ;?></button>
		</div><!-- .adp-header -->
	</div>
<?php endif; ?>