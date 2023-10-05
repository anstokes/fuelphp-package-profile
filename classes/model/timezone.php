<?php

namespace Anstech\Profile\Model;

class Timezone extends \Anstech\Profile\Entity\Timezone
{
    public static $default_timezone = 'Europe/London';

    public static function defaultTimezoneObject()
    {
        return static::query()
                ->where('timezone', static::$default_timezone)
                ->get_one();
    }


    public static function id($timezone_id)
    {
        return static::query()
                ->where('id', $timezone_id)
                ->get_one();
    }


    public static function timezones($zone_type = 'Canonical')
    {
        $timezones = [];

        $timezone_objects = static::query()
                            ->where('zone_type', $zone_type)
                            ->order_by(['timezone' => 'asc'])
                            ->get();

        foreach ($timezone_objects as $timezone_object) {
            $timezones[$timezone_object->id] = str_replace('_', ' ', $timezone_object->timezone);
        }

        return $timezones;
    }
}
