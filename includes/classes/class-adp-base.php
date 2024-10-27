<?php
	
	defined( 'ABSPATH' ) || exit;
	
	/**
	 * Базовый класс для создания частей системы
	 * @copyright (c) 2018, ArtStudio
	 * @version 1.0
	 */
	class ADP_Base {
		
		/**
		 * Возвращает инстанс основного плагина
		 *
		 * @return object ADP_Plugin
		 */
		public function app() {
			return ADP_Plugin::instance();
		}
	}
