<?php

namespace Anstech\Profile;

use AuthFee\Login;
use Anstech\File;
use Anstech\Profile\Model\Profile;
use Anstech\UploadHandler;
use Fuel\Core\Uri;

class Avatar extends UploadHandler
{
    /**
     * Default avatar location
     * @var string      Path to default avatar
     */
    protected static $default_avatar = 'assets/img/avatars/avatar.png';

    /**
     * Generate Avatar
     * For options see: https://en.gravatar.com/site/implement/images/
     * @var boolean|string      Type of gravatar to generate, or false to disable
     */
    protected static $generate_avatar = 'robohash';

    /**
     * Uploaded avatar location
     * @var string      Path to avatars directory
     */
    protected static $sub_directory = 'avatars' . DS;

    /**
     * Supported avatar file types
     * @var array       Array of allowed file type strings
     */
    protected static $allowed_file_types = [
        'jpg',
        'png',
    ];


    /**
     * Default avatar location
     * @return string   Path to default avatar
     */
    public static function defaultAvatar()
    {
        return DOCROOT . static::$default_avatar;
    }


    /**
     * Returns local path for file, by adding relevant date-related folders to root file path above
     *
     * @param integer $timestamp    Timestamp to use when generating date folder
     * @return string
     */
    public static function filePath($timestamp = false, $include_date_folder = true)
    {
        // Prepend customer account number to uploaded file
        $file_path = static::$file_path . static::$sub_directory;
        File::createPath($file_path);
        return $file_path;
    }


    /**
     * Define target filename for Avatar
     *
     * @param string $original_name
     * @param string $additional_data
     * @return string
     */
    public static function targetFileName($original_name, $additional_data = false)
    {
        // Use loginId, followed by two random numbers
        return Login::loggedInId() . '-' . rand(1111, 9999) . '-' . rand(1111, 9999) . '.' . File::fileExtension($original_name);
    }


    /**
     * Reads avatar for given login ID
     *
     * @param integer $login_id
     * @return string
     */
    public static function avatar($login_id = false)
    {
        if ($profile = Profile::profile($login_id)) {
            // Use avatar from profile, if it is still available
            if ($avatar = static::checkAvatar($profile->avatar, $profile)) {
                return $avatar;
            }
        }

        // Use default
        return Uri::base() . static::$default_avatar;
    }


    /**
     * Check current avatar, writing to user profile if provided
     *
     * @param string        $avatar
     * @param bool|Profile  $profile
     *
     * @return bool|string URL to avatar, or false if unavailable
     */
    public static function checkAvatar($avatar, $profile = false)
    {
        $filename = static::filePath() . $avatar;

        // Check file exists
        if ($avatar && file_exists($filename)) {
            // Update profile, if provided
            if ($profile) {
                $profile->avatar = $avatar;
            }

            // Return URL to avatar
            return Uri::base() . 'uploads' . DS . static::$sub_directory . $avatar;
        }

        // Local avatar doesn't exist, check 'Gravatar'
        if ($email_address = Login::loggedInEmail()) {
            // Check for gravatar
            if ($gravatar_url = static::gravatar($email_address)) {
                // Try to download the gravatar
                if ($image = static::curlFetch($gravatar_url)) {
                    // Store a local copy of the gravatar
                    $target_file_name = static::targetFileName('gravatar.png');
                    $target_file_path = static::filePath() . DS . $target_file_name;
                    if ($local_file = File::write($target_file_path, $image, true)) {
                        // Update the profile, if provided
                        if ($profile) {
                            $profile->avatar = $target_file_name;
                            $profile->save();
                        }
                    }
                }

                // Unable to store locally; reference external URL
                return $gravatar_url;
            }
        }

        // No avatar
        return false;
    }


    public static function gravatar($email_address)
    {
        // Check if Gravatar holds profile image for given email address...
        // $email_address = 'adrian@anstech.co.uk';
        $email_hash = md5(strtolower($email_address));

        // Fetch gravatar information
        $profile_url = 'https://www.gravatar.com/profile/' . $email_hash . '.json';
        $response = static::curlFetch($profile_url);

        // Not found is string, not JSON
        if ($response && ($response !== '"User not found"')) {
            $json = json_decode($response);
            if (isset($json->thumbnailUrl) && $json->thumbnailUrl) {
                return $json->thumbnailUrl;
            }
        }

        // Generate Gravatar
        if ($gravatar_type = static::$generate_avatar) {
            return 'https://www.gravatar.com/avatar/' . $email_hash . '?d=' . $gravatar_type;
        }

        return false;
    }


    /**
     * Simple function which requests a URL and returns the response
     *
     * @param string $url   URL to fetch
     * @return string       Response body
     */
    protected static function curlFetch($url)
    {
        // Create cURL request, to fetch webpage
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Gravatar Request');        // Without user agent Gravatar returns 403 Forbidden
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $response = curl_exec($ch);
        curl_close($ch);

        // Return response
        return $response;
    }
}
