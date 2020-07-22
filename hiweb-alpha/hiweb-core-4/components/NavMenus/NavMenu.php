<?php
	
	namespace hiweb\components\NavMenus;
	
	
	use hiweb\core\Cache\CacheFactory;
	use stdClass;
	use theme\forms\inputs\number;
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
		 * @param null|number $parent_id
		 * @return array|WP_Post[]
		 * @version 1.1
		 */
		public function get_items( $parent_id = null ){
			if( $parent_id instanceof WP_Post ){
				$parent_id = $parent_id->ID;
			}
			return CacheFactory::get( $this->id . '/' . $parent_id, __CLASS__ . '::$items', function(){
				if( $this->id == 0 ){
					return [];
				}
				else{
					$R = [];
					$items = wp_get_nav_menu_items( $this->id );
					if( is_array( $items ) ) foreach( $items as $item ){
						if( is_numeric( func_get_arg( 0 ) ) && $item->menu_item_parent != func_get_arg( 0 ) ) continue;
						$R[] = $item;
					}
					return $R;
				}
			}, [ $parent_id ] )->get_value();
		}
		
		
		/**
		 * Return true, if items is exists
		 * @param null|number $parent_id
		 * @return bool
		 */
		public function has_items( $parent_id = null ){
			return count( $this->get_items( $parent_id ) ) > 0;
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
		 * @varsion 1.1
		 * @param int    $parent_id
		 * @param string $ul_class
		 * @param string $li_class
		 */
		public function the( $parent_id = 0, $ul_class = '', $li_class = '' ){
			if( $parent_id instanceof WP_Post ){
				$parent_id = $parent_id->ID;
			}
			?>
			<ul class="<?= htmlentities( $ul_class ) ?>"><?php
			$items = $this->get_items();
			if( is_array( $items ) ){
				foreach( $items as $item ){
					if( $item->parent_menu_item != $parent_id ) continue;
					?>
					<li class="<?= htmlentities( $li_class ) ?>"><a href="<?= $item->url ?>"><span><?= $item->title ?></span></a></li><?php
				}
			}
			?></ul><?php
		}
		
	}