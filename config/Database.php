<?php
class Database {
    private $host = "127.0.0.1";
    private $db_name = "gestao_produtos";
    private $username = "root";
    private $password = ""; // Ajuste se seu MySQL tiver senha
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $temp_conn = new PDO("mysql:host=" . $this->host, $this->username, $this->password);
            $temp_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $temp_conn->exec("CREATE DATABASE IF NOT EXISTS `" . $this->db_name . "`");
            
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->initTables();
        } catch(PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
        return $this->conn;
    }

    private function initTables() {
        $queries = [
            "CREATE TABLE IF NOT EXISTS usuarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                senha VARCHAR(256) NOT NULL
            )",
            "CREATE TABLE IF NOT EXISTS fornecedores (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                cnpj VARCHAR(20) NOT NULL UNIQUE,
                email VARCHAR(100) NOT NULL
            )",
            "CREATE TABLE IF NOT EXISTS produtos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(100) NOT NULL,
                descricao TEXT,
                preco DECIMAL(10,2) NOT NULL,
                fornecedor_id INT,
                FOREIGN KEY (fornecedor_id) REFERENCES fornecedores(id) ON DELETE SET NULL
            )",
            "CREATE TABLE IF NOT EXISTS cestas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                usuario_id INT NOT NULL,
                data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
            )",
            "CREATE TABLE IF NOT EXISTS cesta_produtos (
                cesta_id INT NOT NULL,
                produto_id INT NOT NULL,
                PRIMARY KEY (cesta_id, produto_id),
                FOREIGN KEY (cesta_id) REFERENCES cestas(id) ON DELETE CASCADE,
                FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE
            )"
        ];

        foreach ($queries as $query) {
            $this->conn->exec($query);
        }
    }
}
?>
