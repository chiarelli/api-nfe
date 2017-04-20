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

namespace NetChiarelli\Api_NFe\listener;

use GuzzleHttp\TransferStats as Stats;
use NetChiarelli\Api_NFe\assert\Assertion;
use NetChiarelli\Api_NFe\exception\ListenerException;
use Psr\Http\Message\RequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UriInterface as Uri;

/**
 * Description of AbstractServletOfGuzzle
 * 
 * É uma classe que tem o objetivo de disparar os métodos iniciados com 'on{METHOD_HTTP_NAME}' 
 * assim que um send é enviado por \GuzzleHttp\Client.
 * 
 * Esses métodos são disparados assim que a instância que a compoẽ implementam 
 * os callbacks que são disponiveis através do método 'takeCallables()'.
 * 
 * Essa class foi codificada para ser composta com uma instancia de 
 * \NetChiarelli\Api_NFe\service\AbstractService.
 *
 * @author raphael
 */
abstract class AbstractListenerGuzzleHttp {

    /** @var bool */
    private $taken;

    /** @var Stats */
    private $stats;

   
    public function __construct() {
        $this->taken = FALSE;
    }
    
    /**
     * Todos os eventos Request e Response, independentemente do tipo de method Http 
     * (GET, POST, PUT, HEAD, etc), serão transmitidos (call) para esse método.
     * 
     * @example escuta de um POST será transmitido para tanto para o método 'service()'
     * quanto para o método 'onPost()'.
     * 
     * A classe que o estender pode implementar um método genérico Http, ex: 'FOO', 
     * assim sendo chamado de forma dinamica o método correspondente: 'onFoo()'. 
     * Caso não exista o método, um "Fatal error" será lançado.
     * 
     * @param Request $request
     * @param Response $response
     */
    protected function service(Request $request, Response $response) {
        
    }

    protected function onGet(Request $request, Response $response) {
        
    }

    protected function onPost(Request $request, Response $response) {
        
    }

    protected function onPut(Request $request, Response $response) {
        
    }

    protected function onDelete(Request $request, Response $response) {
        
    }

    protected function onHead(Request $request, Response $response) {
        
    }

    protected function onConnect(Request $request, Response $response) {
        
    }

    protected function onOptions(Request $request, Response $response) {
        
    }

    protected function onTrace(Request $request, Response $response) {
        
    }

    protected function onPath(Stats $stats, Uri $uri) {
        
    }
    
    /**
     * Estatísticas de um evento Http (GET, POST, PUT, HEAD, etc) que for ouvido.
     * 
     * @param Stats $stats
     */
    protected function dumpSats(Stats $stats) {
        
    }
    
    /**
     * Se ocorrer um redirect, esse método será invocado.
     * 
     * @param Request $request
     * @param Response $response
     * @param Uri $uri
     */
    protected function observantRedirect(Request $request, Response $response, Uri $uri) {
        
    }

    /**
     * Esse método só pode ser invocado uma vez no ciclo de vida da instância
     * 
     * Segue abaixo o escope da instancia de \stdClass que é retornado:<br/>
     * 
     * \stdClass object scope:
     * 
     *  $data->onResponse = $onResponse;<br/>
     *  $data->onStats = $onStats;<br/>
     *  $data->onRedirect = $onRedirect;<br/>
     * 
     * @return \stdClass|false retorna um objecto stdClass com os callables ou 
     * FALSE em caso deste método já ter sido invocado.
     * 
     * @throws ListenerException
     */
    public final function takeCallables() {

        if ($this->taken) {
            trigger_error("Callback has already been taken", E_USER_NOTICE);
            return FALSE;
        }
        
        // Qualquer exception é capturado e relançado.
        try {
            $onStats = (function (Stats $stats) {
                $this->stats = $stats;
            });

            $onRedirect = function (Request $request, Response $response, Uri $uri) {
                $this->observantRedirect($request, $response, $uri);
            };

            $onResponse = function (Request $request, Response $response) {

                $stats = $this->stats;           

                $this->service($request, $response);
                /* @var $stats Stats */            
                $this->dumpSats($stats);

                // Prepara a sring corresponde em referencia ao método Http que foi ouvido.
                $httpMethod = $request->getMethod();
                $lastName = str_split($httpMethod, 1);

                $fristName = strtoupper(array_shift($lastName));

                array_walk($lastName, function($value) use (&$result) {
                    $result[] = strtolower($value);
                });

                $lastName =  implode('', $result);
                $_method = sprintf('on%1$s%2$s', strtoupper($fristName), $lastName);

                Assertion::methodExists($_method, $this);

                // chama o método correspondente assim que esse callable é invocado (call).
                $this->{$_method}($request, $response);
            };

            $this->taken = TRUE;

            $data = new \stdClass();
                $data->onResponse = $onResponse;
                $data->onStats = $onStats;
                $data->onRedirect = $onRedirect;

            return $data;
            
        } catch (\Exception $exc) {
            throw new ListenerException($exc->getMessage(), $exc->getCode(), $exc);
        }        
    }

//    public function __call($name, array $arguments) {
//        preg_match('/^on(.+)/', 'onBelinha', $matches);
//        
//        if(empty($matches)) {
//            $method = __METHOD__;
//            throw new MethodNotFoundException("Method {$method} if not exists.");
//        }
//        
//        echo 'call method ' . $name;
//    }   
//    private function onRedirect() {
//        
//    }
//    protected function	getLastModified(Request $request) {
//        
//    }
//    
//    protected function 	doHead(Request $request, Response $response) {
//        
//    }
//    
//    protected function 	doOptions(Request $request, Response $response) {
//        
//    }
//    
}
