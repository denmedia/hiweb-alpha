<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08.10.2018
	 * Time: 10:05
	 */

	namespace hiweb_theme\widgets\bootstrap;


	require_once __DIR__ . '/row_options.php';


	class row_align_content extends row_options{

		public function start(){ return $this->set_classes('align-content-start'); }
		public function end(){ return $this->set_classes('align-content-end'); }
		public function center(){ return $this->set_classes('align-content-center'); }
		public function around(){ return $this->set_classes('align-content-around'); }
		public function stretch(){ return $this->set_classes('align-content-stretch'); }
		public function sm_start(){ return $this->set_classes('align-content-sm-start'); }
		public function sm_end(){ return $this->set_classes('align-content-sm-end'); }
		public function sm_center(){ return $this->set_classes('align-content-sm-center'); }
		public function sm_around(){ return $this->set_classes('align-content-sm-around'); }
		public function sm_stretch(){ return $this->set_classes('align-content-sm-stretch'); }
		public function md_start(){ return $this->set_classes('align-content-md-start'); }
		public function md_end(){ return $this->set_classes('align-content-md-end'); }
		public function md_center(){ return $this->set_classes('align-content-md-center'); }
		public function md_around(){ return $this->set_classes('align-content-md-around'); }
		public function md_stretch(){ return $this->set_classes('align-content-md-stretch'); }
		public function lg_start(){ return $this->set_classes('align-content-lg-start'); }
		public function lg_end(){ return $this->set_classes('align-content-lg-end'); }
		public function lg_center(){ return $this->set_classes('align-content-lg-center'); }
		public function lg_around(){ return $this->set_classes('align-content-lg-around'); }
		public function lg_stretch(){ return $this->set_classes('align-content-lg-stretch'); }
		public function xl_start(){ return $this->set_classes('align-content-xl-start'); }
		public function xl_end(){ return $this->set_classes('align-content-xl-end'); }
		public function xl_center(){ return $this->set_classes('align-content-xl-center'); }
		public function xl_around(){ return $this->set_classes('align-content-xl-around'); }
		public function xl_stretch(){ return $this->set_classes('align-content-xl-stretch'); }

	}