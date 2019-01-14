<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08.10.2018
	 * Time: 9:56
	 */

	namespace hiweb_theme\widgets\bootstrap;

	require_once __DIR__.'/row_options.php';


	class row_justify_content extends row_options{

		public function start(){ return $this->set_classes('justify-content-start'); }
		public function end(){ return $this->set_classes('justify-content-end'); }
		public function center(){ return $this->set_classes('justify-content-center'); }
		public function between(){ return $this->set_classes('justify-content-between'); }
		public function around(){ return $this->set_classes('justify-content-around'); }
		public function sm_start(){ return $this->set_classes('justify-content-sm-start'); }
		public function sm_end(){ return $this->set_classes('justify-content-sm-end'); }
		public function sm_center(){ return $this->set_classes('justify-content-sm-center'); }
		public function sm_between(){ return $this->set_classes('justify-content-sm-between'); }
		public function sm_around(){ return $this->set_classes('justify-content-sm-around'); }
		public function md_start(){ return $this->set_classes('justify-content-md-start'); }
		public function md_end(){ return $this->set_classes('justify-content-md-end'); }
		public function md_center(){ return $this->set_classes('justify-content-md-center'); }
		public function md_between(){ return $this->set_classes('justify-content-md-between'); }
		public function md_around(){ return $this->set_classes('justify-content-md-around'); }
		public function lg_start(){ return $this->set_classes('justify-content-lg-start'); }
		public function lg_end(){ return $this->set_classes('justify-content-lg-end'); }
		public function lg_center(){ return $this->set_classes('justify-content-lg-center'); }
		public function lg_between(){ return $this->set_classes('justify-content-lg-between'); }
		public function lg_around(){ return $this->set_classes('justify-content-lg-around'); }
		public function xl_start(){ return $this->set_classes('justify-content-xl-start'); }
		public function xl_end(){ return $this->set_classes('justify-content-xl-end'); }
		public function xl_center(){ return $this->set_classes('justify-content-xl-center'); }
		public function xl_between(){ return $this->set_classes('justify-content-xl-between'); }
		public function xl_around(){ return $this->set_classes('justify-content-xl-around'); }

	}