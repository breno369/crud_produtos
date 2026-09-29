<?php
session_start();
if(isset($_SESSION['usuario_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gestão de Produtos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-md w-96 max-w-full">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-600">Gestão de Produtos</h2>
        
        <div id="login-form">
            <h3 class="text-lg font-semibold mb-4">Acesso</h3>
            <form id="formLogin">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="loginEmail" class="w-full px-3 py-2 border rounded-md" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Senha</label>
                    <input type="password" name="senha" id="loginSenha" class="w-full px-3 py-2 border rounded-md" required>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700">Entrar</button>
            </form>
            <p class="mt-4 text-center text-sm">Não tem conta? <a href="#" onclick="toggleForms()" class="text-blue-600">Cadastre-se</a></p>
        </div>

        <div id="register-form" class="hidden">
            <h3 class="text-lg font-semibold mb-4">Novo Usuário</h3>
            <form id="formRegister">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Nome</label>
                    <input type="text" name="nome" id="regNome" class="w-full px-3 py-2 border rounded-md" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="regEmail" class="w-full px-3 py-2 border rounded-md" required>
                </div>
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Senha</label>
                    <input type="password" name="senha" id="regSenha" class="w-full px-3 py-2 border rounded-md" required>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700">Cadastrar</button>
            </form>
            <p class="mt-4 text-center text-sm">Já tem conta? <a href="#" onclick="toggleForms()" class="text-blue-600">Fazer Login</a></p>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
    <script>
        function toggleForms() {
            document.getElementById('login-form').classList.toggle('hidden');
            document.getElementById('register-form').classList.toggle('hidden');
        }
    </script>
</body>
</html>
