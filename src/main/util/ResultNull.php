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

use NetChiarelli\Api_NFe\util\Result;

/**
 * Description of ResultNull
 * 
 * @todo Futuramente ao refatorar o package NetChiarelli\Api_NFe\api para 
 * um projeto independente, não esquecer de desacoplar NetChiarelli\Api_NFe\util\Result e 
 * NetChiarelli\Api_NFe\util\Severity.
 *
 * @author raphael
 */
class ResultNull extends Result {
    
    protected $calledAdd = false;


    public function __construct() {
        parent::__construct('', \NetChiarelli\Api_NFe\util\Severity::valueOf('SUCCESS'));
    }
    
    function add(Result $last = null) {
        
        if(is_null($last)) {
            return;
        }
        
        if( $this->isTheLast() && $this->calledAdd === FALSE ) {
            
            $this->msg = $last->msg;
            $this->previous = $this->previous;
            $this->severity = $this->severity;
            
        } else {
            parent::add($last);
        }
        
        $this->calledAdd = TRUE;        
    }
    
}
