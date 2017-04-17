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

namespace NetChiarelli\Api_NFe\assert;

use Assert\Assertion;
use NetChiarelli\Api_NFe\util\Result;

/**
 * Description of Assertions
 *
 * @author raphael
 */
class Assertions {

    /**
     * Verifica se uma string é um CPF válido
     * 
     * @param string $cpf
     * @return boolean
     */
    static function cpf($cpf) {
        
        Assertion::notEmpty($cpf);
        Assertion::string($cpf);

        // Elimina possivel mascara
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

        // Verifica se o numero de digitos informados é igual a 11 
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo 
        // foi digitada. Caso afirmativo, retorna falso
        else if ($cpf == '00000000000' ||
                $cpf == '11111111111' ||
                $cpf == '22222222222' ||
                $cpf == '33333333333' ||
                $cpf == '44444444444' ||
                $cpf == '55555555555' ||
                $cpf == '66666666666' ||
                $cpf == '77777777777' ||
                $cpf == '88888888888' ||
                $cpf == '99999999999') {
            return false;

            // Calcula os digitos verificadores para verificar se o
            // CPF é válido
        } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Verifica se uma string é um CPF válido e está mascarada conforme 
     * máscara '999.999.999-99'.
     * 
     * @param string $cpf
     * @return boolean
     */
    static function maskedCpf($cpf) {
        
        // verifica se o cpf está mascarado
        preg_match('/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[0-9]{2}$/', $cpf, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches)) {
            return FALSE;
        }
        
        // verifica se a numeração de cpf é valida
        if (self::cpf($cpf) === FALSE) {
            return FALSE;
        }

        return TRUE;
    }
    
    /**
     * Verifica se uma string é um CNPJ válido
     * 
     * @param string $cnpj
     * @return boolean
     */
    static function cnpj($cnpj) {
        
        // Remove qualquer mascara
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        
	// Valida tamanho
	if (strlen($cnpj) != 14) {
            return false;
        }
        // Valida primeiro dígito verificador
	for ($i1 = 0, $j1 = 5, $soma = 0; $i1 < 12; $i1++) {
		$soma += $cnpj{$i1} * $j1;
		$j1 = ($j1 == 2) ? 9 : $j1 - 1;
	}
        
	$resto = $soma % 11;
	if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }

        // Valida segundo dígito verificador
	for ($i2 = 0, $j2 = 6, $soma = 0; $i2 < 13; $i2++)
	{
		$soma += $cnpj{$i2} * $j2;
		$j2 = ($j2 == 2) ? 9 : $j2 - 1;
	}
	$resto = $soma % 11;
        
	return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }
    
    static function maskedCnpj($cnpj) {
        
        // verifica se o cnpj está mascarado
        preg_match('/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\/[0-9]{4}\-[0-9]{2}$/', $cnpj, $matches, PREG_OFFSET_CAPTURE);

        if (empty($matches)) {
            return FALSE;
        }
        
        // verifica se a numeração de cpf é valida
        if (self::cnpj($cnpj) === FALSE) {
            return FALSE;
        }

        return TRUE;
    }
    
    /**
     * Verifica se o CEP (ZIP code Brazil) está mascarado segundo a mask '99.999-999'
     * 
     * @param string $cep
     * @return boolean
     */
    static function maskedCEP($cep) {
        
        // verifica se o cnpj está mascarado corretamente
        preg_match('/^[0-9]{2}\.[0-9]{3}-[0-9]{3}$/', $cep, $matches, PREG_OFFSET_CAPTURE);
        
        return !empty($matches);
    }
    
    static function maskedPhoneBrazil($phone) {
        
        // verifica se o phone está mascarado corretamente
        preg_match('/^\([0-9]{2}\) ([7,8,9]{1}[0-9]{4}|[^7,^8,^9]{4})\-[0-9]{4}$/', $phone, $matches, PREG_OFFSET_CAPTURE);
        
        return !empty($matches);
    }
    
    /**
     * 
     * Verifica se o array somente tem valores de um determinado tipo.
     * 
     * @param array $arrayANDclassName [required] Um array contentdo o array que 
     * será verificado e o className para testar.
     * 
     * @return boolean
     */
    static function valuesArrayIsInstanceOf(array $arrayANDclassName) {
        
        if(empty($arrayANDclassName)) {
            return FALSE;
        }
        
        $array = $arrayANDclassName[0];
        $className = $arrayANDclassName[1];
        
        if(is_array($array) === FALSE) {
            return FALSE;
        }
        
        if(AssertionSoft::classExists($className) === FALSE) {
            return FALSE;
        }
        
        foreach ($array as $value) {
            
            if($value === NULL) {
                continue;
            }
            
            /**
             * @todo Futuramente ao refatorar o package NetChiarelli\WP_Plugin_NFe\api para 
             * um projeto independente, não esquecer de desaclopar NetChiarelli\WP_Plugin_NFe\util\Result.
             */            
            /* @var $result Result */
            $result = AssertionSoft::isInstanceOf($value, $className);
            
            if($result->isValid() === FALSE) {
                return FALSE;
            }
            
        }
        
        return TRUE;
    }

}
