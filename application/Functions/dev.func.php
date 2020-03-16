<?php


if(!function_exists('d')) {
    function d($var) {
        print "<pre>";
            print_r($var);
        print "</pre>";
    }
}