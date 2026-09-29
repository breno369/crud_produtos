<?php
session_start();
if(!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestão de Produtos</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex h-screen">

    <div class="w-64 bg-blue-800 text-white flex flex-col">
        <div class="p-4 text-2xl font-bold border-b border-blue-700">
            Gestão Pro
        </div>
        <div class="p-4">
            <p>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></p>
        </div>
        <nav class="flex-1 p-2">
            <a href="#" onclick="showSection('dashboard')" class="block p-3 rounded hover:bg-blue-700 mb-2"><i class="fas fa-home mr-2"></i> Início</a>
            <a href="#" onclick="showSection('fornecedores')" class="block p-3 rounded hover:bg-blue-700 mb-2"><i class="fas fa-truck mr-2"></i> Fornecedores</a>
            <a href="#" onclick="showSection('produtos')" class="block p-3 rounded hover:bg-blue-700 mb-2"><i class="fas fa-box mr-2"></i> Produtos</a>
            <a href="#" onclick="showSection('loja')" class="block p-3 rounded hover:bg-blue-700 mb-2"><i class="fas fa-store mr-2"></i> Seleção de Produtos</a>
            <a href="#" onclick="showSection('cesta')" class="block p-3 rounded hover:bg-blue-700 mb-2"><i class="fas fa-shopping-cart mr-2"></i> Minha Cesta</a>
        </nav>
        <div class="p-4 border-t border-blue-700">
            <a href="#" onclick="logout()" class="block text-center p-2 bg-red-600 rounded hover:bg-red-700">Sair</a>
        </div>
    </div>

    <div class="flex-1 overflow-y-auto p-8">
        
        <div id="sec-dashboard" class="section block">
            <h1 class="text-3xl font-bold mb-4">Bem-vindo ao Sistema</h1>
            <p class="text-gray-600">Utilize o menu lateral para gerenciar fornecedores, produtos e sua cesta.</p>
        </div>

        <div id="sec-fornecedores" class="section hidden">
            <h1 class="text-3xl font-bold mb-4">Gerenciar Fornecedores</h1>
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-4">Novo Fornecedor</h3>
                <form id="formFornecedor" class="flex gap-4">
                    <input type="text" name="nome" placeholder="Nome" class="border p-2 rounded flex-1" required>
                    <input type="text" name="cnpj" placeholder="CNPJ" class="border p-2 rounded flex-1" required>
                    <input type="email" name="email" placeholder="Email" class="border p-2 rounded flex-1" required>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Salvar</button>
                </form>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4">Lista de Fornecedores</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 border">ID</th>
                            <th class="p-2 border">Nome</th>
                            <th class="p-2 border">CNPJ</th>
                            <th class="p-2 border">Email</th>
                        </tr>
                    </thead>
                    <tbody id="lista-fornecedores">
                        <!-- Carregado via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <div id="sec-produtos" class="section hidden">
            <h1 class="text-3xl font-bold mb-4">Gerenciar Produtos</h1>
            <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                <h3 class="text-xl font-semibold mb-4">Novo Produto</h3>
                <form id="formProduto" class="grid grid-cols-2 gap-4">
                    <input type="text" name="nome" placeholder="Nome do Produto" class="border p-2 rounded" required>
                    <input type="number" step="0.01" name="preco" placeholder="Preço" class="border p-2 rounded" required>
                    <select name="fornecedor_id" id="select-fornecedor" class="border p-2 rounded" required>
                        <option value="">Selecione um Fornecedor</option>
                        <!-- Carregado via AJAX -->
                    </select>
                    <input type="text" name="descricao" placeholder="Descrição" class="border p-2 rounded">
                    <div class="col-span-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Salvar Produto</button>
                    </div>
                </form>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold mb-4">Lista de Produtos</h3>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="p-2 border">ID</th>
                            <th class="p-2 border">Nome</th>
                            <th class="p-2 border">Preço</th>
                            <th class="p-2 border">Fornecedor</th>
                        </tr>
                    </thead>
                    <tbody id="lista-produtos">
                        <!-- Carregado via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <div id="sec-loja" class="section hidden">
            <h1 class="text-3xl font-bold mb-4">Catálogo - Selecionar Produtos</h1>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <form id="formCesta">
                    <div id="lista-loja" class="grid grid-cols-3 gap-4 mb-4">
                        <!-- Carregado via AJAX -->
                    </div>
                    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded text-lg w-full font-bold">
                        <i class="fas fa-cart-plus mr-2"></i> Adicionar Selecionados à Cesta
                    </button>
                </form>
            </div>
        </div>

        <div id="sec-cesta" class="section hidden">
            <h1 class="text-3xl font-bold mb-4">Sua Cesta de Compras</h1>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div id="conteudo-cesta">
                    <!-- Carregado via AJAX -->
                </div>
            </div>
        </div>

    </div>

    <script src="assets/js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            carregarFornecedores();
            carregarProdutos();
        });
    </script>
</body>
</html>
