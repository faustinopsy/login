<?php

namespace App\Test;

use App\Model\Model;

class UserTest {

    private $db;

    public function __construct() {
        $this->db = new Model();
    }

    public function runTests() {
        echo "CRUD tests...<br>";
        $this->select();
        $this->insert();
        $this->select();
        $this->update();
        $this->select();
        $this->delete();
        $this->select();
    }
    public function select(){
        echo "<b>SELEÇÃO:</b>";
        $user = $this->db->select('users');
        echo "Test do Select " . count($user)  . " users.\n";
        foreach($user as $users){
            foreach($users as $key => $value){
                echo '<br>'.$key.': '.$value;
            }
        }
        echo "<hr>";
    }
    public function insert(){
        echo "<b>INSERÇÃO:</b>";
        $data = ['nome' => 'testUser', 'email' => 'testPass@xxx.com', 'senha' => '123456'];
        $result = $this->db->insert('users', $data);
        echo "Teste do Insert: " . ($result ? "Sucesso" : "Falha") . "\n";
        echo "<hr>";
    }
    public function update(){
        echo "<b>ATUALIZAÇÃO:</b>";
        $newData = ['nome' => 'updatedUser'];
        $conditions = ['nome' => 'testUser'];
        $result = $this->db->update('users', $newData, $conditions);
        echo "Update : " . ($result ? "Sucesso" : "Falha") . "\n";
        echo "<hr>";
    }
    public function delete(){
        echo "<b>EXCLUSÃO:</b>";
        $conditions = ['nome' => 'updatedUser'];
        $result = $this->db->delete('users', $conditions);
        echo "Delete : " . ($result ? "Sucesso" : "Falha") . "\n";
        echo "<hr>";
    }
}
