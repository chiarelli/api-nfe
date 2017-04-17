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
use NetChiarelli\Api_NFe\model\rj\Transportador;
use NetChiarelli\Api_NFe\util\ResultNull;

/**
 * Description of TransportadorValidation
 *
 * @author raphael
 */
class TransportadorValidation extends AbstractValidation {
    
    /*
            // 'transportadorModalidade' => $modalidade,// 1
            'ransportadorTipoDoc' => $doc->getTipoDoc(), // CNPJ
            'ransportadorNumDoc' => $numDoc, // 34.028.316/0027-42
            // 'ransportadorIE' => $this->getInscEst(),
            // 'ransportadorRazaoSocial' => $this->getNome(), // Empresa Brasileira de Correios e Telégrafos
            //'ransportadorEndereco' => $fullAddress, //Av Pref Rolando Moreira, 336
            //'ransportadorUF' => $endereco->getUf(), // AC
            //'ransportadorMunicipio' => $endereco->getCodeIbgeMunicipio(), // 1200104
            'ranspPlaca' => null,
            'ufVeiculo' => 'org.jboss.seam.ui.NoSelectionConverter.noSelectionValue', // org.jboss.seam.ui.NoSelectionConverter.noSelectionValue
            'ranspQuantidade' => null,
            'ranspEspecie' => null,
            'ranspPesoBruto' => null,
            'ranspPesoLiquido' => null,
            'ranspNumVols' => null,
            'ranspMarca' => null,
            'importacaoInfoComplementar' => $this->getInfComplementar(),
     */
    
    public static function isValid($transportador) {
        /* @var $transportador Transportador */
        Assertion::isInstanceOf($transportador, Transportador::class);
        
        $query = $transportador->toArrayQuery();
        
        $result = new ResultNull();
        
        $modalidade = $query['transportadorModalidade'];
        $result->add( static::checkModalidade($modalidade) );
        
        if($modalidade == 9) {
            $result->add( static::checkSemFrete($query) );
            
        } else {
            
            $result->add( static::checkNomePessoa($query['ransportadorRazaoSocial']) );
            
            $result->add( static::checkCEP($transportador) );
             
            $result->add( static::checkUfVeiculo($query['ufVeiculo']) );
        }
        
        return static::managerException(Transportador::class, $result);
    }
    
    public static function checkModalidade($modalidade) {
        return AssertionSoft::choice($modalidade, [0, 1, 2, 9]);
    }
    
    public static function checkSemFrete(array $query) {
        $result = new ResultNull();
         
        $result->add( AssertionSoft::eq(count($query), 2) );
        $keys = array_keys($query);       
        
        $result->add( AssertionSoft::inArray('transportadorModalidade', $keys) );
        $result->add( AssertionSoft::inArray('importacaoInfoComplementar', $keys) );
        
        return $result;        
    }
    
    public static function checkUfVeiculo($uf) {
        return AssertionSoft::eq($uf, 'org.jboss.seam.ui.NoSelectionConverter.noSelectionValue');
    }

}
