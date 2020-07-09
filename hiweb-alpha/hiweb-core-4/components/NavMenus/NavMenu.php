<?php
	
	namespace hiweb\components\NavMenus;
	
	
	use hiweb\core\Cache\CacheFactory;
	use stdClass;
	use WP_Post;
	use WP_Term;
	
	
	class NavMenu{
		
		
		private $id;
		private $wp_term;
		
		
		public function __construct( $nav_menu_id ){
			$this->id = intval( $nav_menu_id );
		}
		
		
		/**
		 * @return WP_Term
		 */
		public function get_wp_term(){
			if( !$this->wp_term instanceof WP_Term ){
				if( $this->is_exists() ){
					$test_term = get_term_by( 'term_id', $this->id, 'nav_menu' );
					if( $test_term instanceof WP_Term ){
						$this->wp_term = $test_term;
					}
					else{
						$this->wp_term = new WP_Term( new stdClass() );
					}
				}
				else{
					$this->wp_term = new WP_Term( new stdClass() );
				}
			}
			return $this->wp_term;
		}
		
		
		/**
		 * @return bool
		 */
		public function is_exists(){
			return intval( $this->id ) > 0;
		}
		
		
		/**
		 * @return array|WP_Post[]
		 */
		public function get_items(){
			return CacheFactory::get( $this->id, __CLASS__ . '::$items', function(){
				if( $this->id == 0 ){
					return [];
				}
				else{
					$R = wp_get_nav_menu_items( $this->id );
					return is_array( $R ) ? $R : [];
				}
			} )->get_value();
		}
		
		
		/**
		 * @return array
		 */
		public function get_associated_objects(){
			return CacheFactory::get( $this->id, __CLASS__ . '::$associated_objects', function(){
				$R = [];
				foreach( self::get_items() as $nav_menu_item ){
					$R[ $nav_menu_item->ID . ':' . hiweb_nav_menu_item_to_wp_object_id( $nav_menu_item ) ] = hiweb_nav_menu_item_to_wp_object( $nav_menu_item );
				}
				return $R;
			} )->get_value();
		}
		
		
		/**
		 * @param int $parent_id
		 */
		public function the( $parent_id = 0 ){
			?>
			<ul>
				<?php
					$items = $this->get_items();
					if( is_array( $items ) ){
						foreach( $items as $item ){
							if( $item->parent_menu_item != $parent_id ) continue;
							?>
							<li><a href="<?= $item->url ?>"><span><?= $item->title ?></span></a></li>
							<?php
						}
					}
				?>
			</ul>
			<?php
		}
		
	}