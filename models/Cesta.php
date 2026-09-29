<?php
class Cesta {
    private $conn;

    public $id;
    public $usuario_id;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCestaAtiva() {
        $query = "SELECT id FROM cestas WHERE usuario_id = :usuario_id ORDER BY data_criacao DESC LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
        } else {
            // Cria nova cesta
            $query_create = "INSERT INTO cestas SET usuario_id = :usuario_id";
            $stmt_create = $this->conn->prepare($query_create);
            $stmt_create->bindParam(":usuario_id", $this->usuario_id);
            if($stmt_create->execute()) {
                $this->id = $this->conn->lastInsertId();
            }
        }
        return $this->id;
    }

    public function adicionarProdutos($produtos_ids) {
        if(empty($this->id)) return false;
        
        $sucesso = true;
        foreach($produtos_ids as $produto_id) {
            $query = "INSERT IGNORE INTO cesta_produtos SET cesta_id = :cesta_id, produto_id = :produto_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":cesta_id", $this->id);
            $stmt->bindParam(":produto_id", $produto_id);
            if(!$stmt->execute()) {
                $sucesso = false;
            }
        }
        return $sucesso;
    }

    public function listarProdutos() {
        $query = "SELECT p.id, p.nome, p.preco 
                  FROM cesta_produtos cp
                  JOIN produtos p ON cp.produto_id = p.id
                  WHERE cp.cesta_id = :cesta_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":cesta_id", $this->id);
        $stmt->execute();
        return $stmt;
    }
}
