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

namespace NetChiarelli\Api_NFe\listener\rj;

use GuzzleHttp\Psr7\Request;
use NetChiarelli\Api_NFe\listener\AbstractListenerGuzzleHttp;
use NetChiarelli\Api_NFe\model\rj\Destinatario;
use NetChiarelli\Api_NFe\model\rj\Remetente;
use NetChiarelli\Api_NFe\model\rj\Transportador;
use NetChiarelli\Api_NFe\model\rj\TransportadorModalidadeEnum;
use NetChiarelli\Api_NFe\service\Connection;
use NetChiarelli\Api_NFe\service\Params;
use NetChiarelli\Api_NFe\service\rj\HeadersHtmlFaces;
use NetChiarelli\Api_NFe\service\rj\IdentificacaoEventsFaces;
use NetChiarelli\Api_NFe\service\rj\IdentificacaoEventsFacesComFrete;
use NetChiarelli\Api_NFe\service\rj\SubmitFormIdentificacao;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Description of IdentificacaoServlet
 *
 * @author raphael
 */
class IdentificacaoListener extends AbstractListenerGuzzleHttp {
    
    const URI_IDENTIFICACAO = '/sefaz-dfe-nfae/paginas/identificacao.faces';

    /** @var Connection */
    protected $conn;
    
    /** @var IdentificacaoEventsFaces */
    protected $FacesEvents;
    
    /** @var SubmitFormIdentificacao */
    protected $SubmitForm;

    protected $currentViewState;
    
    protected $last_cid;
    

    public function __construct(Connection $conn, Remetente $remetente, Destinatario $destinatario, Transportador $transportador = null) {
        parent::__construct();
        
        $conn->setListener($this);
        
        $modalidade = $transportador->getModalidade()->name();
        
        if($modalidade === TransportadorModalidadeEnum::SEM_FRETE) {
            $facesEventsClass = IdentificacaoEventsFaces::class;
        } else {
            $facesEventsClass = IdentificacaoEventsFacesComFrete::class;            
        }
        
        $this->conn = $conn;
        $this->FacesEvents = new $facesEventsClass($remetente, $destinatario, $transportador);
        $this->SubmitForm = new SubmitFormIdentificacao($remetente, $destinatario, $transportador);
    }
    
    function processPage() {
        $this->runner();
    }
    
    private function runner() {
        $conn = $this->conn;
        
        $conn->send(new Request('GET', static::URI_IDENTIFICACAO));

        $this->formAction = static::URI_IDENTIFICACAO . '?cid=1';
        
        $FacesEvents = $this->FacesEvents;

        $oObject = new \ReflectionObject($FacesEvents);

        foreach ($oObject->getMethods() as $method) {
            if($method->name == '__construct') {
                continue;
            }
            
            echo '<h2 style="text-align: center;">' . $method->name . '</h2>';
            $this->shoot( $FacesEvents->{$method->name}() );
        }
        
        $this->submitForm();
    }
    
    private function submitForm() {
        
        $this->shoot($this->SubmitForm->submitForm(), FALSE);
    }

    private function shoot($query, $headersFaces = TRUE) {
        $conn = $this->conn;

        $params = new Params($query);
            $params->addQueryParam('javax.faces.ViewState', $this->currentViewState);

//        echo '</pre>';
//        echo '<hr><h3>Inputs form POST:</h3>';
//        var_dump($this->formAction);    echo PHP_EOL;        
//        var_dump($params);        echo PHP_EOL;              
//        echo '</pre>';
//        die('DIE params');

        if ($headersFaces) {
            $headers = HeadersHtmlFaces::facesSendRequests();
        } else {
            $headers = HeadersHtmlFaces::formSubmit();
            
        echo '</pre>';
        echo '<hr><h3>Inputs form SUBMIT POST:</h3>';
        var_dump($this->formAction);    echo PHP_EOL;        
        var_dump($params);        echo PHP_EOL;              
        echo '</pre>';
        
        }

        $conn->send(
            new Request('POST', $this->formAction, $headers), $params
        );

        $parts = parse_url($this->formAction);

        $this->last_cid = $parts['query'];
    }
    
    public function getCurrentViewState() {
        return $this->currentViewState;
    }

    public function getLast_cid() {
        return $this->last_cid;
    }

        
    protected function service(RequestInterface $request, ResponseInterface $response) {
        echo '<hr><h3>Headers all response</h3>';
        var_dump($response->getHeaders());
        
        echo '<hr><h3>Headers all $request</h3>';
        var_dump($request->getHeaders());
    }

    protected function onGet(RequestInterface $request, ResponseInterface $response) {
       $html = $response->getBody()->getContents();
       
       $used = libxml_use_internal_errors(TRUE);
       
            $document = new \DOMDocument();
                $document->loadHTML($html);       
       
       libxml_use_internal_errors($used);
       
        $crawer = new Crawler();
            $crawer->addDocument($document);
       
       $facesViewState = $crawer->filter('input[name="javax.faces.ViewState"]')->attr('value');
       
       $this->currentViewState = $facesViewState;       
       
       echo '<hr><h3>onGet: Response page identificacao.faces:</h3>';
       echo $html;
    }
    
    protected function onPost(RequestInterface $request, ResponseInterface $response) {
        $html = $response->getBody()->getContents();
//        var_dump($request->getHeaders());
//        var_dump($response->getHeaders());
        
        echo '<hr><h3>onPost: Response page identificacao.faces:</h3>';
        echo $html;
    }
       
}
