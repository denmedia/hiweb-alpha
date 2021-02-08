<?php
/**
 * @var \theme\Landing\Landing $this
 */

$object = $this->add_object('_editor');
$object->add_field(add_field_content('content'));
$object->set_icon('far fa-text-size');
$object->set_label('Text editor');
$object->set_description('Edit text by WYSIWYG editor');
$object->set_template_callback(function(){
    echo apply_filters('the_content', the_landing_object_array()->content);
});