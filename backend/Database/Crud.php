<?php
namespace App\Database;

use App\Cryptonita\Crypto;
use App\Database\Connection;
use Exception;
use PDO;
use ReflectionProperty;
class Crud extends Connection{
    private $cripto;
    public function __construct() {
        parent::__construct();
        $this->cripto=new Crypto();
    }
    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }
    public function insert($object) {
        $reflectionClass = new \ReflectionClass($object);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE);
        $table=$reflectionClass->getShortName();
        $data = [];
        foreach ($properties as $property) {
            $property->setAccessible(true); 
            if ($property->getName() === 'id') { 
                continue;
            }
            $data[$property->getName()] = $property->getValue($object);
           
        }
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($query);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $this->cripto->hidden($value));
        }
        return $stmt->execute();
    }
    public function select($object, $conditions = []) {
        
        $reflectionClass = new \ReflectionClass($object);
        $table=$reflectionClass->getShortName();
        $query = "SELECT * FROM $table";
        if (!empty($conditions)) {
            $conditionsStr = implode(" AND ", array_map(function($item) {
            return "$item = :$item";
            }, array_keys($conditions)));
            $query .= " WHERE $conditionsStr";
        }
        $stmt = $this->conn->prepare($query);
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $this->cripto->hidden($value));
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function selectPermissoesPorPerfil($perfilId) {
        $stmt = $this->conn->prepare("CALL GetPermissoesPorPerfil(:perfilId)");
        $stmt->bindValue(":perfilId", $perfilId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function update($object, $conditions) {
        $reflectionClass = new \ReflectionClass($object);
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PRIVATE);
        $table = $reflectionClass->getShortName();
        $data = [];
        foreach ($properties as $property) {
            $property->setAccessible(true);
            if ($property->getName() === 'id') { 
                continue;
            }
            $data[$property->getName()] = $property->getValue($object);
        }
        $dataStr = implode(", ", array_map(function($item) {
            return "$item = :$item";
        }, array_keys($data)));
        $conditionsStr = implode(" AND ", array_map(function($item) {
            return "$item = :condition_$item";
        }, array_keys($conditions)));
        $query = "UPDATE $table SET $dataStr WHERE $conditionsStr";
        $stmt = $this->conn->prepare($query);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $this->cripto->hidden($value));
        }
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":condition_$key", $value);
        }
        return $stmt->execute();
    }
    public function delete($object, $conditions) {
        $reflectionClass = new \ReflectionClass($object);
        $table = $reflectionClass->getShortName();
        $conditionsStr = implode(" AND ", array_map(function($item) {
            return "$item = :$item";
        }, array_keys($conditions)));
        $query = "DELETE FROM $table WHERE $conditionsStr";
        $stmt = $this->conn->prepare($query);
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        return $stmt->execute();
    }
   
public function listarTodosOsPerfis()
{
    $query = "SELECT id, nome FROM perfil";
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function cadPermissao($permissao)
{
    $query = "
        INSERT INTO permissoes (nome) VALUES (:nome)
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":nome", $permissao);
    return $stmt->execute();
}
public function associar($perfilId, $permissaoId)
{
    $query = "
        INSERT INTO perfil_permissoes (perfil_id, permissao_id) VALUES (:perfil_id, :permissao_id)
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":perfil_id", $perfilId);
    $stmt->bindParam(":permissao_id", $permissaoId);
    return $stmt->execute();
}

public function desassociar($perfilId, $permissaoId)
{
    $query = "
        DELETE FROM perfil_permissoes WHERE perfil_id = :perfil_id AND permissao_id = :permissao_id
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":perfil_id", $perfilId);
    $stmt->bindParam(":permissao_id", $permissaoId);
    return $stmt->execute();
}
public function listarPermissao($permissao)
{
    $query = "
    SELECT id FROM permissoes where nome=:permissao
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":permissao", $permissao);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function listarPerfisPorPermissao($permissaoId)
{
    $query = "
        SELECT perfil.id, perfil.nome 
        FROM perfil_permissoes
        JOIN perfil ON perfil.id = perfil_permissoes.perfil_id
        WHERE perfil_permissoes.permissao_id = :permissao_id
    ";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":permissao_id", $permissaoId);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
