<?php

	namespace {


		if( !function_exists( 'add_field_terms' ) ){
			/**
			 * @param $id
			 * @return \hiweb\fields\types\terms\field
			 */
			function add_field_terms( $id ){
				$new_field = new hiweb\fields\types\terms\field( $id );
				hiweb\fields::register_field( $new_field );
				return $new_field;
			}
		}
	}

	namespace hiweb\fields\types\terms {


		use hiweb\arrays;
		use hiweb\fields\value;


		class field extends \hiweb\fields\field{

			protected $placeholder = '';


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function taxonomy( $set = null ){
				return $this->set_input_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 * @return $this|string
			 */
			public function placeholder( $set = null ){
				return $this->set_property( __FUNCTION__, $set );
			}


			/**
			 * @param null $set
			 * @return $this|null
			 */
			public function hide_empty( $set = null ){
				return $this->set_input_property( __FUNCTION__, $set );
			}


			protected function get_input_class(){
				return __NAMESPACE__ . '\\input';
			}


		}


		class input extends \hiweb\fields\input{

			public $taxonomy = [ 'category' ];
			public $hide_empty = false;


			public function __construct( \hiweb\fields\field $field, value $value ){
				parent::__construct( $field, $value );
				$this->attributes['multiple'] = 'multiple';
				$this->attributes['placeholder'] = 'Выберите категорию';
				$this->attributes['no_results_text'] = 'Ничего не найдено';
			}


			/**
			 * @return array
			 */
			private function get_terms_by_taxonomy(){
				$terms_by_taxonomy = [];
				$taxonomies = $this->taxonomy;
				if( is_array( $taxonomies ) ){
					foreach( $taxonomies as $taxonomy ){
						if( !taxonomy_exists( $taxonomy ) ) continue;
						$args = [
							'taxonomy' => $taxonomy,
							'hide_empty' => $this->hide_empty
						];
						$terms = get_terms( $args );
						foreach( $terms as $wp_term ){
							//if( is_array( $terms ) ) $terms_by_taxonomy[ $taxonomy ][ $wp_term->term_id ] = $wp_term;
							if( is_array( $terms ) ) $terms_by_taxonomy[ $wp_term->term_id ] = $wp_term;
						}
					}
				}
				return $terms_by_taxonomy;
			}


			/**
			 * @param $wp_term
			 * @return string|null
			 */
			private function get_term_title( $wp_term ){
				$title = null;
				if( $wp_term instanceof \WP_Term ){
					$title = '';
					$taxonomy = get_taxonomy( $wp_term->taxonomy );
					if( $taxonomy instanceof \WP_Taxonomy ){
						$title = $taxonomy->label . '→ ';
					}
					$title .= $wp_term->name . ' (' . $wp_term->count . ')';
				}
				return $title;
			}


			/**
			 * @param \WP_Term[] $wp_terms
			 * @param null       $terms_level
			 */
			private function get_html_options_from_terms( $wp_terms, $terms_level = null ){
				$selected_ids = [];
				if( is_array( $this->VALUE()->get() ) ) foreach( $this->VALUE()->get() as $term_taxonomy_id ){
					if( isset( $wp_terms[ $term_taxonomy_id ] ) ){
						$wp_term = $wp_terms[ $term_taxonomy_id ];
						?>
						<option selected value="<?= $term_taxonomy_id ?>"><?= $this->get_term_title( $wp_term ) ?></option><?php
						$selected_ids[ $term_taxonomy_id ] = $term_taxonomy_id;
					}
				}
				/** @var \WP_Term $wp_term */
				foreach( $wp_terms as $wp_term ){
					if( isset( $selected_ids[ $wp_term->term_taxonomy_id ] ) ) continue;
					?>
					<option value="<?= $wp_term->term_taxonomy_id ?>"><?= $this->get_term_title( $wp_term ) ?></option>
					<?php
				}
			}


			public function html(){
				\hiweb\css( HIWEB_DIR_CSS . '/field-terms.css' );
				\hiweb\js( HIWEB_DIR_JS . '/field-terms.js', [ 'jquery' ] );
				ob_start();
				$terms = $this->get_terms_by_taxonomy();
				?>
				<div class="hiweb-field-terms">
					<select class="" name="<?= $this->name() ?>[]" <?= $this->sanitize_attributes() ?>>
						<?php
							self::get_html_options_from_terms( $terms );
						?>
					</select>
				</div>
				<?php
				return ob_get_clean();
			}

		}
	}