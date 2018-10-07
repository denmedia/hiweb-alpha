<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 05.10.2018
	 * Time: 22:58
	 */

	namespace hiweb_theme\modules\slider;


	use hiweb\files\file;
	use hiweb\images\image;
	use hiweb\strings;


	class slide{

		public $index = 0;
		public $is_video = false;
		public $is_image = false;
		/** @var image */
		public $image;
		/** @var file */
		public $video_mp4;
		/** @var file */
		public $video_webm;
		/** @var file */
		public $video_ogv;
		public $video_muted = true;
		/** @var string */
		public $content;
		public $content_classes = [];
		public $darken = false;


		public function __construct( $index ){
			$this->index = $index;
		}


		public function set_image( $attachIdOrSrc ){
			$this->image = get_image( $attachIdOrSrc );
			$this->is_image = $this->image->is_attachment_exists() && !$this->is_video;
		}


		/**
		 * Set mp4, webm or ogv file
		 * @param $pathOrUrlOrId
		 * @return bool
		 */
		public function set_video( $pathOrUrlOrId ){
			$file = \hiweb\file( $pathOrUrlOrId );
			if( !$file->is_exists_and_readable() ){
				console_warn( 'Указанный файл [' . $pathOrUrlOrId . '] для [' . __CLASS__ . '] указан не верно' );
				return false;
			}
			switch( $file->extension ){
				case 'mp4':
					$this->video_mp4 = $file;
					$this->is_video = $file->is_exists_and_readable();
					if( $this->is_image ) $this->is_image = !$this->is_video;
					return true;
					break;
				case 'webm':
					$this->video_webm = $file;
					$this->is_video = $file->is_exists_and_readable();
					if( $this->is_image ) $this->is_image = !$this->is_video;
					return true;
					break;
				case 'org':
					$this->video_ogv = $file;
					$this->is_video = $file->is_exists_and_readable();
					if( $this->is_image ) $this->is_image = !$this->is_video;
					return true;
					break;
				default:
					console_warn( 'Указанный файл [' . $pathOrUrlOrId . '] для [' . __CLASS__ . '] имеет не верный формат [' . $file->extension . ']' );
					break;
			}
			return false;
		}


		public function set_content( $htmlOrText ){
			$this->content = $htmlOrText;
		}


		private function get_slide_class(){
			$classes = [ 'slide' ];
			if( $this->is_video ) $classes[] = 'video'; elseif( $this->is_image ) $classes[] = 'image';
			if( $this->darken ) $classes[] = 'darken';
			return 'class="' . implode( ' ', $classes ) . '"';
		}


		public function the(){
			if( !$this->is_image && !$this->is_video ) return;
			?>
			<div <?= $this->get_slide_class() ?>>
				<?php
					if( $this->is_video ) $this->the_video();
					if( $this->is_image ) $this->the_image();
				?>
				<?php
					if( $this->content != '' ){
						?>
						<div class="content">
							<?= $this->content ?>
						</div>
						<?php
					}
				?>
			</div>
			<?php
		}


		private function the_video(){
			$id_rand = strings::rand();
			$poster = '';
			if( $this->image instanceof image && $this->image->is_attachment_exists() ){
				$poster = 'poster="' . $this->image->get_original_src() . '"';
			}
			?>
			<video id="<?= $id_rand ?>" <?= wp_is_mobile() ? 'controls' : '' ?> width="100%" height="auto" preload="auto" playsinline <?= get_field( 'slider-mute' ) ? 'muted' : '' ?> <?= $poster ?> <?= $this->video_muted ? 'muted' : '' ?> >
				<?php if( $this->video_mp4 instanceof \hiweb\files\file && $this->video_mp4->is_exists_and_readable() ){
					?>
					<source src="<?= $this->video_mp4->url ?>" type="video/mp4">
					<?php
				} ?>
				<?php if( $this->video_webm instanceof \hiweb\files\file && $this->video_webm->is_exists_and_readable() ){
					?>
					<source src="<?= $this->video_webm->url ?>" type="video/webm">
					<?php
				} ?>
				<?php
					if( $this->video_ogv instanceof \hiweb\files\file && $this->video_ogv->is_exists_and_readable() ){
						?>
						<source src="<?= $this->video_ogv->url ?>" type="video/ogv">
						<?php
					}
				?>
			</video>
			<?php
		}


		private function the_image(){
			?>
			<img class="slide-poster" src="<?= $this->image->get_src( [ 1920, 1080 ], 1 ) ?>"/>
			<?php
		}

	}