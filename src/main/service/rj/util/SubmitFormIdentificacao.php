<?php

/*
 * Copyright (C) 2017 raphael
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

namespace NetChiarelli\Api_NFe\service\rj\util;

use NetChiarelli\Api_NFe\model\rj\Destinatario;
use NetChiarelli\Api_NFe\model\rj\Remetente;
use NetChiarelli\Api_NFe\model\rj\Transportador;

/**
 * Description of SubmitFormIdentificacao
 *
 * @author raphael
 */
class SubmitFormIdentificacao {

    /** @var Remetente */
    protected $remetente;

    /** @var Destinatario */
    protected $destinatario;

    /** @var Transportador */
    protected $transportador;

    public function __construct(
    Remetente $remetente, Destinatario $destinatario, Transportador $transportador = null
    ) {
        $this->remetente = $remetente;
        $this->destinatario = $destinatario;
        $this->transportador = $transportador ?: Transportador::getInstanceSemFrete();
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
