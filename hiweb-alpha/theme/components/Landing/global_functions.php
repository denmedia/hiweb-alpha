<?php

use hiweb\core\ArrayObject\ArrayObject_Rows;
use theme\Landing\Landing;
use theme\Landing\Landing_Context;
use theme\Landing\LandingFactory;


if ( !function_exists('get_landing')) {

    /**
     * @param $landingId
     * @return Landing
     */
    function get_landing($landingId = 'landing'): Landing {
        return LandingFactory::get($landingId);
    }
}

if ( !function_exists('the_landing')) {
    /**
     * @return Landing
     */
    function the_landing(): Landing {
        return LandingFactory::get_last_landing();
    }
}

if ( !function_exists('the_landing_object_array')) {
    /**
     * @return \hiweb\core\ArrayObject\ArrayObject|null
     */
    function the_landing_object_array(): ?\hiweb\core\ArrayObject\ArrayObject {
        return LandingFactory::get_last_landing()->get_last_context()->the_objects_rows()->get_current();
    }
}