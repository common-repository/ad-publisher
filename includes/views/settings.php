<?php
	$screen = get_current_screen();
	$adp = ADP_Plugin::instance();
	$screens = array(
		'edit-' . $adp->get_post_type(),
	)
?>

<?php if ( in_array( $screen->id, $screens ) ) : ?>
	<?php if ( isset( $_GET['adp_settings_updated'] ) ) : ?>
		<div class="updated notice notice-success is-dismissible">
			<p><?php _e( 'Settings succesfully updated', 'ad-publisher'); ?></p>
		</div>
	<?php endif; ?>
	
	<div class="notice adp-notice"> 
		<div id="poststuff">
			<form class="adp-form" action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">

				<div class="adp-section">
					<div class="adp-section__heading"><?php _e( 'Settings', 'ad-publisher'); ?></div>
					<div class="adp-section__inner">

					<div class="adp-row">
						<div class="adp-row__title"><?php _e( 'Display the advert', 'ad-publisher' ) ;?>:</div>
						<div class="adp-row__content">

						<div class="adp-option">

							<div class="adp-checkboxs-wrap">
								<?php
									$post_types = get_post_types( array(
										'public' => true,
									), 'objects' );
									$settings = get_option( 'adp-settings' );
									if ( $post_types ) {
										foreach ( $post_types as $post_type ) {
											if ( 'attachment' == $post_type->name ) {
												continue;
											}
											?>
											<label class="adp-checkbox">
												<input class="adp-checkbox__input  visually-hidden" name="adp-settings[<?php echo esc_attr( $post_type->name ); ?>]" value="1" type="checkbox" <?php checked( true, isset( $settings[ $post_type->name ] ) ); ?> />
												<span class="adp-checkbox__indicator"></span>
												<?php echo esc_attr( $post_type->labels->name ); ?>
											</label>
											<?php
										}
									}
								?>

								<label class="adp-checkbox">
									<input class="adp-checkbox__input  visually-hidden" type="checkbox" name="adp-settings[css]" value="1" <?php checked( true, isset( $settings['css'] ) ); ?> />
									<span class="adp-checkbox__indicator"></span>
									<?php _e( 'Exclude CSS', 'ad-publisher' ) ;?>
								</label>
							</div><!-- .adp-checkboxs-wrap -->

						</div><!-- .adp-option -->

						</div><!-- .adp-row__content -->
					</div><!-- .adp-row -->

					<div class="adp-section__info"><?php _e( 'By default, Post Ads are wrapped in a container that has some CSS to aid layout. Developers may wish to use their own CSS, and should check this Exclude CSS option.', 'ad-publisher' ); ?></div>

					</div><!-- .adp-section__inner -->
				</div><!-- .adp-section -->
				<input type="hidden" name="_nonce" value="<?php echo wp_create_nonce( 'adp' ); ?>" />
				<input type="hidden" name="action" value="adp_save_settings">
				<button id="set-submit" name="submit" class="adp-button  adp-form__button" type="submit"><?php _e( 'Save Settings', 'ad-publisher' ); ?></button>
			</form><!-- .adp-form -->
		</div>
	</div>
<?php endif; ?>
