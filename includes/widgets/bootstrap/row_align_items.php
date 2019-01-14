<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08.10.2018
	 * Time: 10:01
	 */

	namespace hiweb_theme\widgets\bootstrap;


	require_once __DIR__ . '/row_options.php';


	class row_align_items extends row_options{

		public function start(){ return $this->set_classes('align-items-start'); }
		public function end(){ return $this->set_classes('align-items-end'); }
		public function center(){ return $this->set_classes('align-items-center'); }
		public function baseline(){ return $this->set_classes('align-items-baseline'); }
		public function stretch(){ return $this->set_classes('align-items-stretch'); }
		public function sm_start(){ return $this->set_classes('align-items-sm-start'); }
		public function sm_end(){ return $this->set_classes('align-items-sm-end'); }
		public function sm_center(){ return $this->set_classes('align-items-sm-center'); }
		public function sm_baseline(){ return $this->set_classes('align-items-sm-baseline'); }
		public function sm_stretch(){ return $this->set_classes('align-items-sm-stretch'); }
		public function md_start(){ return $this->set_classes('align-items-md-start'); }
		public function md_end(){ return $this->set_classes('align-items-md-end'); }
		public function md_center(){ return $this->set_classes('align-items-md-center'); }
		public function md_baseline(){ return $this->set_classes('align-items-md-baseline'); }
		public function md_stretch(){ return $this->set_classes('align-items-md-stretch'); }
		public function lg_start(){ return $this->set_classes('align-items-lg-start'); }
		public function lg_end(){ return $this->set_classes('align-items-lg-end'); }
		public function lg_center(){ return $this->set_classes('align-items-lg-center'); }
		public function lg_baseline(){ return $this->set_classes('align-items-lg-baseline'); }
		public function lg_stretch(){ return $this->set_classes('align-items-lg-stretch'); }
		public function xl_start(){ return $this->set_classes('align-items-xl-start'); }
		public function xl_end(){ return $this->set_classes('align-items-xl-end'); }
		public function xl_center(){ return $this->set_classes('align-items-xl-center'); }
		public function xl_baseline(){ return $this->set_classes('align-items-xl-baseline'); }
		public function xl_stretch(){ return $this->set_classes('align-items-xl-stretch'); }

	}