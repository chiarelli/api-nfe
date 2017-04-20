<?php

namespace NetChiarelli\Api_NFe\util;

/**
 * Description of Result
 * 
 * Responsável por armazenar o estado de um resultado genérico de processamento.
 * 
 * Uma instância da classe de Severity compõe uma instância desta classe para 
 * sinalizar o nível de severidade do estado.
 *
 * @author raphael
 */
class Result {
    
    protected $msg;
    
    protected $severity;
    
    protected $previous;
    
    /**
     * 
     * @param string $msg required
     * @param Severity $severity required
     * @param Result $previous optional default NULL
     */
    function __construct($msg, Severity $severity, Result $previous = null) {
        $this->msg = $msg;
        $this->severity = $severity;
        $this->previous = $previous;
    }
    
    function add(Result $last) {
        if($this->isTheLast()) {
            $this->previous = $last;
        } else {
            $this->previous->add($last);
        }
    }
    
    /**
     * 
     * @return string
     */
    function getMsg() {
        return $this->msg;
    }
    
    /**
     * 
     * @return Severity
     */
    function getSeverity() {
        return $this->severity;
    }
    
    /**
     * Varifica se toda a cadeia de resultados é verdadeira.
     * 
     * @return boolean
     */
    function isValid() {
        
        if( ! $this->isTheLast() && ! $this->previous->isValid() ) {
            return FALSE;
        }
        
        return ( $this->severity->name() === 'SUCCESS' || $this->severity->name() === 'INFO' );
    }
    
    /**
     * Verifica se pelo menos uma ocorrência da cadeia de resultados é verdadeira.
     * 
     * @return boolean
     */
    function atLeastOneValid() {
        
        if( $this->isTheLast() ) {
            return $this->isValid();
        }
        
        return ( $this->isValid() || $this->previous->atLeastOneValid() );
    }
    
    /**
     * Verifica se este é o ultima da cadeia.
     * 
     * @return boolean
     */
    function isTheLast() {
        return empty($this->previous);
    }
    
    /**
     * Retorna o resultado imediatamente posterior a este; ou FALSE se este for 
     * o último (ou único). 
     * 
     * @return Result
     */
    function getPrevious() {
        if($this->isTheLast()) {
            return FALSE;
        }
        return $this->previous;
    }
    
    /**
     * 
     * @return array
     */
    public function getMessagesInSequence() {  
        
        $msgConcat = array();
        $msgConcat[] = $this->getMsg();
        
        if(!$this->isTheLast()) {
           $msgConcat =  array( $msgConcat, $this->previous->getMessagesInSequence() ); 
        }
        
        return $msgConcat;
    }
    
    /**
     * 
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ' ['
                . 'isValid()=\'' . ( $this->isValid() ? 'TRUE' : 'FALSE' ) . '\''
                . ', msg=\'' . $this->msg . '\''
                . ', severity=\'' . $this->severity->name() . '\''
                . ', atLeastOneValid()=\'' . ( $this->atLeastOneValid() ? 'TRUE' : 'FALSE' ) . '\''
                . ', isTheLast()=\'' . ( $this->isTheLast() ? 'TRUE' : 'FALSE' ) . '\''
            . ']'; 
    }


}