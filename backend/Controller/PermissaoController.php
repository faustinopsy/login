<?php

namespace App\Controller;
use App\Model\Usuario;

class PermissaoController {

    private $ips_permitidos;
    private $origesPermitidas;
    public function __construct() {
        $this->ips_permitidos = ['::1', '123.123.123.124'];
        $this->origesPermitidas= ['http://localhost:8089','http://192.168.56.1'];
        
    }
    public function autorizado(){
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: * ');
        header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        //$this->verOrigem();
        //$this->verIP();
        $this->Token();
    }
    public function verOrigem(){
        if(!in_array($_SERVER['HTTP_ORIGIN'], $this->origesPermitidas)){
            echo json_encode(['status'=>false,'mensagem' => 'Acesso não autorizado origem'], 403);
            exit;
        }
    }
    public function verIP(){
        if (!in_array($_SERVER['REMOTE_ADDR'], $this->ips_permitidos)) {
            echo json_encode(['status'=>false,'mensagem' => 'Acesso não autorizado ip'], 403);
            exit;
        }
    }
    public function Token(){
        $usuario = new Usuario();
        $headers = getallheaders();
        if(!isset($headers['Authorization'])) {
            echo json_encode(['status' => false, 'message' => "sem token"]);
            exit;
        }
        $token = $headers['Authorization'] ?? null;
        $usuariosController = new UsuarioController($usuario);
        $validationResponse = $usuariosController->validarToken($token);
        if ($token === null || !$validationResponse['status']) {
            echo json_encode(['status' => false, 'message' => $validationResponse['message']]);
            exit;
        }
        
    }
    public function verToken(){
        $usuario = new Usuario();
        $headers = getallheaders();
        if(!isset($headers['Authorization'])) {
            echo json_encode(['status' => false, 'message' => "sem token"]);
            exit;
        }
        $token = $headers['Authorization'] ?? null;
        $usuariosController = new UsuarioController($usuario);
        $validationResponse = $usuariosController->validarToken($token);
        if ($token === null || !$validationResponse['status']) {
            echo json_encode(['status' => false, 'message' => $validationResponse['message']]);
            exit;
        }
        echo json_encode(['status' => true, 'message' => 'Token válido','telas'=>$validationResponse['telas']]);
        exit;
    }
}