<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08.10.2018
	 * Time: 8:55
	 */

	namespace hiweb_theme\widgets\bootstrap;


	use hiweb\strings;


	class wrap{

		/** @var row[] */
		private $rows = [];
		private $id = '';
		protected $classes = [ 'hiweb-theme-bootstrap-wrap' ];
		protected $wrap_tag = 'div';


		public function __construct( $id = null ){
			$this->id = empty( $id ) ? strings::rand() : $id;
		}


		/**
		 * @return null|string
		 */
		public function get_id(){
			return $this->id;
		}


		/**
		 * @param string $wrap_tag
		 * @return $this
		 */
		public function tag($wrap_tag = 'div'){
			$this->wrap_tag = $wrap_tag;
			return $this;
		}


		/**
		 * @param $class
		 * @return $this
		 */
		public function add_class( $class ){
			$this->classes[] = $class;
			return $this;
		}


		/**
		 * @return row
		 */
		public function add_row(){
			$index = count( $this->rows );
			$row = new row( $this );
			$this->rows[ $index ] = $row;
			return $row;
		}


		public function get_classes(){
			$R = [];
			foreach( $this->classes as $class ){
				$class = trim( $class );
				if( $class != '' ) $R[] = $class;
			}
			return $R = implode( ' ', $R );
		}


		public function the(){
			?>
			<<?=$this->wrap_tag?> class="<?= $this->get_classes() ?>" id="<?= $this->id ?>">
				<?php
					foreach( $this->rows as $index => $row ){
						$row->the();
					}
				?>
			</<?=$this->wrap_tag?>>
			<?php
		}

	}