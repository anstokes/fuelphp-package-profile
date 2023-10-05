<?php

namespace Anstech\Profile;

class Theme
{
    // Theme support
    public static $theme_support = true;

    // Default theme
    public static $default_theme = 'unikit';

    // List of available themes
    public static $themes = ['unikit' => 'Default'];

    /**
     * Check if theme support enabled
     *
     * @return bool
     */
    public static function themeSupport()
    {
        return static::$theme_support;
    }

    /**
     * Array of available themes
     *
     * @return array
     */
    public static function themes()
    {
        return static::$themes;
    }
}
