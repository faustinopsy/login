# Sistema de Login com JWT

Sistema de autenticação simples utilizando JSON Web Tokens (JWT).


## Requisitos

- PHP
- Biblioteca `firebase/php-jwt`

## Instalação

Clone o repositório depois instale a dependencia abaixo
Instale a biblioteca via composer:
```php
composer require firebase/php-jwt
composer require bramus/router
composer update
```
## Uso
Após a instalçao das dependências acima mencionadas, uma para gerenciar a criação e validação do token, a outra é para gerenciar rotas semelhante o que acontece com os principais frameworks, onde é capturado o metodo e o recurso e direciona para a função no contralador especifico.

### Login

Ao realizar o login, um token JWT é gerado para o usuário com base em suas credenciais.
o frontend recebe o token caso a autenticação seja bem sucedida, e a página index.html a cada 1 minuto faz uma requisição para verificar o token

```php
$key = "YOUR_SECRET_KEY";
$payload = [
 "iss" => "localhost",
 "aud" => "localhost",
 "iat" => time(),
 "exp" => time() + 3600,  // Expira em 1 hora
 "sub" => "user@email.com"
];

$jwt = JWT::encode($payload, $key);
```
Validação de Token
Para validar o token recebido em requisições subsequentes:
```php
$key = "YOUR_SECRET_KEY";

try {
    $decoded = JWT::decode($token, $key, ['HS256']);
    // Token é válido
} catch(Exception $e) {
    // Token inválido
}
```

## Estrutura do Projeto
backend/Controller/UsuarioController.php: Contém a lógica para gerar e validar o JWT.
backend/Router/LoginRouter.php: Roteamento e processamento das requisições HTTP.
## Como Usar
- Login
Para fazer login, envie uma requisição POST para backend/Router/loginRouter.php com os campos email e senha.
Se for bem-sucedido, você receberá um JWT que será usado para futuras autenticações.

- Validar Token
Para validar o token, envie uma requisição GET para backend/Router/loginRouter.php com o token no cabeçalho de Autorização.
O token será validado e, se ainda estiver válido, a resposta será positiva.

## Bônus (recursos adicionais)

- Os javascripts cards e times fazem uma busca fetch para um json que está estruturado, e o javascript ler o conteúdo e monta os cards, essa parte foi criada para mostrar a manipulação do DOM sem um banco de dados
