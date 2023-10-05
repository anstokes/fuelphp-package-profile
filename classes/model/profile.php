<?php

namespace Anstech\Profile\Model;

use Anstech\Profile\DateFormat;
use Anstech\Profile\TimeFormat;
use Anstech\Profile\Theme;
use AuthFee\App;

class Profile extends \Anstech\Profile\Entity\Profile
{
    // Profile cache
    protected static $_profile_cache = [];

    // Home screen options
    protected static $default_home_screen = 'notifications';
    protected static $home_screens_options = [
        'notifications' => 'Notifications',
        'dashboard'     => 'Dashboard',
    ];

    public static function flushCache($class = null)
    {
        // Remove the static caches
        static::clear_static_cache();
        parent::flush_cache($class);
    }

    // Clear cache
    public static function clearStaticCache()
    {
        static::$_profile_cache = [];
    }


    protected static function cachedProfile($login_id)
    {
        if (! isset(static::$_profile_cache[$login_id])) {
            $profile = static::query()
                        ->where('login_id', $login_id)
                        ->get_one();
            static::$_profile_cache[$login_id] = $profile ?: false;
        }

        return static::$_profile_cache[$login_id];
    }


    public static function profile($login_id = false, $create = true)
    {
        // Find existing profile
        $login_class = App::parameter('loginClass');
        $login_id = $login_id ?: $login_class::loggedInId();
        $profile = static::cachedProfile($login_id);

        // Check whether to create, if not found
        if (! $profile && $create) {
            $profile = static::forge([
                'loginId'         => $login_id,
                'timeFormat'      => TimeFormat::$default_time_format,
                'shortDateFormat' => DateFormat::$default_short_date_format,
                'longDateFormat'  => DateFormat::$default_long_date_format,
                'theme'           => Theme::$default_theme,
            ]);
            // Set default timezone, and save
            $profile->timezone = Timezone::default_timezone_object();
            $profile->save();
        }

        // Return profile (false if not found/created)
        return $profile;
    }


    public static function defaultHomeScreen()
    {
        return static::$default_home_screen;
    }


    public static function homeScreenOptions()
    {
        // TODO; check permissions
        return static::$home_screens_options;
    }


    /**
     * Returns the current home screen profile setting
     *
     * @return string
     */
    public static function homeScreen()
    {
        // Find the home screen for the currently logged in user
        $login_class = App::parameter('loginClass');
        $login_id = $login_class::loggedInId();

        if ($profile = static::loginProfile($login_id)) {
            if (isset($profile['homeScreen']) && in_array($profile['homeScreen'], array_keys(static::homeScreenOptions()))) {
                return $profile['homeScreen'];
            }
        }

        return static::defaultHomeScreen();
    }


    public static function value($value, $default = null)
    {
        if (($profile = static::profile()) && isset($profile->{$value})) {
            return $profile->{$value};
        }

        return $default;
    }
}
