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
use NetChiarelli\Api_NFe\exception\InvalidArgumentException;

/**
 * Description of Identificacao
 * 
 * Este model é responsável por armazenar os dados dos atributos do Remetente do 
 * formulário da page http://www4.fazenda.rj.gov.br/sefaz-dfe-nfae/paginas/identificacao.faces
 * 
 * @uses RemetenteDateCreate A instância desta classe é composta por uma instancia de RemetenteDateCreate.
 * @author raphael
 */
class Remetente implements IModel {

    /**
     * @var TipoRemetenteEnum
     */
    protected $TipoRemetente;

    /** @var string */
    protected $nome;

    /**
     * Validar segunda a mask (21) 2261-9818 | (21) 92261-9818
     *
     * @var string
     */
    protected $telefone;

    /**
     * @todo um email válido
     * @var string
     */
    protected $email;    

    /**
     *
     * @var Documento
     */
    protected $Documento;

    /**
     * Validar segunda a mask 99.999-99
     *
     * @var Endereco
     */
    protected $Endereco;

    /***
     * @var OperacaoNatureza
     */
    protected $Operacao;
    
    /** @var RemetenteDateCreate */
    protected $RemetenteDateCreate;
    
    /**
     * 
     * @param TipoRemetenteEnum $TipoRemetente [required]
     * @param Documento $documento [required]
     * @param string $nome [required]
     * @param string $telefone [required]
     * @param string $email [required]
     * @param Endereco $Endereco [required]
     * @param OperacaoNatureza $operacao [required]
     * @param RemetenteDateCreate $RemetenteDateCreate [required]
     * 
     * @return Remetente
     * @throws InvalidArgumentException
     */
    static function getInstance(
            TipoRemetenteEnum $TipoRemetente,
            Documento $documento,
            $nome, $telefone, $email, 
            Endereco $Endereco, 
            OperacaoNatureza $operacao,
            RemetenteDateCreate $RemetenteDateCreate
                                ) {
        Assertion::notEmpty($nome);
        Assertion::notEmpty($telefone);
        Assertion::notEmpty($email);
        
        $instance = new Remetente($TipoRemetente, $documento, $nome, $telefone, $email, $Endereco, $operacao, $RemetenteDateCreate);
        
        return $instance;
    }
    
    protected function __construct(
            TipoRemetenteEnum $TipoRemetente,
            Documento $documento,
            $nome, $telefone, $email, 
            Endereco $Endereco, 
            OperacaoNatureza $operacao,
            RemetenteDateCreate $RemetenteDateCreate
                                    ) {
        $this->TipoRemetente = $TipoRemetente;
        $this->Documento = $documento;
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->Endereco = $Endereco;
        $this->Operacao = $operacao;
        $this->RemetenteDateCreate = $RemetenteDateCreate;
    }
    
    public function getDocumento() {
        return $this->Documento;
    }

    public function setDocumento(Documento $documento) {
        $this->Documento = $documento;
        return $this;
    }
        
    public function getTipoRemetente() {
        return $this->TipoRemetente;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getEndereco() {
        return $this->Endereco;
    }

    public function getRemetenteDateCreate() {
        return $this->RemetenteDateCreate;
    }
    
    public function getOperacao() {
        return $this->Operacao;
    }   
        
    function toArrayQuery() {
        $doc = $this->getDocumento();
        $endereco = $this->getEndereco();
        $operacao = $this->getOperacao();
        $nat = $operacao->getTipoNat()->value()[1];
        
        return [
            'tipoRemetenteSelecionado' => $this->TipoRemetente->value()[1],
            'remetenteCPF' => $doc->getCpf(),
            'remetenteCNPJ' => $doc->getCnpj(),
            'remetenteNomePessoa' => $this->getNome(),
//            'javax.faces.source' => 'remetenteTipoNaturezaOperacao:' . ($nat == 'SAIDA' ? 1 : 0),
            'remetenteCEP' => $endereco->getCep(),
            'remetenteEndereco' => $endereco->getLogradouro(),
            'remetenteNumero' => $endereco->getNumero(),
            'remetenteComplemento' => $endereco->getComplemento(),
            'remetenteBairro' => $endereco->getBairro(),
            'remetenteMunicipio' => $endereco->getCodeIbgeMunicipio(),
            'remetenteTelefone' => $this->getTelefone(),
            'remetenteEmail' => $this->getEmail(),
            'remetenteTipoOperacao' => $operacao->getTipoOper()->value()[1],
            'remetenteTipoNaturezaOperacao' => $nat,
            'remetenteOperacaoNatureza' => $operacao->getValue(),
            'remetenteFinalidade' => 1,
        ];
    }

}
