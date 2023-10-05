<?php

namespace Anstech\Profile;

class TimeFormat
{
    public static $default_time_format = 'H:i';

    public static $time_formats = [
        'H:i'   => '24-hour (e.g. 19:30)',
        'h:i a' => '12-hour (e.g. 7:30 pm)',
    ];

    public static function timeFormats()
    {
        return static::$time_formats;
    }
}
