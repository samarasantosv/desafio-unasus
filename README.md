# Sistema de Verificação - Cadastro e Upload de arquivo Excel

Este sistema foi desenvolvido em PHP para automação do processo de importação de dados de arquivos Excel (.xlsx) diretamente para um banco de dados MariaDB, incluindo validações de dados e interface de gerenciamento.

## Funcionalidades

- **Autenticação:** Sistema de login e criação de conta com senhas criptografadas (`password_hash`).
- **Upload Dinâmico:** Interface para upload de arquivos `.xlsx` (Excel).
- **Processamento de Dados:** Leitura binária via biblioteca `PhpSpreadsheet`.
- **Validações Automáticas:**
    - Verificação de campos vazios (Nome).
    - Validação de CPF (formato e comprimento).
    - Validação de E-mail (formato e domínio).
    - Validação de Telefone (Regex).
- **Relatório Visual:** Tabela de resultados colorida (Verde para sucesso, Vermelho para erro) detalhando falhas linha por linha.
- **Dockerizado:** Ambiente completo e isolado para uso.

## Tecnologias Utilizadas

- **Linguagem:** PHP 8.3 (com extensões GD e ZIP)
- **Banco de Dados:** MariaDB 11
- **Gerenciador de Dependências:** Composer
- **Biblioteca:** PhpSpreadsheet
- **Containerização:** Docker & Docker Compose
- **Front-end:** HTML5 e CSS3

## Estrutura do Projeto

```text
.
├── desafio-unasus/        # Pasta principal do sistema
│   ├── arquivos/          # Pasta para armazenamento do arquivo Excel
│   ├── auth.php           # Middleware de proteção de rotas
│   ├── conexao.php        # Configuração PDO com o banco
│   ├── docker-compose.yml # Configuração do ambiente Docker
│   ├── index.php          # Tela de Login
│   ├── logout.php         # Encerramento de sessão
│   ├── nova_conta.php     # Cadastro de usuários
│   ├── processa.php       # Lógica de leitura e importação
│   ├── style.css          # Estilização  
│   └── upload.php         # Interface de upload
├── vendor/                # Dependências do Composer (gerado automaticamente)
└── README.md
```

## Como Rodar o Projeto

### Pré-requisitos
- [Docker](https://docker.com) instalado.

### Passo a Passo

1. **Clonar o repositório:**
   ```bash
   git clone https://github.com
   cd seu-repositorio
   ```

2. **Subir os containers:**
   ```bash
   docker-compose up -d --build
   ```

3. **Instalar as dependências do PHP:**
   ```bash
   docker exec -it php_app composer require phpoffice/phpspreadsheet
   ```

4. **Configurar o Banco de Dados:**
   - Acesse o phpMyAdmin em `http://localhost:8081` (Usuário: `root` | Senha: `root`).
   - Crie a tabela `clientes` e `usuarios` conforme as definições de esquema do projeto.

5. **Acessar a aplicação:**
   - Abra o navegador em: `http://localhost:8080`

## Segurança

O sistema utiliza:
- **Prepared Statements:** Proteção total contra SQL Injection.
- **Session Management:** Páginas internas protegidas que exigem login.
- **Criptografia:** Senhas nunca são salvas em texto puro.

---
Desenvolvido por **Samara Santos Viegas** como entrega do Desafio UNASUS/UFMA.
