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
use Psr\Http\Message\ResponseInterface;

/**
 * Description of MainControl
 * 
 * Tem o objetivo de abstrair toda a implementação de conexão e envio de 
 * requisições Http da lib GuzzleHttp.
 *
 * @deprecated
 * 
 * @author raphael
 */
abstract class AbstractService {
    
    /** @staticvar string header user agent */
    const USER_AGENT = 'Mozilla/5.0 (X11; U; Linux amd64; rv:5.0) Gecko/20100101 Firefox/5.0 (Debian)';

    /** @staticvar string Base URI is used with relative requests */
    const BASE_URI = '';

    /** @staticvar integer Atraso para disparar a requisição em milisegundos */
    const DELAY = 0;

    /** @staticvar integer Número de segundos para aguardar ao tentar se conectar a um servidor */
    const CONNECT_TIMEOUT = 0;

    /** @var Client */
    private $client;

    /** @var callable */
    private $callableStats;

    /** @var callable */
    private $callableResponse;

    /** @var callable */
    private $callableRedirect;
    
    /** @var AbstractListenerGuzzleHttp */
    private $Listener;
    
    /** @var bool */
    private $debugMode;   
    
    /**
     * 
     * @param AbstractListenerGuzzleHttp|string $classListener string className, ou uma instancia.
     * 
     * @throws InvalidArgumentException
     */
    protected function __construct($classListener = NULL) {
        
        if( is_null($classListener)) {            
            $classListener = new inner\NullServlet();
//            trigger_error('Você não passou um "classeName" válido para ser instânciado; ou uma instância da classe "'
//                . AbstractServletOfGuzzle::class . '". Isso não é um comportameto normal.', E_USER_NOTICE
//            );
            
        } else {
            Assertion::subclassOf($classListener, AbstractListenerGuzzleHttp::class);            
        }

        /* @var $listener AbstractListenerGuzzleHttp */
        $listener = is_object($classListener) ? $classListener : new $classListener();
        
        $this->_setListener($listener);            
        $this->debugMode = FALSE;
        $this->createCliente();
    }
    
    private function createCliente() {        
        $config = [
            'base_uri' => static::BASE_URI,
            'delay' => static::DELAY,
            'connect_timeout' => static::CONNECT_TIMEOUT,
        ];
        
        $handler = $this->getHandler();        
        if($handler !== FALSE) {            
            Assertion::isInstanceOf($handler, HandlerStack::class);
            
            $config['handler'] = $handler;            
        }

        $this->client = new Client($config);            
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
        
        // ########## tarefa cookie ##########
        
        $cookieJar = $this->getCookieJar();
        if( $cookieJar !== FALSE ) {
            Assertion::subclassOf($cookieJar, CookieJarInterface::class);
        }
        
        $default = array_merge($default, array('cookies' => $cookieJar));
        
        // ########## tarefa headers ##########
        
        $headers = $this->getDefaultHeaders();
        
        Assertion::isArray($headers);
        
        $default = array_merge($default, array('headers' => $headers ));
        
        // ########## tarefa allowRedirect ##########
        
        $allowRedirect = $this->getAllowRedirect();
        
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
            $response = $this->client->send($request, $config);

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
     * @param \NetChiarelli\Api_NFe\service\Params $params
     * 
     * @return ResponseInterface
     * @throws ServiceException
     */
    function send(Request $request, Params $params = NULL) {        
        try {            
            $config = $this->makeConfig($params, $request->getMethod());
            unset($config['headers']);
            
            /* @var $response ResponseInterface */
            $response = $this->client->send($request, $config);

            $data = [$request, $response];
            
            call_user_func_array($this->callableResponse, $data);
            
            return $data[1];
            
        } catch (\Exception $exc) {
            throw new ServiceException($exc->getMessage(), NULL, $exc);
        }        
    }    
    
    protected final function _getListener() {
        return $this->Listener;
    }
        
    protected final function _getDebugMode() {
        return $this->debugMode;
    }

    protected final function _setDebugMode($debugMode = FALSE) {
        Assertion::boolean($debugMode);
        
        $this->debugMode = $debugMode;
        
        return $this;
    }
    
    /**
     * 
     * @return HandlerStack|false
     */
    protected function getHandler() {
        return FALSE;
    }
    
    /**
     * 
     * @return CookieJarInterface|false
     */
    protected function getCookieJar() {
        return FALSE;
    }
    
    /**
     * 
     * @return array
     */
    protected function getDefaultHeaders() {
        return [
            'User-Agent' => static::USER_AGENT,
        ];
    }
    
    /**
     * 
     * @return array
     */
    protected function getAllowRedirect() {
        return [
            'max' => 5, // allow at most 50 redirects.
            'strict' => true, // use "strict" RFC compliant redirects.
            'referer' => true, // add a Referer header
            'track_redirects' => true
        ];
    }
    
    protected final function _setListener(AbstractListenerGuzzleHttp $listener) {
        $callables = $listener->takeCallables();
            $this->callableResponse = $callables->onResponse;
            $this->callableStats = $callables->onStats;
            $this->callableRedirect = $callables->onRedirect;
            
        $this->Listener = $listener;
    }
    
    

}

namespace NetChiarelli\Api_NFe\service\inner;

use NetChiarelli\Api_NFe\listener\AbstractListenerGuzzleHttp;

/**
 * @internal Classe interna. Somente para ser associada à classe AbstractService.
 */
class NullServlet extends AbstractListenerGuzzleHttp { 
    
    
}
