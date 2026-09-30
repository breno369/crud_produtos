# Mini Sistema de Gestão de Produtos

Este é um projeto acadêmico para criar um sistema de Gestão de Produtos, utilizando **PHP (PDO/POO)**, **MySQL**, **HTML5**, **CSS3**, **JavaScript (AJAX)** e **Tailwind CSS**. 

O projeto adota Orientação a Objetos e engloba o cadastro e relacionamento entre Usuários, Fornecedores, Produtos e Cesta de Compras. A senha dos usuários é criptografada utilizando hash **SHA-256**. O projeto foi construído no modelo **Single Page Application (SPA)** para as áreas autenticadas, realizando todas as operações de dados dinamicamente através de AJAX.

---

## Integrantes da Equipe
- **Breno Possagnolo** - RA: 09052946 *(Responsável pelo Backend e Banco de Dados)*
- **Luiz Vinicius Pires Simões** - RA: 60009727 *(Responsável pelo Frontend e Integração AJAX)*

---

## Diagrama Entidade Relacionamento (DER)
A modelagem foi estruturada para suportar as regras de negócio de produtos, fornecedores e cestas.
O diagrama pode ser visualizado no arquivo anexado [DER.svg](DER.svg).

### Estrutura das Entidades:
- **`usuarios`**: `id` (PK), `nome`, `email`, `senha` (Hash SHA-256)
- **`fornecedores`**: `id` (PK), `nome`, `cnpj`, `email`
- **`produtos`**: `id` (PK), `nome`, `descricao`, `preco`, `fornecedor_id` (FK)
- **`cestas`**: `id` (PK), `usuario_id` (FK), `data_criacao`
- **`cesta_produtos`**: `cesta_id` (PK, FK), `produto_id` (PK, FK)

---

## Protótipos (Figma)
Os esboços e protótipos das telas desenvolvidos no Figma podem ser acessados através do link abaixo:
- [Visualizar Protótipos no Figma](https://www.figma.com/design/cSGn9gPUAUrLKuCsRB0ddu/Untitled?node-id=0-1&t=D45ijlgqONvD76Lg-1)

---

## Execução no XAMPP

Siga os passos abaixo para rodar o projeto localmente no **XAMPP**:

1. **Copiar os arquivos do projeto**:
   - Mova ou clone a pasta `crud_produtos` para dentro do diretório `htdocs` da sua instalação do XAMPP (exemplo: `C:\xampp\htdocs\crud_produtos` ou `/opt/lampp/htdocs/crud_produtos`).

2. **Iniciar os Serviços**:
   - Abra o **XAMPP Control Panel**.
   - Clique em **Start** nos módulos **Apache** e **MySQL**.

3. **Banco de Dados (Criação Automática)**:
   - **Não é necessário importar scripts SQL manualmente.**
   - A classe [`config/Database.php`](config/Database.php) verifica e cria o banco de dados `gestao_produtos` e todas as tabelas automaticamente na primeira conexão.
   - *Nota:* Se o seu MySQL no XAMPP possuir senha no usuário `root`, altere a propriedade `$password` no arquivo `config/Database.php`.

4. **Acessar a Aplicação**:
   - Abra o navegador e acesse: [http://localhost/crud_produtos](http://localhost/crud_produtos)

---

## Funcionalidades
- **Autenticação**: Cadastro de usuários com hash SHA-256 e login via sessões PHP.
- **CRUD Fornecedores**: Cadastro e listagem em tempo real (via AJAX).
- **CRUD Produtos**: Cadastro atrelado a fornecedores cadastrados e listagem dinâmica.
- **Catálogo Interativo**: Exibição dos produtos em cards com seleção via checkbox para envio à Cesta.
- **Cesta de Compras**: Resumo dinâmico contendo o Total de Itens e Valor Total da cesta do usuário.

