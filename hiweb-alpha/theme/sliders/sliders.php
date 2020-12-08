<?php
/**
 * Created by PhpStorm.
 * User: denmedia
 * Date: 06.10.2018
 * Time: 12:40
 */

namespace theme;


use hiweb\core\Strings;
use theme\sliders\slider;


class sliders {

    static $sliders = [];


    static function init() {
        static $init = false;
        if ( !$init) {
            $init = true;
            include_frontend_css(__DIR__ . '/slider.css');
            include_frontend_js(__DIR__ . '/slider.min.js', include_frontend()->owl_carousel());
        }
    }


    /**
     * @param null $id
     * @return slider
     */
    static function slider($id = null) {
        if (is_null($id)) $id = strings::rand();
        if ( !array_key_exists($id, self::$sliders)) {
            self::$sliders[$id] = new slider($id);
        }
        return self::$sliders[$id];
    }


}