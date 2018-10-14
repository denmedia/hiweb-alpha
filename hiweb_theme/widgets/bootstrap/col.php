<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08.10.2018
	 * Time: 10:15
	 */

	namespace hiweb_theme\widgets\bootstrap;


	class col{

		private $_row;
		protected $content = '';
		protected $classes = [];
		protected $addition_classes = [];
		private $index = 0;


		public function __construct( row $row, $index = 0 ){
			$this->_row = $row;
			$this->index = $index;
		}


		/**
		 * @param null $content
		 * @return $this
		 */
		public function content( $content = null ){
			$this->content = $content;
			return $this;
		}


		public function add_class( $class ){
			$this->addition_classes[] = $class;
		}


		/**
		 * @return int
		 */
		public function get_index(){
			return $this->index;
		}


		/**
		 * @return string
		 */
		public function get_classes(){
			return implode( ' ', array_unique( array_merge( $this->classes, $this->addition_classes ) ) );
		}


		/**
		 * @param int $cols
		 * @return $this
		 */
		public function col( $cols = 12 ){
			$this->classes[''] = 'col-' . $cols;
			return $this;
		}


		/**
		 * @param int $cols
		 * @return $this
		 */
		public function col_sm( $cols = 12 ){
			$this->classes['sm'] = 'col-sm-' . $cols;
			return $this;
		}


		/**
		 * @param int $cols
		 * @return $this
		 */
		public function col_md( $cols = 12 ){
			$this->classes['md'] = 'col-md-' . $cols;
			return $this;
		}


		/**
		 * @param int $cols
		 * @return $this
		 */
		public function col_lg( $cols = 12 ){
			$this->classes['lg'] = 'col-lg-' . $cols;
			return $this;
		}


		/**
		 * @param int $cols
		 * @return $this
		 */
		public function col_xl( $cols = 12 ){
			$this->classes['xl'] = 'col-xl-' . $cols;
			return $this;
		}


		public function fill(){
			$this->classes['fill'] = 'flex-fill';
		}


		public function fill_sm(){
			$this->classes['fill'] = 'flex-sm-fill';
		}


		public function fill_md(){
			$this->classes['fill'] = 'flex-md-fill';
		}


		public function fill_lg(){
			$this->classes['fill'] = 'flex-lg-fill';
		}


		public function fill_xl(){
			$this->classes['fill'] = 'flex-xl-fill';
		}


		public function the(){
			?>
			<div class="<?= $this->get_classes() ?>"><?= $this->content ?></div>
			<?php
		}


	}