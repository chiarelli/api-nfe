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

namespace NetChiarelli\Api_NFe\model\rj;

use NetChiarelli\Api_NFe\util\Enum;

/**
 * Description of TipoContribuinteEnum
 * 
 * @todo Futuramente ao refatorar o package NetChiarelli\Api_NFe\api para 
 * um projeto independente, não esquecer de desaclopar NetChiarelli\Api_NFe\util\Enum.
 * 
 * @example TipoContribuinteEnum Schema of this enum [ {CONST_NAME}, {ID}, {CANONICAL_NAME}, {DESCRIPTION} ]
 *
 * @author raphael
 */
class TipoContribuinteEnum extends Enum {
    
    const CONTRIBUINTE_ICMS = [
        'CONTRIBUINTE_ICMS', 1, 
        'Contribuinte do ICMS', 
        'Contribuinte do ICMS'
    ];
    const CONTRIBUINTE_ISENTO = [
        'CONTRIBUINTE_ISENTO', 2, 
        'Contribuinte isento de Inscrição no cadastro de Contribuintes', 
        'Contribuinte isento de Inscrição no cadastro de Contribuintes'
    ];
    const NAO_CONTRIBUINTE = [
        'NAO_CONTRIBUINTE', 9, 
        'Não contribuinte', 
        'Não contribuinte'
    ];  

}
