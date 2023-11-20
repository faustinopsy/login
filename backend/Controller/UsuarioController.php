<?php

namespace App\Controller;
use App\Database\Crud;
use App\Model\Usuario;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use stdClass;
use App\Cryptonita\Crypto;

class UsuarioController extends Crud{
    private $usuarios;
    public function __construct($usuario)
    {
        parent::__construct();
        $this->usuarios=$usuario;
    }
    
    public function validarToken($token){
        
        $key = TOKEN;
        $algoritimo = 'HS256';
        try {
            $decoded = JWT::decode($token, new Key($key, $algoritimo));
            $permissoes = $decoded->telas;
            if($_SERVER['SERVER_NAME']==$decoded->aud){
                return ['status' => true, 'message' => 'Token válido!', 'telas'=>$permissoes];
            }else{
                return ['status' => false, 'message' => 'Token inválido! Motivo: dominio invalido' ];
            }
        } catch(Exception $e) {
            return ['status' => false, 'message' => 'Token inválido! Motivo: ' . $e->getMessage()];
        }
    }
    public function login($senha,$lembrar) {
        $condicoes = ['email' => $this->usuarios->getEmail()];
        $resultado = $this->select($this->usuarios, $condicoes);
        $checado=$lembrar? 60*12 : 3;
        if (!$resultado) {
            return ['status' => false, 'message' => 'Usuário não encontrado.'];
        }
        if (!password_verify($senha, $resultado[0]['senha'])) {
            return ['status' => false, 'message' => 'Senha incorreta.'];
        }
        $permissoes = $this->selectPermissoesPorPerfil($resultado[0]['perfilid']);
        $key = TOKEN;
        $local=$_SERVER['HTTP_HOST'];
        $nome=$_SERVER['SERVER_NAME'];
        $algoritimo='HS256';
            $payload = [
                "iss" =>  $local,
                "aud" =>  $nome,
                "iat" => time(),
                "exp" => time() + (60 * $checado),  
                "sub" => $this->usuarios->getEmail(),
                'telas'=>$permissoes
            ];
            
            $jwt = JWT::encode($payload, $key,$algoritimo);
        return ['status' => true, 'message' => 'Login bem-sucedido!','token'=>$jwt,'telas'=>$permissoes];
    }
    public function adicionarUsuario(){
        return $this->insert($this->usuarios);
    }
    
    public function listarUsuarios(){
        return $this->select($this->usuarios);
    }
    
    public function buscarPorEmail(string $email){
        $condicoes = ['email' => $email];
        $resultados = $this->select($this->usuarios, $condicoes);
        return count($resultados) > 0 ? $resultados[0] : null;
    }
    public function buscarPorId(int $id){
        $condicoes = ['id' => $id];
        $resultados = $this->select($this->usuarios, $condicoes);
        return count($resultados) > 0 ? $resultados[0] : null;
    }
    
    public function removerUsuario(){
        $condicoes = ['email' => $this->usuarios->getEmail()];
        return $this->delete($this->usuarios, $condicoes);
    }
    
}
