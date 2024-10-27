<?php
	defined( 'ABSPATH' ) || exit;
	
	/**
	 * Класс реализует функционал админской части плагина
	 
	 * @copyright (c) 2018, ArtStudio
	 * @version 1.0
	 */
	class ADP_Backend extends ADP_Base {
		
		/**
		 * Инициализация админской части
		 */
		public function __construct() {
			add_action( 'init', array( $this, 'register_post_types' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'admin_post_adp_save_settings', array( $this, 'save_settings' ) );
			add_action( 'save_post', array( $this, 'save_adp_post' ), 10, 3 );
			add_action( 'save_post', array( $this, 'save_post' ), 10, 3 );
		}
		
		/**
		 * Регистрация CPT для хранения пользовательских вставок
		 */
		public function register_post_types() {
			register_post_type( $this->app()->get_post_type(), array(
				'labels' => array(
					'name'               => __( 'AD Publisher', 'ad-publisher' ),
					'singular_name'      => __( 'AD Publisher', 'ad-publisher' ),
					'add_new'            => __( 'Add New Adv', 'ad-publisher' ),
					'add_new_item'       => __( 'Add New Adv', 'ad-publisher' ),
					'edit_item'          => __( 'Edit Adv', 'ad-publisher' ),
					'new_item'           => __( 'New Adv', 'ad-publisher' ),
					'view_item'          => __( 'View Adv', 'ad-publisher' ),
					'search_items'       => __( 'Search Adv', 'ad-publisher' ),
					'not_found'          => __( 'No adv found', 'ad-publisher' ),
					'not_found_in_trash' => __( 'No adv found in Trash', 'ad-publisher' ),
					'parent_item_colon'  => ''
				),
				'description'         => __( 'AD Publisher', 'ad-publisher' ),
				'public'              => false,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_position'       => 100,
				'menu_icon'           => ADP_PLUGIN_URL . '/assets/images/icon.png',
				'capability_type'     => 'post',
				'hierarchical'        => false,
				'has_archive'         => false,
				'show_in_nav_menus'   => false,
				'supports'            => array( 'title' ),
			));
		}
		
		/**
		 * Подключение скриптов и стилей
		 */
		public function admin_scripts( $hook ) {
			if ( ! is_admin() ) {
				return;
			}
			$screen = get_current_screen();
			if ( $screen->post_type == $this->app()->get_post_type() ) {
				wp_enqueue_style( 'adp-admin', ADP_PLUGIN_URL . 'assets/css/admin.css' );
				wp_enqueue_script( 'adp-admin', ADP_PLUGIN_URL . 'assets/js/admin.js', array( 'jquery' ) );
			}
			if ( 'adpublisher_page_adp_modules' == $hook ) {
				wp_enqueue_style( 'adp-bulma', ADP_PLUGIN_URL . 'assets/css/bulma.min.css' );
			}
		}
		
		/**
		 * Админ меню, метабоксы и уведомления
		 */
		public function admin_menu() {
			$menu_key = 'edit.php?post_type=' . $this->app()->get_post_type();
			global $submenu;
			if ( isset( $submenu[ $menu_key ] ) ) {
				// Меняем название первого субменю со стандартного на своё
				$submenu[ $menu_key ][5][0] = __( 'Settings', 'ad-publisher' );
			}
			add_action( 'admin_notices', array( $this, 'display_settings' ) );
			add_meta_box( 'adp_meta', __( 'New Advert', 'ad-publisher' ), array( $this, 'display_meta_box' ), $this->app()->get_post_type(), 'normal', 'high' );
			$post_types = get_post_types( array(
				'public' => true,
			), 'objects' );
			if ( $post_types ) {
				foreach ( $post_types as $post_type ) {
					// Skip attachments
					if ( $post_type->name == 'attachment' ) {
						continue;
					}

					// Skip our CPT
					if ( $post_type->name == $this->app()->get_post_type() ) {
						continue;
					}
					add_meta_box( 'adp_meta', __( 'AD Publisher', 'ad-publisher' ), array( $this, 'display_post_options_metabox' ), $post_type->name, 'normal', 'high' );
				}
			}
		}
		
		/**
		 * Вывод содержимого страницы настроек
		 */
		public function display_settings() {	
			include_once ADP_PLUGIN_PATH . '/includes/views/head.php';
			include_once ADP_PLUGIN_PATH . '/includes/views/settings.php';
		}
		
		/**
		 * Сохранение настроек
		 */
		public function save_settings() {
			if ( ! wp_verify_nonce( $_REQUEST['_nonce'], 'adp' ) ) {
				die();
			}
			update_option( 'adp-settings', $_POST[ 'adp-settings' ] );
			wp_redirect( admin_url( 'edit.php?post_type=' . $this->app()->get_post_type() . '&adp_settings_updated=1' ) );
			die();
		}
		
		/**
		 * Вывод содержимого метабокса для записей
		 */
		public function display_meta_box( $post ) {
			include_once ADP_PLUGIN_PATH . '/includes/views/adp-post-metabox.php';
		}
		
		/**
		 * Сохранение пользовательских вставок и их настроек
		 */
		public function save_adp_post( $post_id, $post, $update ) {
			// Check if our nonce is set.
			if ( ! isset( $_REQUEST[ 'adp_nonce'] ) ) {
				return $post_id;
			}
			if ( ! isset( $_REQUEST[ 'post_type'] ) or $_REQUEST[ 'post_type' ] != $this->app()->get_post_type() ) {
				return $post_id;
			}

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $_REQUEST['adp_nonce'], 'adp' ) ) {
				return $post_id;
			}

			// Check the logged in user has permission to edit this post
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
			
			// настройки показа
			if ( isset( $_REQUEST['adp_code'] ) ) {
				update_post_meta( $post_id, '_adp_code', $_REQUEST['adp_code'] );
			}
			if ( isset( $_REQUEST['adp_unit'] ) ) {
				update_post_meta( $post_id, '_adp_unit', $_REQUEST['adp_unit'] );
			}
			
			$adp_position_before  = isset( $_REQUEST['adp_position_before'] ) ? 1 : 0;
			$adp_position_after   = isset( $_REQUEST['adp_position_after'] ) ? 1 : 0;
			$adp_position_in      = isset( $_REQUEST['adp_position_in'] ) ? 1 : 0;
			$adp_paragraph_number = isset( $_REQUEST['adp_paragraph_number'] ) ? intval( $_REQUEST['adp_paragraph_number'] ) : 3;
			$adp_display_desktop  = isset( $_REQUEST['adp_display_desktop'] ) ? 1 : 0;
			$adp_display_mobile   = isset( $_REQUEST['adp_display_mobile'] ) ? 1 : 0;
			
			update_post_meta( $post_id, '_adp_position_before', $adp_position_before );
			update_post_meta( $post_id, '_adp_position_after', $adp_position_after );
			update_post_meta( $post_id, '_adp_position_in', $adp_position_in );
			update_post_meta( $post_id, '_adp_paragraph_number', $adp_paragraph_number );
			update_post_meta( $post_id, '_adp_display_desktop', $adp_display_desktop );
			update_post_meta( $post_id, '_adp_display_mobile', $adp_display_mobile );
			
			do_action( 'adp/backend/save_adp_post', $post_id );
		}
		
		/**
		 * Хук на событие сохранения записи
		 */
		public function save_post( $post_id, $post, $update ) {
			// Check if our nonce is set.
			if ( ! isset( $_REQUEST[ 'adp_nonce'] ) ) {
				return $post_id;
			}

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $_REQUEST['adp_nonce'], 'adp' ) ) {
				return $post_id;
			}
			
			// Check the logged in user has permission to edit this post
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
			
			// для отключения показа на конкретном посте 
			if ( isset( $_REQUEST['adp_disable_ads'] ) ) {
				update_post_meta( $post_id, '_adp_disable_ads', sanitize_text_field( $_REQUEST['adp_disable_ads'] ) );
			} else {
				delete_post_meta( $post_id, '_adp_disable_ads' );
			}
			
		}
		
		/**
		 * Вывод метабокса для записей
		 * В нём можно отключить вывод рекламмы для текущей записи
		 */
		public function display_post_options_metabox( $post ) {
			// Get meta
			$disable = get_post_meta( $post->ID, '_adp_disable_ads', true );

			// Nonce field
			wp_nonce_field( 'adp', 'adp_nonce' );
			?>
			<p>
				<label for="adp_disable_ads"><?php _e( 'Disable Adverts', 'ad-publisher' ); ?></label>
				<input type="checkbox" name="adp_disable_ads" id="adp_disable_ads" value="1" <?php echo ( $disable ? ' checked' : '' ); ?> />
			</p>
			<p class="description">
				<?php _e( 'Check this option if you wish to disable all Post Ads from displaying on this content.', 'ad-publisher' ); ?>
			</p>
			<?php
		}
	}
