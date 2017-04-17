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

namespace NetChiarelli\Api_NFe\model\rj;

use NetChiarelli\Api_NFe\assert\Assertion;
use NetChiarelli\Api_NFe\util\ConvertUtil;

/**
 * Description of IdentificacaoTransportador
 * 
 * @todo Implementar esta classe.
 *
 * @author raphael
 */
class Transportador implements IModel {
    
    protected $nome;
    
    protected $inscEst;
    
    protected $infComplementar;

    /**
     *
     * @var Documento
     */
    protected $Documento;
    
    /**
     *
     * @var TransportadorModalidadeEnum
     */
    protected $Modalidade;
    
    /**
     * 
     * @var Endereco
     */
    protected $Endereco;
    
    /**
     * 
     * @param string $nome [required]
     * @param Documento $Documento [required]
     * @param TransportadorModalidadeEnum $Modalidade [required]
     * @param Endereco $Endereco [required]
     * @param type $inscEst [optional]
     * @param type $infComplementar [optional]
     * 
     * @return \static
     */
    static function getInstance(
            $nome, 
            Documento $Documento, 
            TransportadorModalidadeEnum $Modalidade, 
            Endereco $Endereco, 
            $inscEst = '', 
            $infComplementar = ''
                                ) {
        
        Assertion::notBlank($nome);
        $instance = new static($nome, $Documento, $Modalidade, $Endereco, $inscEst, $infComplementar);
        
        return $instance;
    }
    
    static function getInstanceSemFrete() {

        $instance = new static(NULL, NULL, TransportadorModalidadeEnum::valueOf('SEM_FRETE'), NULL, NULL, NULL);
        
        return $instance;
    }


    public function __construct(
            $nome = null, 
            Documento $Documento = null, 
            TransportadorModalidadeEnum $Modalidade = null, 
            Endereco $Endereco = null, 
            $inscEst = '', 
            $infComplementar = ''
                                ) {
        $this->nome = $nome;
        $this->inscEst = $inscEst;
        $this->infComplementar = $infComplementar;
        $this->Documento = $Documento;
        $this->Modalidade = $Modalidade;
        $this->Endereco = $Endereco;
    }

        public function getDocumento() {
        return $this->Documento;
    }

    public function getModalidade() {
        return $this->Modalidade;
    }
    
    /**
     * 
     * @return Endereco
     */
    public function getEndereco() {
        return $this->Endereco;
    }

    public function setDocumento(Documento $Documento) {
        $this->Documento = $Documento;
        return $this;
    }

    public function setModalidade(TransportadorModalidadeEnum $Modalidade) {
        $this->Modalidade = $Modalidade;
        return $this;
    }

    public function setEndereco($Endereco) {
        $this->Endereco = $Endereco;
        return $this;
    }
    
    public function getNome() {
        return $this->nome;
    }

    public function getInscEst() {
        return $this->inscEst;
    }

    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    public function setInscEst($inscEst) {
        $this->inscEst = $inscEst;
        return $this;
    }
    
    public function getInfComplementar() {
        return $this->infComplementar;
    }

    public function setInfComplementar($infComplementar) {
        $this->infComplementar = $infComplementar;
        return $this;
    }
        
    function toArrayQuery() {        
        $modalidade = $this->getModalidade()->value()[1];
        
        if($modalidade == 9) {
            return $this->toArrayQuerySemFrete();
        }
        
        $endereco = $this->getEndereco();
        $doc = $this->getDocumento();
        $numDoc = $doc->getCpf() ?: $doc->getCnpj(); 
        
        $fullAddress = $endereco->getLogradouro() ?: '';
        $fullAddress .= $endereco->getNumero() ? (', ' . $endereco->getNumero()) : '';
        $fullAddress .= $endereco->getComplemento() ? (', ' . $endereco->getComplemento()) : '';
        $fullAddress .= $endereco->getBairro() ? ('; Bairro: ' . $endereco->getBairro()) : '';
        $fullAddress .= $endereco->getCep() ? (' — CEP: ' . $endereco->getCep()) : '';
            $mun = ConvertUtil::codeIbgeToMunicipioData($endereco->getCodeIbgeMunicipio());
        $fullAddress .= $mun ? (' / ' . $mun->nome) : '';
        
        return [
            'transportadorModalidade' => $modalidade,// 1
            'ransportadorTipoDoc' => $doc->getTipoDoc(), // CNPJ
            'ransportadorNumDoc' => $numDoc, // 34.028.316/0027-42
            'ransportadorIE' => $this->getInscEst(),
            'ransportadorRazaoSocial' => $this->getNome(), // Empresa Brasileira de Correios e Telégrafos
            'ransportadorEndereco' => $fullAddress, //Av Pref Rolando Moreira, 336
            'ransportadorUF' => $endereco->getUf(), // AC
            'ransportadorMunicipio' => $endereco->getCodeIbgeMunicipio(), // 1200104
            'ranspPlaca' => null,
            'ufVeiculo' => 'org.jboss.seam.ui.NoSelectionConverter.noSelectionValue', // org.jboss.seam.ui.NoSelectionConverter.noSelectionValue
            'ranspQuantidade' => null,
            'ranspEspecie' => null,
            'ranspPesoBruto' => null,
            'ranspPesoLiquido' => null,
            'ranspNumVols' => null,
            'ranspMarca' => null,
            'importacaoInfoComplementar' => $this->getInfComplementar(),
        ];
    }
    
    private function toArrayQuerySemFrete() {        
        return [
            'transportadorModalidade' => 9,
            'importacaoInfoComplementar' => $this->getInfComplementar(),
        ];        
    }
    
}
