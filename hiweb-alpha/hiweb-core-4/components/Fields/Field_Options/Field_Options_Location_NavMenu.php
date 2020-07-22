<?php
	
	namespace hiweb\components\Fields\Field_Options;
	
	
	use hiweb\core\Options\Options;
	
	
	class Field_Options_Location_NavMenu extends Options{
		
		public function __construct( $parent_OptionsObject = null ){
			parent::__construct( $parent_OptionsObject );
		}
		
		
		public function depth( $set = null ){
			return $this->_( 'depth', $set );
		}
		
	}