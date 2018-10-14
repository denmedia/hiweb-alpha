<?php
	/**
	 * Created by PhpStorm.
	 * User: denmedia
	 * Date: 08.10.2018
	 * Time: 9:42
	 */


	namespace hiweb_theme\widgets\bootstrap;

	require_once __DIR__.'/row_options.php';


	class row_direction extends row_options{


		public function row(){ return $this->set_classes('flex-row'); }
		public function row_reverse(){ return $this->set_classes('flex-row-reverse'); }
		public function column(){ return $this->set_classes('flex-column'); }
		public function column_reverse(){ return $this->set_classes('flex-column-reverse'); }
		public function sm_row(){ return $this->set_classes('flex-sm-row'); }
		public function sm_row_reverse(){ return $this->set_classes('flex-sm-row-reverse'); }
		public function sm_column(){ return $this->set_classes('flex-sm-column'); }
		public function sm_column_reverse(){ return $this->set_classes('flex-sm-column-reverse'); }
		public function md_row(){ return $this->set_classes('flex-md-row'); }
		public function md_row_reverse(){ return $this->set_classes('flex-md-row-reverse'); }
		public function md_column(){ return $this->set_classes('flex-md-column'); }
		public function md_column_reverse(){ return $this->set_classes('flex-md-column-reverse'); }
		public function lg_row(){ return $this->set_classes('flex-lg-row'); }
		public function lg_row_reverse(){ return $this->set_classes('flex-lg-row-reverse'); }
		public function lg_column(){ return $this->set_classes('flex-lg-column'); }
		public function lg_column_reverse(){ return $this->set_classes('flex-lg-column-reverse'); }
		public function xl_row(){ return $this->set_classes('flex-xl-row'); }
		public function xl_row_reverse(){ return $this->set_classes('flex-xl-row-reverse'); }
		public function xl_column(){ return $this->set_classes('flex-xl-column'); }
		public function xl_column_reverse(){ return $this->set_classes('flex-xl-column-reverse'); }
	}