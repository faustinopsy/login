<?php

namespace App\Router;

require "../../vendor/autoload.php";

use App\Controller\PerfilPermissaoController;
use App\Controller\UsuarioPermissaoController;
use App\Controller\PerfilController;
use App\Model\Perfil;

$perfil = new Perfil();
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
        $perfil->setNome($body['nome']);
        $perfilController = new PerfilController($perfil);
        $resultado = $perfilController->adicionarPerfil();
        echo json_encode(['status' => $resultado]);
    break;
    
}
