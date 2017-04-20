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

use DateTime;
use DateTimeZone;

/**
 * Description of RemetenteDateCreate
 * 
 * Este model é responsável por armazenar os dados dos atributos de date do Remetente do 
 * formulário da page http://www4.fazenda.rj.gov.br/sefaz-dfe-nfae/paginas/identificacao.faces
 *
 * @author raphael
 */
class RemetenteDateCreate {

    /**
     * Date na formatação 'd/m/Y' da hora da emissão da NFA-e.
     *
     * @var string
     */
    protected $inputDate;

    /**
     * Date na formatação 'm/Y' do mês corrente.
     *
     * @var string
     */
    protected $inputCurrentDate;

    /**
     * Hora da emissão da nota no formato 'H:i'
     *
     * @var string
     */
    protected $operacaoHora;

    public function __construct(DateTime $date = NULL, $timezone = 'America/Sao_Paulo') {

        if(empty($date)) {
            $date = new DateTime();
        }

        $date->setTimezone(new DateTimeZone($timezone));

        $this->inputDate = $date->format('d/m/Y'); // date('d/m/Y', $date->getTimestamp());
        $this->inputCurrentDate = date('m/Y');
        $this->operacaoHora = $date->format('H:i'); // date('H:i', $date->getTimestamp());
    }

    /**
     *
     * @return string
     */
    public function getInputDate() {
        return $this->inputDate;
    }

    /**
     *
     * @return string
     */
    public function getInputCurrentDate() {
        return $this->inputCurrentDate;
    }

    /**
     *
     * @return string
     */
    public function getOperacaoHora() {
        return $this->operacaoHora;
    }

    

}
