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

use Assert\InvalidArgumentException;
use Che\Math\Decimal\Decimal;
use NetChiarelli\Api_NFe\assert\Assertion;
use NetChiarelli\Api_NFe\assert\Assertions;
use NetChiarelli\Api_NFe\util\ConvertUtil;

/**
 * Description of Pedido
 * 
 * @todo Implementar esta classe
 *
 * @author raphael
 */
class Pedido {
    
    /** @var mixed */
    protected $id;
    
    /** @var Decimal */
    protected $valorFrete;

    /** @var Produto[] */
    protected $ProdutoList;
    
    /**
     * 
     * @param numeric $id [required]
     * @param Produto[] $produtoList [optional]
     * 
     * @throws InvalidArgumentException
     */
    public function __construct($id, array $produtoList = [], Decimal $valorFrete = null) {
        $this->requiredArrayOfProduto($produtoList);
        Assertion::numeric($id);
        
        $this->ProdutoList = $produtoList;
        $this->valorFrete = $valorFrete ?: Decimal::zero();
    }
    
    public function addProduto(Produto $produto) {
        $this->ProdutoList[] = $produto;
    }
    
    public function getProdutoList() {
        return $this->ProdutoList;
    }

    /**
     * 
     * @param array $ProdutoList
     * 
     * @throws InvalidArgumentException
     */
    public function setProdutoList(array $ProdutoList) {  
        $this->requiredArrayOfProduto($ProdutoList);
        
        $this->ProdutoList = $ProdutoList;
        
        return $this;
    }

    function getValorFrete() {
        return $this->valorFrete;
    }

    function setValorFrete(Decimal $valorFrete) {
        $this->valorFrete = $valorFrete;
    }
    
    function hasFrete() {
        if(get_class($this->valorFrete) === Decimal::class) {
            return ($this->valorFrete->value() != '0');
        }
        return FALSE;
    }
    
    /**
     * 
     * @return array()
     */
    public function toArrayQuery() {
        return [
            'valorFrete' => ConvertUtil::decimalToBrl($this->getValorFrete()),
            'valorSeguro' => null,
            'valorOutrasDesp' => null,
            'descontoNota' => null,
        ];
    }

    /**
     * 
     * @param array $array
     * 
     * @throws InvalidArgumentException
     */
    protected final function requiredArrayOfProduto(array $array) {
        $className = Produto::class;
        
        // Verifica se o array é do tipo Produto
        Assertion::satisfy(
            array($array, Produto::class), 
            array(Assertions::class, 'valuesArrayIsInstanceOf'),
            __("This array does not contain values of type {$className}.", 'api-nfae')
        );        
    }


    
    
}
