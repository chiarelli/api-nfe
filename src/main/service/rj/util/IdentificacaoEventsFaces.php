<?php

namespace NetChiarelli\Api_NFe\service\rj\util;

use NetChiarelli\Api_NFe\model\rj\Destinatario;
use NetChiarelli\Api_NFe\model\rj\Remetente;
use NetChiarelli\Api_NFe\model\rj\Transportador;
use NetChiarelli\Basics\collections\GenericIterator;

/**
 * Description of IdentificacaoEventsFaces
 *
 * @author raphael
 */
class IdentificacaoEventsFaces implements IFacesEvents {
    use TraitIdentificacaoEvents;

    /** @var Remetente */
    protected $remetente;

    /** @var Destinatario */
    protected $destinatario;

    /** @var Transportador */
    protected $transportador;
    
    /** @var GenericIterator */    
    protected $it;

    public function __construct(
    Remetente $remetente, Destinatario $destinatario, Transportador $transportador = null
    ) {
        $this->remetente = $remetente;
        $this->destinatario = $destinatario;
        $this->transportador = $transportador ?: Transportador::getInstanceSemFrete();
    }
    
    function getTasks() {
        $tasks = [];

        $oClass = new \ReflectionClass(TraitIdentificacaoEvents::class);

        foreach ($oClass->getMethods() as $method) {
            $tasks[] = $this->{$method->name}();
        }

        return GenericIterator::getInstance($tasks);
    }
    
    function submitForm() {
        $date = $this->remetente->getRemetenteDateCreate();
        
        $array =  [
            'formulario' => 'formulario',
            'temRascunho' => 'false',
            'tipoRemetenteSelecionado' => '',
            'remetenteCPF' => '',
            'remetenteCNPJ' => '',
            'remetenteNomePessoa' => '',
            'remetenteCEP' => '',
            'remetenteEndereco' => '',
            'remetenteNumero' => '',
            'remetenteComplemento' => '',
            'remetenteBairro' => '',
            'remetenteMunicipio' => '',
            'remetenteTelefone' => '',
            'remetenteEmail' => '',
            'remetenteConfirmarEmail' => '',
            'regimeTributario' => '',
            'remetenteTipoOperacao' => '',
            'remetenteTipoNaturezaOperacao' => '',
            'remetenteOperacaoDataInputDate' => $date->getInputDate(),
            'remetenteOperacaoDataInputCurrentDate' => $date->getInputCurrentDate(),
            'remetenteOperacaoHora' => $date->getOperacaoHora(),
            'remetenteOperacaoNatureza' => '',
            'remetenteFinalidade' => '',
            'destinatarioTipoDoc' => '',
            'destinatarioNumDoc' => '',
            'destinatarioIE' => '',
            'destinatarioNomePessoa' => '',
            'destinatarioConsumidorFinal' => '',
            'identificacaoDestinatario' => '',
            'destinatarioCEP' => '',
            'destinatarioLogradouro' => '',
            'destinatarioNumero' => '',
            'destinatarioComplemento' => '',
            'destinatarioBairro' => '',
            'destinatarioUF' => '',
            'destinatarioMunicipio' => '',
            'destinatarioTelefone' => '',
            'destinatarioEmail' => '',
            'destinatarioConfirmarEmail' => '',
            'transportadorModalidade' => '',
            'transportadorTipoDoc' => '',
            'transportadorNumDoc' => '',
            'transportadorIE' => '',
            'transportadorRazaoSocial' => '',
            'transportadorEndereco' => '',
            'transportadorUF' => '',
            'transportadorMunicipio' => '',
            'transpPlaca' => '',
            'ufVeiculo' => 'org.jboss.seam.ui.NoSelectionConverter.noSelectionValue',
            'transpQuantidade' => '',
            'transpEspecie' => '',
            'transpPesoBruto' => '',
            'transpPesoLiquido' => '',
            'transpNumVols' => '',
            'transpMarca' => '',
            'importacaoInfoComplementar' => '',
            'javax.faces.ViewState' => '',
        ];
        
        return array_merge( $array,
                $this->remetente->toArrayQuery(), 
                $this->destinatario->toArrayQuery(), 
                $this->transportador->toArrayQuery()
        );
    }

}

trait TraitIdentificacaoEvents {

