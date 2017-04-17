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
use NetChiarelli\Api_NFe\model\rj\Destinatario;
use NetChiarelli\Api_NFe\util\NullResult;
use NetChiarelli\Api_NFe\assert\AssertionSoft;
use NetChiarelli\Api_NFe\util\Result;
use NetChiarelli\Api_NFe\util\Severity;

/**
 * Description of DestinatarioValidation
 *
 * @author raphael
 */
class DestinatarioValidation extends AbstractValidation {
    
    /*
     *      
            // &destinatarioTipoDoc=
            // &destinatarioNumDoc=
            // &destinatarioIE=
            // &destinatarioNomePessoa=
            //&destinatarioConsumidorFinal=
            &identificacaoDestinatario=
            // &destinatarioCEP=
            // &destinatarioLogradouro=
            // &destinatarioNumero=
            // &destinatarioComplemento=
            // &destinatarioBairro=
            // &destinatarioUF=
            // &destinatarioMunicipio=
            // &destinatarioTelefone=
            // &destinatarioEmail=
            // &destinatarioConfirmarEmail=
     */
    
    public static function isValid($model) {
        /* @var $model Destinatario */
        Assertion::isInstanceOf($model, Destinatario::class);
        
        $queryArray = $model->toArrayQuery();
        
        $result = new NullResult();
        
        // Checkando o Doc
        $result->add( static::checkDocument($model) );
        unset( $queryArray['destinatarioTipoDoc'] );
        unset( $queryArray['destinatarioNumDoc'] );
        
        // Inscr. Estatual não é checado
        unset( $queryArray['destinatarioIE'] );
        
        // destinatarioConsumidorFinal não é validado pois só pode ser true ou false
        unset( $queryArray['destinatarioConsumidorFinal'] );
        
        // checkIdentificacaoDestinatario
        $result->add( static::checkIdentificacaoDestinatario($queryArray['identificacaoDestinatario']) );
        unset( $queryArray['identificacaoDestinatario'] );
        
        // Checando o Endereço
        $result->add( static::checkCEP($model) );
        unset($queryArray['destinatarioCEP']);     
        unset($queryArray['destinatarioLogradouro']);     
        unset($queryArray['destinatarioNumero']);     
        unset($queryArray['destinatarioComplemento']);     
        unset($queryArray['destinatarioBairro']);     
        unset($queryArray['destinatarioMunicipio']);   
        unset($queryArray['destinatarioUF']);   
        
        
        // checando os demais parametros
        foreach ($queryArray as $input => $value) {
            
            $input = str_replace('destinatario', 'check', $input);
            
            $result->add( static::{$input}($value) );            
        }
        
        return static::managerException(Destinatario::class, $result);
    }
    
    static function checkIdentificacaoDestinatario($value) {
        return AssertionSoft::choice($value, [1, 2, 9]);
    }
    
    static function checkDocument(Destinatario $model) {        
        $documento = $model->getDocumento();        
        
        $str = $documento->getTipoDoc();
        switch ($str) {
            case 'AMBOS':
                return new Result( __("Enumeração '$str' inválida para destinatário", 'api-nfae'), Severity::valueOf('ERROR'));

                break;
            
            case 'CPF':
                return static::checkCPF($documento->getCpf());

                break;
            
            case 'CNPJ':
                return static::checkCNPJ($documento->getCnpj());

                break;

            default:
                throw new \InvalidArgumentException( __("Enumeração '$str' ainda não prevista.", 'api-nfae') );
        }
        
    }

}
