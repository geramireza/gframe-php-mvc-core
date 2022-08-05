<?php

namespace gframe\phpmvc;

class Session
{
    public const FLASH_KEY = 'flash_messages';
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage){
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    public function flash($key,$message = '')
    {
        if($message === '')
           return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;

        $_SESSION[self::FLASH_KEY][$key] = ['value' => $message,'remove' => false];
        return null;
    }
    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => $flashMessage){
            if($flashMessage['remove'])
                unset($flashMessages[$key]);
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;

    }

    public function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get(string $key)
    {
        return $_SESSION[$key] ?? false;
    }

    public function forget($key)
    {
        unset($_SESSION[$key]);
    }
}