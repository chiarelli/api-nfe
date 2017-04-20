<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NetChiarelli\Api_NFe\service\rj\util;

use NetChiarelli\Api_NFe\util\GenericIterator;

/**
 *
 * @author raphael
 */
interface IFacesEvents {
    /**
     * @return GenericIterator
     */
    function getTasks();
    
    /**
     * @return array
     */
    function submitForm();
    
}
