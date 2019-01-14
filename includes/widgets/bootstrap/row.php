<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08.10.2018
	 * Time: 9:39
	 */

	namespace hiweb_theme\widgets\bootstrap;


	class row{

		/** @var wrap */
		private $_wrap;
		/** @var row_direction */
		private $direction;
		/** @var row_wrap */
		private $wrap;
		/** @var row_justify_content */
		private $justify_content;
		/** @var row_align_items */
		private $align_items;
		/** @var row_align_content */
		private $align_content;
		/** @var array */
		protected $classes = [ 'row' ];
		public $options_classes = [ 'row' ];
		/** @var col[] */
		private $cols = [];


		public function __construct( wrap $wrap ){
			$this->_wrap = $wrap;
			$this->direction = new row_direction( $this );
			$this->wrap = new row_wrap( $this );
			$this->justify_content = new row_justify_content( $this );
			$this->align_items = new row_align_items( $this );
			$this->align_content = new row_align_content( $this );
		}


		/**
		 * @param $class
		 * @return $this
		 */
		public function add_class($class){
			$this->classes[] = $class;
			return $this;
		}


		/**
		 * @return string
		 */
		public function get_classes(){
			$this->classes[] = $this->DIRECTION()->get_classes();
			$this->classes[] = $this->JUSTIFY_CONTENT()->get_classes();
			$this->classes[] = $this->ALIGN_CONTENT()->get_classes();
			$this->classes[] = $this->ALIGN_ITEMS()->get_classes();
			$this->classes[] = $this->WRAP()->get_classes();
			$R = [];
			foreach($this->classes as $class){
				$class = trim($class);
				if($class != '') $R[] = $class;
			}
			return trim(implode( ' ', $R ));
		}


		/**
		 * @return row_direction
		 */
		public function DIRECTION(){
			return $this->direction;
		}


		/**
		 * @return row_wrap
		 */
		public function WRAP(){
			return $this->wrap;
		}


		/**
		 * @return row_justify_content
		 */
		public function JUSTIFY_CONTENT(){
			return $this->justify_content;
		}


		/**
		 * @return row_align_items
		 */
		public function ALIGN_ITEMS(){
			return $this->align_items;
		}


		/**
		 * @return row_align_content
		 */
		public function ALIGN_CONTENT(){
			return $this->align_content;
		}


		/**
		 * @return col
		 */
		public function add_col(){
			$col = new col( $this, count($this->cols) );
			$this->cols[] = $col;
			return $col;
		}


		public function the(){
			?>
			<div class="<?= $this->get_classes() ?>"><?php
					foreach( $this->cols as $index => $col ){
						$col->the();
					}
				?></div>
			<?php
		}

	}