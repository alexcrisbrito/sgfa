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

    /* Remove all sessions */
    public static function unset() :void
    {
        for ($i = 0; $i < count(self::$allowed); $i++) {
            if(array_key_exists(self::$allowed[$i], $_SESSION)) {
                unset($_SESSION[self::$allowed[$i]]);
            }
        }
    }

    /* Retrieve a session */
    public static function get($name)
    {
        return $_SESSION[$name] ?? null;
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
        if(isset($_SESSION['first_login'])) unset($_SESSION['first_login']);
    }

    public static function setIsFirstLogin() :void
    {
        $_SESSION['first_login'] = true;
    }

}