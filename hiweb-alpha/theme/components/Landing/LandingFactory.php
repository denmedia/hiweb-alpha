<?php

namespace theme\Landing;


class LandingFactory {

    static $landings = [];
    /** @var null|Landing */
    static private $last_landing;


    /**
     * @param string $landing_id
     * @return Landing
     */
    static function get($landing_id = 'landing', $setupLastLanding = true): Landing {
        $landing_id = (string)$landing_id;
        if (!array_key_exists($landing_id, self::$landings)) {
            self::$landings[$landing_id] = new Landing($landing_id);
        }
        ///setup last landing instance
        if ($setupLastLanding) self::$last_landing = self::$landings[$landing_id];
        return self::$landings[$landing_id];
    }


    /**
     * Return current Landing instance
     * @return Landing
     */
    static function get_last_landing(): Landing {
        if ( !self::$last_landing instanceof Landing) {
            self::$last_landing = self::get('');
        }
        return self::$last_landing;
    }

}