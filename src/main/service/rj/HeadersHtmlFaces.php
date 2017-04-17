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

/**
 * Description of HeadersEventsFaces
 *
 * @author raphael
 */
class HeadersHtmlFaces {
    
    static function formSubmit() {
        return [            
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Encoding' => 'gzip, deflate, sdch',
            'Accept-Language' => 'pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4',
            'Cache-Control' => 'max-age=0',
//            'Connection' => 'keep-alive',
            'Host' => 'www4.fazenda.rj.gov.br',
            'Upgrade-Insecure-Requests' => '1'
        ];
    }
    
    static function facesSendRequests() {
        return [    
            'Accept' => '*/*',
            'Accept-Encoding'=> 'gzip,deflate',
            'Accept-Language'=>'pt-BR,pt;q=0.8,en-US;q=0.6,en;q=0.4',
//            'Connection'=> 'keep-alive',
            'Content-type'=>'application/x-www-form-urlencoded;charset=UTF-8',
            'Faces-Request' => 'partial/ajax',
            'Host'=>'www4.fazenda.rj.gov.br',
            'Origin'=>'http=>//www4.fazenda.rj.gov.br',
        ];
    }
    
}
