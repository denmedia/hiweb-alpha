<?php
/**
 * @var \theme\Landing\Landing $this
 */
$object = $this->add_object('_col');
$col_options = [];
foreach (\theme\bootstrap::$piece_to_class_simple as $key => $class) {
    $col_options[$key] = $key . ' of row';
}
$object->add_field(add_field_select('piece')->options($col_options)->default_value('1/2')->label('How much width should a column occupy in a row'))->width('50%');
$object->add_field(add_field_select('align')->options([
    'left' => 'Left align',
    'center' => 'Center align',
    'right' => 'Right align'
])->allow_empty(false))->label('Align in column')->width('50%');
$object->set_label('Row column');
$object->set_description(__('To place objects in this column, place them directly below. In order for new objects to fall into a new column, create one more object "column for objects" and place new objects after it.'));
$object->set_icon('far fa-columns');
$object->add_field(add_field_select('align_items')->options([
    'start' => 'flex-start',
    'end' => 'flex-end',
    'center' => 'center',
    'stretch' => 'stretch',
    'baseline' => 'baseline'
])->allow_empty(true))->label('Align items')->hidden(true);
$object->add_field(add_field_checkbox('nogutters')->label_checkbox('No gutters'))->hidden(true);


class Landing_Object_Col {

    static $open_row = false;
    static $open_col = false;
}


$object->set_template_callback(function($args) {
    $args = get_array($args);
    if (Landing_Object_Col::$open_col) {
        echo '</div>';
    }
    if ( !Landing_Object_Col::$open_row) {
        $row_class = [ 'row' ];
        if ($args->align_items != '') $row_class[] = 'align-items-'.$args->align_items;
        if ($args->nogutters) $row_class[] = 'no-gutters';
        echo '<div class="' . esc_attr(join(' ', $row_class)) . '">';
        Landing_Object_Col::$open_row = true;
    }
    Landing_Object_Col::$open_col = true;
    ///
    $class = [ \theme\bootstrap::piece_to_class_simple($args->piece) ];
    if ($args->align != '') {
        $class[] = 'text-' . $args->align;
    }
    echo '<div class="' . esc_attr(join(' ', $class)) . '">';
});

$object->set_section_after_callback(function() {
    if (Landing_Object_Col::$open_col) {
        Landing_Object_Col::$open_col = false;
        Landing_Object_Col::$open_row = false;
        echo '</div></div>';
    }
}); ?>