<footer>
	<div class="container">
		<?php
			$wrap = \hiweb_theme\footer::wrap();

			$sections = get_field( 'sections', 'footer' );
			$sections_chunk = array_chunk( $sections, intval( get_field( 'sections-limit', 'footer' ) ) );
			foreach( $sections_chunk as $sections ){
				$row = $wrap->add_row();
				foreach( $sections as $section ){
					$col = $row->add_col();
					$col->add_class( 'footer-1' );
					$col->add_class( \hiweb_theme\widgets\bootstrap::cols_to_class_simple( count( $sections ) ) );

					ob_start();
					$blocks = $section['section'];
					foreach( $blocks as $block ){
						?>
						<!--<?= $block['_flex_row_id'] ?>-->
						<?php
						switch( $block['_flex_row_id'] ){
							case 'Изображение (лого)':
								echo get_image( $block['logo'] )->html( [ 180, 80 ] );
								break;
							case 'Текстовой блок':
								echo apply_filters( 'the_content', $block['content'] );
								break;
							case 'Разделитель':
								?>
								<div class="footer-2"><p></p></div><?php
								break;
							case 'Социальные ссылки':
								?>
								<ul class="social">
									<?php
										if( $block['title'] != '' ){
											?>
											<li><a href="#"><span><?= $block['title'] ?></span></a></li><?php
										}
										foreach( $block['socials'] as $social ){
											?>
											<li><a href="<?= $social['href'] ?>" <?= $social['blank'] != '' ? 'target="_blank"' : '' ?>><i class="<?= $social['icon'] ?>"></i></a></li>
											<?php
										}
									?>
								</ul>
								<?php
								break;
							case 'Титл':
								if( $block['title'] != '' ){
									?><p class="footer-title"><?= $block['title'] ?></p><?php
								}
								break;
							case 'Навигация (меню)':
								$nav_items = $block['menu'];
								?>
								<div class="footer-3">
									<p>
										<?php
											foreach( $nav_items as $item ){
												?>
												<a href="<?= get_permalink( $item['post'] ) ?>"><span><?= $item['title'] == '' ? get_the_title( $item['post'] ) : $item['title'] ?></span></a><br/>
												<?php
											}
										?>
									</p>
								</div>
								<?php
								break;
							case 'Галерея':
								?>
								<div class="flickr-feed clearfix">
									<ul>
										<?php
											foreach( $block['images'] as $image_id ){
												$image = get_image( $image_id );
												if( $image->is_attachment_exists() ){
													?>
													<li><a href="<?= $image->get_src_limit( [ 1920, 1920 ] ) ?>" data-fancybox="footer" title="<?= $image->description() ?>"><?= $image->html( [ 150, 150 ] ) ?></a></li>
													<?php
												}
											}
										?>
									</ul>
								</div>
								<?php
								break;
							default:
								console_warn( 'Не найден шаблон для секции [' . $block['_flex_row_id'] . ']' );
								console_info( $block );
								break;
						}
						?>
						<!--//<?= $block['_flex_row_id'] ?>-->
						<?php
					}

					$col->content( ob_get_clean() );
				}
			}

			\hiweb_theme\footer::wrap()->the()
		?>
	</div>
</footer>