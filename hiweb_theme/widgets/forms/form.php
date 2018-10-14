<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11.10.2018
	 * Time: 12:45
	 */

	namespace hiweb_theme\widgets\forms;


	use hiweb\arrays;
	use hiweb\strings;
	use hiweb_theme\includes;
	use hiweb_theme\widgets\forms;


	class form{

		protected $post_id;
		protected $wp_post;
		protected $template_name = 'default';
		protected $inputs_options;
		/** @var forms\inputs\input[] */
		protected $inputs;
		protected $the_inputs;
		/** @var forms\inputs\input */
		protected $the_input;
		protected $action_url = '';
		protected $method = 'post';


		public function __construct( $form_postOrId ){
			$this->wp_post = get_post( $form_postOrId );
			$this->action_url = rest_url( 'hiweb_theme/widgets/forms/submit' );
			if( $this->wp_post instanceof \WP_Post ){
				$this->post_id = $this->wp_post->ID;
			}
		}


		/**
		 * @return string
		 */
		public function get_action_url(){
			return $this->action_url;
		}


		/**
		 * @return string
		 */
		public function get_method(){
			return $this->method;
		}


		/**
		 * @param string $name
		 * @return $this
		 */
		public function set_template_name( $name = 'default' ){
			$this->template_name = $name;
			return $this;
		}


		/**
		 * @return \WP_Post
		 */
		public function get_wp_post(){
			return $this->wp_post;
		}


		/**
		 * @return int
		 */
		public function get_id(){
			return $this->post_id;
		}


		/**
		 *
		 */
		public function the(){
			if( !$this->get_wp_post() instanceof \WP_Post ){
				console_error( __FUNCTION__ . ': форма [' . $this->get_id() . '] не найдена' );
				?>
				<!-- <?= __FUNCTION__ ?>: форма [<?= $this->get_id() ?>] не найдена -->
				<?php
			} elseif( $this->get_wp_post()->post_type != forms::$post_type_name ) {
				console_error( __FUNCTION__ . ': форма [' . $this->get_id() . '] не является типом записей формы [' . forms::$post_type_name . ']' );
				?>
				<!-- <?= __FUNCTION__ ?>: форма [<?= $this->get_id() ?>] не является типом записей формы [<?= forms::$post_type_name ?>] -->
				<?php
			} else {
				includes::defer_script_file( 'forms' );
				includes::fancybox();
				includes::jquery_form();
				includes::css( HIWEB_THEME_ASSETS_DIR . '/css/widget-forms.min.css' );
				forms::setup_postdata( $this->get_id() );
				get_template_part( HIWEB_THEME_PARTS . '/widgets/forms/form', forms::$template_name );
			}
		}


		public function the_fancybox_button( $html = 'Открыть форму', $button_classes = [ 'hiweb-theme-widget-form-button' ] ){
			$rand_id = strings::rand();
			if( !is_array( $button_classes ) ) $button_classes = [ $button_classes ];
			?>
			<a href="#<?= $rand_id ?>" class="<?= implode( ' ', $button_classes ) ?>" data-fancybox data-touch="false" data-widget-form-modal-open><?= $html ?></a>
			<div class="hiweb-theme-widget-form-modal-wrap">
				<div class="hiweb-theme-widget-form-modal" id="<?= $rand_id ?>">
					<?php $this->the() ?>
				</div>
			</div>
			<?php
		}


		/**
		 * @param string $html
		 * @param array  $button_classes
		 * @return string
		 */
		public function get_fancybox_button( $html = 'Открыть форму', $button_classes = [ 'hiweb-theme-widget-form-button' ] ){
			ob_start();
			$this->the_fancybox_button( $html, $button_classes );
			return ob_get_clean();
		}


		/**
		 * @return string
		 */
		public function get_html(){
			ob_start();
			$this->the();
			return ob_get_clean();
		}


		/**
		 * @param $name
		 * @return bool|array
		 */
		public function get_input_options( $name ){
			$inputs = $this->get_inputs_options();
			if( array_key_exists( $name, $inputs ) ){
				return $inputs[ $name ];
			}
			return null;
		}


		/**
		 * @param $name
		 * @return string
		 */
		public function get_input_object( $name ){
			$data = $this->get_input_options( $name );
			$input_id = $data['_flex_row_id'];
			foreach( forms::get_input_classes() as $input_class_name ){
				if( $input_id == $input_class_name::$input_title ){
					return $input_class_name;
				}
			}
			return 'hiweb_theme\widgets\forms\inputs\input';
		}


		/**
		 * @return array
		 */
		public function get_inputs_options(){
			if( !is_array( $this->inputs_options ) ){
				$this->inputs_options = [];
				if( have_rows( 'inputs', $this->wp_post ) ){
					while( have_rows( 'inputs', $this->wp_post ) ){
						$this->inputs_options[] = the_row();
					}
				}
			}
			return $this->inputs_options;
		}


		/**
		 * @return forms\inputs\input[]
		 */
		public function get_inputs(){
			if( !is_array( $this->inputs ) ){
				$this->inputs = [];
				foreach( $this->get_inputs_options() as $name => $options ){
					$class_name = $this->get_input_object( $name );
					/** @var forms\inputs\input $new_class */
					$new_class = new $class_name();
					$new_class->data = $options;
					$this->inputs[] = $new_class;
				}
			}
			return $this->inputs;
		}


		/**
		 * @return bool
		 */
		public function have_inputs(){
			if( is_null( $this->the_inputs ) ){
				$this->the_inputs = $this->get_inputs();
			}
			if( is_array( $this->the_inputs ) && count( $this->the_inputs ) > 0 ){
				return true;
			}
			//
			$this->the_inputs = null;
			$this->the_input = null;
			return false;
		}


		/**
		 * @return inputs\input
		 */
		public function the_input(){
			if( $this->have_inputs() ){
				$this->the_input = array_shift( $this->the_inputs );
			}
			return $this->the_input;
		}


		/**
		 * @return inputs\input
		 */
		public function get_the_input(){
			return $this->the_input;
		}


		/**
		 * @return array
		 */
		public function get_target_emails(){
			$emails = [];
			$emails_str = trim( get_field( 'email', forms::$options_name ), ',' );
			if( $emails_str = '' ){
				$emails = [ get_bloginfo( 'admin_email' ) ];
			} elseif( strpos( $emails_str, ' ' ) ) {
				$emails = explode( ' ', $emails_str );
			} elseif( strpos( $emails_str, ',' ) ) {
				$emails = explode( ',', $emails_str );
			}
			$R = [];
			foreach( $emails as $test_email ){
				$test_email = trim( $test_email );
				if( $test_email == '' ) continue;
				if( filter_var( $test_email, FILTER_VALIDATE_EMAIL ) ) $R[] = $test_email;
			}
			return $R;
		}


		/**
		 * @param $post_data
		 * @return array
		 */
		public function get_strtr_templates( $post_data ){
			$R = forms::get_strtr_templates();
			return $R;
		}


		public function do_submit( $submit_data ){
			$inputs = $this->get_inputs_options();
			$require_empty_inputs = [];

			foreach( $inputs as $input ){
				if( !array_key_exists( 'name', $input ) ) continue;
				$name = $input['name'];
				$require = arrays::get_value_by_key( $input, 'require' ) != '';
				if( $require && strlen( $require ) < 2 ){
					$require_empty_inputs[] = $submit_data[ $name ];
				}
			}
			///Send Message to Admin
			foreach( $this->get_target_emails() as $email ){
				$theme = strtr( get_field( 'theme-email-admin' ), form::get_strtr_templates( $submit_data ) );
				$content = strtr( get_field( 'content-email-admin' ), form::get_strtr_templates( $submit_data ) );
				wp_mail( $email, $theme, $content );
			}
			///
			if( count( $require_empty_inputs ) > 0 ){
				return [ 'success' => false, 'message' => 'Не верно переданны данные формы', 'status' => 'warn', 'error_inputs' => $require_empty_inputs ];
			} else {
				return [ 'success' => true, 'message' => get_field( 'text-success', forms::$options_name ), 'status' => 'success' ];
			}
		}
	}