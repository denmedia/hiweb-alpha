<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08.10.2018
	 * Time: 9:49
	 */

	namespace hiweb_theme\widgets\bootstrap;


	class row_wrap extends row_options{

		public function nowrap(){ return $this->set_classes('flex-nowrap'); }
		public function wrap(){ return $this->set_classes('flex-wrap'); }
		public function wrap_reverse(){ return $this->set_classes('flex-wrap-reverse'); }
		public function sm_nowrap(){ return $this->set_classes('flex-sm-nowrap'); }
		public function sm_wrap(){ return $this->set_classes('flex-sm-wrap'); }
		public function sm_wrap_reverse(){ return $this->set_classes('flex-sm-wrap-reverse'); }
		public function md_nowrap(){ return $this->set_classes('flex-md-nowrap'); }
		public function md_wrap(){ return $this->set_classes('flex-md-wrap'); }
		public function md_wrap_reverse(){ return $this->set_classes('flex-md-wrap-reverse'); }
		public function lg_nowrap(){ return $this->set_classes('flex-lg-nowrap'); }
		public function lg_wrap(){ return $this->set_classes('flex-lg-wrap'); }
		public function lg_wrap_reverse(){ return $this->set_classes('flex-lg-wrap-reverse'); }
		public function xl_nowrap(){ return $this->set_classes('flex-xl-nowrap'); }
		public function xl_wrap(){ return $this->set_classes('flex-xl-wrap'); }
		public function xl_wrap_reverse(){ return $this->set_classes('flex-xl-wrap-reverse'); }

	}