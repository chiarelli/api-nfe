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
use NetChiarelli\Api_NFe\exception\RulesException;
use Noodlehaus\Config;

/**
 * Description of OperacaoNatureza
 *
 * @author raphael
 */
class OperacaoNatureza {
    
    const PATH_RESOURCE = API_NFE_RESOURCE;
    
    protected $value;
    
    protected $description;
    
    protected $code;
    
    /** @var TipoOperacaoEnum  */
    protected $TipoOper;
    
    /** @var TipoNaturezaOperacaoEnum  */
    protected $TipoNat;
     
    /**
     * 
     * @param numeric $value
     * @param TipoOperacaoEnum $tipoOper
     * @param TipoNaturezaOperacaoEnum $tipoNat
     * 
     * @throws InvalidArgumentException
     * @throws RulesException Caso param $value não pertença a esse escopo de 'Natureza da operação'
     */
    function __construct($value, TipoOperacaoEnum $tipoOper, TipoNaturezaOperacaoEnum $tipoNat) {        
        Assertion::numeric($value);
        Assertion::greaterThan($value, 0);

        $oper = $tipoOper->value()[2];
        $nat = $tipoNat->value()[2];

        $config = static::getJson();
        $cfop = $config->get("cfop.{$oper}.{$nat}.{$value}");

        try {
            Assertion::notEmpty($cfop, __("@param $value não pertence a esse escopo de 'Natureza da operação'."));
        } catch (InvalidArgumentException $exc) {
            throw new RulesException($exc->getMessage(), 0,  $exc);
        }

        $this->TipoOper = $tipoOper;
        $this->TipoNat = $tipoNat;

        preg_match('/^([0-9]*) - /', $cfop, $matches);

        $this->code = $matches[1];
        $this->value = $value;
        $this->description = $cfop;
            
    }    
    
    public function getValue() {
        return $this->value;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getCode() {
        return $this->code;
    }

    public function getTipoOper() {
        return $this->TipoOper;
    }

    public function getTipoNat() {
        return $this->TipoNat;
    }

    static function getJson() {
        return Config::load(static::PATH_RESOURCE . '/cfop-select.json');  
    }
    
}
