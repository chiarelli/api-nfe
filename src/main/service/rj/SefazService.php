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

namespace NetChiarelli\Api_NFe\service\rj;

use GuzzleHttp\Cookie\CookieJar;
use NetChiarelli\Api_NFe\exception\ServiceException;
use NetChiarelli\Api_NFe\listener\rj\IdentificacaoListener;
use NetChiarelli\Api_NFe\model\rj\Destinatario;
use NetChiarelli\Api_NFe\model\rj\Remetente;
use NetChiarelli\Api_NFe\model\rj\Transportador;
use NetChiarelli\Api_NFe\service\Connection;
use NetChiarelli\Api_NFe\service\CreateConnection;
use NetChiarelli\Api_NFe\service\Params;

/**
 * Description of SefazNFAe
 * 
 * Responsável por conectar e buscar dados brutos do domínio 'http://www4.fazenda.rj.gov.br/' 
 * do estado do Rio de Janeiro para a geração de NFA-e.
 * 
 * Depende da classe GuzzleHttp\Client para emular um browser, com isso, propiciando 
 * manipular/extrair nodes do DOM html.
 * 
 * @uses GuzzleHttp\Client É composta pela classe GuzzleHttp\Client para emular um browser.
 *
 * @author raphael
 */
class SefazService {

    const BASE_URI = 'http://www4.fazenda.rj.gov.br/';
    
    const CONNECT_TIMEOUT = 20;
    
    /** @var Connection */
    protected $conn;

    /** @var CookieJar */
    protected $jar;
    
    /** @var Remetente */
    protected $remetente;
    
    /** @var Destinatario */
    protected $destinatario;

    /** @var Transportador */
    protected $transportador;
    
    
    public function __construct(/*Connection $conn = null*/) {
        
        $this->jar = new CookieJar();        
        
        if(isset($conn)) {
            $this->conn = $conn;
            
        } else {
            $creater = new CreateConnection();
            
            $creater
                ->setBaseURI(self::BASE_URI)
                ->setTimeout(self::CONNECT_TIMEOUT)
                ->setJar($this->jar)      
            ;       

            $this->conn = $creater->getConnection();
//            $this->creater = $creater;
        }
        
    }

    /**
     * 
     * @param Remetente $remetente
     * @param Destinatario $destinatario
     * @param Transportador $transportador
     * 
     * @return \DOMDocument Contendo o xml da NFEa
     * @throws ServiceException qualquer exceção é relançada.
     */
    function processIdentificacao(
            Remetente $remetente, 
            Destinatario $destinatario, 
            Transportador $transportador = null
                    ) {
        
        $this->remetente = $remetente;
        $this->destinatario = $destinatario;
        $this->transportador = $transportador;
        
        $this->sendIdentificacao();
    }

    /*protected*/ function sendIdentificacao() {
        $conn = $this->conn;          

        $listener = new IdentificacaoListener( $conn, 
                $this->remetente, 
                $this->destinatario, 
                $this->transportador
        );
        
        // TODO remover essas linhas abaixo
        $listener->processPage();

        $conn->freezeListener();            
            
        echo 'view /sefaz-dfe-nfae/paginas/produtos.faces';
        $resp = $conn->sendFlexible('/sefaz-dfe-nfae/paginas/produtos.faces', 'GET', new Params($listener->getLast_cid()));

        echo $resp->getBody()->getContents();
    }

}
