<?php

namespace theme\Landing;


use hiweb\components\Fields\Field_Options;
use hiweb\components\Fields\Types\Repeat\Field_Repeat_Col;
use hiweb\components\Fields\Types\Repeat\Field_Repeat_Flex;
use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;


class Landing_Object {

    private $id;

    private $css = [];
    private $js = [];
    /** @var null|string */
    private $template;
    private $template_callback;
    private $template_callback_args = [];

    private $section_before_callback;
    private $section_before_callback_args;

    private $section_after_callback;
    private $section_after_callback_args;

    private $landing;


    public function __construct(Landing $landing, $objectId) {
        $this->landing = $landing;
        $this->id = $objectId;
    }


    /**
     * @return mixed
     */
    public function get_id() {
        return $this->id;
    }


    /**
     * @return Landing
     */
    public function get_landing(): Landing {
        return $this->landing;
    }


    /**
     * @return Field_Repeat_Options
     */
    public function get_objects_field(): Field_Repeat_Options {
        return $this->get_landing()->get_objects_field();
    }


    /**
     * @return Field_Repeat_Flex
     */
    public function get_flex(): Field_Repeat_Flex {
        return $this->get_objects_field()->field()->get_flex($this->get_id());
    }


    /**
     * @param Field_Options $field_Options
     * @return Field_Repeat_Col
     */
    public function add_field(Field_Options $field_Options): Field_Repeat_Col {
        return $this->get_objects_field()->add_col_flex_field($this->get_id(), $field_Options);
    }


    /**
     * Set css file(s) for this object
     * @param $pathUrlOrHandle - path | url | handle, string or string[]
     * @return array
     */
    public function add_css($pathUrlOrHandle): array {
        if ( !is_array($pathUrlOrHandle)) $pathUrlOrHandle = [ $pathUrlOrHandle ];
        $this->css = array_merge($this->css, $pathUrlOrHandle);
        return $this->css;
    }


    /**
     * @return array
     */
    public function get_css(): array {
        return $this->css;
    }


    /**
     * Set js file(s) for this object
     * @param $pathUrlOrHandle - path | url | handle, string or string[]
     * @return array
     */
    public function add_js($pathUrlOrHandle): array {
        if ( !is_array($pathUrlOrHandle)) $pathUrlOrHandle = [ $pathUrlOrHandle ];
        $this->js = array_merge($this->js, $pathUrlOrHandle);
        return $this->js;
    }


    /**
     * @return array
     */
    public function get_js(): array {
        return $this->js;
    }


    /**
     * @param string $label
     */
    public function set_label(string $label) {
        $this->get_flex()->label($label);
    }


    /**
     * @param string $description
     */
    public function set_description(string $description) {
        $this->get_flex()->description($description);
    }


    /**
     * @param string $fontawesome
     */
    public function set_icon(string $fontawesome) {
        $this->get_flex()->icon($fontawesome);
    }


    /**
     * Set template php file
     * @param $templateFile
     */
    public function set_template($templateFile) {
        $this->template = $templateFile;
    }


    public function set_template_callback($callback, $args = []) {
        $this->template_callback = $callback;
        $this->template_callback_args = (array)$args;
    }


    /**
     * @param array $args
     * @return bool
     */
    public function get_html($args = []): bool {
        $result = false;
        if ( !is_null($this->template_callback) && is_callable($this->template_callback)) {
            call_user_func($this->template_callback, $args, $this->template_callback_args);
            $result = true;
        }
        if (is_string($this->template) && $this->template != '' && file_exists($this->template) && is_file($this->template) && is_readable($this->template)) {
            try {
                include $this->template;
                $result = true;
            } catch(\Exception $exception) {
                console_warn(printf('Error while include template [%s]', $this->template), __METHOD__);
            }
        }
        return $result;
    }


    /**
     * @param       $callback
     * @param array $args
     * @return $this
     */
    public function set_section_before_callback($callback, $args = []): Landing_Object {
        $this->section_before_callback = $callback;
        $this->section_before_callback_args = $args;
        return $this;
    }


    /**
     * @param       $callback
     * @param array $args
     * @return $this
     */
    public function set_section_after_callback($callback, $args = []): Landing_Object {
        $this->section_after_callback = $callback;
        $this->section_after_callback_args = $args;
        return $this;
    }


    /**
     * @return bool|null
     */
    public function call_section_before(): ?bool {
        if ( !is_null($this->section_before_callback) && is_callable($this->section_before_callback)) return call_user_func($this->section_before_callback, $this->section_before_callback_args);
        return null;
    }


    /**
     * @return bool|null
     */
    public function call_section_after(): ?bool {
        if ( !is_null($this->section_after_callback) && is_callable($this->section_after_callback)) return call_user_func($this->section_after_callback, $this->section_after_callback_args);
        return null;
    }

}