<?php

namespace NetChiarelli\Api_NFe\util;

/**
 * Description of Level
 * 
 * As constantes dessa classe sinalizam o estado da severidade para o cliente 
 * que for consumí-la. Uma vez atribuido o estado ele é imutável por todo ciclo 
 * de vida da instância.
 *
 * @author raphael
 */
class Severity extends Enum {
   
    const INFO       = 'INFO';
    const SUCCESS    = 'SUCCESS';
    const WARN       = 'WARN';
    const ERROR      = 'ERROR';
    const FAIL       = 'FAIL';

}