<?php


namespace App\Helpers;


class SessionManager
{
    /* Registered session types */
    private static $allowed = [
        'user',
        'admin',
        'worker',
    ];

    /* Create/update the session on the server */
    public static function set($name, $data) :void
    {
        if(in_array($name, self::$allowed)) {
            $_SESSION[$name] = $data;
        }
    }

    /* Remove current's user session */
    public static function unset() :void
    {
        if($key = array_search(self::$allowed, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }

    /* Retrieve a session */
    public static function get($name)
    {
        return isset($_SESSION[$name]) && in_array($name, self::$allowed) ? $_SESSION[$name] : false;
    }

    /* Check if exists */
    public static function has() :bool
    {
        return in_array(self::$allowed, $_SESSION);
    }

    /* Check if it's first login ever */
    public static function isFirstLogin() :bool
    {
        return isset($_SESSION['first_login']);
    }

    /* Remove first login */
    public static function unsetFirstLogin() :void
    {
        unset($_SESSION['first_login']);
    }

    public static function setIsFirstLogin() :void
    {
        $_SESSION['first_login'] = true;
    }

}