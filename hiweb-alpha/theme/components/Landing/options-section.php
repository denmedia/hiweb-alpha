<?php

/**
 * @var Landing $this
 */

use theme\Landing\Landing;


///Section options
$this->sectionsField->add_col_field(add_field_separator('Section options'))->hidden(true);
$this->sectionsField->add_col_field(add_field_text('section-id'))->label('Section ID')->hidden(true);
$this->sectionsField->add_col_field(add_field_checkbox('disable-section_wrap')->label_checkbox('Disable section and inner div <code>' . htmlentities('<section><div>...</div></section>') . '</code> wrap. Most used when slider section or some things.'))->hidden(true);
$this->sectionsField->add_col_field(add_field_checkbox('hide-mobile')->label_checkbox('Hide this section on mobile devices'))->hidden(true);
$this->sectionsField->add_col_field(add_field_color('bg-color'))->label(__('Background color', 'hiweb-core-4'))->hidden(true);
$this->sectionsField->add_col_field(add_field_image('bg-image'))->label('Background image')->hidden(true);
$this->sectionsField->add_col_field(add_field_text('section-class'))->label('Append classes to tag <code>class="..."</code>')->hidden(true);
$this->sectionsField->add_col_field(add_field_text('section-style'))->label('Append styles to tag <code>style="..."</code>')->hidden(true);
///Inner section options
$this->sectionsField->add_col_field(add_field_separator('Inner section wrap options'))->hidden(true);
$this->sectionsField->add_col_field(add_field_select('inner-wide')->options([ 'container' => 'Container', 'container-fluid' => 'Fluid container', 'none' => 'Full width' ])->default_value('container')->label(__('Stretch a section to its full available width', 'hiweb-core-4')))->hidden(true);