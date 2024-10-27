<?php
$modules = $this->get_modules_list();
$nonce   = wp_create_nonce( 'adp' );
?>
<div class="wrap">
    <h1 class="wp-heading-inline">AD Publisher
        <a href="<?php echo admin_url( 'post-new.php?post_type=adpublisher' ); ?>" class="page-title-action"><?php _e( 'Add New Adv', 'ad-publisher' ); ?></a>
		<?php do_action( 'adp/modules/actions_panel' ); ?>
    </h1>
    <div class="notice notice-success adp-modules-notice">
    </div>
    <div id="poststuff">
        <div class="postbox adp-modules-container">



            <div class="adp-section">
                <div class="adp-section__heading"><?php _e( 'Управление модулями', 'ad-publisher' ); ?></div>
                <div class="adp-section__inner">

                <div class="adp-modules">
                    <?php foreach ( $modules as $module ) : ?>
                        <?php
                            $is_active = is_plugin_active( $module['basename'] );
                            $is_disabled = isset( $module['disabled'] ) ? true : false;
                            $is_not_free = isset( $module['not_free'] ) ? true : false;
                        ?>
                        <div class="adp-module">
                            <div class="adp-module__title"><?php echo esc_attr( $module['name'] ); ?></div>
                            <?php if ( ! $is_disabled && ! $is_not_free ) : ?>
                                <div class="adp-module__free"><?php _e( '6 months', 'ad-publisher' ); ?> <span><?php _e( 'FREE', 'ad-publisher' ); ?></span></div>
                            <?php endif; ?>
                            <div class="adp-module__image">
                                <img src="<?php echo esc_attr( $module['img_url'] ); ?>"  alt="" />
                            </div>
                            <div class="adp-module__info"><?php echo esc_attr( $module['descr'] ); ?></div>
                            <div class="adp-module__bottom">
                                <label class="adp-checkbox  adp-module__checkbox">
                                <input class="adp-checkbox__input  visually-hidden adp-checkbox-tos" type="checkbox" <?php checked( true, $is_active ); ?> />
                                <span class="adp-checkbox__indicator"></span>
                                <?php _e( 'Согласен', 'ad-publisher' ); ?><br />
                                <a class="adp-module__terms" href="<?php echo esc_attr( $module['tos_url'] ); ?>"><?php _e( 'условия использования', 'ad-publisher' ); ?></a>
                                </label>
                                <button type="button" class="adp-button  adp-module__button <?php if ( $is_active ) : ?>adp-button--green<?php else : ?>adp-button--blue<?php endif; ?> <?php if ( ! $is_disabled ) : ?>adp-toggle-module<?php endif; ?>" <?php if ( $is_disabled ) : ?>disabled<?php endif; ?> data-state="<?php if ( $is_active ) : ?>1<?php else : ?>0<?php endif; ?>" data-nonce="<?php echo $nonce; ?>" data-slug="<?php echo esc_attr( $module['slug'] ); ?>" data-deactivate="<?php _e( 'Deactivate', 'ad-publisher' ); ?>" data-activate="<?php _e( 'Activate', 'ad-publisher' ); ?>"><?php if ( $is_active ) : ?><?php _e( 'Dectivate', 'ad-publisher' ); ?><?php else : ?><?php _e( 'Activate', 'ad-publisher' ); ?><?php endif; ?></button>
                                <div class="adp-loader">
                                    <img width="50" src="<?php echo ADP_PLUGIN_URL . 'assets/images/loader.gif'; ?>">
                                </div>
                            </div><!-- .adp-module__bottom -->
                        </div><!-- .adp-module -->
                    <?php endforeach; ?>
                </div><!-- .adp-modules -->

                </div><!-- .adp-section__inner -->
            </div><!-- .adp-section -->


        </div>
        <!-- /postbox -->
        <p>
            <input type="button" class="adp-btn green" value="<?php _e( 'Deactivate all', 'ad-publisher' ); ?>"/>
        </p>
    </div>
</div>
