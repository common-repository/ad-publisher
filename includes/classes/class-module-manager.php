<?php
defined( 'ABSPATH' ) || exit;

/**
 * Класс реализует функционал дополнительных модулей
 * @copyright (c) 2018, ArtStudio
 * @version 1.0
 */
class ADP_Module_Manager extends ADP_Base {
	
	protected $modules = array();
	
	/**
	 * Инициализация модулей
	 */
	public function __construct() {
		$this->init();
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wp_ajax_adp_toggle_module', array( $this, 'ajax_toggle_module' ) );
	}
	
	public function ajax_toggle_module() {
		check_ajax_referer( 'adp', 'wpnonce' );
		
		$module_slug = sanitize_title( $_POST['module'] );
		$modules     = $this->get_modules_list();
		
		if ( ! isset( $modules[ $module_slug ] ) ) {
			wp_send_json_error( array( 'msg' => __( 'Модуль не найден', 'ad-publisher' ) ) );
		}
		$module      = $modules[ $module_slug ];
		$all_plugins = get_plugins();
		
		do_action( 'adp/modules/before_activate', $module_slug, $modules );
		
		if ( isset( $all_plugins[ $module['basename'] ] ) ) {
			// если плагин есть в папке
			if ( is_plugin_active( $module['basename'] ) ) {
				// если плагин активен - деактивируем
				deactivate_plugins( $module['basename'] );
				wp_send_json_success( array( 'msg' => __( 'Модуль успешно деактивирован', 'ad-publisher' ), 'active' => 0 ) );
			} else {
				
				// если плагин не активен - активируем
				$result = activate_plugin( $module['basename'] );
				
				if ( is_wp_error( $result ) ) {
					wp_send_json_error( array( 'msg' => __( 'Ошибка при активации плагина', 'ad-publisher' ) ) );
				} else {
					do_action( 'adp/modules/after_activate', $module_slug, $modules );
					
					wp_send_json_success( array( 'msg' => __( 'Модуль успешно активирован', 'ad-publisher' ), 'active' => 1 ) );
				}
			}
		} else {
			// если плагина нет - устанавливаем и активируем
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/misc.php' );
			require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
			require_once( ADP_PLUGIN_PATH . '/includes/classes/class-upgrader-skin.php' );
			add_filter( 'async_update_translation', '__return_false', 1 );
			
			ob_start();
			
			$upgrader = new Plugin_Upgrader( new ADP_Upgrader_Skin );
			// установка из каталога WP
			$download_url = 'https://downloads.wordpress.org/plugin/' . dirname( $module['basename'] ) . '.latest-stable.zip';
			if ( isset( $module['download_url'] ) and $module['download_url'] ) {
				// если установлен кастомный УРЛ - устанавливаем из него
				$download_url = $module['download_url'];
			}
			$res = $upgrader->install( $download_url );
			ob_end_clean();
			
			if ( is_wp_error( $res ) ) {
				wp_send_json_error( array( 'msg' => __( 'Ошибка при установке модуля', 'ad-publisher' ) ) );
			}
			
			activate_plugin( $module['basename'] );
			
			do_action( 'adp/modules/after_activate', $module_slug, $modules );
			
			wp_send_json_success( array( 'msg' => __( 'Модуль успешно активирован', 'ad-publisher' ), 'active' => 1 ) );
		}
	}
	
	/**
	 * Добавляет страницу модулей в админ меню
	 */
	public function admin_menu() {
		add_submenu_page( 'edit.php?post_type=' . $this->app()->get_post_type(), __( 'Модули', 'ad-publisher' ), __( 'Модули', 'ad-publisher' ), 'manage_options', 'adp_modules', array(
			$this,
			'modules_page'
		) );
	}
	
	/**
	 * Выводит содержимое страницы с модулями
	 */
	public function modules_page() {
		include_once ADP_PLUGIN_PATH . '/includes/views/modules.php';
	}
	
	public function init() {
		$this->modules = apply_filters( 'adp/modules/init', array() );
	}
	
	public function get_modules_list() {
		$module_list = array(
			'smart_shortcode' => array(
				'slug'         => 'smart_shortcode',
				'name'         => 'Smart shortcode',
				'descr'        => __( 'Вставляйте рекламный или любой другой код в записи на страницы или в виджеты вашего сайта, с помощью шорткодов', 'ad-publisher' ),
				'tos_url'      => 'http://ad-publisher.com/license_agreement/',
				'img_url'      => ADP_PLUGIN_URL . '/assets/images/module-4.png',
				'basename'     => 'smart_shortcode/smart_shortcode.php',
				'download_url' => 'http://ad-publisher.com/modules/adp-smart-shortcode.zip',
				'not_free'     => true,
			),
			'ctr_booster'    => array(
				'slug'         => 'ctr_booster',
				'name'         => 'CTR Booster',
				'descr'        => __( 'Позволяет в 2-3 раза увеличить число кликов по рекламме благодаря блокировке части статьи и подстановке рекламмного блока', 'ad-publisher' ),
				'tos_url'      => 'http://ad-publisher.com/license_agreement/',
				'img_url'      => ADP_PLUGIN_URL . '/assets/images/module-1.png',
				'basename'     => 'adp-ctr-booster/ctr-booster.php',
				'download_url' => 'http://ad-publisher.com/modules/adp-ctr-booster.zip',
			),
			'sticky_ads'    => array(
				'slug'         => 'sticky_ads',
				'name'         => 'Sticky ads',
				'descr'        => __( 'Увеличивает число кликов на рекламу благодаря прилипающему к верхней части экрана рекламному блоку', 'ad-publisher' ),
				'tos_url'      => 'http://ad-publisher.com/license_agreement/',
				'img_url'      => ADP_PLUGIN_URL . '/assets/images/module-2.png',
				'basename'     => 'adp-sticky-ads/sticky-ads.php',
				'download_url' => 'http://ad-publisher.com/modules/adp-sticky-ads.zip',
			),
			'ads_rotator' => array(
				'slug'     => 'ads_rotator',
				'name'     => 'Ads Rotator',
				'descr'    => 'Display random ads banner on page refresh.',
				'tos_url'  => 'http://ad-publisher.com/license_agreement/',
				'img_url'  => ADP_PLUGIN_URL . '/assets/images/module-3.png',
				'basename' => 'ads_rotator/ads-rotaror.php',
				'disabled' => true,
			),
		);
		
		return $module_list;
	}
}
