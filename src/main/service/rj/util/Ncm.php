<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NetChiarelli\Api_NFe\service\rj\util;

use NetChiarelli\Api_NFe\assert\Assertion;
use Noodlehaus\Config;

/**
 * Description of Ncm
 *
 * @author raphael
 */
class Ncm {
    
    protected $code;
    
    protected $name;
    
    /**
     * 
     * @param numeric $code
     * @return \static|boolean
     */
    static function get($code) {
        Assertion::numeric($code);        
        
        foreach (static::getAll() as $ncm) {
            if($code == $ncm['cod_ncm']) {
                return new static($ncm['cod_ncm'], $ncm['nome_ncm']);
            }
        }
        return FALSE;
    }
    
    /**
     * 
     * @param string $name
     * @return \static|boolean
     */
    static function getForName($name) {
        foreach (static::getAll() as $ncm) {
            if($name == $ncm['nome_ncm']) {
                return new static($ncm['cod_ncm'], $ncm['nome_ncm']);
            }
        }
        return FALSE;                
    }

    static function getAll() {
        $ncm = Config::load(API_NFE_RESOURCE . '/ncm.json');
        
        return $ncm->get('ncm');
    }
    
    protected function __construct($code, $name) {
        $this->code = $code;
        $this->name = $name;
    }
    
    function getCode() {
        return $this->code;
    }

    function getName() {
        return $this->name;
    }


}
