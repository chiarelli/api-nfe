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
 * Description of Endereco
 *
 * @author raphael
 */
class Endereco {
    
    /** 
     * Validar segunda a mask '99.999-99'
     * @var string */
    protected $cep;
    
    /** @var string */
    protected $logradouro;
    
    /** @var string */
    protected $numero;
    
    /** @var string */
    protected $complemento;
    
    /** @var string */
    protected $bairro;
    
    /** @var string */    
    protected $uf;
    
    /**
     *  Código do IBGE do município
     * @var integer
     */
    protected $municipio;
    
    
    static function getInstance($cep, $logradouro, $numero, $bairro, $uf, $municipio, $complemento = null) {
        $instance = new static($cep, $logradouro, $numero, $bairro, $uf, $municipio, $complemento);
        
        return $instance;
    }


    /**
     * 
     * @param string $cep [required]
     * @param string $logradouro [required]
     * @param string $numero [required]
     * @param string $bairro [required]
     * @param string $uf [required]
     * @param integer $municipio [required]
     * @param string $complemento [optional]
     */
    protected function __construct($cep, $logradouro, $numero, $bairro, $uf, $municipio, $complemento = null) {
        $this->cep = $cep;
        $this->logradouro = $logradouro;
        $this->numero = $numero;
        $this->bairro = $bairro;
        $this->uf = $uf;
        $this->municipio = $municipio;
        $this->complemento = $complemento;
    }
    
    public function getCep() {
        return $this->cep;
    }

    public function getLogradouro() {
        return $this->logradouro;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function getComplemento() {
        return $this->complemento;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function getUf() {
        return $this->uf;
    }

    public function getCodeIbgeMunicipio() {
        return $this->municipio;
    }

    public function setComplemento($complemento) {
        $this->complemento = $complemento;
        return $this;
    }

    
    
}
