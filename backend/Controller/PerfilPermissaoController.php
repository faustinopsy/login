<?php

namespace App\Controller;

use App\Model\Perfil;
use App\Model\Permissoes;
use App\Database\Crud;

class PerfilPermissaoController extends Crud
{
    public function __construct(){
        parent::__construct();
    }
    public function adicionarPermissao(Perfil $perfil, $Permissoes){
        $resultado=$this->listarPermissao($Permissoes);
        if(!$resultado){
            $this->cadPermissao($Permissoes);
            return $this->associar($perfil->getId(), $this->getLastInsertId());
        }else{
            return $this->associar($perfil->getId(), $resultado[0]['id']);
        }
    }
    public function removerPermissao(Perfil $perfil, $Permissoes){
        $resultado=$this->listarPermissao($Permissoes);
        return $this->desassociar($perfil->getId(), $resultado[0]['id']);
    }

    public function obterPermissoesDoPerfil(Perfil $perfil){
        return $this->selectPermissoesPorPerfil($perfil->getId());
    }

    public function obterPerfisDaPermissao(Permissoes $Permissoes){
        return $this->listarPerfisPorPermissao($Permissoes->getId());
    }
    public function listarTodos(){
        return $this->listarTodosOsPerfis();
    }
    public function listarPermissoes(){
        return $this->listarTodasPermissoes();
    }

}
