function showSection(secId) {
    document.querySelectorAll('.section').forEach(el => el.classList.add('hidden'));
    document.getElementById('sec-' + secId).classList.remove('hidden');

    if(secId === 'fornecedores') carregarFornecedores();
    if(secId === 'produtos') { carregarFornecedoresSelect(); carregarProdutos(); }
    if(secId === 'loja') carregarLoja();
    if(secId === 'cesta') carregarCesta();
}

const formLogin = document.getElementById('formLogin');
if(formLogin) {
    formLogin.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'login');

        fetch('api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                window.location.href = 'dashboard.php';
            } else {
                alert(data.message);
            }
        });
    });
}

const formRegister = document.getElementById('formRegister');
if(formRegister) {
    formRegister.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'cadastrar_usuario');

        fetch('api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if(data.status === 'success') {
                toggleForms();
            }
        });
    });
}

function logout() {
    fetch('api.php?action=logout').then(() => {
        window.location.href = 'index.php';
    });
}

const formFornecedor = document.getElementById('formFornecedor');
if(formFornecedor) {
    formFornecedor.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'cadastrar_fornecedor');

        fetch('api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if(data.status === 'success') {
                this.reset();
                carregarFornecedores();
            }
        });
    });
}

function carregarFornecedores() {
    fetch('api.php?action=listar_fornecedores')
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            const tbody = document.getElementById('lista-fornecedores');
            if(tbody) {
                tbody.innerHTML = '';
                data.data.forEach(f => {
                    tbody.innerHTML += `
                        <tr class="border-b">
                            <td class="p-2">${f.id}</td>
                            <td class="p-2">${f.nome}</td>
                            <td class="p-2">${f.cnpj}</td>
                            <td class="p-2">${f.email}</td>
                        </tr>
                    `;
                });
            }
        }
    });
}

function carregarFornecedoresSelect() {
    fetch('api.php?action=listar_fornecedores')
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            const select = document.getElementById('select-fornecedor');
            if(select) {
                select.innerHTML = '<option value="">Selecione um Fornecedor</option>';
                data.data.forEach(f => {
                    select.innerHTML += `<option value="${f.id}">${f.nome}</option>`;
                });
            }
        }
    });
}

const formProduto = document.getElementById('formProduto');
if(formProduto) {
    formProduto.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'cadastrar_produto');

        fetch('api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if(data.status === 'success') {
                this.reset();
                carregarProdutos();
            }
        });
    });
}

function carregarProdutos() {
    fetch('api.php?action=listar_produtos')
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            const tbody = document.getElementById('lista-produtos');
            if(tbody) {
                tbody.innerHTML = '';
                data.data.forEach(p => {
                    tbody.innerHTML += `
                        <tr class="border-b">
                            <td class="p-2">${p.id}</td>
                            <td class="p-2">${p.nome}</td>
                            <td class="p-2">R$ ${p.preco}</td>
                            <td class="p-2">${p.fornecedor_nome}</td>
                        </tr>
                    `;
                });
            }
        }
    });
}

function carregarLoja() {
    fetch('api.php?action=listar_produtos')
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            const div = document.getElementById('lista-loja');
            if(div) {
                div.innerHTML = '';
                data.data.forEach(p => {
                    div.innerHTML += `
                        <div class="border p-4 rounded bg-gray-50 flex items-center gap-4">
                            <input type="checkbox" name="produtos[]" value="${p.id}" class="w-5 h-5">
                            <div>
                                <h4 class="font-bold text-lg">${p.nome}</h4>
                                <p class="text-sm text-gray-600">${p.descricao}</p>
                                <p class="text-blue-600 font-bold mt-1">R$ ${p.preco}</p>
                            </div>
                        </div>
                    `;
                });
            }
        }
    });
}

const formCesta = document.getElementById('formCesta');
if(formCesta) {
    formCesta.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'adicionar_cesta');

        fetch('api.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if(data.status === 'success') {
                showSection('cesta');
            }
        });
    });
}

function carregarCesta() {
    fetch('api.php?action=ver_cesta')
    .then(res => res.json())
    .then(data => {
        if(data.status === 'success') {
            const div = document.getElementById('conteudo-cesta');
            if(div) {
                const info = data.data;
                if(info.quantidade === 0) {
                    div.innerHTML = '<p class="text-gray-600">Sua cesta está vazia.</p>';
                    return;
                }

                let html = `
                    <div class="mb-4 text-lg">
                        <p><strong>Total de Produtos:</strong> ${info.quantidade}</p>
                        <p class="text-2xl font-bold text-green-600"><strong>Valor Total:</strong> R$ ${parseFloat(info.total).toFixed(2)}</p>
                    </div>
                    <table class="w-full text-left border-collapse border">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="p-2 border">Produto</th>
                                <th class="p-2 border">Preço</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                info.produtos.forEach(p => {
                    html += `
                        <tr class="border-b">
                            <td class="p-2">${p.nome}</td>
                            <td class="p-2">R$ ${p.preco}</td>
                        </tr>
                    `;
                });
                html += `</tbody></table>`;
                div.innerHTML = html;
            }
        }
    });
}
