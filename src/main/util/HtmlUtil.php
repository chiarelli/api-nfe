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

use NetChiarelli\Api_NFe\assert\Assertion;
use NetChiarelli\Api_NFe\exception\InvalidArgumentException;

/**
 * Description of HtmlUtil
 *
 * @author raphael
 */
class HtmlUtil {
    
    /**
     * 
     * @param string $str
     * @return array
     * @throws InvalidArgumentException
     */
    static function queryOfArray($str) {
        Assertion::string($str);
        
        $parts = explode('?', $str);        
        $query = array_pop($parts);
        
        $step1 = explode('&', $query);
        
        
        $queryInArray = [];
        foreach ($step1 as $input) {
            $step2 = explode('=', $input);
            
            if(count($step2) != 2) {
                throw new InvalidArgumentException("argument \"{$str}\" is not query html valid.");
            }
            
            $queryInArray[trim($step2[0])] = trim($step2[1]);
        }
        
        return $queryInArray;        
    }
    
}
