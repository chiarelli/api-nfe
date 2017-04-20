<?php

if( ! defined('API_NFE_ROOT_DIR')) {
    
    define('API_NFE_ROOT_DIR', dirname( dirname( __DIR__ ) ) );
    
}

if( ! defined('API_NFE_APP_DIR')) {
    
    define('API_NFE_APP_DIR', API_NFE_ROOT_DIR . '/app' );
    
}

if( ! defined('API_NFE_RESOURCE')) {
    
    define('API_NFE_RESOURCE', API_NFE_APP_DIR . '/resource');
    
}

if( ! defined('API_NFE_CACHE')) {
    
    define('API_NFE_CACHE', API_NFE_APP_DIR . '/cache');
    
}