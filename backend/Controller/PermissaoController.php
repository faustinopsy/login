<?php

namespace App\Controller;
use App\Model\Permissoes;
use App\Database\Crud;
class PermissaoController extends Crud{
    private $Permissoes;
    public function __construct($Permissoes){
        parent::__construct();
        $this->Permissoes=$Permissoes;
    }
    public function adicionarPermissao(){
        return $this->insert($this->Permissoes);
    }
}