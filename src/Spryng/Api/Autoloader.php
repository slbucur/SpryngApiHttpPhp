<?php

/**
 * Checks if the server is has the right software installed to use this library.
 *
 * @license         Berkeley Software Distribution License (BSD-License 2) http://www.opensource.org/licenses/bsd-license.php
 * @author          Roemer Bakker
 * @copyright       Complexity Software
 *
 */
class Spryng_Api_Autoloader
{
    /**
     * @param string $class_name
     */
    public static function autoload ($class_name)
    {
        if (strpos($class_name, "Spryng_") === 0)
        {
            $file_name = str_replace("_", "/", $class_name);
            $file_name = realpath(dirname(__FILE__) . "/../../{$file_name}.php");

            if ($file_name !== false)
            {
                require $file_name;
            }
        }
    }

    /**
     * @return bool
     */
    public static function register ()
    {
        return spl_autoload_register(array(__CLASS__, "autoload"));
    }

    /**
     * @return bool
     */
    public static function unregister ()
    {
        return spl_autoload_unregister(array(__CLASS__, "autoload"));
    }
}

Spryng_Api_Autoloader::register();