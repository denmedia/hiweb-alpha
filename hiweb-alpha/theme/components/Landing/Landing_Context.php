<?php

namespace theme\Landing;


use hiweb\core\ArrayObject\ArrayObject_Rows;


class Landing_Context {

    /** @var Landing */
    private $landing;
    private $contextObject;
    /** @var ArrayObject_Rows */
    private $sectionsRows;
    /** @var ArrayObject_Rows */
    private $objectsRows;


    public function __construct(Landing $landing, $contextObject = null) {
        $this->landing = $landing;
        $this->contextObject = $contextObject;
    }


    /**
     * @return Landing
     */
    public function get_landing(): Landing {
        return $this->landing;
    }


    /**
     * Return landing id
     * @return string
     */
    public function get_id(): string {
        return $this->get_landing()->get_id();
    }


    /**
     * @return mixed|null
     */
    public function get_contextObject() {
        return $this->contextObject;
    }


    public function get_value() {
        return get_field($this->get_id(), $this->get_contextObject());
    }


    /**
     * @param false $reset
     * @return ArrayObject_Rows
     */
    public function the_sections_rows($reset = false): ArrayObject_Rows {
        if ( !$this->sectionsRows instanceof ArrayObject_Rows || $reset) {
            $this->sectionsRows = get_array($this->get_value())->rows();
        }
        return $this->sectionsRows;
    }


    /**
     * @param false $reset
     * @return ArrayObject_Rows
     */
    public function the_objects_rows($reset = false): ArrayObject_Rows {
        if ( !$this->objectsRows instanceof ArrayObject_Rows || $reset) {
            $this->objectsRows = get_array($this->the_sections_rows()->get_sub_field('objects', []))->rows();
        }
        return $this->objectsRows;
    }


    public function the_objects_html() {
        if ( !$this->the_objects_rows()->have()) {
            hw_template_part(HIWEB_THEME_PARTS . '/landing/object_empty', $this->get_landing()->get_id());
        } else {
            ///call objects section before
            $this->the_objects_rows(true);
            while($this->the_objects_rows()->have()) {
                $this->the_objects_rows()->the();
                $object = $this->get_landing()->get_object($this->the_objects_rows()->get_layout());
                if ($object instanceof Landing_Object) {
                    $object->call_section_before();
                }
            }
            ///print objects html
            $this->the_objects_rows(true);
            while($this->the_objects_rows()->have()) {
                $this->the_objects_rows()->the();
                ///
                $object = $this->get_landing()->get_object($this->the_objects_rows()->get_layout());
                if ($object instanceof Landing_Object) {
                    hw_template_part(HIWEB_THEME_PARTS . '/landing/object_before', $this->get_landing()->get_id(), $this);
                    if ( !$object->get_html($this->the_objects_rows()->get_current()->get())) {
                        console_warn(sprintf(__('Template for object [%s] not found', 'hiweb-core-4'), $this->the_objects_rows()->get_layout()), __METHOD__);
                    }
                    hw_template_part(HIWEB_THEME_PARTS . '/landing/object_after', $this->get_landing()->get_id(), $this);
                } else {
                    console_warn(sprintf(__('Not object found [%s]', 'hiweb-core-4'), $this->the_objects_rows()->get_layout()), __METHOD__);
                }
            }
            ///call objects section before
            $this->the_objects_rows(true);
            while($this->the_objects_rows()->have()) {
                $this->the_objects_rows()->the();
                $object = $this->get_landing()->get_object($this->the_objects_rows()->get_layout());
                if ($object instanceof Landing_Object) {
                    $object->call_section_before();
                }
            }
            $this->the_objects_rows()->reset();
        }
    }


    public function the_sections_html() {
        $this->the_sections_rows(true);
        if ( !$this->the_sections_rows()->have()) {
            hw_template_part(HIWEB_THEME_PARTS . '/landing/sections_empty', $this->get_landing()->get_id());
        } else {
            while($this->the_sections_rows()->have()) {
                $this->the_sections_rows()->the();
                ///hidden on mobile
                if ($this->the_sections_rows()->get_sub_field('hide-mobile') != '' && get_client()->is_mobile()) continue;
                ///
                if ( !$this->the_sections_rows()->get_sub_field('disable-section_wrap')) hw_template_part(HIWEB_THEME_PARTS . '/landing/section_before', $this->get_landing()->get_id(), $this->the_sections_rows()->get_current());
                $this->the_objects_html();
                if ( !$this->the_sections_rows()->get_sub_field('disable-section_wrap')) hw_template_part(HIWEB_THEME_PARTS . '/landing/section_after', $this->get_landing()->get_id(), $this->the_sections_rows()->get_current());
            }
            $this->the_sections_rows()->reset();
        }
    }


    public function the_html() {
        hw_template_part(HIWEB_THEME_PARTS . '/landing/before', $this->get_landing()->get_id(), $this);
        $this->the_sections_html();
        hw_template_part(HIWEB_THEME_PARTS . '/landing/after', $this->get_landing()->get_id(), $this);
    }


    /**
     * Include css and js scripts for current objects
     */
    public function enqueue_scripts() {
        $js = [];
        $css = [];
        while($this->the_sections_rows()->have()) {
            $this->the_sections_rows()->the();
            $this->the_objects_rows(true);
            while($this->the_objects_rows()->have()) {
                $this->the_objects_rows()->the();
                $object = $this->get_landing()->get_object($this->the_objects_rows()->get_layout());
                if ( !$object instanceof Landing_Object) continue;
                $css = array_merge($css, $object->get_css());
                $js = array_merge($js, $object->get_js());
            }
            $this->the_objects_rows()->reset();
        }
        $this->the_sections_rows()->reset();
        ///
        $css = array_unique($css);
        foreach ($css as $file) {
            include_frontend_css($file);
        }
        $js = array_unique($js);
        foreach ($js as $file) {
            include_frontend_js($file);
        }
    }

}