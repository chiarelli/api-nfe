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

use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\HandlerStack;

/**
 * Description of MakeConn
 *
 * @author raphael
 */
class CreateConnection {
    
    /** @staticvar string header user agent */
    const USER_AGENT = 'Mozilla/5.0 (X11; U; Linux amd64; rv:5.0) Gecko/20100101 Firefox/5.0 (Debian)';

    /** @staticvar string Base URI is used with relative requests */
    const BASE_URI = '';

    /** @staticvar integer Atraso para disparar a requisição em milisegundos */
    const DELAY = 0;

    /** @staticvar integer Número de segundos para aguardar ao tentar se conectar a um servidor */
    const CONNECT_TIMEOUT = 0;
    
    const DEFAULT_HEADERS = [
        'User-Agent' => self::USER_AGENT,
    ];
    
    const ALLOW_REDIRECT = [
        'max' => 5, // allow at most 50 redirects.
        'strict' => true, // use "strict" RFC compliant redirects.
        'referer' => true, // add a Referer header
        'track_redirects' => true
    ];

    /** @var HandlerStack */
    private $handler;
    
    /** @var CookieJarInterface */
    private $jar;
        
    /** @var array() */
    private $defaultHeaders;
    
    /** @var array() */
    private $allowRedirect;
    
    /** @var string */
    private $userAgent;

    /** @var string */
    private $baseURI;
    
    /** @var int */
    private $delay;
    
    /** @var int */
    private $timeout;
    
    public function __construct(
        CookieJar  $jar = null,
            $baseURI = self::BASE_URI,
            $userAgent = self::USER_AGENT,
            $timeout = self::CONNECT_TIMEOUT,
            $delay = self::DELAY
                                ) {
        $this->userAgent = $userAgent;
        $this->baseURI = $baseURI;
        $this->delay = $delay;
        $this->timeout = $timeout;
        $this->jar = $jar;
        
        $this->defaultHeaders = self::DEFAULT_HEADERS;
        $this->allowRedirect = self::ALLOW_REDIRECT;
        $this->userAgent = self::USER_AGENT;
    }
    
    /**
     * 
     * @param Connection $classListener
     */
    function getConnection($classListener = NULL) {
       $conn = new Connection($this, $classListener);       
       
       return $conn;
    }
    
    public function getHandler() {
        return $this->handler;
    }

    public function getJar() {
        return $this->jar;
    }

    public function getDefaultHeaders() {
        return $this->defaultHeaders;
    }

    public function getAllowRedirect() {
        return $this->allowRedirect;
    }

    public function getUserAgent() {
        return $this->userAgent;
    }

    public function getBaseURI() {
        return $this->baseURI;
    }

    public function getDelay() {
        return $this->delay;
    }

    public function getTimeout() {
        return $this->timeout;
    }
    
    public function setHandler(HandlerStack $handler) {
        $this->handler = $handler;
        return $this;
    }

    public function setJar(CookieJarInterface $jar) {
        $this->jar = $jar;
        return $this;
    }

    public function setDefaultHeaders(array $defaultHeaders) {
        $this->defaultHeaders = $defaultHeaders;
        return $this;
    }

    public function setAllowRedirect(array $allowRedirect) {
        $this->allowRedirect = $allowRedirect;
        return $this;
    }    

    public function setUserAgent($userAgent) {
        $this->userAgent = $userAgent;
        return $this;
    }

    public function setBaseURI($baseURI) {
        $this->baseURI = $baseURI;
        return $this;
    }

    public function setDelay($milliseconds) {
        $this->delay = $milliseconds;
        return $this;
    }

    public function setTimeout($seconds) {
        $this->timeout = $seconds;
        return $this;
    }    
    
}