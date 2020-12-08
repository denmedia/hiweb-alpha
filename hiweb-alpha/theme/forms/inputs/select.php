<?php
/**
 * Created by PhpStorm.
 * User: denmedia
 * Date: 16.10.2018
 * Time: 19:03
 */

namespace theme\forms\inputs;


use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;
use hiweb\core\ArrayObject\ArrayObject;


class select extends input {

    static $input_title = 'Выпадающий список';


    static function add_repeat_field(Field_Repeat_Options $parent_repeat_field) {
        $parent_repeat_field->add_col_flex_field(self::$input_title, add_field_text('label')->placeholder('Лэйбл выпадающего списка'))->compact(1)->flex()->icon('<i class="far fa-chevron-square-down"></i>');
        $parent_repeat_field->add_col_flex_field(self::$input_title, add_field_text('name')->placeholder('Имя поля на латинице'))->label('Имя поля на латинице')->compact(1);
        $parent_repeat_field->add_col_flex_field(self::$input_title, add_field_textarea('variants')->placeholder('Варианты, на каждой новой строчке'))->label('Варианты списка');
        $parent_repeat_field->add_col_flex_field(self::$input_title, add_field_checkbox('require')->label_checkbox('Обязательно для заполнения'))->compact(1);
        $parent_repeat_field->add_col_flex_field(self::$input_title, add_field_text('require-message')->label('Сообщение об ошибке проверки')->default_value('Выберите вариант'))->compact(1);
        $parent_repeat_field->add_col_flex_field(self::$input_title, add_field_checkbox('send_enable')->label_checkbox('Не отправлять данное поле по почте'))->compact(1);
    }


    public function the_prefix() {
        ?>
        <div class="input-wrap input-wrap-<?= self::get_name() ?>">
        <?php if (self::get_data('label') != '') {
            ?>

            <?php
        } elseif (self::is_required_empty_label()) {
            ?>
            <div class="required-empty-label">
            <?php
        }
    }


    public function the() {
        $this->the_prefix();
        $items = self::get_data('variants');
        if (trim($items) == '') return;
        ?>
        <div class="form-input-field-title"><?= self::get_data('label') ?>  <?= $this->is_required() ? '<span class="required">*</span>' : '' ?></div>
        <select name="<?= self::get_name() ?>">
            <?php
            foreach (explode("\n", $items) as $item) {
                if ($item == '') continue;
                ?>
                <option value="<?= htmlentities(trim($item)) ?>"><?= $item ?></option>
                <?php
            }
            ?>
        </select>
        <?php
        $this->the_sufix();
    }


    public function the_sufix() {
        ?>
        <?= self::get_require_error_message_html() ?>
        </div>
        <?php
        if (self::is_required_empty_label()) {
            ?>
            </div>
            <?php
        }
    }


    /**
     * @return bool
     */
    public function is_email_submit_enable() {
        return self::get_data('send_enable') != 'on';
    }


    /**
     * @param string $submit_value
     * @return bool
     */
    public function is_required_validate($submit_value = '') {
        return $submit_value !== '';
    }


}