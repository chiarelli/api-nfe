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

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJarInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use NetChiarelli\Api_NFe\assert\Assertion;
use NetChiarelli\Api_NFe\exception\InvalidArgumentException;
use NetChiarelli\Api_NFe\exception\ServiceException;
use NetChiarelli\Api_NFe\listener\AbstractListenerGuzzleHttp;
use NetChiarelli\Api_NFe\listener\NullListener;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of Connection
 *
 * @author raphael
 */
class Connection {

    /** @var CreateConnection */
    protected $Creater;
    
    /** @var Client */
    private $Client;        
    
    /** @var AbstractListenerGuzzleHttp */
    private $Listener;
    
    /** @var AbstractListenerGuzzleHttp */
    private $frozenListener;
    
    /** @var callable */
    private $callableStats;

    /** @var callable */
    private $callableResponse;

    /** @var callable */
    private $callableRedirect;
    
    /** @var bool */
    private $debugMode;   
    
    /**
     * 
     * @param AbstractListenerGuzzleHttp|string $classListener string className, ou uma instancia.
     * 
     * @throws InvalidArgumentException
     */
    public function __construct(CreateConnection $creater, $classListener = NULL) {
        
        if( is_null($classListener)) {            
            $classListener = new NullListener();
//            trigger_error('Você não passou um "classeName" válido para ser instânciado; ou uma instância da classe "'
//                . AbstractServletOfGuzzle::class . '". Isso não é um comportameto normal.', E_USER_NOTICE
//            );
            
        } else {
            Assertion::subclassOf($classListener, AbstractListenerGuzzleHttp::class);            
        }
        
        $this->Creater = $creater;

        /* @var $listener AbstractListenerGuzzleHttp */
        $listener = is_object($classListener) ? $classListener : new $classListener();
        
        $this->setListener($listener);            
        $this->debugMode = FALSE;
        $this->createCliente();
    }
    
    private function createCliente() {  
        $creater = $this->Creater;
        
        $config = [
            'base_uri' => $creater->getBaseURI(),
            'delay' => $creater->getDelay(),
            'connect_timeout' => $creater->getTimeout(),
        ];
        
        $handler = $creater->getHandler();     
        if($handler) {            
            Assertion::isInstanceOf($handler, HandlerStack::class);
            
            $config['handler'] = $handler;            
        }

        $this->Client = new Client($config);            
    }   
    
    /**
     * 
     * @param Params $params
     * @param string $method
     * @return void
     */
    private function makeConfig(Params $params = NULL, $method = 'POST') {
        
        $default = [           
            'debug' => $this->debugMode,
            'on_stats' => $this->callableStats,
        ];
        $creater = $this->Creater;
        
        // ########## tarefa cookie ##########
        
        $cookieJar = $creater->getJar();
        if( $cookieJar !== FALSE ) {
            Assertion::subclassOf($cookieJar, CookieJarInterface::class);
        }
        
        $default = array_merge($default, array('cookies' => $cookieJar));
        
        // ########## tarefa headers ##########
        
        $headers = $creater->getDefaultHeaders();
        
        Assertion::isArray($headers);
        
        $default = array_merge($default, array('headers' => $headers ));
        
        // ########## tarefa allowRedirect ##########
        
        $allowRedirect = $creater->getAllowRedirect();
        
        Assertion::isArray($allowRedirect);
        
        $allowRedirect['on_redirect'] = $this->callableRedirect;
        
        $default = array_merge($default, array('allow_redirects' => $allowRedirect ));
        
        // ########## Tarefa params ##########
        
        if(is_null($params)) {
            return $default;
        }
        
        if(empty($params->getJsonParam()) === FALSE) {
            $default = array_merge($default, array('json' => $params->getJsonParam()));
        }
        
        if(strcasecmp($method, 'GET') == 0) {            
            return array_merge($default, array('query' => $params->getQuery()));            
        }
        
        if(empty($params->getQuery()) === FALSE) {
            return array_merge($default, array('form_params' => $params->getQuery()));
        }
        
        if(empty($params->getMultipart()) === FALSE) {
            return array_merge($default, array('multipart' => $params->getMultipart()));
        }
        
        return $default;
    }
    
    private function sendShot(array $config, $uri = '', $method = 'GET') {
        try {
            // weak type check to also accept null until we can add scalar type hints
            if ($uri != '') {
                $parts = \parse_url($uri);
                if ($parts === false) {
                    throw new InvalidArgumentException("Unable to parse URI: $uri");
                }
            }
            
            $request = new Request($method, $uri, $config['headers']);
            
            unset($config['headers']);
            
            /* @var $response ResponseInterface */
            $response = $this->Client->send($request, $config);

            return [$request, $response];
            
        } catch (\Exception $exc) {
            throw new ServiceException($exc->getMessage(), NULL, $exc);
        }
    }
    
    /**
     * 
     * @param string $uri
     * 
     * @return ResponseInterface 
     * @throws ServiceException
     */
    function sendFlexible($uri, $method = 'GET', Params $params = NULL) {       
        $data = $this->sendShot($this->makeConfig($params, $method), $uri, $method);
        
        call_user_func_array($this->callableResponse, $data);
        
        return $data[1];
    }   
    
    /**
     * Envia uma solicitação Guzzle Http às cegas, ou seja, sem invocar os 
     * callables da instância de AbstractServletOfGuzzle.
     * 
     * @param string $uri
     * @param string $method
     * @param Params $params
     * 
     * @return ResponseInterface
     * @throws ServiceException
     */
    function sendSilently($uri, $method = 'GET', Params $params = NULL) {
        $config = $this->makeConfig($params, $method);
        
        unset($config['on_stats']);
        unset($config['allow_redirects']['on_redirect']);
        
        $data = $this->sendShot($config, $uri, $method);
        
        return $data[1];
    }
    
    /**
     * 
     * @param Request $request
     * @param Params $params
     * 
     * @return ResponseInterface
     * @throws ServiceException
     */
    function send(Request $request, Params $params = NULL) {        
        try {            
            $config = $this->makeConfig($params, $request->getMethod());
            unset($config['headers']);
            
            /* @var $response ResponseInterface */
            $response = $this->Client->send($request, $config);

            $data = [$request, $response];
            
            call_user_func_array($this->callableResponse, $data);
            
            return $data[1];
            
        } catch (\Exception $exc) {
            throw new ServiceException($exc->getMessage(), NULL, $exc);
        }        
    } 
    
    function reloadClient() {
        $this->createCliente();
    }
    
    function getListener() {
        return $this->Listener;
    }
        
    function isDebugMode() {
        return $this->debugMode;
    }

    function setDebugMode($debugMode = FALSE) {
        Assertion::boolean($debugMode);
        
        $this->debugMode = $debugMode;
        
        return $this;
    }
    
    function freezeListener() {
        $this->frozenListener = $this->Listener;
        $this->clearListener();
    }
    
    function defrostListener() {
        if(empty($this->frozenListener)) {
            return;
        }        
        $this->setListener($this->frozenListener);
        $this->frozenListener = NULL;
    }
    
    function clearListener() {
        $this->setListener(new NullListener());
    }
    
    function setListener(AbstractListenerGuzzleHttp $listener) {
        $callables = $listener->takeCallables();
            $this->callableResponse = $callables->onResponse;
            $this->callableStats = $callables->onStats;
            $this->callableRedirect = $callables->onRedirect;
            
        $this->Listener = $listener;
    }
    
}