<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NetChiarelli\Api_NFe\service\rj\util;

use NetChiarelli\Api_NFe\model\rj\Pedido;
use NetChiarelli\Api_NFe\model\rj\Produto;
use NetChiarelli\Api_NFe\util\GenericIterator;

/**
 * Description of ProdutosFacesEvents
 *
 * @author raphael
 */
class ProdutoFacesEvents implements IFacesEvents {
    use TraitRegisterProduto, TraitAddFrete, TraitAddProduct;
    
    /** @var Pedido */
    protected $pedido;
    
    /** @var GenericIterator */
    protected $it;
            
    function __construct(Pedido $pedido) {
        $this->pedido = $pedido;
    }
    
    function submitForm($viewState = null) {
        return [
            'formulario' => 'formulario',
            'idProduto' => '',
            'codigo' => '',
            'descricao' => '',
            'ncm' => '',
            'unidade' => '',
            'quantidade' => '',
            'valorUnitario' => '',
            'tributacao' => '1',
            'valorFrete' => '',
            'valorSeguro' => '',
            'valorOutrasDesp' => '',
            'descontoNota' => '',
            'javax.faces.ViewState' => $viewState,
        ];
    }
    
    /**
     * Retorna um interator em ordem com os eventos Faces para ser executado em 
     * um envio de Http. 
     * 
     * @return GenericIterator Retorna um interator com os tasks dos eventos Faces.
     */
    public function getTasks() {
        
        if( ! is_null($this->it)) {            
            return $this->it;
        }
        
        $pedido = $this->pedido;        
        $produtos = $pedido->getProdutoList();
        
        if($pedido->hasFrete()) {
            $produtoFirst = array_shift($produtos);
            
            $task1 = $this->makeFrete($produtoFirst);
            $task2 = $this->makeRegister($produtoFirst);
            $task3 = $this->makeAdd($produtoFirst);
            
            $tasks = array_merge($task1, $task2, $task3);
        }
        
        foreach ($produtos as $prod) {
            $task4 = $this->makeRegister($prod);
            $task5 = $this->makeAdd($prod);
            
            $tasks = array_merge($tasks, $task4, $task5);
        }
        
        $this->it = GenericIterator::getInstance($tasks);
        
        return $this->it;
    }
    
    protected function makeFrete(Produto $produto) {
        $array = $this->valorFrete($this->pedido);
        $array = array_merge($produto->toArrayQuery(), $array);
        
        return array($array);
    }
    
    protected function makeRegister(Produto $produto) {
        $array = [];
        
        $oClass = new \ReflectionClass(TraitRegisterProduto::class);
        foreach ($oClass->getMethods() as $method) {
            $array[] = $this->{$method->name}($produto);
        }
        
        return $array;
    }
    
    protected function makeAdd(Produto $produto) {
        $array = [];
        
        $oClass = new \ReflectionClass(TraitAddProduct::class);
        foreach ($oClass->getMethods() as $method) {
            $array[] = $this->{$method->name}($produto);
        }    
        
        return $array;
    }
    

}

trait TraitAddFrete {
    
    function valorFrete(Pedido $pedido) {
        $event = [
            'javax.faces.source' => 'valorFrete',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'valorFrete @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'valorFrete',
            'rfExt' => null,
            'AJAX:EVENTS_COUNT' => 1,
            'javax.faces.partial.ajax' => 'true',
        ];
        
        return array_merge($pedido->toArrayQuery() ,$event);
    }

}

trait TraitRegisterProduto {
    
    function codigo(Produto $produto) {
        $event = array(
            'javax.faces.source' => 'codigo',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'codigo @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'codigo',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        );
        
        return array_merge($produto->toArrayQuery() ,$event);
    }

    function quantidade(Produto $produto) {
        $event = array(
            'javax.faces.source' => 'quantidade',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'quantidade @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'quantidade',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        );
        
        return array_merge($produto->toArrayQuery() ,$event);
    }

    function valorUnitario(Produto $produto) {
        $event = array(
            'javax.faces.source' => 'valorUnitario',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'valorUnitario @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'valorUnitario',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        );
        
        return array_merge($produto->toArrayQuery() ,$event);
    }

    function valorSeguro(Produto $produto) {
        $event = [
            'javax.faces.source' => 'valorSeguro',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'valorSeguro @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'valorSeguro',
            'rfExt' => null,
            'AJAX:EVENTS_COUNT' => 1,
            'javax.faces.partial.ajax' => true,
        ];
        
        return array_merge($produto->toArrayQuery() ,$event);
    }
    
    function valorOutrasDesp(Produto $produto) {
        $event = [
            'javax.faces.source' => 'valorOutrasDesp',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'valorOutrasDesp @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'valorOutrasDesp',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];
        
        return array_merge($produto->toArrayQuery() ,$event);
    }
    
    function descontoNota(Produto $produto) {
        $event = [
            'javax.faces.source' => 'descontoNota',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'descontoNota @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'descontoNota',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];
        
        return array_merge($produto->toArrayQuery() ,$event);
    }
    
}

trait TraitAddProduct {
    
    function j_idt135(Produto $produto) {
        $event = array(
            'javax.faces.source' => 'j_idt135',
            'javax.faces.partial.event' => 'click',
            'javax.faces.partial.execute' => 'j_idt135 @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'click',
            'org.richfaces.ajax.component' => 'j_idt135',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        );
        
        return array_merge($produto->toArrayQuery() ,$event);
    }

    function tributacao(Produto $produto) {
        $event = array(
            'javax.faces.source' => 'tributacao',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'tributacao @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'change',
            'org.richfaces.ajax.component' => 'tributacao',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        );
        
        return array_merge($produto->toArrayQuery() ,$event);
    }
    
}
