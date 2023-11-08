<?php

namespace App\Router;

require "../../vendor/autoload.php";

use App\Controller\PerfilPermissaoController;
use App\Controller\PermissaoController;
use App\Model\Perfil;
use App\Model\Permissao;

$perfil = new Perfil();
$permissao = new Permissao();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');
header('Cache-Control: no-cache, no-store, must-revalidate');

$body = json_decode(file_get_contents('php://input'), true);

$perfilId = isset($_GET['perfilId']) ? $_GET['perfilId'] : '';
$permissaoId = isset($body['permissaoId']) ? $body['permissaoId'] : '';

$controller = new PerfilPermissaoController();

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        $perfil->setId($perfilId);

        $resultado = $controller->adicionarPermissao($perfil, $body['nome']);
        echo json_encode(['status' => $resultado]);
        break;

        case "GET":
            if ($perfilId) {
                $perfil->setId($perfilId);
                $resultado = $controller->obterPermissoesDoPerfil($perfil);
        
                if (!$resultado) {
                    echo json_encode(["status" => false, "mensagem" => "Nenhum resultado encontrado"]);
                    exit;
                } else {
                    echo json_encode($resultado);
                    exit;
                }
            } else {
                $permitido = new PermissaoController();
                $permitido->autorizado();
                $controller = new PerfilPermissaoController();
                $resultado = $controller->listarPermissoes();
                if (!$resultado) {
                    echo json_encode(["status" => false, "mensagem" => "Nenhuma permissao encontrado"]);
                    exit;
                } else {
                    echo json_encode($resultado);
                    exit;
                }     
            }
            break;
        

    case "DELETE":
        $perfil->setId($perfilId);
        $resultado = $controller->removerPermissao($perfil, $body['nome']);
        echo json_encode(['status' => $resultado]);
        break;
}
