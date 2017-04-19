<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace NetChiarelli\Api_NFe\service\rj\util;

/**
 * Description of SubmitFormProdutos
 *
 * @author raphael
 */
class SubmitFormProdutos {

    protected $viewState;
    
    /**
     * 
     * @param string $viewState
     */
    public function __construct($viewState) {
        $this->viewState = $viewState;
    }

    function submitForm() {
        return [
            'formulario' => 'formulario',
            'idProduto' => '',
            'codigo' => '',
            'descricao' => '',
            'ncm' => '',
            'unidade' => '',
            'quantidade' => '',
            'valorUnitario' => '',
            'tributacao' => '1',
            'valorFrete' => '',
            'valorSeguro' => '',
            'valorOutrasDesp' => '',
            'descontoNota' => '',
            'javax.faces.ViewState' => $this->viewState,
        ];
    }

}
