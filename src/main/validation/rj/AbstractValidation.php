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

namespace NetChiarelli\Api_NFe\validation\rj;

use NetChiarelli\Api_NFe\assert\Assertion;
use NetChiarelli\Api_NFe\exception\ApiException;
use NetChiarelli\Api_NFe\exception\RulesException;
use NetChiarelli\Api_NFe\model\rj\IModel;
use NetChiarelli\Api_NFe\util\Postmon;
use NetChiarelli\Api_NFe\util\StringUtil;
use NetChiarelli\Api_NFe\assert\Assertions;
use NetChiarelli\Api_NFe\assert\AssertionSoft;
use NetChiarelli\Api_NFe\util\Result;
use NetChiarelli\Api_NFe\util\Severity;

/**
 * Description of AbstractBean
 * 
 * @todo Futuramente ao refatorar o package NetChiarelli\Api_NFe\api para 
 * um projeto independente, não esquecer de desacoplar NetChiarelli\Api_NFe\assert\AssertionSoft e 
 * NetChiarelli\Api_NFe\util\Result.
 *
 * @author raphael
 */
abstract class AbstractValidation {
    
    static abstract function isValid($instance);
    
    
    static function checkNomePessoa($value) {        
        return AssertionSoft::betweenLength(
            $value, 3, 59, 
            __('O nome tem que ter no mínimo 3 e no máximo 60 caracteres.', 'api-nfae')
        );
    }
    
    static function checkTelefone($value) {
        return AssertionSoft::satisfy(
                $value, 
                array(Assertions::class, 'maskedPhoneBrazil'),
                __('Número de telefone "%1$s" inválido.', 'api-nfae')
        );
    }
    
    static function checkEmail($value) {
        return AssertionSoft::email(
                $value, __('O e-mail "%1$s" não é válido.', 'api-nfae')
        );        
    }
    
    static function checkConfirmarEmail($value) {
        return static::checkEmail($value);
    }


    static function checkCEP(IModel $model) {
        
        $endereco = $model->getEndereco();
        
        $cep = $endereco->getCep();
        
        $result = AssertionSoft::satisfy(
                $cep, 
                array(Assertions::class, 'maskedCEP'),
                __('O CEP "%1$s" é inválido.', 'api-nfae')
        );
        
        if($result->isValid() == FALSE) {
            return $result;
        }    
        
        $cep = str_replace(array('.', '-', ' '), '', $cep);
        
        try {
            $json = Postmon::addressByCep($cep);            
            
        } catch (ApiException $exc) {
            return new Result('Falha ao acessar a API Postmon.', Severity::valueOf('FAIL'));
        }
        
        if($json === FALSE) {
            return new Result(
                    "O CEP \"{$cep}\" não corresponde a um existente.", 
                    Severity::valueOf('ERROR')
            );
        }
        
        
        $logradouro = isset($json->logradouro) ? $json->logradouro : $endereco->getLogradouro();
        $bairro = isset($json->bairro) ? $json->bairro : $endereco->getBairro();
        
//        var_dump($json->logradouro, $logradouro, StringUtil::similar($json->logradouro, $logradouro));
        
        if(
               StringUtil::similar($endereco->getLogradouro(), $logradouro) === FALSE
            || StringUtil::similar($endereco->getBairro(), $bairro) === FALSE
            || $json->cidade_info->codigo_ibge != $endereco->getCodeIbgeMunicipio()
            || $json->estado != $endereco->getUf()
                                    ) {
            
            return new Result(__('Endereço inválido.', 'api-nfae'), Severity::valueOf('ERROR'));            
        }
        
        return new Result('OK', Severity::valueOf('SUCCESS'));        
    }
    
    /**
     * 
     * @param string $className
     * @param Result $result
     * 
     * @return Result Em caso de sucesso. 
     * @throws RulesException É lançado em caso de falha.
     */
    protected static function managerException($className, Result $result) {
        
        if($result->isValid()) {
            return $result;
        }        
        Assertion::classExists($className);
        
        $msgError = sprintf( __('Instância de "%1$s" é inválida.', 'api-nfae'), $className );
        
        throw new RulesException($msgError, 0, NULL, $result);
    }
    

    static function checkCPF($value) {
        return AssertionSoft::satisfy(
                $value, 
                array(Assertions::class, 'maskedCpf'),
                __('Código "%1$s" de CPF inválido.', 'api-nfae')
        );        
    }
    
    static function checkCNPJ($value) {
        return AssertionSoft::satisfy(
                $value, 
                array(Assertions::class, 'maskedCnpj'),
                __('Código "%1$s" de CNPJ inválido.', 'api-nfae')
        );        
    }
    
}
