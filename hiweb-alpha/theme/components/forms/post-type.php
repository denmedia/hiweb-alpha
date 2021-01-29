<?php
/**
 * Created by PhpStorm.
 * User: denisivanin
 * Date: 2019-01-20
 * Time: 18:18
 */

use theme\forms;
use theme\forms\inputs\button;
use theme\forms\inputs\checkbox;
use theme\forms\inputs\checkboxes;
use theme\forms\inputs\select;
use theme\forms\inputs\email;
use theme\forms\inputs\html_insert;
use theme\forms\inputs\image;
use theme\forms\inputs\info_text;
use theme\forms\inputs\json;
use theme\forms\inputs\listing;
use theme\forms\inputs\number;
use theme\forms\inputs\phone;
use theme\forms\inputs\postlink;
use theme\forms\inputs\privacy;
use theme\forms\inputs\text;
use theme\forms\inputs\textarea;


self::$post_type_object = add_post_type(self::$post_type_name);
self::$post_type_object->menu_icon('fas fa-comment-alt-edit');
//self::$post_type->menu_icon('data:image/svg+xml;base64,');
self::$post_type_object->labels()->menu_name(__('Contact Form', 'hiweb-core-4'))->name(__('Forms', 'hiweb-core-4'));
self::$post_type_object->supports()->title();
self::$post_type_object->public_(true)->publicly_queryable(false)->has_archive(false)->show_ui(true)->show_in_menu(true)->show_in_nav_menus(false)->show_in_admin_bar(false)->exclude_from_search(true);
///
add_action('admin_menu', function() {
    global $menu, $submenu;
    $submenu['edit.php?post_type=' . forms::$post_type_name][5][0] = get_fontawesome('fal fa-list-alt') . ' ' . __('Forms', 'hiweb-core-4');
    $submenu['edit.php?post_type=' . forms::$post_type_name][10][0] = get_fontawesome('fal fa-comment-alt-plus') . ' ' . __('Create form', 'hiweb-core-4');
});
///
add_field_tab(__('Form', 'hiweb-core-4'))->location()->posts(self::$post_type_name);
add_field_checkbox('show-title')->label_checkbox(__('Show form title', 'hiweb-core-4'))->location(true);
$INPUTS = add_field_repeat('inputs');
$INPUTS->label(__('Input fields', 'hiweb-core-4'))->location()->posts(self::$post_type_name)->columnsManager()->name(__('Shortcodes', 'hiweb-core-4'))->callback(function($post_id) { hw_template_part('columns-shortcode', '', get_defined_vars()); });
//
text::add_repeat_field($INPUTS);
number::add_repeat_field($INPUTS);
textarea::add_repeat_field($INPUTS);
email::add_repeat_field($INPUTS);
phone::add_repeat_field($INPUTS);
checkbox::add_repeat_field($INPUTS);
checkboxes::add_repeat_field($INPUTS);
select::add_repeat_field($INPUTS);
privacy::add_repeat_field($INPUTS);
button::add_repeat_field($INPUTS);
json::add_repeat_field($INPUTS);
postlink::add_repeat_field($INPUTS);
info_text::add_repeat_field($INPUTS);
html_insert::add_repeat_field($INPUTS);
image::add_repeat_field($INPUTS);
listing::add_repeat_field($INPUTS);
//

$strtr_descriptions = [];
foreach (
    forms::get_strtr_templates([
        '{data-list}' => __('List of filled data', 'hiweb-core-4'), //'Список заполненных данных'
        '{name}' => __('Content of this field (replace {name} with the field name)', 'hiweb-core-4') //'Содержимое данного поля (вместо {name} укажите имя поля)'
    ], true) as $key => $descript
) {
    $strtr_descriptions[] = '<code>' . $key . '</code> - ' . $descript;
}
$strtr_descriptions = implode(', ', $strtr_descriptions);
//
add_field_tab(__('Submission status of this form', 'hiweb-core-4'))->location()->posts(self::$post_type_name);
///
add_field_separator(__('AJAX form submit status', 'hiweb-core-4'), sprintf(__('These settings are relevant only for this form. If left blank, the default settings from the <a data-tooltip="Open Options Page" href="%s"> Form Options </a> page will be used instead', 'hiweb-core-4'), get_admin_url(null, 'edit.php?post_type=' . self::$post_type_name . '&page=' . self::$options_name)))->location()->posts(self::$post_type_name);
add_field_fontawesome('icon-process')->label(__('Submission process icon', 'hiweb-core-4'))->location(true);
add_field_fontawesome('icon-success')->label(__('Successful message icon', 'hiweb-core-4'))->location(true);
add_field_fontawesome('icon-warn')->label(__('Incorrectly filled form icon', 'hiweb-core-4'))->location(true);
add_field_fontawesome('icon-error')->label(__('Error icon while submitting', 'hiweb-core-4'))->location(true);
add_field_textarea('text-process')->label(__('Form submit text', 'hiweb-core-4'))->location(true);
add_field_textarea('text-success')->label(__('Successful form submission text', 'hiweb-core-4'))->location(true);
add_field_textarea('text-warn')->label(__('Filled form error text', 'hiweb-core-4'))->location(true);
add_field_textarea('text-error')->label(__('Error text during form submission', 'hiweb-core-4'))->location(true);
add_field_tab(__('Email templates', 'hiweb-core-4'))->location(true);
///
add_field_separator(__('Email templates for this form', 'hiweb-core-4'), sprintf(__('These template settings are relevant only for this form. If left blank, the default settings from the <a data-tooltip="Open Options Page" href="%s"> Form Options </a> page will be used instead', 'hiweb-core-4'), get_admin_url(null, 'edit.php?post_type=' . self::$post_type_name . '&page=' . self::$options_name)))->location()->posts(self::$post_type_name);
add_field_text('theme-email-admin')->label(__('Email subject for admin', 'hiweb-core-4'))->description($strtr_descriptions)->location(true);
add_field_content('content-email-admin')->label(__('Default email content for admin', 'hiweb-core-4'))->description($strtr_descriptions)->location(true);
add_field_checkbox('send-client-email')->label_checkbox(__('Send a email to the form filler to the address specified by him, if the form had an email field and it was filled in correctly.', 'hiweb-core-4'))->location(true);
add_field_text('theme-email-client')->label(__('Placeholder email subject', 'hiweb-core-4'))->description($strtr_descriptions)->location(true);
add_field_content('content-email-client')->label(__('Default email content for placeholder', 'hiweb-core-4'))->description($strtr_descriptions)->location(true);
///
add_field_tab('JavaScript Events')->location(true);
add_field_textarea('callback_js')->label(__('JavaScript that will be executed if the form is submitted successfully.', 'hiweb-core-4'))->description(sprintf(__('Filling example:', 'hiweb-core-4'), " <code>let foo = 'bar';\nalert(foo);</code>"))->location(true);