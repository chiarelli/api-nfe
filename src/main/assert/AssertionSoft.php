<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NetChiarelli\Api_NFe\assert;

use Assert\Assertion;
use NetChiarelli\Api_NFe\exception\InvalidArgumentException;
use NetChiarelli\Api_NFe\util\Result;
use NetChiarelli\Api_NFe\util\Severity;



/**
 * Description of Assertion
 * 
 * Proxy dinâmico estático da classe Assert\Assertion para não lançar exceções, mas somente 
 * instâncias da classe Result para informar o estado do resultado ao cliente.
 * 
 * @todo Futuramente ao refatorar o package NetChiarelli\WP_Plugin_NFe\api para 
 * um projeto independente, não esquecer de desacoplar NetChiarelli\WP_Plugin_NFe\util\Result.
 * 
 * @author raphael
 */
class AssertionSoft {
    
    /**
     * 
     * @param string $name
     * @param array $arguments
     * 
     * @return Result
     */
    public static function __callStatic($name, $arguments) {
        try {
            \forward_static_call_array(array(Assertion::class, $name), $arguments);

            return new Result("valid {$name}", Severity::valueOf(Severity::SUCCESS));
            
        } catch (InvalidArgumentException $exc) {
            
            return new Result($exc->getMessage(), Severity::valueOf(Severity::ERROR));
        }
    }
    
}
