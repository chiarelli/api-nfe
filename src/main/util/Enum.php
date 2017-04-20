<?php

namespace NetChiarelli\Api_NFe\util;

/**
 * Description of Enum
 * 
 * Emula o comportamento de uma enumeração para a classe que o extende; 
 * semelhantemente a enumeração de outras linguagens de programação.
 *
 * @author raphael
 */
abstract class Enum {
    
    private $ordinal;
    private $value;
    private $name;
    
    /**
     * 
     * @param string $constName Name of the class constant that extends this abstract Enum class.
     * @return static Returns an instance of the class that extends this abstract Enum class.
     * @throws \InvalidArgumentException
     */
    public static function valueOf($constName) {
        return self::prepareEnum($constName, NULL, 'valueOf');
    }
    
    /**
     * 
     * @param int $int Index of the class constant that extends this abstract Enum class.
     * @return static Returns an instance of the class that extends this abstract Enum class.
     * @throws \InvalidArgumentException
     */
    public static function indexOf($int) {
        return self::prepareEnum(NULL, (int) $int, 'indexOf');
    }
    
    private static function prepareEnum($constName, $int, $oper) {
        $class = static::class;
        $oClass = new \ReflectionClass($class);
        
        $ordinal = 0;        
        foreach ($oClass->getConstants() as $const => $value) {
            
            if( $int === $ordinal ) {
                return new $class($ordinal, $value, $const);
            }
            
            if( $constName === $const ) {
                return new $class($ordinal, $value, $constName);
            }
            
            $ordinal++;
        }
        
        switch ($oper) {
            case 'valueOf':
                $typeError = 'Constante';

                break;
            
            default:
            case 'indexOf':
                $typeError = 'Index';

                break;
        }
        
        $className = static::class;
        throw new \InvalidArgumentException("{$typeError} \"{$int}{$constName}\" não existe na enumeração \"{$className}\".");
    }

    private function __construct($ordinal, $value, $constName) {
        $this->ordinal = $ordinal;
        $this->value = $value;
        $this->name = $constName;
    }
    
    final function name() {
        return $this->name;
    }
    
    final function ordinal() {
        return $this->ordinal;
    }
    
    final function value() {
        return $this->value;
    }
    
    /**
     * @deprecated
     * 
     * @param static $class
     * @param string $constName
     */
    final protected static function prepareDefine($class, $constName) {
        return self::valueOf($constName);
    }
    
    /**
     * @deprecated
     * 
     * @param static $class
     * @param int $int
     */
    final protected static function prepareIndexOf($class, $int) {
        return self::indexOf($int);
    }
    
    final public function __clone() {
        trigger_error('Enumeration can not be cloned.', E_USER_ERROR);
    }
    
}
