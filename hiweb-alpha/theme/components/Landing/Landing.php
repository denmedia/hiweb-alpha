<?php

namespace theme\Landing;


use hiweb\components\Fields\Field_Options\Field_Options_Location;
use hiweb\components\Fields\Types\Repeat\Field_Repeat_Options;
use hiweb\components\Structures\StructuresFactory;
use hiweb\core\Strings;


class Landing {

    /** @var string */
    private $id;
    /** @var Landing_Object[] */
    private $objects;
    /** @var Field_Repeat_Options */
    private $sectionsField;
    /** @var Field_Repeat_Options */
    private $objectsField;
    /** @var Landing_Context[] */
    private $contexts = [];
    /** @var Landing_Context */
    private $last_context;
    private $wrap_enable = true;
    /** @var bool|int|string Landing wrap id="..." */
    private $wrap_id;
    /** @var string[] Landing wrap class="..." */
    private $wrap_classes = [ 'landing-wrap' ];

    private $section_class = [ 'landing-section' ];
    private $section_inner_class = [ 'landing-section-inner' ];
    //    /** @var array */
    //    private $sectionDefaultClass = [];
    //    /** @var array */
    //    private $objectDefaultClass = [];

    /**
     * Landing constructor.
     * @param string $landingId
     */
    public function __construct($landingId = 'landing') {
        $this->id = Strings::sanitize_id($landingId);
        $this->sectionsField = add_field_repeat($this->id);
        $this->objectsField = add_field_repeat('objects');
        $this->wrap_id = $this->id;
        $this->wrap_classes[] = 'landing-wrap-' . $this->id;
        ///setup sections field
        $this->sectionsField->add_col_field($this->objectsField);
        $this->sectionsField->label_button_new_row(__('Add new section', 'hiweb-core-4'));
        ///options
        require __DIR__ . '/options-section.php';
        ///default objects
        include __DIR__ . '/objects/cols.php';
        include __DIR__ . '/objects/editor.php';
    }


    /**
     * @return string
     */
    public function get_id(): string {
        return (string)$this->id;
    }


    /**
     * @param bool $enable
     * @return Landing
     */
    public function set_wrap_enable($enable = true): Landing {
        $this->wrap_enable = (bool)$enable;
        return $this;
    }


    /**
     * Return true if landing wrap is enable
     * @return bool
     */
    public function get_wrap_enabled(): bool {
        return (bool)$this->wrap_enable;
    }


    /**
     * @param $id
     * @return $this
     */
    public function set_wrap_id($id): Landing {
        $this->wrap_id = (string)$id;
        return $this;
    }


    /**
     * Return wrap ID tag
     * @return string
     */
    public function get_wrap_id(): string {
        return $this->wrap_id;
    }


    /**
     * Set/add landing class to class="..." tag
     * @param string|array $class
     * @param bool         $clear_current_class
     * @return $this
     */
    public function set_wrap_class($class, $clear_current_class = false): Landing {
        $class = is_array($class) ? $class : [ $class ];
        $this->wrap_classes = $clear_current_class ? $class : array_merge($this->wrap_classes, $class);
        return $this;
    }


    /**
     * Return class="..." tag content
     * @return string
     */
    public function get_wrap_class(): string {
        return join(' ', $this->wrap_classes);
    }


    /**
     * @param $objectId
     * @return Landing_Object
     */
    public function add_object($objectId): Landing_Object {
        $objectId = Strings::sanitize_id($objectId);
        if ( !array_key_exists($objectId, $this->objects)) {
            $this->objects[$objectId] = new Landing_Object($this, $objectId);
        }
        return $this->objects[$objectId];
    }


    /**
     * @param string|array $class
     * @return array
     */
    public function add_section_class($class): array {
        if ( !is_array($class)) $class = [ $class ];
        $this->section_class = array_merge($this->section_class, $class);
        return $this->section_class;
    }


    /**
     * @param bool $join
     * @return string|string[]
     */
    public function get_section_class($join = true) {
        return $join ? join(' ', $this->section_class) : $this->section_class;
    }


    /**
     * @param $class
     * @return array
     */
    public function add_section_inner_class($class): array {
        if ( !is_array($class)) $class = [ $class ];
        $this->section_inner_class = array_merge($this->section_inner_class, $class);
        return $this->section_inner_class;
    }


    /**
     * @param bool $join
     * @return string|string[]
     */
    public function get_section_inner_class($join = true) {
        return $join ? join(' ', $this->section_inner_class) : $this->section_inner_class;
    }


    /**
     * @param $objectId
     * @return Landing_Object|null
     */
    public function get_object($objectId): ?Landing_Object {
        if ( !array_key_exists($objectId, $this->objects)) {
            return null;
        }
        return $this->objects[$objectId];
    }


    /**
     * @return Field_Repeat_Options
     */
    public function get_sections_field(): Field_Repeat_Options {
        return $this->sectionsField;
    }


    /**
     * @param null $contextObject
     * @param bool $setupContext
     * @return Landing_Context
     */
    public function get_context($contextObject = null, $setupContext = true): Landing_Context {
        $contextId = StructuresFactory::get_id_from_object($contextObject);
        if ( !array_key_exists($contextId, $this->contexts)) {
            $this->contexts[$contextId] = new Landing_Context($this, $contextObject);
        }
        if ($setupContext) $this->last_context = $this->contexts[$contextId];
        return $this->contexts[$contextId];
    }


    /**
     * @return Field_Repeat_Options
     */
    public function get_objects_field(): Field_Repeat_Options {
        return $this->objectsField;
    }


    /**
     * Set landing location in admin screens
     * @return Field_Options_Location
     */
    public function set_location(): Field_Options_Location {
        return $this->get_sections_field()->location();
    }


    /**
     * Print full Landing html
     * @param null $contextObject
     */
    public function the_html($contextObject = null) {
        $this->get_context($contextObject)->the_html();
    }


    /**
     * @return Landing_Context
     */
    public function get_last_context(): Landing_Context {
        if ( !$this->last_context instanceof Landing_Context) $this->get_context();
        return $this->last_context;
    }

}