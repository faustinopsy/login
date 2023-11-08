<?php

namespace App\Controller;

use App\Model\Perfil;
use App\Model\Permissao;
use App\Database\Crud;

class PerfilPermissaoController extends Crud
{
    public function __construct(){
        parent::__construct();
    }
    public function adicionarPermissao(Perfil $perfil, $permissao){
        $resultado=$this->listarPermissao($permissao);
        if(!$resultado){
            $this->cadPermissao($permissao);
            return $this->associar($perfil->getId(), $this->getLastInsertId());
        }else{
            return $this->associar($perfil->getId(), $resultado[0]['id']);
        }
    }
    public function removerPermissao(Perfil $perfil, $permissao){
        $resultado=$this->listarPermissao($permissao);
        return $this->desassociar($perfil->getId(), $resultado[0]['id']);
    }

    public function obterPermissoesDoPerfil(Perfil $perfil){
        return $this->selectPermissoesPorPerfil($perfil->getId());
    }

    public function obterPerfisDaPermissao(Permissao $permissao){
        return $this->listarPerfisPorPermissao($permissao->getId());
    }
    public function listarTodos(){
        return $this->listarTodosOsPerfis();
    }
    public function listarPermissoes(){
        return $this->listarTodasPermissoes();
    }
}
