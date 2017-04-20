<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NetChiarelli\Api_NFe\util;

/**
 * Description of newPHPClass
 *
 * @author raphael
 */
class GenericIterator implements \Iterator {
    
    private $position = 0;
    
    protected $array;    

    public function __construct(array $array) {
        $this->position = 0;
        
        $newArray = [];
        foreach ($array as $value) {
            $newArray[] = $value;
        }
        
        $this->array = $newArray;
    }

    function rewind() {
        $this->position = 0;
        
        return $this;
    }

    function current() {
        return $this->array[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
        
        return $this;
    }

    function valid() {
        return isset($this->array[$this->position]);
    }
    
}