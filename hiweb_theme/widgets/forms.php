<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 10.10.2018
	 * Time: 9:19
	 */

	namespace hiweb_theme\widgets;


	use hiweb\context;
	use hiweb\path;
	use hiweb_theme\widgets\forms\form;


	class forms{

		static $post_type_name = 'hiweb-forms';
		static protected $post_type_object;
		static $options_name = 'hiweb-forms';
		static protected $options_object;
		static $options_name_recapthca = 'hiweb-forms-recaptcha';
		static protected $options_object_recaptcha;
		static $template_name = 'default';
		/** @var \WP_Post current form wp post */
		static protected $the_wp_post;
		/** @var form[] */
		static protected $forms = [];
		static $input_classes;


		static function init(){
			if( context::is_admin_page() ){
				include_js( HIWEB_DIR_VENDORS . '/font-awesome-5/js/all.min.js' );
				include_css( HIWEB_DIR_VENDORS . '/font-awesome-5/css/all.min.css' );
			}
			require_once __DIR__ . '/forms/-options.php';
		}


		static function get_strtr_templates( $additions = [], $return_descriptions = false ){
			$data = [
				'#{home-url}' => [ get_home_url(), 'URL адрес домашней страницы' ],
				'{site-name}' => [ get_bloginfo( 'name' ), 'Название сайта' ]
			];
			$R = [];
			foreach( $data as $key => $raw ){
				$R[ $key ] = $return_descriptions ? $raw[1] : $raw[0];
			}
			if( is_array( $additions ) ) foreach( $additions as $key => $raw ){
				$R[ $key ] = is_array( $raw ) ? ( $return_descriptions ? $raw[1] : $raw[0] ) : $raw;
			}
			return $R;
		}


		/**
		 * @param $form_post_id
		 * @return form
		 */
		public static function get( $form_post_id ){
			if( !array_key_exists( $form_post_id, self::$forms ) ){
				self::$forms[ $form_post_id ] = new form( $form_post_id );
			}
			return self::$forms[ $form_post_id ];
		}


		/**
		 * @return array
		 */
		static function get_input_classes(){
			if( !is_array( self::$input_classes ) ){
				self::$input_classes = [];
				foreach( \hiweb\file( __DIR__ . '/forms/inputs' )->get_sub_files( [ 'php' ] ) as $class_file ){
					self::$input_classes[ $class_file->filename ] = ( 'hiweb_theme\\widgets\\forms\\inputs\\' . $class_file->filename );
				}
			}
			return self::$input_classes;
		}


		/**
		 * @param $form_postOrId
		 * @return array|null|\WP_Post
		 */
		static function setup_postdata( $form_postOrId ){
			self::$the_wp_post = get_post( $form_postOrId );
			return self::$the_wp_post;
		}


		/**
		 * @return int|null
		 */
		static function get_the_ID(){
			if( self::$the_wp_post instanceof \WP_Post ){
				return self::$the_wp_post->ID;
			} else return null;
		}


		/**
		 * @return form
		 */
		static function get_the_form(){
			return self::get( self::get_the_ID() );
		}

	}