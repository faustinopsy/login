<?php

namespace App\Router;

require "../../vendor/autoload.php";

use App\Controller\PerfilPermissaoController;
use App\Controller\UsuarioPermissaoController;
use App\Controller\PermissaoController;
use App\Model\Perfil;
use App\Model\Permissoes;

$perfil = new Perfil();
$Permissoes = new Permissoes();
$controller = new PerfilPermissaoController();
$permitido = new UsuarioPermissaoController();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Cache-Control: no-cache, no-store, must-revalidate');

$body = json_decode(file_get_contents('php://input'), true);

$permitido->autorizado();
switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST";
        $Permissoes->setNome($body['nome']);
        $permissaoController = new PermissaoController($Permissoes);
        $resultado = $permissaoController->adicionarPermissao();
        echo json_encode(['status' => $resultado]);
    break;
    case "GET":
        $resultado = $controller->listarPermissoes();
        if (!$resultado) {
            echo json_encode(["status" => false, "mensagem" => "Nenhuma Permissoes encontrado"]);
            exit;
        } else {
            echo json_encode($resultado);
            exit;
        }     
        
        break;
        

    case "DELETE":
        
        break;
}
