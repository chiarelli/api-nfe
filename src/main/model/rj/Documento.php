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
use NetChiarelli\Api_NFe\assert\Assertions;

/**
 * Description of Document
 * 
 * @todo Futuramente ao refatorar o package NetChiarelli\Api_NFe\api para 
 * um projeto independente, não esquecer de desaclopar NetChiarelli\Api_NFe\assert\Assertions.
 *
 * @author raphael
 */
class Documento {
    
    /** @var TipoDocEnum  */
    protected $TipoDoc;
    
    /** 
     * @var string 
     */
    protected $cpf;
    
    /** 
     * @var string 
     */
    protected $cnpj;
    
    /**
     * 
     * @param TipoDocEnum $tipoDoc [required]
     * @param array $numDoc [required]
     * 
     * @throws InvalidArgumentException
     */
    public function __construct(TipoDocEnum $tipoDoc, $numDoc) {
        
        isset($numDoc['cnpj']) ?: $numDoc['cnpj'] = '';
        isset($numDoc['cpf']) ?: $numDoc['cpf'] = '';
        
        /* Verifica se o ou CPF ou CNPJ é válido e estão mascarados, caso 
         * contrário lança um Exceção.
         */
        $msgError = __('Numeração do doc. inválido ou; não mascarado conforme as regras de negócio.', 'api-nfae');
        if($tipoDoc->name() === TipoDocEnum::CPF[0]) {
            Assertion::satisfy($numDoc['cpf'], array(Assertions::class, 'maskedCpf'), $msgError);
        } 
        else if($tipoDoc->name() === TipoDocEnum::CNPJ[0]) {
            Assertion::satisfy($numDoc['cnpj'], array(Assertions::class, 'maskedCnpj'), $msgError);     
        }
        else if($tipoDoc->name() === TipoDocEnum::AMBOS[0]) {
            Assertion::satisfy($numDoc['cpf'], array(Assertions::class, 'maskedCpf'), $msgError);
            Assertion::satisfy($numDoc['cnpj'], array(Assertions::class, 'maskedCnpj'), $msgError);            
        }
        
        $this->TipoDoc = $tipoDoc;
        $this->cpf = $numDoc['cpf'];
        $this->cnpj = $numDoc['cnpj'];
    }
    
    public function getTipoDoc() {
        return $this->TipoDoc->value()[1];
    }

    public function getCpf() {
        return $this->cpf;
    }

    public function getCnpj() {
        return $this->cnpj;
    }

    
}
