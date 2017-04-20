<?php

if( ! function_exists('__')) {
    
    function __($str, $domain) {
        return $str;
    }
    
}

if( ! function_exists('_e')) {
    
    function _e($str, $domain) {
        echo __($str, $domain);
    }
    
}