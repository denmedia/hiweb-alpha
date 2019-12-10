<?php

	namespace {


		use hiweb\fields\types\map_yandex\field;


		if( !function_exists( 'add_field_map_yandex' ) ){
			/**
			 * @param $id
			 * @return field
			 */
			function add_field_map_yandex( $id ){
				$new_field = new field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\map_yandex {


		use hiweb\js;
		use hiweb\strings;


		class field extends \hiweb\fields\field{

			public function __construct( $id = null ){
				parent::__construct( $id );
				$this->INPUT()->attributes['type'] = 'map_yandex';
				$this->INPUT()->attributes['class'] = 'hiweb-field-map_yandex';
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			private static $api_key = '';


			private function get_api_key(){
				if(self::$api_key == '') {
					self::$api_key = apply_filters( '\hiweb\fields\types\map_yandex\input::get_api_key-set', '', $this );
				}
				return trim(apply_filters( '\hiweb\fields\types\map_yandex\input::get_api_key', self::$api_key, $this ));
			}


			/**
			 * @return bool
			 */
			private function is_api_key_exists(){
				return $this->get_api_key() != '';
			}


			public function html(){
				\hiweb\css( HIWEB_URL_CSS . '/field-map_yandex.css' );
				js::add( 'https://api-maps.yandex.ru/2.1/?load=package.standard&lang=ru-RU'.($this->is_api_key_exists() ? '&apikey='.$this->get_api_key() : '') );
				js::add( HIWEB_URL_JS . '/field-map_yandex.min.js', [ 'jquery' ] );
				ob_start();
				$rand_id = strings::rand();
				?>
				<div class="hiweb-field-map-yandex" id="<?= $rand_id ?>" style="min-height: 400px">
					<input type="hidden" name="<?= $this->name() ?>[]" value="<?= $this->VALUE()->get()[0] ?>" data-long/>
					<input type="hidden" name="<?= $this->name() ?>[]" value="<?= $this->VALUE()->get()[1] ?>" data-lat/>
					<input type="hidden" name="<?= $this->name() ?>[]" value="<?= $this->VALUE()->get()[2] ?>" data-zoom/>
					<div class="hiweb-field-map-yandex-place" id="<?= $rand_id ?>-map"></div>
				</div>
				<?php
				return ob_get_clean();
			}

		}
	}