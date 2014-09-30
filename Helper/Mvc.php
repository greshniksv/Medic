<?php

class Mvc{
    public static function View($folder,$file="index",$DATA=Array())
    {
        $path = "View/{$folder}/{$file}.php";
        if(file_exists($path))
        {
            include $path;
        }
        else
        {
            echo "Not found: {$path}";
        }
    }

}


