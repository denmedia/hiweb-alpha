<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 04/11/2018
	 * Time: 17:25
	 */

	namespace hiweb_theme\tools;


	use hiweb\arrays;
	use hiweb\urls;
	use hiweb_theme\head;
	use hiweb_theme\tools\languages\language;
	use hiweb_theme\tools\languages\post;
	use hiweb_theme\tools\languages\term;


	require_once __DIR__ . '/languages/language.php';


	class languages{

		static $options_page_slug = 'languages';
		/** @var array */
		protected static $default_data;
		/** @var array */
		protected static $languages_data;
		/** @var language[] */
		protected static $languages;
		protected static $current_id = 'ru';
		protected static $current_locale = 'ru_RU';
		protected static $session_key = 'hiweb-language-select-id';

		///Posts
		static $exclude_post_types = [ 'attachment', 'revision', 'custom_css', 'customize_changeset', 'action', 'author', 'order', 'theme' ];
		static protected $posts = [];
		static protected $terms = [];
		static $post_meta_key_lang_id = 'hiweb-languages-lang-id';
		static $post_meta_key_default_post_id = 'hiweb-languages-default-id';
		static $post_create_sibling_get_key_id = 'hiweb-theme-lang-sibling-id';
		static $post_create_sibling_get_key_lang_id = 'hiweb-theme-lang-sibling-lang-id';
		static $post_columns_id = 'hiweb-theme-lang-id';

		/**
		 * Языка был установлен через URL
		 * @var bool
		 */
		static $url_change_set = false;


		static function init(){
			require_once __DIR__ . '/languages/-admin.php';
			self::set_lang_id( self::autodetect_lang_id() );
			require_once __DIR__ . '/languages/-hooks.php';
			require_once __DIR__ . '/languages/-query.php';
		}


		static protected function autodetect_lang_id(){
			$R = '';
			///CHECK SUBDOMAIN
			preg_match( '/^(?<subdomain>[\w\-_]+)\..*/i', $_SERVER['HTTP_HOST'], $domains );
			if( $R == '' && array_key_exists( 'subdomain', $domains ) && array_key_exists( $domains['subdomain'], self::get_languages() ) ){
				$R = $domains['subdomain'];
			}
			///CHECK URL LANG REQUEST
			if( $R == '' ){
				if( preg_match( '/^\/(?<lang_id>[\w\d-_]+)\/?.*/i', $_SERVER['REQUEST_URI'], $matches ) > 0 && isset( $matches['lang_id'] ) ){
					if( self::is_exists( $matches['lang_id'] ) ){
						$R = $matches['lang_id'];
					}
				}
			}
			///CHECK CURRENT POST/PAGE
			if( $R == '' && urls::get()->get_clear() != urls::root() && function_exists( 'get_queried_object' ) ){
				if( get_queried_object() instanceof \WP_Post && !is_front_page() ) $R = self::get_post( get_queried_object_id() )->get_lang_id();
				if( get_queried_object() instanceof \WP_Term ) $R = self::get_term( get_queried_object_id() )->get_lang_id();
			}
			///SET FROM SESSIONS
//			if( session_id() == '' ) session_start();
//			if( $R == '' && array_key_exists( self::$session_key, $_SESSION ) ){
//				$test_lang_id = $_SESSION[ self::$session_key ];
//				if( self::is_exists( $test_lang_id ) ){
//					$R = $test_lang_id;
//				}
//			}
			///CHECK BROWSER
			if( $R == '' && array_key_exists( substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 ), self::get_languages() ) ){
				$R = substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 );
			}
			///SET DEFAULT LANG
			if( $R == '' ){
				$R = self::get_default_id();
			}
			return $R;
		}


		/**
		 * Return true if lang id exists
		 * @param $lang_id
		 * @return bool
		 */
		static function is_exists( $lang_id ){
			return array_key_exists( $lang_id, self::get_languages() );
		}


		/**
		 * @param      $lang_id
		 * @param bool $url_change_set
		 * @return bool
		 */
		static function set_lang_id( $lang_id, $url_change_set = false ){
			if( array_key_exists( $lang_id, self::get_languages() ) ){
				self::$current_id = $lang_id;
				self::$current_locale = self::get_current_language()->get_locale();
				setlocale( LC_ALL, self::get_current_locale() . '.UTF-8' );
				//Add to head LANG tag
				head::add_html_tag( 'lang', self::get_current_id() );
				if( session_id() == '' ) session_start();
				$_SESSION[ self::$session_key ] = self::$current_id;
				self::$url_change_set = $url_change_set;
				return true;
			}
			return false;
		}


		/**
		 * @param $postOrIdOrSlug
		 * @return post
		 */
		static function get_post( $postOrIdOrSlug ){
			if( is_string( $postOrIdOrSlug ) && !is_numeric( $postOrIdOrSlug ) ){
				$test_posts = get_posts( [
					'name' => $postOrIdOrSlug,
					'post_type' => 'post',
					'post_status' => 'publish',
					'posts_per_page' => 1
				] );
				if( count( $test_posts ) > 0 ) $postOrIdOrSlug = $test_posts[0]; else $postOrIdOrSlug = 0;
			}
			if( $postOrIdOrSlug instanceof \WP_Post ) $postOrIdOrSlug = $postOrIdOrSlug->ID;
			if( !array_key_exists( $postOrIdOrSlug, self::$posts ) ){
				self::$posts[ $postOrIdOrSlug ] = new post( $postOrIdOrSlug );
			}
			return self::$posts[ $postOrIdOrSlug ];
		}


		/**
		 * @param $termOrId
		 * @return term
		 */
		static function get_term( $termOrId ){
			if( $termOrId instanceof \WP_Term ) $termOrId = $termOrId->term_id;
			if( !array_key_exists( $termOrId, self::$terms ) ){
				self::$terms[ $termOrId ] = new term( $termOrId );
			}
			return self::$terms[ $termOrId ];
		}


		/**
		 * @param string|integer $timestamp
		 * @param string         $format
		 * @return string
		 */
		static function get_date( $timestamp, $format = 'd F' ){
			if( self::get_current_id() === 'ru' ){
				return date_i18n( $format, $timestamp );
			} else {
				$format = \hiweb\date::formatToLocalize($format);
				return strtolower( strftime( $format, $timestamp ) );
				//return strtolower( strftime( '%e %B', $timestamp ) );
			}
		}


		/**
		 * Возвращает ID текущей версии сайта
		 * @return string
		 */
		static function get_current_id(){
			return self::$current_id;
		}


		static function get_current_locale(){
			return self::$current_locale;
		}


		/**
		 * @return language
		 */
		static function get_current_language(){
			$languages = self::get_languages();
			if( array_key_exists( self::get_current_id(), $languages ) ){
				return $languages[ self::get_current_id() ];
			}
			return $languages[ self::get_default_id() ];
		}


		/**
		 * @return array
		 */
		static private function get_default_data(){
			if( !is_array( self::$default_data ) ){
				self::$default_data = [
					'id' => get_field( 'default-id', self::$options_page_slug ),
					'name' => get_field( 'default-name', self::$options_page_slug ),
					'title' => get_field( 'default-title', self::$options_page_slug )
				];
			}
			return self::$default_data;
		}


		/**
		 * @param bool $return_allowed
		 * @return array
		 */
		static function get_post_types( $return_allowed = true ){
			$R = [];
			foreach( get_post_types() as $post_type_name ){
				if( array_key_exists( $post_type_name, array_flip( self::$exclude_post_types ) ) ) continue;
				if( !$return_allowed || get_field( 'post-type-' . $post_type_name, self::$options_page_slug ) ) $R[] = $post_type_name;
			}
			return $R;
		}


		/**
		 * @param $post_type
		 * @return bool
		 */
		static function is_post_type_allowed( $post_type ){
			return array_key_exists( $post_type, array_flip( self::get_post_types( true ) ) );
		}


		/**
		 * @param $taxonomy
		 * @return bool
		 */
		static function is_taxonomy_allowed( $taxonomy ){
			foreach( self::get_post_types( true ) as $post_type ){
				if( arrays::in_array( $taxonomy, get_object_taxonomies( $post_type ) ) ) return true;
			}
			return false;
		}


		/**
		 * @return array
		 */
		static private function get_languages_data(){
			if( !is_array( self::$languages_data ) ){
				self::$languages_data = [];
				//Default
				self::$languages_data[ self::get_default_data()['id'] ] = self::get_default_data();
				//Addition Languages
				if( have_rows( 'languages', self::$options_page_slug ) ){
					while( have_rows( 'languages', self::$options_page_slug ) ){
						the_row();
						self::$languages_data[ get_sub_field( 'id' ) ] = [
							'id' => get_sub_field( 'id' ),
							'locale' => get_sub_field( 'locale' ),
							'name' => get_sub_field( 'name' ),
							'title' => get_sub_field( 'title' )
						];
					}
				}
			}
			return self::$languages_data;
		}


		/**
		 * @return language[]
		 */
		static function get_languages(){
			if( !is_array( self::$languages ) ){
				self::$languages = [];
				foreach( self::get_languages_data() as $id => $data ){
					self::$languages[ $id ] = new language( $data );
				}
			}
			return self::$languages;
		}


		/**
		 * @param $lang_id
		 * @return language
		 */
		static function get_language( $lang_id ){
			$languages = self::get_languages();
			if( !array_key_exists( $lang_id, $languages ) ){
				self::$languages[ $lang_id ] = new language( [ 'id' => $lang_id, 'locale' => $lang_id ] );
			}
			return $languages[ $lang_id ];
		}


		/**
		 * @return array
		 */
		static function get_ids(){
			return array_keys( self::get_languages() );
		}


		/**
		 * @return string
		 */
		static function get_default_id(){
			return self::get_default_data()['id'];
		}


		/**
		 * @param $field_id
		 * @return string
		 */
		static function get_field_id( $field_id ){
			return ( self::get_current_id() == self::get_default_data()['id'] ) ? $field_id : $field_id . '-lang-' . self::get_current_id();
		}


		/**
		 * @param      $field_id
		 * @param null $contextObject
		 * @return mixed
		 */
		static function get_field( $field_id, $contextObject = null ){
			return get_field( self::get_field_id( $field_id ), $contextObject );
		}


		/**
		 * @param      $field_id
		 * @param null $contextObject
		 * @return bool
		 */
		static function have_rows( $field_id, $contextObject = null ){
			return have_rows( self::get_field_id( $field_id ), $contextObject );
		}


		/**
		 * @param bool $echo
		 * @return string
		 */
		static function the_select( $echo = true ){
			if( !$echo ) ob_start();
			?>
			<div class="languages-select">
				<?php foreach( self::get_languages() as $language ){
					$active = $language->get_id() == self::get_current_id();
					?><a href="<?= $language->get_url() ?>" class="language<?= $active ? ' active' : '' ?>" lang="<?=$language->get_id()?>"><?= $language->get_title() ?></a><?php
				} ?>
			</div>
			<?php
			if( !$echo ) return ob_get_clean();
			return '';
		}


		static function get_the_content( $post_id, $apply_filters = false ){
			return self::get_current_language()->get_the_content( $post_id, $apply_filters );
		}


		/**
		 * @param $source_post_id
		 * @param $destination_lang_id
		 * @return bool|int
		 */
		static function do_make_sibling_post( $source_post_id, $destination_lang_id ){
			return self::get_post( $source_post_id )->do_make_sibling( $destination_lang_id );
		}


		/**
		 * @param $source_term_id
		 * @param $destination_lang_id
		 * @return bool|int
		 */
		static function do_make_sibling_term( $source_term_id, $destination_lang_id ){
			return self::get_term( $source_term_id )->do_make_sibling( $destination_lang_id );
		}


		/**
		 * @param \WP_Query $wp_query
		 * @return \WP_Query
		 */
		static function filter_wp_query( \WP_Query &$wp_query ){
			return self::get_current_language()->filter_wp_query( $wp_query );
		}


	}