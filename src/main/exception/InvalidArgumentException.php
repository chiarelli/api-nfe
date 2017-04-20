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

use Assert\AssertionFailedException;
use NetChiarelli\Api_NFe\util\Result;

/**
 * Description of InvalidArgumentationException
 *
 * @author raphael
 */
class InvalidArgumentException extends RulesException implements AssertionFailedException {
    
    private $propertyPath;
    private $value;
    private $constraints;
    
    public function __construct($message = '', $code = 0, \Exception $previous = null, Result $result = null) {
        parent::__construct($message, $code, $previous, $result);
    }
    
    public function setPropertyPath($propertyPath) {
        $this->propertyPath = $propertyPath;
        return $this;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    public function setConstraints(array $constraints) {
        $this->constraints = $constraints;
        return $this;
    }

    
    public function getPropertyPath() {
        return $this->propertyPath;
    }

    public function getValue() {
        return $this->value;
    }

    public function getConstraints() {
        return $this->constraints;
    }



}
