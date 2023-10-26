# Sistema de Login com JWT

Sistema de autenticação simples utilizando JSON Web Tokens (JWT).

## Índice

- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Uso](#uso)
  - [Login](#login)
  - [Validação de Token](#validação-de-token)
- [Exemplos de Código](#exemplos-de-código)

## Requisitos

- PHP
- Biblioteca `firebase/php-jwt`

## Instalação

1. Instale a biblioteca via composer:
composer require firebase/php-jwt
composer update
## Uso

### Login

Ao realizar o login, um token JWT é gerado para o usuário com base em suas credenciais.

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