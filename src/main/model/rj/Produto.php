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

use Che\Math\Decimal\Decimal;
use NetChiarelli\Api_NFe\service\rj\util\Unidade;

/**
 * Description of Produtos
 *
 * @todo Implementar esta classe
 * 
 * @author raphael
 */
class Produto {
    
    /** @var string */
    protected $codigo;
    
    /** @var string */
    protected $descricao;
    
    /** @var integer */
    protected $qtd;
    
    /** @var Decimal */
    protected $valor;
    
    /** @var Decimal */
    protected $desconto;
    
    /** @var Decimal */
    protected $outrasDesp;
    
    /** @var Decimal */
    protected $seguro;

    protected $NCM;
    
    /** @var Unidade */
    protected $Unid;
    
}
