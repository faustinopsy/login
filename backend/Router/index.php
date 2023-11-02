<?php
namespace App\Router;
require "../../vendor/autoload.php";

use App\Controller\PerfilPermissaoController;
use App\Controller\UsuarioController;
use App\Model\Perfil;
use App\Model\Usuario;


use Bramus\Router\Router;
$usuario = new Usuario();
$router = new Router();


    // In case one is using PHP 5.4's built-in server
    $filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
    if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}


    $router->set404(function () {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        echo '404, route not found!';
    });

  
    $router->set404('/test(/.*)?', function () {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        echo '<h1><mark>404, route not found!</mark></h1>';
    });

    $router->set404('/api(/.*)?', function() {
        header('HTTP/1.1 404 Not Found');
        header('Content-Type: application/json');
        $jsonArray = array();
        $jsonArray['status'] = "404";
        $jsonArray['status_text'] = "route not defined";
        echo json_encode($jsonArray);
    });

 
    $router->before('GET', '/.*', function () {
        header('X-Powered-By: bramus/router');
    });

    // GET token
    $router->get('/token', function () {
        $usuario = new Usuario();
        $headers = getallheaders();
        $token = $headers['Authorization'] ?? null;
        $usuariosController = new UsuarioController($usuario);
        $validationResponse = $usuariosController->validarToken($token);
        if ($token === null || !$validationResponse['status']) {
            echo json_encode(['status' => false, 'message' => $validationResponse['message']]);
            exit;
        }
        echo json_encode(['status' => true, 'message' => 'Token válido','telas'=>$validationResponse['telas']]);
        exit;
    });
    // Rota Login
    $router->post('/login', function () {
        $body = json_decode(file_get_contents('php://input'), true);
        $usuario = new Usuario();
        if (isset($body['email'])) {
            $usuario->setEmail($body['email']);
            $senha=$body['senha'];
            $lembrar=$body['lembrar'];
            $usuariosController = new UsuarioController($usuario);
            $resultado = $usuariosController->login($senha,$lembrar);
            if(!$resultado['status']){
                echo json_encode(['status' => $resultado['status'], 'message' => $resultado['message']]);
               exit;
            }
            echo json_encode(['status' => $resultado['status'], 'message' => $resultado['message'],'token'=>$resultado['token']]);
        }
    });
    
    // Todos metodos Usuarios
    $router->mount('/Usuarios', function () use ($router) {
        $router->get('/', function () {
            $usuario = new Usuario();
            $usuariosController = new UsuarioController($usuario);
            $resultado = $usuariosController->listarUsuarios();
            if(!$resultado){
                echo json_encode(["status" => false, "Usuarios" => $resultado,"mensagem"=>"nenhum resultado encontrado"]);
                exit;
            }else{
                echo json_encode(["status" => true, "Usuarios" => $resultado]);
                exit;
            }
        });

        $router->get('/(\d+)', function ($id) {
            $usuario = new Usuario();
            $usuariosController = new UsuarioController($usuario);
            $resultado = $usuariosController->buscarPorEmail($id);
                if(!$resultado){
                    echo json_encode(["status" => false, "Usuarios" => $resultado,"mensagem"=>"nenhum resultado encontrado"]);
                    exit;
                }else{
                    echo json_encode(["status" => true, "Usuarios" => $resultado[0]]);
                    exit;
                }
        });

        $router->put('/(\d+)', function ($id) {
            echo 'Update usuario id ' . htmlentities($id);
        });
        $router->post('/Registrar', function () {
            $body = json_decode(file_get_contents('php://input'), true);
            $usuario = new Usuario();
            $usuario->setNome($body['nome']);
            $usuario->setEmail($body['email']);
            $usuario->setSenha($body['senha']);
            $usuario->setPerfilId(2);
            $usuariosController = new UsuarioController($usuario);
            $resultado = $usuariosController->adicionarUsuario();
            echo json_encode(['status' => $resultado]);
        });
    });

    // Todos metodos Permissao
    $router->mount('/Permissao', function () use ($router) {
        $router->get('/', function () {
        $controller = new PerfilPermissaoController();
            $resultado = $controller->listarTodos();
            if (!$resultado) {
                echo json_encode(["status" => false, "mensagem" => "Nenhum perfil encontrado"]);
                exit;
            } else {
                echo json_encode($resultado);
                exit;
            }       
        });

        $router->get('/(\d+)', function ($id) {
            $perfil = new Perfil();
            $perfil->setId($id);
            $controller = new PerfilPermissaoController();
            $resultado = $controller->obterPermissoesDoPerfil($perfil);
            if (!$resultado) {
                echo json_encode(["status" => false, "mensagem" => "Nenhum resultado encontrado"]);
                exit;
            } else {
                echo json_encode($resultado);
                exit;
            }
        });

        $router->put('/(\d+)', function ($id) {
            echo 'Update usuario id ' . htmlentities($id);
        });
        $router->delete('/(\d+)', function ($id) {
            $controller = new PerfilPermissaoController();
            $perfil = new Perfil();
            $body = json_decode(file_get_contents('php://input'), true);
            $perfil->setId($id);
            $resultado = $controller->removerPermissao($perfil, $body['nome']);
            echo json_encode(['status' => $resultado]);
        });
    });

    $router->run();

// EOF
