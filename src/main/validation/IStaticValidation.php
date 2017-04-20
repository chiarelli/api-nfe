<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NetChiarelli\Api_NFe\validation;

/**
 *
 * @author raphael
 */
interface IStaticValidation {
    
     static function isValid($instance);
     
}
