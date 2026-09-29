<?php
class Fornecedor {
    private $conn;
    private $table_name = "fornecedores";

    public $id;
    public $nome;
    public $cnpj;
    public $email;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function lerTodos() {
        $query = "SELECT id, nome, cnpj, email FROM " . $this->table_name . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " SET nome=:nome, cnpj=:cnpj, email=:email";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->cnpj = htmlspecialchars(strip_tags($this->cnpj));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":cnpj", $this->cnpj);
        $stmt->bindParam(":email", $this->email);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
