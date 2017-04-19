<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NetChiarelli\Api_NFe\service\rj\util;

use NetChiarelli\Api_NFe\model\rj\Produto;

/**
 * Description of ProdutosFacesEvents
 *
 * @author raphael
 */
class AddProdutoFacesEvents implements IOrderEvents {
    use TraitAddProdutoFacesEvents;
    
    /** @var Produto */
    protected $produto;
            
    function __construct(Produto $produto) {
        $this->produto = $produto;
    }
    
    public static function getOrder() {
        $order = [];
        
        $oClass = new \ReflectionClass(TraitAddProdutoFacesEvents::class);
        
        foreach ($oClass->getMethods() as $method) {
            $order[] = $method->name;
        }
        
        return $order;
    }
    

}

trait TraitAddProdutoFacesEvents {
    
    function codigo() {
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
    }

    function quantidade() {
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
    }

    function valorUnitario() {
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
    }

    function j_idt135() {
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
    }

    function tributacao() {
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
    }
    
}
