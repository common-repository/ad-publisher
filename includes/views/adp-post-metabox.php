<?php

// Get meta
$post_id = $post->ID;
$ad_code = get_post_meta( $post_id, '_adp_code', true );
$ad_unit = get_post_meta( $post_id, '_adp_unit', true );
$ad_position_before = get_post_meta( $post_id, '_adp_position_before', true );
$ad_position_after = get_post_meta( $post_id, '_adp_position_after', true );
$ad_position_in = get_post_meta( $post_id, '_adp_position_in', true );
$paragraph_number = get_post_meta( $post_id, '_adp_paragraph_number', true );
$paragraph_number = $paragraph_number ? $paragraph_number : 3;

$display_mobile = get_post_meta( $post_id, '_adp_display_mobile', true );
$display_desktop = get_post_meta( $post_id, '_adp_display_desktop', true );

$adp_module_strbooster = get_post_meta( $post_id, '_adp_module_strbooster', true );
$adp_strbooster_btn = get_post_meta( $post_id, '_adp_strbooster_btn', true );
$adp_module_sticky_ads = get_post_meta( $post_id, '_adp_module_sticky_ads', true );
$adp_sticky_block_height = get_post_meta( $post_id, '_adp_sticky_block_height', true );
if ( ! $adp_sticky_block_height ) {
	$adp_sticky_block_height = 600;
}

