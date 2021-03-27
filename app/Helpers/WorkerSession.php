<?php


namespace App\Helpers;


class WorkerSession
{
    /* create/update the session on the server */
    public static function set($data) :void
    {
        $_SESSION['worker'] = $data;
    }

    /* Retrieve the current session */
    public static function get()
    {
        return $_SESSION['worker'] ?: false;
    }

    /* Check if session exists */
    public static function has() :bool
    {
        return isset($_SESSION['worker']);
    }
}