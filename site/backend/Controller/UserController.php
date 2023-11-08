<?php

namespace App\Controller;

use App\Model\Model;
use App\Model\Usuario;
use App\Model\Endereco;
use App\Controller\EnderecoController;
class UserController {

    private $db;
    private $usuarios;
    private $enderecos;
    private $controllerenderecos;
    public function __construct() {
        $this->db = new Model();
        $this->usuarios = new Usuario();
        $this->enderecos = new Endereco();
        //$this->db->ExcluirTabelaEndereco();
        $this->db->criarTabelaEndereco();
    }
    public function select(){
        $user = $this->db->select('users');
        
        return  $user;
    }
    public function selectId($id){
        $user = $this->db->select('users',['id'=>$id]);
        
        return  $user;
    }
    public function insert($data){
        $this->usuarios->setNome($data['nome']);
        $this->usuarios->setEmail($data['email']);
        $this->usuarios->setSenha($data['senha']);
        if($this->db->insert('users', [
        'nome'=> $this->usuarios->getNome(),
        'email'=> $this->usuarios->getEmail(),
        'senha'=>$this->usuarios->getSenha() ])){
            $iduser=$this->db->getLastInsertId();
            $this->enderecos->setCep($data['cep']);
            $this->enderecos->setRua($data['rua']);
            $this->enderecos->setBairro($data['bairro']);
            $this->enderecos->setCidade($data['cidade']);
            $this->enderecos->setUf($data['uf']);
            $this->enderecos->setIduser($iduser);
            $this->controllerenderecos = new EnderecoController($this->enderecos);
            if($this->controllerenderecos->insert()){
                return true;
            }
        }
        return false;
    }
    public function update($newData,$condition){
        if($this->db->update('users', $newData, ['id'=>$condition])){
            return true;
        }
        return false;
    }
    public function delete( $conditions){
        if($this->db->delete('users', ['id'=>$conditions])){
            return true;
        }
        return false;
        
    }
}