// Nonce field
wp_nonce_field( 'adp', 'adp_nonce' );
?>









	<div class="adp-row">
	<label class="adp-row__title" for="adv-title"><?php _e( 'Advert Title:', 'ad-publisher' ); ?></label>
	<div class="adp-row__content">
		<input class="adp-row__input" type="text" name="post_title" id="adv-title" value="<?php echo esc_attr( $post->post_title ); ?>"/>
	</div><!-- .adp-row__content -->
	</div><!-- .adp-row -->

	<div class="adp-row">
	<label class="adp-row__title" for="adv-code"><?php _e( 'Advert code:', 'ad-publisher' ); ?></label>
	<div class="adp-row__content">
		<textarea class="adp-row__input" name="adp_code" id="adp_code" rows="5"><?php echo esc_html( wp_unslash( $ad_code ) ); ?></textarea>
	</div><!-- .adp-row__content -->
	</div><!-- .adp-row -->

	<div class="adp-row">
	<div class="adp-row__title"><?php _e( 'Display the advert:', 'ad-publisher' ); ?></div>
	<div class="adp-row__content">

		<div class="adp-option">

		<label class="adp-checkbox">
			<input class="adp-checkbox__input  visually-hidden" type="checkbox" name="adp_position_before" value="1" <?php checked( 1, $ad_position_before ); ?> />
			<span class="adp-checkbox__indicator"></span>
			<?php _e( 'Before content', 'ad-publisher' ) ;?>
		</label>

		<div class="adp-option__info"><?php _e( 'Рекламный блок будет размещен сразу после заголовка статьи.', 'ad-publisher' ) ;?></div>

		</div><!-- .adp-option -->

		<div class="adp-option">

		<label class="adp-checkbox">
			<input class="adp-checkbox__input  visually-hidden" type="checkbox" name="adp_position_after" value="1" <?php checked( 1, $ad_position_after ); ?> />
			<span class="adp-checkbox__indicator"></span>
			<?php _e( 'After content', 'ad-publisher' ) ;?>
		</label>

		<div class="adp-option__info"><?php _e( 'Рекламный блок будет размещен сразу после последнего параграфа статьи.', 'ad-publisher' ) ;?></div>

		</div><!-- .adp-option -->

		<div class="adp-option">

		<div class="adp-option__inner">
			<label class="adp-checkbox">
			<input class="adp-checkbox__input  visually-hidden" type="checkbox" name="adp_position_in" value="1" <?php checked( 1, $ad_position_in ); ?>/>
			<span class="adp-checkbox__indicator"></span>
			<?php _e( 'After paragraph number', 'ad-publisher' ) ;?>
			</label>

			<input class="adp-row__input  adp-row__input--number" type="number" name="adp_paragraph_number" value="<?php echo esc_attr( $paragraph_number ); ?>" />
		</div><!-- .adp-option__inner -->

		<div class="adp-option__info"><?php _e( 'Рекламный блок будет размещен после указанного параграфа.', 'ad-publisher' ) ;?></div>

		</div><!-- .adp-option -->

		<div class="adp-option">

		<div class="adp-checkboxs">
			<label class="adp-checkbox">
			<input class="adp-checkbox__input  visually-hidden" type="checkbox" name="adp_display_desktop" value="1" <?php checked( 1, $display_desktop ); ?> />
			<span class="adp-checkbox__indicator"></span>
			<?php _e( 'Desktop', 'ad-publisher' ) ;?>
			</label>

			<label class="adp-checkbox">
			<input class="adp-checkbox__input  visually-hidden" type="checkbox" name="adp_display_mobile" value="1" <?php checked( 1, $display_mobile ); ?> />
			<span class="adp-checkbox__indicator"></span>
			<?php _e( 'Mobile', 'ad-publisher' ) ;?>
			</label>
		</div><!-- .adp-checkboxs -->

		<div class="adp-option__info">
		<?php _e( 'Выберите тип устройств на котрых будет показываться рекламный блок.', 'ad-publisher' ) ;?><br />
		<?php _e( 'Если отметить оба типа, рекламный блок будет показываться и на компьютерах и на мобильных устройствах.', 'ad-publisher' ) ;?>
		</div>

		</div><!-- .adp-option -->

	</div><!-- .adp-row__content -->
	</div><!-- .adp-row -->

	<div class="adp-row">
	<?php //do_action( 'adp/view/settings_metabox', $post_id ); ?>
	<div class="adp-row__title">
	<?php _e( 'CTR tools', 'ad-publisher' ) ;?>:
		<div class="adp-row__title-note">(<?php _e( 'only mobile', 'ad-publisher' ) ;?>)</div>
	</div>
	<div class="adp-row__content">

		<div class="adp-option">
        <div class="adp-option__inner">
            <label class="adp-checkbox  adp-checkbox--premium">
                <input class="adp-checkbox__input  visually-hidden" type="checkbox" name="adp_module_strbooster" value="1" <?php checked( 1, $adp_module_strbooster ); ?> />
                <span class="adp-checkbox__indicator"></span>
                <?php _e( 'STR Booster (premium)', 'ad-publisher' ) ;?>
            </label>
            <label for=""><?php _e( 'Текст кнопки', 'ad-publisher' ) ;?>:</label>
            <input class="adp-row__input  adp-row__input--string" type="text"  name="adp_strbooster_btn" value="<?php echo esc_attr( $adp_strbooster_btn ); ?>" placeholder="<?php _e('Продолжить чтение', 'ad-publisher');?>"/>
        </div>

		<div class="adp-option__info">
		<?php _e( 'Увеличивает CTR рекламного блока на 200% путем перекрытия части контента и подстановки рекламного блока.<br />
			Рекомендуем не более двух STR Booster на страницу.', 'ad-publisher' ) ;?>
		</div>

		</div><!-- .adp-option -->

		<div class="adp-option">

		<div class="adp-option__inner">
			<label class="adp-checkbox  adp-checkbox--premium">
			<input class="adp-checkbox__input  visually-hidden" type="checkbox" name="adp_module_sticky_ads" value="1" <?php checked( 1, $adp_module_sticky_ads ); ?> />
			<span class="adp-checkbox__indicator"></span>
			<?php _e( 'Sticky Ads (premium)', 'ad-publisher' ) ;?>
			</label>

			<label for=""><?php _e( 'Display on', 'ad-publisher' ) ;?>:</label>
			<input class="adp-row__input  adp-row__input--number" type="number" name="adp_sticky_block_height" value="<?php echo esc_attr( $adp_sticky_block_height ); ?>" />
		</div><!-- .adp-option__inner -->

		<div class="adp-option__info">
		<?php _e( 'Прикрепляет рекламный блок в верхней части экрана на установленное количество пикселей прокрутки.<br />
			Рекомендуем установить 600 - 800 px.', 'ad-publisher' ) ;?>
		</div>

		</div><!-- .adp-option -->

	</div><!-- .adp-row__content -->
	</div><!-- .adp-row -->

	<div class="adp-section__note"><?php _e( '*Не рекомендуем сочетать инструменты STR Booster и Sticky Ads на одной странице. Подробнее о методах использования инструментов повышения CTR  на', 'ad-publisher' ) ;?> <a href="https://ad-publisher.com/">ad-publisher.com</a></div>
<button class="adp-button  adp-form__button" name="publish" value="<?php _e( 'Опубликовать', 'ad-publisher' ); ?>" type="submit"><?php _e( 'Опубликовать', 'ad-publisher' ); ?></button>

