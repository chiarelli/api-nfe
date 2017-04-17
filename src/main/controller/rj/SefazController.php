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

namespace NetChiarelli\Api_NFe\controller\rj;

use NetChiarelli\Api_NFe\exception\ApiException;
use NetChiarelli\Api_NFe\exception\ControllerException;
use NetChiarelli\Api_NFe\model\rj\Destinatario;
use NetChiarelli\Api_NFe\model\rj\Pedido;
use NetChiarelli\Api_NFe\model\rj\Remetente;
use NetChiarelli\Api_NFe\model\rj\Transportador;

/**
 * Description of SefazController
 *
 * @author raphael
 */
class SefazController {
    
    /**
     * 
     * @param string $chaveAcesso
     * 
     * @return \ZipArchive Arquivo zip contendo xml e pdf da NFAe
     * @throws ControllerException
     */
    public function get_NFAe($chaveAcesso) {        
        try {
            
            
            
            
        } catch (ApiException $exc) {
            throw new ControllerException($exc->getMessage(), $exc->getCode(), $exc);
        }            
    }
    
    /**
     * 
     * @param Remetente $remetente
     * @param Destinatario $destinatario
     * @param Transportador $transportador
     * @param Pedido $pedido
     * 
     * @return \ZipArchive Arquivo zip contendo xml e pdf da NFAe
     * @throws ControllerException Caso o arquivo zip não exista ou não possa ser 
     * recuperado.
     */
    public function gerar_NFAe(
            Remetente $remetente, 
            Destinatario $destinatario, 
            Transportador $transportador, 
            Pedido $pedido
                              ) {        
        try {
            
            
        
        } catch (ApiException $exc) {
            throw new ControllerException($exc->getMessage(), $exc->getCode(), $exc);
        }
    }
    
    
    public static function extractXmlFile(\ZipArchive $zipArchive) {
        
    }
    
    public static function extractPdfFile(\ZipArchive $zipArchive) {
        
    }
    
    public static function DANFE_ToPng(\ZipArchive $zipArchive) {
        
    }
    
    
}
