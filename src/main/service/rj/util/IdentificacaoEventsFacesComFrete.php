<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NetChiarelli\Api_NFe\service\rj\util;

use NetChiarelli\Api_NFe\util\GenericIterator;

/**
 * Description of IdentificacaoEventsFacesComFrete
 *
 * @author raphael
 */
class IdentificacaoEventsFacesComFrete extends IdentificacaoEventsFaces implements IFacesEvents {
    use TraitIdentificacaoEventsFacesComFrete;

    /**
     * Retorna um array representado a ordem que deve ser feita a invocação dos 
     * métodos que representam os eventos do formulário faces.
     * 
     * @return array Contendo a ordem que deve ser executado os métodos.
     */
    function getTasks() {
        $tasks = [];

        $oObject = new \ReflectionClass(TraitIdentificacaoEventsFacesComFrete::class);
        foreach ($oObject->getMethods() as $method) {
            $tasks[] = $this->{$method->name}();
        }

        return GenericIterator::merge(parent::getTasks(), $tasks);
    }

}

trait TraitIdentificacaoEventsFacesComFrete {

    function transportadorTipoDoc() {
        $query = [
            'javax.faces.source' => 'transportadorTipoDoc',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'transportadorTipoDoc @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'transportadorTipoDoc',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->transportador->toArrayQuery(), $query);
    }

    function transportadorNumDoc() {
        $query = [
            'javax.faces.source' => 'transportadorNumDoc',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'transportadorNumDoc @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'transportadorNumDoc',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->transportador->toArrayQuery(), $query);
    }

    function transportadorRazaoSocial() {
        $query = [
            'javax.faces.source' => 'transportadorRazaoSocial',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'transportadorRazaoSocial @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'transportadorRazaoSocial',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true"',
        ];

        return array_merge($this->transportador->toArrayQuery(), $query);
    }

    function transportadorEndereco() {
        $query = [
            'javax.faces.source' => 'transportadorEndereco',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'transportadorEndereco @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'transportadorEndereco',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->transportador->toArrayQuery(), $query);
    }

    function transportadorUF() {
        $query = [
            'javax.faces.source' => 'transportadorUF',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'transportadorUF @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'transportadorUF',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->transportador->toArrayQuery(), $query);
    }

    function transportadorMunicipio() {
        $query = [
            'javax.faces.source' => 'transportadorMunicipio',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'transportadorMunicipio @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'transportadorMunicipio',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->transportador->toArrayQuery(), $query);
    }

    function importacaoInfoComplementar() {
        $query = [
            'javax.faces.source' => 'importacaoInfoComplementar',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'importacaoInfoComplementar @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'importacaoInfoComplementar',
            'rfExt' => 'null',
            'AJAX:EVENTS_COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->transportador->toArrayQuery(), $query);
    }

}
