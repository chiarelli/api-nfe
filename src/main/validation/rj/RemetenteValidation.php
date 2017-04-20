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
use NetChiarelli\Api_NFe\assert\AssertionSoft;
use NetChiarelli\Api_NFe\model\rj\Remetente;
use NetChiarelli\Api_NFe\model\rj\TipoRemetenteEnum;
use NetChiarelli\Api_NFe\util\NullResult;
use NetChiarelli\Basics\validation\Result;
use NetChiarelli\Basics\validation\Severity;

/**
 * Description of RemetenteValidation
 * 
 * @todo Futuramente ao refatorar o package NetChiarelli\Api_NFe\api para 
 * um projeto independente, não esquecer de desacoplar NetChiarelli\Api_NFe\assert\AssertionSoft e 
 * NetChiarelli\Api_NFe\util\Result.
 *
 * @author raphael
 */
class RemetenteValidation extends AbstractValidation {
    /*
     * 'tipoRemetenteSelecionado' => int 1
      'remetenteCPF' => string '093.273.077-98' (length=14)
      'remetenteCNPJ' => string 'meu cnpj' (length=8)
      'remetenteNomePessoa' => string 'Raphael Mathias' (length=15)
     * 
      'remetenteCEP' => string '20.771-560' (length=10)
      'remetenteEndereco' => string 'Rua Almeida Junior' (length=18)
      'remetenteNumero' => string '50' (length=2)
      'remetenteComplemento' => null
      'remetenteBairro' => string 'Del Castilho' (length=12)
      'remetenteMunicipio' => string 'NaN' (length=3)
     * 
      'remetenteTelefone' => string '(21) 98307-0257' (length=15)
      'remetenteEmail' => string 'oi.sucesso@gmail.com' (length=20)
     * 
      'remetenteTipoOperacao' => int 1
      'remetenteTipoNaturezaOperacao' => string 'SAIDA' (length=5)
      'remetenteOperacaoNatureza' => null
      'remetenteFinalidade' => int 1
     */

    public static function isValid($remente) { 
        /* @var $remente Remetente */
        Assertion::isInstanceOf($remente, Remetente::class);
        
        $queryArray = $remente->toArrayQuery();
        
        $result = new NullResult();
        
        $result->add( static::checkTipoSelecionado($queryArray['tipoRemetenteSelecionado']) );
        unset($queryArray['tipoRemetenteSelecionado']); 
        
        $result->add( static::checkDocument($remente) );
        unset($queryArray['remetenteCPF']);     
        unset($queryArray['remetenteCNPJ']);     
        
        $result->add( static::checkCEP($remente) );
        unset($queryArray['remetenteCEP']);     
        unset($queryArray['remetenteEndereco']);     
        unset($queryArray['remetenteNumero']);     
        unset($queryArray['remetenteComplemento']);     
        unset($queryArray['remetenteBairro']);     
        unset($queryArray['remetenteMunicipio']);     
        
        // @param remetenteOperacaoNatureza já é validado em OperacaoNatureza
        unset($queryArray['remetenteOperacaoNatureza']);
        
        foreach ($queryArray as $input => $value) {
            
            $input = str_replace('remetente', 'check', $input);
            
            $result->add( static::{$input}($value) );            
        }
        
        return static::managerException(Remetente::class, $result);
    }
    
    static function checkDocument(Remetente $model) {
        
        $documento = $model->getDocumento();        
        $tipoRemetente = $model->getTipoRemetente();
        
        switch ($tipoRemetente->name()) {
            case TipoRemetenteEnum::MEI[0]:                
                
                if( ! $documento->getCnpj() || !$documento->getCpf() ) {
                    return new Result(__('Não é uma instancia MEI válida', 'api-nfae'), Severity::valueOf('ERROR'));
                }

                break;

            case TipoRemetenteEnum::PF_NC[0]:
                
                if( ! $documento->getCpf() ) {
                    return new Result(__('Não é uma instancia PF válida', 'api-nfae'), Severity::valueOf('ERROR'));
                }
                
                break;
                
            case TipoRemetenteEnum::PJ_NC[0]:
                
                if( ! $documento->getCpf() ) {
                    return new Result(__('Não é uma instancia PJ válida', 'api-nfae'), Severity::valueOf('ERROR'));
                }
                
                break;
                
            default:
                throw new \InvalidArgumentException( __('Houve uma enumeração de TipoRemetenteEnum não esperada.', 'api-nfae') );
        }
        
        return new Result('checkDocument is valid.', Severity::valueOf('SUCCESS'));
    }

    static function checkTipoSelecionado($value) {
        return AssertionSoft::choice($value, [1, 5, 6]);
    }
    
    static function checkTipoOperacao($value) {
        return AssertionSoft::choice($value, [1, 2]);
    }
    
    static function checkTipoNaturezaOperacao($value) {
        return AssertionSoft::choice($value, ['ENTRADA', 'SAIDA']);
    }
    
    static function checkFinalidade($value) {
        return AssertionSoft::eq($value, '1');
    }

    public static function __callStatic($name, $arguments) {
        var_dump( "call {$name}() mas não implementado ainda" );
    }

}
