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

use Assert\Assertion;
use Che\Math\Decimal\Decimal;
use NetChiarelli\Api_NFe\service\rj\util\Ncm;
use NetChiarelli\Api_NFe\service\rj\util\Unidade;
use NetChiarelli\Api_NFe\util\ConvertUtil;

/**
 * Description of Produtos
 *
 * @todo Implementar esta classe
 * 
 * @author raphael
 */
class Produto {
    
    /** @var string */
    protected $codigo;
    
    /** @var string */
    protected $descricao;
    
    /** @var float */
    protected $qtd;
    
    /** @var Decimal */
    protected $valor;
    
    /** @var Decimal */
    protected $desconto;
    
    /** @var Decimal */
    protected $outrasDesp;
    
    /** @var Decimal */
    protected $seguro;

    /** @var Ncm */
    protected $NCM;
    
    /** @var Unidade */
    protected $Unid;
    
    /**
     * 
     * @param string $codigo [required]
     * @param string $descricao [required]
     * @param string $qtd [required]
     * @param Decimal $valor [required]
     * @param Ncm $NCM [required]
     * @param Unidade $Unid [required]
     * @param Decimal $desconto [optional]
     * @param Decimal $seguro [optional]
     * @param Decimal $outrasDesp [optional]
     * 
     * @return \static
     */
    static function getInstance(
            $codigo, $descricao, $qtd, 
            Decimal $valor, 
            Ncm $NCM, 
            Unidade $Unid,
            Decimal $desconto = null, 
            Decimal $seguro = null, 
            Decimal $outrasDesp = null
            ) {
        Assertion::numeric($qtd);
        
        $desconto = $desconto ?: Decimal::zero();
        $seguro = $seguro ?: Decimal::zero();
        $outrasDesp = $outrasDesp ?: Decimal::zero();
        
        $instance = new static($codigo, $descricao, $qtd, $valor, $desconto, $outrasDesp, $seguro, $NCM, $Unid);
        
        return $instance;
    }
            
    protected function __construct(
            $codigo, $descricao, $qtd, 
            Decimal $valor, 
            Decimal $desconto, 
            Decimal $outrasDesp, 
            Decimal $seguro, 
            Ncm $NCM, 
            Unidade $Unid) {
        
        $this->codigo = $codigo;
        $this->descricao = $descricao;
        $this->qtd = $qtd;
        $this->valor = $valor;
        $this->desconto = $desconto;
        $this->outrasDesp = $outrasDesp;
        $this->seguro = $seguro;
        $this->NCM = $NCM;
        $this->Unid = $Unid;
    }
    
    function getCodigo() {
        return $this->codigo;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getQtd() {
        return $this->qtd;
    }

    function getValor() {
        return $this->valor;
    }

    function getDesconto() {
        return $this->desconto;
    }

    function getOutrasDesp() {
        return $this->outrasDesp;
    }

    function getSeguro() {
        return $this->seguro;
    }

    function getNCM() {
        return $this->NCM;
    }

    function getUnid() {
        return $this->Unid;
    }
    
    /**
     * 
     * @return array()
     */
    public function toArrayQuery() {
        $qt = (new Decimal($this->getQtd()))->round(2, Decimal::ROUND_FLOOR);
        
        return [
            'idProduto' => null,
            'codigo' => $this->getCodigo(),
            'descricao' => $this->getDescricao(),
            'ncm' => $this->getNCM()->getCode(),
            'unidade' => $this->getUnid()->value()[1],
            'quantidade' => ConvertUtil::decimalToBrl($qt),
            'valorUnitario' => ConvertUtil::decimalToBrl($this->getValor()), //  '50,00',
            'tributacao' => '1',
            'valorSeguro' => ConvertUtil::decimalToBrl($this->getSeguro()),
            'valorOutrasDesp' => ConvertUtil::decimalToBrl($this->getOutrasDesp()),
            'descontoNota' => ConvertUtil::decimalToBrl($this->getDesconto()),
        ];
    }

    
}