    function tipoRemetenteSelecionado() {
        $array = [
            'javax.faces.source' => 'tipoRemetenteSelecionado',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'tipoRemetenteSelecionado @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'tipoRemetenteSelecionado',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function remetenteCPF() {
        $array = [
            'javax.faces.source' => 'remetenteCPF',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'remetenteCPF @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'remetenteCPF',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function remetenteCNPJ() {
        $array = [
            'javax.faces.source' => 'remetenteCNPJ',
            'javax.faces.partial.event' => 'blur',
            'javax.faces.partial.execute' => 'remetenteCNPJ @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'blur',
            'org.richfaces.ajax.component' => 'remetenteCNPJ',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function remetenteNomePessoa() {
        $array = [
            'javax.faces.source' => 'remetenteNomePessoa',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'remetenteNomePessoa @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'remetenteNomePessoa',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function remetenteCEP() {
        $array = [
            'javax.faces.source' => 'remetenteCEP',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'remetenteCEP @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'remetenteCEP',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function remetenteNumero() {
        $array = [
            'javax.faces.source' => 'remetenteNumero',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'remetenteNumero @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'remetenteNumero',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function remetenteTelefone() {
        $array = [
            'javax.faces.source' => 'remetenteTelefone',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'remetenteTelefone @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'remetenteTelefone',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function remetenteEmail() {
        $array = [
            'javax.faces.source' => 'remetenteEmail',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'remetenteEmail @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'remetenteEmail',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function remetenteConfirmarEmail() {
        $array = [
            'javax.faces.source' => 'remetenteConfirmarEmail',
            'javax.faces.partial.event' => 'blur',
            'javax.faces.partial.execute' => 'remetenteConfirmarEmail @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'blur',
            'org.richfaces.ajax.component' => 'remetenteConfirmarEmail',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function remetenteTipoOperacao() {
        $array = [
            'javax.faces.source' => 'remetenteTipoOperacao',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'remetenteTipoOperacao @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'remetenteTipoOperacao',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function remetenteTipoNaturezaOperacao() {
        $operacao = $this->remetente->getOperacao();
        $nat = $operacao->getTipoNat()->value()[1];

        $array = [
            'javax.faces.source' => 'remetenteTipoNaturezaOperacao:' . ($nat == 'SAIDA' ? '1' : '0'),
            'javax.faces.partial.event' => 'click',
            'javax.faces.partial.execute' => 'remetenteTipoNaturezaOperacao @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'remetenteTipoNaturezaOperacao',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function remetenteOperacaoNatureza() {
        $array = [
            'javax.faces.source' => 'remetenteOperacaoNatureza',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'remetenteOperacaoNatureza @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'remetenteOperacaoNatureza',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->remetente->toArrayQuery(), $array);
    }

    function destinatarioTipoDoc() {
        $array = [
            'javax.faces.source' => 'destinatarioTipoDoc',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'destinatarioTipoDoc @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'destinatarioTipoDoc',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->destinatario->toArrayQuery(), $array);
    }

    function destinatarioNumDoc() {
        $array = [
            'javax.faces.source' => 'destinatarioNumDoc',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'destinatarioNumDoc @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'destinatarioNumDoc',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->destinatario->toArrayQuery(), $array);
    }

    function destinatarioNomePessoa() {
        $array = [
            'javax.faces.source' => 'destinatarioNomePessoa',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'destinatarioNomePessoa @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'destinatarioNomePessoa',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->destinatario->toArrayQuery(), $array);
    }

    function destinatarioConsumidorFinal() {
        $array = [
            'javax.faces.source' => 'destinatarioConsumidorFinal:' . ($this->destinatario->isConsumidorFinal() ? '1' : '0'),
            'javax.faces.partial.event' => 'click',
            'javax.faces.partial.execute' => 'destinatarioConsumidorFinal @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'destinatarioConsumidorFinal',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->destinatario->toArrayQuery(), $array);
    }

    function identificacaoDestinatario() {
        $array = [
            'javax.faces.source' => 'identificacaoDestinatario',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'identificacaoDestinatario @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'identificacaoDestinatario',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->destinatario->toArrayQuery(), $array);
    }

    function destinatarioCEP() {
        $array = [
            'javax.faces.source' => 'destinatarioCEP',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'destinatarioCEP @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'destinatarioCEP',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->destinatario->toArrayQuery(), $array);
    }

    function destinatarioNumero() {
        $array = [
            'javax.faces.source' => 'destinatarioNumero',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'destinatarioNumero @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'destinatarioNumero',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->destinatario->toArrayQuery(), $array);
    }

    function destinatarioTelefone() {
        $array = [
            'javax.faces.source' => 'destinatarioTelefone',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'destinatarioTelefone @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'destinatarioTelefone',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->destinatario->toArrayQuery(), $array);
    }

    function destinatarioEmail() {
        $array = [
            'javax.faces.source' => 'destinatarioEmail',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'destinatarioEmail @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'destinatarioEmail',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->destinatario->toArrayQuery(), $array);
    }

    function destinatarioConfirmarEmail() {
        $array = [
            'javax.faces.source' => 'destinatarioConfirmarEmail',
            'javax.faces.partial.event' => 'blur',
            'javax.faces.partial.execute' => 'destinatarioConfirmarEmail @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'blur',
            'org.richfaces.ajax.component' => 'destinatarioConfirmarEmail',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->destinatario->toArrayQuery(), $array);
    }

    function transportadorModalidade() {
        $array = [
            'javax.faces.source' => 'transportadorModalidade',
            'javax.faces.partial.event' => 'change',
            'javax.faces.partial.execute' => 'transportadorModalidade @component',
            'javax.faces.partial.render' => '@component',
            'javax.faces.behavior.event' => 'valueChange',
            'org.richfaces.ajax.component' => 'transportadorModalidade',
            'rfExt' => 'null',
            'AJAX:EVENTS.COUNT' => '1',
            'javax.faces.partial.ajax' => 'true',
        ];

        return array_merge($this->transportador->toArrayQuery(), $array);
    }

}
