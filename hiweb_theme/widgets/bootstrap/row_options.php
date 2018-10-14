<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08.10.2018
	 * Time: 13:39
	 */

	namespace hiweb_theme\widgets\bootstrap;


	abstract class row_options{


		protected $_row;
		protected $classes = [];


		public function __construct( row $row ){
			$this->_row = $row;
		}


		/**
		 * @param $class
		 * @return $this
		 */
		protected function set_classes( $class ){
			$grid_name = '';
			$known_grid_names = [ 'sm', 'md', 'lg', 'xl' ];
			foreach( $known_grid_names as $name ){
				if( strpos( $class, '-' . $name . '-' ) != false ){
					$grid_name = $name;
					break;
				}
			}
			$this->classes[ $grid_name ] = $class;
			return $this;
		}


		/**
		 * @param bool $return_string
		 * @return array
		 */
		public function get_classes( $return_string = true ){
			return $return_string ? implode( ' ', $this->classes ) : array_values( $this->classes );
		}


	}