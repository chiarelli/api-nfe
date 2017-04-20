<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NetChiarelli\Api_NFe\util;

use NetChiarelli\Api_NFe\exception\ApiException;

/**
 * Description of Postmon
 *
 * @author raphael
 */
class Postmon {
    
    const TIMEOUT = 10;
    const IGNORE_ERRORS = TRUE;
    const PATH_CACHE = API_NFE_CACHE . '/cep';
    
    /**
     * 
     * @param string $cep required O CEP no formato XXXXX-XXX ou XXXXXXXX.
     * @return \stdClass|boolean stdClass em caso de CEP encontrado; ou FALSE, no caso o CEP ser inválido ou não encontrado.
     * @throws ApiException Caso a a URL da API retorne erro 404 ou 500.
     */
    static function addressByCep($cep) {
        
        if( $json = self::getCache($cep) ) {
//            var_dump('pego no cache');
            return $json;
        }
        
        $url = 'http://api.postmon.com.br/v1/cep/' . $cep;

        $context = stream_context_create(

              array('http' => 
                  array(
                      'method'=>"GET",
                      'header'  => "Content-Type: text/xml\r\n",
                      'timeout' => self::TIMEOUT,
                      'ignore_errors' => self::IGNORE_ERRORS
                  )
              )

          );

        $result = file_get_contents($url, false, $context);

        if( $result === FALSE )
            throw new ApiException(__("API Postman fora do ar."));

        $json = json_decode($result);

        if( ! $json )
            return FALSE;

        $file = self::PATH_CACHE . "/{$cep}.json";
        
        if( is_writable(self::PATH_CACHE) ) {
            file_put_contents( $file, json_encode($json) );
        }

        return $json;    
    }
    
    static function getCache($cep) {
        
        $file = self::PATH_CACHE . "/{$cep}.json";
        
        if( ! file_exists(self::PATH_CACHE)) {            
            @mkdir(self::PATH_CACHE, 0775, true);
        }
        
        if( ! file_exists($file) || ! is_readable($file) ) {
            return FALSE;
        }
        $json = json_decode( file_get_contents($file) );
        chgrp ( $file , 'raphael' );
        
        return $json;
    }
    
}
