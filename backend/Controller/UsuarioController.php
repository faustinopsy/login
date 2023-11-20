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
    public function recupasenha(){
        $novasenha = $this->gerarStringAlfanumerica(8);
        $condicoes = ['email' => $this->usuarios->getEmail()];
        $resultado = $this->select($this->usuarios, $condicoes);
        if(!$resultado){
            return ['status' => false, 'message' => 'Usuário não encontrado.'];
        }
        $email= new EnviaEMail();
        $dados=['email'=>$this->usuarios->getEmail(),'senha'=>$novasenha];
        $emailuser = $this->usuarios->getEmail();
        if($email->recupasenha($dados)){
            $senhacriptografada=password_hash($novasenha, PASSWORD_DEFAULT);
            $query = "UPDATE usuario SET senha=:senha WHERE email=:email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':senha', $senhacriptografada);
            $stmt->bindParam(':email', $emailuser);
            $stmt->execute();
            return ['status'=>true,'message'=>'E-mail enviado com sucesso!'];
        }else {
            return ['status'=>false,'message'=>'falha ao enviar email!'];
        }
    }
    public function adicionarUsuario(){
        return $this->insert($this->usuarios);
    }
    
    public function listarUsuarios(){
        return $this->select($this->usuarios);
    }
    public function listarUsuariosDescriptografado(){
        $resultado=$this->select($this->usuarios);
        $retorno[]=[
            'id' => $resultado[0]['id'],
            'nome' => $resultado[0]['nome'],
            'email' => $resultado[0]['email'],
            'criado' => $resultado[0]['criado'],
        ];
             
        return $retorno;
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
    public function gerarStringAlfanumerica($tamanho) {
        $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $stringAleatoria = '';
        for ($i = 0; $i < $tamanho; $i++) {
            $index = rand(0, strlen($caracteres) - 1);
            $stringAleatoria .= $caracteres[$index];
        }
        return $stringAleatoria;
    }
    
    
}
