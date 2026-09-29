<?php
class Produto {
    private $conn;
    private $table_name = "produtos";

    public $id;
    public $nome;
    public $descricao;
    public $preco;
    public $fornecedor_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function lerTodos() {
        $query = "SELECT p.id, p.nome, p.descricao, p.preco, p.fornecedor_id, f.nome as fornecedor_nome 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN fornecedores f ON p.fornecedor_id = f.id 
                  ORDER BY p.nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function criar() {
        $query = "INSERT INTO " . $this->table_name . " SET nome=:nome, descricao=:descricao, preco=:preco, fornecedor_id=:fornecedor_id";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));
        $this->preco = htmlspecialchars(strip_tags($this->preco));
        $this->fornecedor_id = htmlspecialchars(strip_tags($this->fornecedor_id));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":preco", $this->preco);
        $stmt->bindParam(":fornecedor_id", $this->fornecedor_id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
