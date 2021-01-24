<?php

add_admin_menu_page(\theme\error_404::$admin_menu_slug, __('Page 404', 'hiweb-core-4'), \theme\error_404::$admin_menu_parent)->icon_url('fas fa-exclamation-square')->page_title(__('Error page 404', 'hiweb-core-4'));

add_field_text('title')->font_size(1.4)->default_value('404')->label(__('Page title'))->location()->options(\theme\error_404::$admin_menu_slug);
add_field_content('content')->editor_height(200)->default_value(__('<h2>Oops! Page not found.</h2><h4>Sorry...The requested URL was not found on the server....</h4>'))->label(__('Page text content'))->location()->options(\theme\error_404::$admin_menu_slug);