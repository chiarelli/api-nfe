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

/**
 * Description of IdentificacaoDestinatario
 * 
 * Este model é responsável por armazenar os dados dos atributos do destinatário do 
 * formulário da page http://www4.fazenda.rj.gov.br/sefaz-dfe-nfae/paginas/identificacao.faces
 *
 * @author raphael
 */
class Destinatario implements IModel {
    
    /** @var string */
    private $inscEst;
    
    /** @var string */
    public $nome;
    
    /** @var boolean */
    protected $isConsumidorFinal;    
    
    /**
     * Validar segunda a mask (21) 2261-9818 | (21) 92261-9818
     *
     * @var string
     */
    protected $telefone;
    
    /** @var string */
    protected $email;
    
    /** @var TipoContribuinteEnum */
    protected $TipoContribuinte;

    /** @var Documento  */
    protected $Document;
    
    /** @var Endereco */
    protected $Endereco;
    
    
    /**
     * 
     * @param string $nome
     * @param string $isConsumidorFinal
     * @param string $telefone
     * @param string $email
     * @param TipoContribuinteEnum $TipoContribuinte
     * @param Documento $Document
     * @param Endereco $Endereco
     * @param string $inscEst
     * 
     * @return \static
     */
    static function getInstance(
            $nome, $isConsumidorFinal, $telefone, $email, 
            TipoContribuinteEnum $TipoContribuinte, 
            Documento $Document, 
            Endereco $Endereco, 
            $inscEst = null
                                ) {
        $instance = new static($nome, $isConsumidorFinal, $telefone, $email, $TipoContribuinte, $Document, $Endereco, $inscEst);
        
        
        return $instance;
    }

    protected function __construct(
            $nome, $isConsumidorFinal, $telefone, $email, 
            TipoContribuinteEnum $TipoContribuinte, 
            Documento $Document, 
            Endereco $Endereco, 
            $inscEst = null
                                ) {
        $this->nome = $nome;
        $this->isConsumidorFinal = $isConsumidorFinal;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->TipoContribuinte = $TipoContribuinte;
        $this->Document = $Document;
        $this->Endereco = $Endereco;
        $this->inscEst = $inscEst;
    }
    
    public function getInscEst() {
        return $this->inscEst;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setConsumidorFinal($isConsumidorFinal) {
        $this->isConsumidorFinal = $isConsumidorFinal;
        return $this;
    }

        
    /**
     * 
     * @return bool
     */
    public function isConsumidorFinal() {
        return $this->isConsumidorFinal;
    }
    
    /**
     * 
     * @return TipoContribuinteEnum
     */
    public function getTipoContribuinte() {
        return $this->TipoContribuinte;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function getEmail() {
        return $this->email;
    }
    
    /**
     * 
     * @return Documento
     */
    public function getDocumento() {
        return $this->Document;
    }
    
    /**
     * 
     * @return Endereco
     */
    public function getEndereco() {
        return $this->Endereco;
    }   
    
    public function toArrayQuery() {
        $endereco = $this->getEndereco();
        $doc = $this->getDocumento();
        
        $numDoc = $doc->getCpf() ?: $doc->getCnpj(); 
        
        return [
            'destinatarioIE' => $this->getInscEst(),
            'destinatarioNomePessoa' => $this->getNome(),
            'destinatarioConsumidorFinal' => ($this->isConsumidorFinal ? 'true' : 'false'),
//            'javax.faces.source' => 'destinatarioConsumidorFinal:' . ($this->isConsumidorFinal ? '1' : '0'),
            'identificacaoDestinatario' => $this->getTipoContribuinte()->value()[1],
            'destinatarioTelefone' => $this->getTelefone(),
            'destinatarioEmail' => $this->getEmail(),
            'destinatarioTipoDoc' => $doc->getTipoDoc(),
            'destinatarioNumDoc' => $numDoc,
            'destinatarioCEP' => $endereco->getCep(),
            'destinatarioLogradouro' => $endereco->getLogradouro(),
            'destinatarioNumero' => $endereco->getNumero(),
            'destinatarioComplemento' => $endereco->getComplemento(),
            'destinatarioBairro' => $endereco->getBairro(),
            'destinatarioUF' => $endereco->getUf(),
            'destinatarioMunicipio' => $endereco->getCodeIbgeMunicipio(),
        ];
    }
    
}
