<?php

$section = the_landing()->get_last_context()->the_sections_rows();

///section classes
$section_class = the_landing()->get_section_class(false);
if ($section->get_sub_field('section-class', '') != '') $section_class = array_merge($section_class, (array)$section->get_sub_field('section-class'));
///section inner classes
$section_inner_class = the_landing()->get_section_inner_class(false);
$section_inner_class[] = $section->get_sub_field('inner-wide', 'container') == 'none' ? '' : $section->get_sub_field('wide', 'container');
///section style
$section_style = get_array();
if ($section->get_sub_field('bg-color') != '') $section_style->push('background-color', $section->get_sub_field('bg-color'));
if ($section->get_sub_field('bg-image') != '') {
    $bg_image = get_image($section->get_sub_field('bg-image'));
    if ($bg_image->is_exists()) {
        $section_style->push('background-image', 'url(' . $bg_image->get_src([ 1920, 800, 1 ]) . ')');
    }
}
if ($section->get_sub_field('section-style') != '') $section_style->push($section->get_sub_field('section-style'));
///collect attributes
$section_attributes = get_array();
$section_attributes->push('class', join(' ', $section_class));
if ( !$section_style->is_empty()) $section_attributes->push('style', $section_style->get_as_tag_style());
if ($section->get_sub_field('section-id') != '') $section_attributes->push('id', $section->get_sub_field('section-id'));

?>
<section <?= $section_attributes->get_as_tag_attributes() ?>>
    <div class="<?= esc_attr(join(' ', $section_inner_class)) ?>">