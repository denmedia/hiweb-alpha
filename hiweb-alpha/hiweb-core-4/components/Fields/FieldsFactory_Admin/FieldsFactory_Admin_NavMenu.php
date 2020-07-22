<?php
	
	namespace hiweb\components\Fields\FieldsFactory_Admin;
	
	
	use hiweb\components\Fields\FieldsFactory;
	use hiweb\components\Fields\FieldsFactory_Admin;
	
	
	class FieldsFactory_Admin_NavMenu{
		
		static function wp_nav_menu_item_custom_fields( $item_id, $item, $depth, $args, $id ){
			echo FieldsFactory_Admin::get_ajax_form_html( [ 'nav_menu' => [ 'ID' => $item->ID, 'depth' => $depth ] ], [ 'name_before' => 'hiweb-nav_menu-', 'name_after' => '[' . $item_id . ']' ] );
		}
		
		
		static function wp_update_nav_menu_item( $menu_id, $menu_item_db_id, $args ){
			if( !array_key_exists( 'hiweb-core-field-form-nonce', $_POST ) || !wp_verify_nonce( $_POST['hiweb-core-field-form-nonce'], 'hiweb-core-field-form-save' ) ) return;
			$fields = FieldsFactory::get_field_by_query( [ 'nav_menu' => [] ] );
			foreach( $fields as $Field ){
				$field_name = 'hiweb-nav_menu-' . $Field->id();
				if( array_key_exists( $field_name, $_POST ) && array_key_exists( $menu_item_db_id, $_POST[ $field_name ] ) ){
					update_post_meta( $menu_item_db_id, $Field->id(), $Field->get_sanitize_admin_value( $_POST[ $field_name ][ $menu_item_db_id ], true ) );
				}
				else{
					update_post_meta( $menu_item_db_id, $Field->id(), $Field->get_sanitize_admin_value( '', true ) );
				}
			}
		}
		
	}