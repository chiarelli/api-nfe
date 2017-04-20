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

namespace NetChiarelli\Api_NFe\exception;

use NetChiarelli\Basics\validation\Result;

/**
 * Description of RulesException
 * 
 * @todo Futuramente ao refatorar o package NetChiarelli\Api_NFe\api para 
 * um projeto independente, não esquecer de desaclopar NetChiarelli\Api_NFe\util\Enum.
 *
 * @author raphael
 */
class RulesException extends ApiException {
    
    /** @var Result */
    private $result;
    
    public function __construct($message = "", $code = 0, \Exception $previous = null, Result $result = null) {
        parent::__construct($message, $code, $previous);
        
        $this->result = $result;
    }
    
    /**
     * 
     * @return Result
     */
    public function getResult() {
        return $this->result;
    }


    
}
