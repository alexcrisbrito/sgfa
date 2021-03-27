<?php


namespace App\Helpers;


class UserSession
{
    /* create/update the session on the server */
    public static function set($data) :void
    {
        SessionManager::set('user', $data);
    }

    /* Retrieve the current session */
    public static function get()
    {
        return SessionManager::get('user');
    }

    /* Check if session exists */
    public static function has() :bool
    {
        return isset($_SESSION['user']);
    }

}