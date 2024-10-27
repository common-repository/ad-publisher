<?php
	defined( 'ABSPATH' ) || exit;
	
	/**
	 * Основной класс плагина
	 
	 * @copyright (c) 2018, ArtStudio
	 * @version 1.0
	 */
	final class ADP_Plugin {
		
		/**
		 * @var object singleton instance
		 */
		private static $_instance = null;
		
		/**
		 * @var string название CPT для хранения пользовательский вставок
		 */
		private $post_type = 'adpublisher';
		
		/**
		 * @var object дополнительные модули
		 */
		private $module_manager;
		
		/**
		 * @var object админская часть
		 */
		private $backend;
		
		/**
		 * @var object пользовательская часть
		 */
		private $frontent;
		
		/**
		 * Ограничивает клонирование объекта
		 *
		 * @return void
		 */
		protected function __clone() {
			
		}
		
		/**
		 * Ограничивает создание другого экземпляра класса через сериализацию
		 *
		 * @return void
		 */
		protected function __wakeup() {
			
		}
		
		/**
		 * Возвращает единственный экземпляр класса.
		 *
		 * @return ADP_Plugin
		 */
		static public function instance() {
			if( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}
		
		/**
		 * Инициализация плагина
		 */
		private function __construct() {
			$this->include_scripts();
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action( 'init', array( $this, 'textdomain' ) );
		}
		
		/**
		 * Подключение классов
		 */
		protected function include_scripts() {
			include_once ADP_PLUGIN_PATH . '/includes/classes/class-adp-base.php';
			include_once ADP_PLUGIN_PATH . '/includes/classes/class-backend.php';
			include_once ADP_PLUGIN_PATH . '/includes/classes/class-frontend.php';
			include_once ADP_PLUGIN_PATH . '/includes/classes/class-module-manager.php';
		}

		/**
		 * Подключение локализации
		 *
		 * @return void
		 */
		public function textdomain() {
			load_plugin_textdomain( 'ad-publisher', false, basename( ADP_PLUGIN_PATH ) . '/languages' ); 
		}
		
		/**
		 * Инициализация частей системы
		 */
		public function init() {		
			$this->backend  = new ADP_Backend;
			$this->frontend = new ADP_Frontend;
			$this->module_manager  = new ADP_Module_Manager;
		}
		
		/**
		 * Возвращает название CPT, в которой сохраняются пользовательские вставки
		 * 
		 * @return string $post_type Название CPT
		 */
		public function get_post_type() {
			return $this->post_type;
		} 
	}
