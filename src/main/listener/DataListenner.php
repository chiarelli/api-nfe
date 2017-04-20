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

use GuzzleHttp\TransferStats;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Description of DataListenner
 *
 * @author raphael
 */
class DataListenner {
    
    /** @var RequestInterface */
    protected $request;
    
    /** @var ResponseInterface */
    protected $response;
    
    /** @var TransferStats */
    protected $stats;
    
    public function __construct(
            RequestInterface $request, 
            ResponseInterface $response, 
            TransferStats $stats
        ) {
        $this->request = $request;
        $this->response = $response;
        $this->stats = $stats;
    }
    
    public function getRequest() {
        return $this->request;
    }

    public function getResponse() {
        return $this->response;
    }

    public function getStats() {
        return $this->stats;
    }


    
}
