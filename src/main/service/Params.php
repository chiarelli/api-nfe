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

namespace NetChiarelli\Api_NFe\service;

use NetChiarelli\Api_NFe\exception\InvalidArgumentException;
use NetChiarelli\Api_NFe\util\HtmlUtil;

/**
 * Description of Params
 *
 * @author raphael
 */
class Params {
    
    /** @var array */
    protected $query;
    
    /** @var array */
    protected $multipartList;
    
    /** @var array */
    protected $jsonParamList;
    
    /**
     * 
     * @param array|string $query array contendo chave => value dos parametros html; ou um string query html.
     * @param array $multipartList
     * @param array $jsonParamList
     */
    public function __construct(
                  $query = array(), 
            array $multipartList = array(), 
            array $jsonParamList = array()
            ) {        
        $this->setQuery($query);
        /**
         * @todo Ativar o código abaixo quando implementar $this->setMultipart() metodo.
         */
        $this->setMultipart($multipartList);
        $this->setJsonParam($jsonParamList);
    }

    
    public function getQueryEncoded() {
        foreach ($this->query as $key => $value) {
            self::urlEncodeParams($queryEncoded, $key, $value);
        }
        
        return $queryEncoded;
    }
    
    public function getQuery() {        
        return $this->query;
    }

    public function getMultipart() {
        return $this->multipartList;
    }

    public function getJsonParam() {
        return $this->jsonParamList;
    }

    public function addQueryParam($key, $value) {
        $this->query[$key] = $value;
    }
    
    /**
     * 
     * @param array|string $query
     * @return $this
     * 
     * @throws InvalidArgumentException caso o valor não seja nem um array nem uma string.
     */
    public function setQuery($query) {
        
        if(is_array($query)) {            
            $this->query = $query;
        }elseif (is_string($query)) {
//            parse_str($query, $this->query);
            $this->query = HtmlUtil::queryOfArray($query);
        } else {
            throw new InvalidArgumentException("param \"{$query}\" inválido.");
        }        
        
        return $this;
    }

    /**
     * @todo Implementar setMultipart
     */
    public function setMultipart(array $multipartList) {
        
        trigger_error('Method "' . __METHOD__ . '" ainda não implementado.', E_USER_WARNING);
        
        $this->multipartList = $multipartList;
        
        return $this;
    }

    public function setJsonParam(array $jsonParamList) {
        $this->jsonParamList = $jsonParamList;
        
        return $this;
    }

    
    static function urlEncodeParams(&$array, $key, $value) {
        $key = urlencode(trim($key));
        $value = urlencode(trim($value));
        
        $array[$key] = $value;
    }
    
    static function urlDecodeParams(&$array, $key, $value) {
        $key = urldecode(trim($key));
        $value = urldecode(trim($value));
        
        $array[$key] = $value;        
    }
    
    
}
