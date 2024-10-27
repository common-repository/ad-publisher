<?php
defined( 'ABSPATH' ) || exit;

/**
 * Класс реализует функционал пользовательской части плагина
 * @copyright (c) 2018, ArtStudio
 * @version 1.0
 */
class ADP_Frontend extends ADP_Base {
	
	/**
	 * Инициализация пользовательской части
	 */
	public function __construct() {
		add_filter( 'the_content', array( $this, 'check_adverts_required' ) );
	}
	
	/**
	 * Проверка необходимости выводить рекламмный код
	 * Используется в качестве фильтра the_content
	 *
	 * @param string $content содержимое записи
	 *
	 * @return string $content содержимое записи
	 */
	public function check_adverts_required( $content ) {
		global $post;
		
		// Settings
		$settings = get_option( 'adp-settings' );
		if ( ! is_array( $settings ) ) {
			return $content;
		}
		if ( count( $settings ) == 0 ) {
			return $content;
		}
		
		// Check if we are on a singular post type that's enabled
		foreach ( $settings as $post_type => $enabled ) {
			if ( is_singular( $post_type ) ) {
				// Check the post hasn't disabled adverts
				$disable = get_post_meta( $post->ID, '_adp_disable_ads', true );
				if ( ! $disable ) {
					return $this->insert_ads( $content );
				}
			}
		}
		
		return $content;
	}
	
	/**
	 * Вставка рекламмных блоков
	 *
	 * @param string $content содержимое записи
	 *
	 * @return string $content содержимое записи
	 */
	public function insert_ads( $content ) {
		
		$idx = 0;
		
		$ads = get_posts( array(
			'post_type'      => $this->app()->get_post_type(),
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		) );
		
		$count_all_blocks = sizeof( $ads );
		
		if ( $ads ) {
			foreach ( $ads as $adp_post ) {
				
				$ad_code = get_post_meta( $adp_post->ID, '_adp_code', true );
				$ad_code = apply_filters( 'adp/frontend/ad_code', $ad_code, $adp_post->ID, $idx, $count_all_blocks );
				
				$adp_position_before = get_post_meta( $adp_post->ID, '_adp_position_before', true );
				$adp_position_after  = get_post_meta( $adp_post->ID, '_adp_position_after', true );
				$adp_position_in     = get_post_meta( $adp_post->ID, '_adp_position_in', true );
				
				if ( ! $adp_position_in and ! $adp_position_before and ! $adp_position_after ) {
					return $content;
				}
				
				$adp_display_mobile  = get_post_meta( $adp_post->ID, '_adp_display_mobile', true );
				$adp_display_desktop = get_post_meta( $adp_post->ID, '_adp_display_desktop', true );
				$display             = true;
				
				// если только для телефонов или только для кампов
				if ( ( ! $adp_display_mobile and $adp_display_desktop ) or ( $adp_display_mobile and ! $adp_display_desktop ) ) {
					include_once ADP_PLUGIN_PATH . '/includes/classes/class-mobile-detect.php';
					$detect = new ADP_Mobile_Detect;
					if ( $detect->isMobile() ) {
						$display = $adp_display_mobile ? true : false;
					} else {
						$display = $adp_display_desktop ? true : false;
					}
				}
				
				if ( $adp_position_in and apply_filters( 'adp/frontend/display', $display, 'paragraph' ) ) {
					$paragraph_number = get_post_meta( $adp_post->ID, '_adp_paragraph_number', true );
					$content          = $this->insert_ad_after_paragraph( $ad_code, $paragraph_number, $content );
				}
				
				if ( $adp_position_before and apply_filters( 'adp/frontend/display', $display, 'before' ) ) {
					$content = $ad_code . $content;
				}
				
				if ( $adp_position_after and apply_filters( 'adp/frontend/display', $display, 'after' ) ) {
					$content = $content . $ad_code;
				}
				
				$idx ++;
			}
			$content = apply_filters( 'adp/frontend/content', $content );
		}
		
		return $content;
	}
	
	/**
	 * Вставка рекламмного блока после определённого параграфа
	 *
	 * @param string $insertion рекламмный код
	 * @param int $paragraph_id номер параграфа
	 * @param string $content содержимое записи
	 *
	 * @return string $content содержимое записи
	 */
	public function insert_ad_after_paragraph( $insertion, $paragraph_id, $content ) {
		$closing_p  = '</p>';
		$paragraphs = explode( $closing_p, $content );
		$settings   = get_option( 'adp-settings' );
		foreach ( $paragraphs as $index => $paragraph ) {
			// Only add closing tag to non-empty paragraphs
			if ( trim( $paragraph ) ) {
				// Adding closing markup now, rather than at implode, means insertion
				// is outside of the paragraph markup, and not just inside of it.
				$paragraphs[ $index ] .= $closing_p;
			}
			
			// + 1 allows for considering the first paragraph as #1, not #0.
			if ( $paragraph_id == $index + 1 ) {
				$ads                  = '<div ' . ( isset( $settings['css'] ) ? '' : ' style="clear:both;float:left;width:100%;margin:0 0 20px 0;"' ) . '>' . $insertion . '</div>';
				$paragraphs[ $index ] .= $ads;
			}
		}
		
		return implode( '', $paragraphs );
	}
	
}
