<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 0:05
	 */

	namespace hiweb_theme\modules {


		use hiweb\arrays;
		use hiweb\themes;
		use hiweb_theme\includes;


		class nav_menu{

			public $id = 'mobile-menu';
			public $root_classes = [];
			public $root_tag = 'nav';
			//public $use_ul_li = true;
			public $depth = 3;
			public $nav_location = 'mobile-menu';
			public $item_class = '';
			public $item_class_active = 'active';
			public $items;
			public $items_by_parent;
			public $use_stellarnav = true;
			public $use_stellarnav_showArrows = true;


			public function __construct( $nav_location ){
				$this->nav_location = $nav_location;
			}


			/**
			 * @param bool $by_parent
			 * @return array
			 */
			public function get_items( $by_parent = false ){
				if( !is_array( $this->items ) ){
					$this->items = [];
					foreach( themes::get()->menu_items( $this->nav_location ) as $item ){
						$this->items[ (int)$item->ID ] = $item;
						$this->items_by_parent[ (int)$item->menu_item_parent ][ (int)$item->ID ] = $item;
					}
				}
				if( is_bool( $by_parent ) ){
					return $by_parent ? $this->items_by_parent : $this->items;
				} elseif( is_numeric( $by_parent ) ) {
					return array_key_exists( (int)$by_parent, $this->items_by_parent ) ? $this->items_by_parent[ (int)$by_parent ] : [];
				}
			}


			/**
			 * @param int $ID
			 * @return bool
			 */
			public function has_subitems( $ID = 0 ){
				return array_key_exists( $ID, $this->get_items( true ) );
			}


			/**
			 * @param int $parent_id
			 * @param int $sub_level
			 */
			public function the_list( $parent_id = 0, $sub_level = 2 ){
				if( $sub_level > 0 && $this->has_subitems( $parent_id ) ){
					?>
					<ul>
						<?php
							foreach( $this->get_items( $parent_id ) as $item ){
								?>
								<li>
									<a href="<?= $item->url ?>"><?= $item->title ?></a>
									<?php $this->the_list( $item->ID, $sub_level - 1 ); ?>
								</li>
								<?php
							}
						?>
					</ul>
					<?php
				}
			}


			public function the(){
			if( $this->use_stellarnav ){
				includes::fontawesome( false );
				includes::css( HIWEB_THEME_VENDORS_DIR . '/jquery.stellarnav/stellarnav.min.css' );
				includes::js( HIWEB_THEME_VENDORS_DIR . '/jquery.stellarnav/stellarnav.min.js', [ includes::jquery() ] );
				$this->root_classes[] = 'stellarnav';
				?>
				<div id="<?= $this->id ?>" <?= arrays::is_empty( $this->root_classes ) ? '' : 'class="' . implode( ' ', $this->root_classes ) . '"' ?>>
					<nav>
						<?php
							} else {
						?>
						<nav id="<?= $this->id ?>" <?= arrays::is_empty( $this->root_classes ) ? '' : 'class="' . implode( ' ', $this->root_classes ) . '"' ?>>
							<?php
								}
								$this->the_list( 0, $this->depth );
								if( !$this->use_stellarnav ){ ?></nav> <?php }else{ ?>
					</nav>
				</div>
				<?php
				add_action( 'wp_footer', function(){
					?>
					<script type="text/javascript">
                        jQuery(document).ready(function ($) {
                            $('#<?=$this->id?>').stellarNav({
                                theme: 'plain',
                                breakpoint: 0,
                                sticky: false,
                                position: 'static',
                                showArrows: <?=$this->use_stellarnav_showArrows ? 'true' : 'false'?>,
                                closeBtn: false,
                                scrollbarFix: true
                            });
                        });
					</script>
					<?php
				}, 20 );
			}
			}

		}
	}