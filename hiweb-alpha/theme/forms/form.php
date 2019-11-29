<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 11.10.2018
	 * Time: 12:45
	 */

	namespace theme\forms;


	use hiweb\arrays;
	use hiweb\dump;
	use hiweb\paths;
	use hiweb\strings;
	use hiweb\themes\theme;
	use hiweb\urls;
	use theme\breadcrumbs;
	use theme\forms;
	use theme\includes\frontend;
	use theme\mailchimp;
	use theme\recaptcha;
	use theme\sendpulse;


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
		protected $the_fancybox_button_html = 'Открыть форму';
		protected $the_fancybox_button_classes = [];
		protected $the_fancybox_button_values = [];
		static protected $fancy_box_form_added = [];


		public function __construct( $form_postOrId ){
			frontend::js( __DIR__ . '/assets/forms.min.js' );
			frontend::css( __DIR__ . '/assets/forms.css' );
			frontend::fancybox();
			frontend::jquery_form();
			frontend::jquery_mask();
			if( recaptcha::is_enable() && strlen( recaptcha::get_recaptcha_key() ) > 5 ){
				frontend::js( 'https://www.google.com/recaptcha/api.js?render=' . recaptcha::get_recaptcha_key(), [], false );
			}
			//
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
		 * @return int
		 */
		public function get_object_id(){
			return spl_object_hash( $this );
		}


		/**
		 * @param string $status_name - process|success|warn|error
		 * @return mixed
		 */
		public function get_status_icon( $status_name = 'process' ){
			$R = get_field( 'icon-' . $status_name, $this->get_wp_post() );
			if( $R == '' ) $R = get_field( 'icon-' . $status_name, forms::$options_name );
			return $R;
		}


		/**
		 * @param string $status_name - process|success|warn|error
		 * @return mixed
		 */
		public function get_status_message( $status_name = 'process' ){
			$R = get_field( 'text-' . $status_name, $this->get_wp_post() );
			if( $R == '' ) $R = get_field( 'text-' . $status_name, forms::$options_name );
			return $R;
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
				forms::setup_postdata( $this->get_id() );
				get_template_part( HIWEB_THEME_PARTS . '/widgets/forms/form', forms::$template_name );
			}
		}


		/**
		 * @param string $html
		 * @param array  $button_classes
		 * @param array  $values
		 */
		public function the_fancybox_button( $html = 'Открыть форму', $button_classes = [ 'hiweb-theme-widget-form-button' ], $values = [] ){
			forms::setup_postdata( $this->get_wp_post() );
			if( !is_array( $button_classes ) ){
				if( is_string( $button_classes ) && $button_classes != '' ) $button_classes = [ $button_classes ]; else $button_classes = [ 'hiweb-theme-widget-form-button' ];
			}
			$this->the_fancybox_button_html = $html;
			$this->the_fancybox_button_classes = $button_classes;
			$this->the_fancybox_button_values = $values;
			get_template_part( HIWEB_THEME_PARTS . '/widgets/forms/fancybox-button' );

			if( !array_key_exists( $this->get_id(), self::$fancy_box_form_added ) ){
				self::$fancy_box_form_added[ $this->get_id() ] = $this->get_object_id();
				add_action( '\theme\html_layout\body::the_after-before', function(){
					?>
					<div class="hiweb-theme-widget-form-modal-wrap" style="display: none;">
						<div class="hiweb-theme-widget-form-modal hiweb-theme-widget-form-modal-<?= $this->get_wp_post()->ID ?> hiweb-theme-widget-form-modal-<?= $this->get_wp_post()->post_name ?>" id="hiweb-theme-widgets-form-<?= $this->get_id() ?>" data-form-id="<?= get_the_form()->get_id() ?>" data-form-object-id="<?= $this->get_object_id() ?>">
							<?php if( $this->is_exists() ){
								?>
								<div class="form-title"><?= get_the_title( $this->get_wp_post() ) ?></div>
								<?php
							} ?>
							<?php $this->the() ?>
						</div>
					</div>
					<?php
				} );
			}
		}


		/**
		 * @return string
		 */
		public function the_fancybox_button_html(){
			return $this->the_fancybox_button_html;
		}


		/**
		 * @return string
		 */
		public function the_fancybox_button_classes(){
			return implode( ' ', $this->the_fancybox_button_classes );
		}


		/**
		 * @return false|mixed|string
		 */
		public function the_fancybox_button_values(){
			return htmlentities( json_encode( $this->the_fancybox_button_values ) );
		}


		/**
		 * @param string $html
		 * @param array  $button_classes
		 * @param array  $values
		 * @return string
		 */
		public function get_fancybox_button( $html = 'Открыть форму', $button_classes = [ 'hiweb-theme-widget-form-button' ], $values = [] ){
			ob_start();
			$this->the_fancybox_button( $html, $button_classes, $values );
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
		public function get_input_object_class( $name ){
			$data = $this->get_input_options( $name );
			$input_id = $data['_flex_row_id'];
			foreach( forms::get_input_classes() as $input_class_name ){
				if( $input_id == $input_class_name::$input_title ){
					return $input_class_name;
				}
			}
			return 'theme\forms\inputs\input';
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
			paths::get( __DIR__ . '/inputs' )->include_files( 'php' );
			if( !is_array( $this->inputs ) ){
				$this->inputs = [];
				foreach( $this->get_inputs_options() as $name => $options ){
					$class_name = $this->get_input_object_class( $name );
					/** @var forms\inputs\input $new_class */
					$new_class = new $class_name();
					$new_class->data = $options;
					$input_name = $new_class->get_data( 'name' );
					$input_name = $input_name == '' ? strings::rand() : $input_name;
					$this->inputs[ $input_name ] = $new_class;
				}
			}
			return $this->inputs;
		}


		/**
		 * @param $name
		 * @return inputs\input
		 */
		public function get_input_object( $name ){
			$inputs = $this->get_inputs();
			if( array_key_exists( $name, $inputs ) ){
				return $inputs[ $name ];
			}
			return new forms\inputs\input();
		}


		/**
		 * @return bool
		 */
		public function have_inputs(){
			if( !is_array( $this->the_inputs ) ){
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
			$emails_str = trim( get_field( 'email', self::get_wp_post() ), ',' );
			if( $emails_str == '' ) $emails_str = trim( get_field( 'email', forms::$options_name ), ',' );
			if( $emails_str == '' ){
				$emails = [ get_bloginfo( 'admin_email' ) ];
			} elseif( strpos( $emails_str, ' ' ) ) {
				$emails = explode( ' ', $emails_str );
			} elseif( strpos( $emails_str, ',' ) ) {
				$emails = explode( ',', $emails_str );
			} elseif( trim( $emails_str ) != '' ) {
				$emails = [ trim( $emails_str ) ];
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
		 * @param array $addition_strtr
		 * @return array
		 */
		public function get_strtr_templates( $addition_strtr = [] ){
			$R = forms::get_strtr_templates( $addition_strtr );
			return $R;
		}


		/**
		 * @return bool
		 */
		public function is_exists(){
			return ( intval( $this->post_id ) > 0 && $this->get_wp_post() instanceof \WP_Post && $this->get_wp_post()->post_type == forms::$post_type_name );
		}


		/**
		 * @param string $to
		 * @param string $subject
		 * @param string $content
		 * @return bool
		 */
		private function send_mail( $to = '', $subject = '', $content = '' ){
			if( !is_string( $to ) || trim( $to ) == '' ){
				$to = get_bloginfo( 'admin_email' );
				if( !filter_var( $to, FILTER_VALIDATE_EMAIL ) ){
					$to = get_option( 'admin_email' );
				}
			}
			$reply_to = 'noreply@{domain}';
			if( get_field( 'reply_email', $this->get_wp_post() ) != '' ){
				$reply_to = get_field( 'reply_email', forms::$options_name );
			}
			if( get_field( 'reply_email', $this->get_wp_post() ) != '' ){
				$reply_to = get_field( 'reply_email', $this->get_wp_post() );
			}
			$reply_to = str_replace( '{domain}', urls::get()->domain(), $reply_to );
			$headers = [ 'From: ' . get_bloginfo( 'name' ) . ' <' . $reply_to . '>' ];
			$headers[] = 'Reply-To: ' . $reply_to;
			$headers[] = 'Precedence: bulk';
			$headers[] = 'List-Unsubscribe: ' . \hiweb\urls::root( false );
			add_filter( 'wp_mail_content_type', function(){ return "text/html"; } );
			return wp_mail( $to, html_entity_decode( $subject ), $content, $headers );
		}


		/**
		 * Submit form
		 * @param $submit_data
		 * @return array
		 * @version 1.4
		 */
		public function do_submit( $submit_data ){
			if( !$this->is_exists() ){
				return [ 'success' => false, 'message' => 'Формы не существует', 'status' => 'error' ];
			}
			$allow_submit_form = apply_filters_ref_array( '\theme\forms\form::do_submit-allow_submit_form', [ null, $this, $submit_data ] );
			if( is_array( $allow_submit_form ) ) return $allow_submit_form;
			///reCaptcha Validate
			if( !recaptcha::get_recaptcha_verify() ){
				return [ 'success' => false, 'message' => get_field( 'text-error', recaptcha::$admin_menu_slug ), 'status' => 'warn' ];
			}
			///
			$inputs = $this->get_inputs_options();
			$require_empty_inputs = [];
			$addition_strtr = [];
			$addition_strtr['{data-list}'] = '';
			$addition_strtr['{form-title}'] = $this->get_wp_post()->post_title;
			$client_email = [];

			foreach( $inputs as $input ){
				if( !array_key_exists( 'name', $input ) ) continue;
				$name = $input['name'];
				$input_object = $this->get_input_object( $name );
				$require = arrays::get_value_by_key( $input, 'require' ) == 'on';
				$value = $input_object->get_email_value( arrays::get_value_by_key( $submit_data, $name ) );
				$addition_strtr[ '{' . $name . '}' ] = $value;
				if( $value != '' && $input_object->is_email_submit_enable() ) $addition_strtr['{data-list}'] .= $input_object->get_email_html( arrays::get_value_by_key( $submit_data, $name ) ) . "<br>";
				switch( $input['_flex_row_id'] ){
					case 'Адрес почты':
						if( filter_var( $value, FILTER_VALIDATE_EMAIL ) ){
							$client_email[] = $value;
							///MailChimp insert form
							if( forms::mailchimp()->is_api_key_exists() ){
								if( forms::mailchimp()->is_connected() ){
									$list_id = get_field( 'mailchimp-list-id', self::get_wp_post() );
									if( get_field( 'enable-default-list-id', mailchimp::$options_name_mailchimp ) && $list_id == '' ){
										$list_id = get_field( 'list-id', mailchimp::$options_name_mailchimp );
									}
									if( forms::mailchimp()->is_list_exists( $list_id ) ){
										forms::mailchimp()->get_api()->post( "lists/{$list_id}/members", [
											'email_address' => $value,
											'status' => 'subscribed'
										] );
									}
								}
							}
							///SENDPULSE
							if( sendpulse::is_keys_exists() ){
								if( sendpulse::get_instance()->is_api_exists() ){
									$list_id = get_field( 'list-id', self::get_wp_post() );
									if( get_field( 'default-list-id', sendpulse::$options_name ) && $list_id == '' ){
										$list_id = get_field( 'default-list-id', sendpulse::$options_name );
									}
									$list_id = substr( $list_id, 3 );
									if( sendpulse::get_instance()->is_list_exists( $list_id ) ){
										$addition_params = [ 'email' => $value ];
										if( is_array( $inputs ) ){
											foreach( $inputs as $subinput ){
												switch( $subinput['_flex_row_id'] ){
													case 'Чекбоксы':
														if( arrays::get_value_by_key( $subinput, 'sendpusle_append' ) == 'on' && arrays::get_value_by_key( $subinput, 'name' ) != '' && arrays::get_value_by_key( $subinput, 'variants' ) != '' ){
															$exist_variants = [];
															foreach( explode( "\n", trim( arrays::get_value_by_key( $subinput, 'variants' ) ) ) as $exist_variant ){
																if( trim( $exist_variant ) == '' ) continue;
																$exist_variants[] = trim( $exist_variant );
															}
															if( is_array( $_POST[ $subinput['name'] ] ) ) foreach( $_POST[ $subinput['name'] ] as $variant ){
																if( trim( $variant ) == '' || !in_array( trim( $variant ), $exist_variants ) ) continue;
																$addition_params['variables'][ strtr( $subinput['name'] . ' - ' . $variant, [ ':' => '-' ] ) ] = '+';
															}
														}
														break;
												}
											}
										}
										if( isset( $_POST['name'] ) ){
											$addition_params['variables']['имя'] = strtr( $_POST['name'], [ ':' => '-' ] );
										}
										$B = sendpulse::get_instance()->get_api()->addEmails( $list_id, [ $addition_params ] );
									}
								}
							}
						}
						if( $require && !filter_var( $value, FILTER_VALIDATE_EMAIL ) ){
							$require_empty_inputs[] = $name;
						}
						break;
					case 'Цифровое поле':
						if( $require && strlen( $value ) < 1 ){
							$require_empty_inputs[] = $name;
						}
						break;
					case 'Чекбоксы':
						if( $require && ( ( is_array( $value ) && count( $value ) < intval( arrays::get_value_by_key( $input, 'require-min' ) ) ) || ( is_string( $value ) && trim( $value ) == '' ) ) ){
							$require_empty_inputs[] = $name . '[]';
						}
						break;
					default:
						if( $require && strlen( $value ) < 2 ){
							$require_empty_inputs[] = $name;
						}
						break;
				}
			}
			///
			if( count( $require_empty_inputs ) > 0 ){
				return [ 'success' => false, 'message' => $this->get_status_message( 'warn' ), 'inputs' => $inputs, 'status' => 'warn', 'error_inputs' => $require_empty_inputs ];
			} else {
				///
				$allow_submit_form_after_validate = apply_filters_ref_array( '\theme\forms\form::do_submit-allow_submit_form_after_validate', [ null, $this, $submit_data, $require_empty_inputs ] );
				if( is_array( $allow_submit_form_after_validate ) ) return $allow_submit_form_after_validate;
				///Send Message to Admin
				foreach( $this->get_target_emails() as $email ){
					$theme = strtr( get_field( 'theme-email-admin', self::get_wp_post() ), form::get_strtr_templates( $addition_strtr ) );
					$content = apply_filters( 'the_content', strtr( get_field( 'content-email-admin', self::get_wp_post() ), form::get_strtr_templates( $addition_strtr ) ) );
					if( trim( $theme ) == '' ) $theme = strtr( get_field( 'theme-email-admin', forms::$options_name ), form::get_strtr_templates( $addition_strtr ) );
					if( trim( $content ) == '' ) $content = apply_filters( 'the_content', strtr( get_field( 'content-email-admin', forms::$options_name ), form::get_strtr_templates( $addition_strtr ) ) );
					$this->send_mail( $email, $theme, $content );
				}
				///Send client Email
				if( get_field( 'send-client-email', forms::$options_name ) != '' && count( $client_email ) > 0 ){
					foreach( $client_email as $email ){
						$theme = strtr( get_field( 'theme-email-client', self::get_wp_post() ), form::get_strtr_templates( $addition_strtr ) );
						$content = apply_filters( 'the_content', strtr( get_field( 'content-email-client', self::get_wp_post() ), form::get_strtr_templates( $addition_strtr ) ) );
						if( trim( $theme ) == '' ) $theme = strtr( get_field( 'theme-email-client', forms::$options_name ), form::get_strtr_templates( $addition_strtr ) );
						if( trim( $content ) == '' ) $content = apply_filters( 'the_content', strtr( get_field( 'content-email-client', forms::$options_name ), form::get_strtr_templates( $addition_strtr ) ) );
						$this->send_mail( $email, $theme, $content );
					}
				}
				return [ 'success' => true, 'callback_js' => get_field( 'callback_js', $this->get_wp_post() ), 'message' => $this->get_status_message( 'success' ), 'status' => 'success' ];
			}
		}
	}