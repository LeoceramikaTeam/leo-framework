<?php


if(!function_exists("d")) {
    function d($var)
    {
        echo "<pre>".print_r($var,TRUE)."</pre>";
    }
}