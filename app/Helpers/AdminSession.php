<?php


namespace App\Helpers;


class AdminSession
{
    /* create/update the session on the server */
    public static function set($data) :void
    {
        SessionManager::set('admin', $data);
    }

    /* Retrieve the current session */
    public static function get()
    {
        return SessionManager::get('admin');
    }

    /* Check if session exists */
    public static function has() :bool
    {
        return isset($_SESSION['admin']);
    }
}