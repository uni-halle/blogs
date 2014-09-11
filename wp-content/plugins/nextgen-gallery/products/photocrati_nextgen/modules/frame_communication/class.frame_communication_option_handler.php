<?php

class C_Frame_Communication_Option_Handler
{
    function get($key, $default='X-Frame-Events')
    {
        return 'X-Frame-Events';
    }
}