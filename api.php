<?php
session_start();
require_once 'config/Database.php';
require_once 'models/Usuario.php';
require_once 'models/Fornecedor.php';
require_once 'models/Produto.php';
require_once 'models/Cesta.php';

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

$database = new Database();
$db = $database->getConnection();

if(!$db) {
    echo json_encode(['status' => 'error', 'message' => 'Erro de conexão com o banco']);
    exit;
}

switch($action) {
    case 'login':
        $usuario = new Usuario($db);
        $usuario->email = $_POST['email'];
        $usuario->senha = $_POST['senha'];
        if($usuario->login()) {
            $_SESSION['usuario_id'] = $usuario->id;
            $_SESSION['usuario_nome'] = $usuario->nome;
            echo json_encode(['status' => 'success', 'message' => 'Login realizado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Email ou senha incorretos']);
        }
        break;

    case 'cadastrar_usuario':
        $usuario = new Usuario($db);
        $usuario->nome = $_POST['nome'];
        $usuario->email = $_POST['email'];
        $usuario->senha = $_POST['senha'];
        if($usuario->cadastrar()) {
            echo json_encode(['status' => 'success', 'message' => 'Usuário cadastrado com sucesso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar']);
        }
        break;

    case 'cadastrar_fornecedor':
        if(!isset($_SESSION['usuario_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Não autenticado']); exit;
        }
        $fornecedor = new Fornecedor($db);
        $fornecedor->nome = $_POST['nome'];
        $fornecedor->cnpj = $_POST['cnpj'];
        $fornecedor->email = $_POST['email'];
        if($fornecedor->criar()) {
            echo json_encode(['status' => 'success', 'message' => 'Fornecedor cadastrado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar fornecedor']);
        }
        break;

    case 'listar_fornecedores':
        $fornecedor = new Fornecedor($db);
        $stmt = $fornecedor->lerTodos();
        $fornecedores = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $fornecedores]);
        break;

    case 'cadastrar_produto':
        if(!isset($_SESSION['usuario_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Não autenticado']); exit;
        }
        $produto = new Produto($db);
        $produto->nome = $_POST['nome'];
        $produto->descricao = $_POST['descricao'];
        $produto->preco = $_POST['preco'];
        $produto->fornecedor_id = $_POST['fornecedor_id'];
        if($produto->criar()) {
            echo json_encode(['status' => 'success', 'message' => 'Produto cadastrado']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao cadastrar produto']);
        }
        break;

    case 'listar_produtos':
        $produto = new Produto($db);
        $stmt = $produto->lerTodos();
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $produtos]);
        break;

    case 'adicionar_cesta':
        if(!isset($_SESSION['usuario_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Não autenticado']); exit;
        }
        $produtos_ids = isset($_POST['produtos']) ? $_POST['produtos'] : [];
        if(empty($produtos_ids)) {
            echo json_encode(['status' => 'error', 'message' => 'Nenhum produto selecionado']); exit;
        }
        $cesta = new Cesta($db);
        $cesta->usuario_id = $_SESSION['usuario_id'];
        $cesta->getCestaAtiva();
        
        if($cesta->adicionarProdutos($produtos_ids)) {
            echo json_encode(['status' => 'success', 'message' => 'Produtos adicionados à cesta']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar produtos']);
        }
        break;

    case 'ver_cesta':
        if(!isset($_SESSION['usuario_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Não autenticado']); exit;
        }
        $cesta = new Cesta($db);
        $cesta->usuario_id = $_SESSION['usuario_id'];
        $cesta->getCestaAtiva();
        
        $stmt = $cesta->listarProdutos();
        $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $total = 0;
        foreach($produtos as $p) {
            $total += $p['preco'];
        }
        
        echo json_encode([
            'status' => 'success', 
            'data' => [
                'produtos' => $produtos,
                'total' => $total,
                'quantidade' => count($produtos)
            ]
        ]);
        break;
        
    case 'logout':
        session_destroy();
        echo json_encode(['status' => 'success', 'message' => 'Logout realizado']);
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Ação inválida']);
        break;
}
