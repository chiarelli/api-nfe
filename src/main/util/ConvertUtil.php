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

namespace NetChiarelli\Api_NFe\util;

use Assert\Assertion;
use Che\Math\Decimal\Decimal;
use NetChiarelli\Api_NFe\exception\ResourceInaccessible;

/**
 * Description of ConvertUtil
 *
 * @author raphael
 */
class ConvertUtil {
    
    /**
     * 
     * @param numeric $code
     * @return \stdClass|false Retorna um objeto em caso de o código ser encontrado ou false em caso contrário.
     * 
     * @throws ResourceInaccessible Se o arquivo json for inacessível.
     */
    static function codeIbgeToMunicipioData($code) {
        $file = API_NFE_RESOURCE . '/municipio.json';
        
        if( ! file_exists($file) || ! is_readable($file)) {
            $msgError = sprintf( __('O arquivo "%1$s" está inacessível.', 'api-nfae'), $file);
            throw new ResourceInaccessible($msgError);
        }
        
        $contents = file_get_contents($file);
        $json = \json_decode($contents);
        
        foreach ($json as $municipio) {
            
            if($municipio->codigo == $code) {
                return $municipio;
            }
            
        }
        
        return FALSE;
    }
    
    static function decimalToBrl(Decimal $value) {
        $value = $value->round(2, Decimal::ROUND_FLOOR);

        $brl = str_replace('.', ',', $value);       
        
        return $brl;
    }
    
    static function BrlToDecimal($value) {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);
        
        Assertion::numeric($value);
        
        $decimal = new Decimal($value);
        
        return $decimal->round(2, Decimal::ROUND_FLOOR);
    }
    
    
}
