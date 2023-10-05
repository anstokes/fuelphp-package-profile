<?php

namespace Anstech\Profile;

use Anstech\Profile\Model\Profile;
use AuthFee\Login;
use Fuel\Core\Session;

class DateFormat
{
    public static $default_short_date_format = 'd/m/Y';

    public static $short_date_formats = [
        'd/m/Y' => 'British Date Format (e.g. 31/12/2018)',
        'Y/m/d' => 'International Date Format (e.g. 2018/12/31)',
        'm/d/Y' => 'American Date Format (e.g. 12/31/2018)',
    ];

    public static $default_long_date_format = 'l, j F Y';

    public static $long_date_formats = ['l, j F Y' => 'Monday, 31 December 2018'];

    public static function dateFormats($type)
    {
        return static::${$type . '_date_formats'};
    }

    public static function dateFormatOptions($type, $currentDateFormat = false)
    {
        if (! $currentDateFormat) {
            $currentDateFormat = static::getDateFormat($type);
        }

        // Create appropriate array
        // return \Model_Common::buildOptions(static::dateFormats($type), $currentDateFormat);
    }

    public static function getDateFormat($type)
    {
        return Session::get($type . 'DateFormat', static::currentDateFormat($type, Login::loggedInId()));
    }


    public static function currentDateFormat($type, $loginId)
    {
        // Check for user profile
        if ($profile = Profile::profile($loginId)) {
            // Change to profile date format, if still valid
            if ($profileDateFormat = static::changeDateFormat($type, $profile->{$type . '_date_format'})) {
                return $profileDateFormat;
            }
        }

        // Use default
        return static::${'default_' . $type . '_date_format'};
    }


    public static function changeDateFormat($type, $dateFormat, $profile = false)
    {
        // Check that the selected date format is a valid option
        if (in_array($dateFormat, array_keys(static::dateFormats($type)))) {
            // Set the date format in the session
            Session::set($type . '_date_format', $dateFormat);

            // Update profile, if provided
            if ($profile) {
                $profile->{$type . '_date_format'} = $dateFormat;
            }

            return $dateFormat;
        }

        return false;
    }
}
