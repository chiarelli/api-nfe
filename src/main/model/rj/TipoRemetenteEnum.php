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

use NetChiarelli\Basics\lang\Enum;

/**
 * Description of TipoRemetente
 * 
 * @todo Futuramente ao refatorar o package NetChiarelli\Api_NFe\api para 
 * um projeto independente, não esquecer de desaclopar NetChiarelli\Api_NFe\util\Enum.
 * 
 * @example TipoOperacaoEnum Schema of this 
 * enum [ {CONST_NAME(REQUIRED)}, {ID}, {CANONICAL_NAME}, {DESCRIPTION} ]
 *
 * @author raphael
 */
class TipoRemetenteEnum extends Enum {
    
    const MEI = [
        'MEI', 1, 'MEI', 'MEI - Microempreendedor Individual'
    ];
    
    const PJ_NC = [
        'PJ_NC', 5, 'Pessoa Jurídica não contribuinte do ICMS', 'Pessoa Jurídica não contribuinte do ICMS'
    ];
    
    const PF_NC = [
        'PF_NC', 6, 'Pessoa física não contribuinte do ICMS', 'Pessoa física não contribuinte do ICMS'
    ];
    
}
