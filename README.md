# 🚀 CRUD PHP Simples com Bootstrap e PDO

Este é um projeto web simples que implementa as quatro operações básicas de um CRUD (Create, Read, Update, Delete): Cadastro, Leitura, Edição e Exclusão de usuários. O projeto utiliza PHP com a extensão PDO para interação segura com o banco de dados MySQL, e o framework Bootstrap para um design responsivo e moderno.

## ✨ Funcionalidades

  * **Cadastro (Create):** Formulário para inserir novos usuários.
      * **Validação:** Campos obrigatórios, validação de formato de e-mail e validação algorítmica de CPF.
      * **Upload de Imagem:** Upload obrigatório de uma imagem de perfil, com salvamento do arquivo no servidor (`uploads/`) e do caminho no banco.
  * **Leitura (Read):** Exibição de todos os usuários cadastrados em uma tabela paginada e estilizada com Bootstrap.
  * **Edição (Update):** Página dedicada para alterar dados do usuário, incluindo a substituição opcional da imagem de perfil.
  * **Exclusão (Delete):** Remoção completa do registro e do arquivo de imagem associado do servidor.
  * **Segurança:** Uso de **Prepared Statements (PDO)** para prevenir ataques de SQL Injection.

## 🛠️ Tecnologias Utilizadas

  * **Backend:** PHP (com PDO)
  * **Banco de Dados:** MySQL/MariaDB
  * **Frontend:** HTML5, CSS3
  * **Framework CSS:** Bootstrap 5.3

## ⚙️ Pré-requisitos

Para rodar este projeto localmente, você precisa ter um ambiente de desenvolvimento web configurado, como:

  * **Servidor Web:** Apache (gerenciado por XAMPP/WAMP/MAMP) ou o servidor embutido do PHP (`php -S`).
  * **Servidor de Banco de Dados:** MySQL ou MariaDB (gerenciado por XAMPP/WAMP/MAMP).
  * **PHP:** Versão 7.x ou superior.

## 🚀 Instalação e Configuração

Siga estes passos para colocar o projeto no ar:

### 1\. Clonar o Repositório

```bash
git clone https://github.com/GbrlSouza/CRUD-PHP cadastro-simples
cd cadastro-simples
```

### 2\. Configurar o Banco de Dados

1.  Acesse o **phpMyAdmin** ou sua ferramenta de gerenciamento MySQL.

2.  Crie um novo banco de dados chamado `cadastro_db`.

3.  Execute o seguinte código SQL para criar a tabela `usuarios`:

    ```sql
    CREATE TABLE `usuarios` (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `nome` VARCHAR(100) NOT NULL,
      `email` VARCHAR(100) NOT NULL,
      `cpf` VARCHAR(14) NOT NULL,
      `caminho_imagem` VARCHAR(255) NOT NULL,
      `data_cadastro` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `email` (`email`),
      UNIQUE KEY `cpf` (`cpf`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
    ```

### 3\. Configurar a Conexão PHP (PDO)

Abra o arquivo **`conexao.php`** e atualize as variáveis de conexão com suas credenciais:

```php
// ARQUIVO: conexao.php
$host = '127.0.0.1'; // Use '127.0.0.1' para evitar erros de socket
$db   = 'cadastro_db'; 
$user = 'seu_usuario_mysql'; // Geralmente 'root'
$pass = 'sua_senha_mysql'; // Geralmente '' (string vazia)
// ...
```

### 4\. Configurar a Pasta de Upload

1.  Crie uma pasta chamada **`uploads`** na raiz do projeto (no mesmo nível de `cadastro.php`).
2.  **Permissões (Linux/Mac):** Se tiver problemas no upload, conceda permissão de escrita para a pasta:
    ```bash
    sudo chmod -R 777 uploads
    ```

### 5\. Rodar o Projeto

Coloque a pasta `cadastro-simples` no diretório do seu servidor web (ex: `htdocs` do XAMPP) ou use o servidor embutido do PHP:

```bash
php -S localhost:8080
```

Acesse o projeto em seu navegador: `http://localhost:8080/cadastro.php` (ou o endereço do seu servidor).

## 📄 Estrutura de Arquivos

| Arquivo | Descrição |
| :--- | :--- |
| `conexao.php` | Configuração da conexão com o banco de dados via PDO. |
| `cadastro.php` | Contém o formulário de cadastro (CREATE) e a exibição da lista de usuários (READ). |
| `processa_cadastro.php` | Lógica de validação (CPF, e-mail), upload de imagem e inserção (CREATE) e exclusão (DELETE). |
| `editar.php` | Formulário pré-preenchido para edição de um usuário específico. |
| `processa_edicao.php` | Lógica de validação e atualização (UPDATE) de dados e substituição da imagem. |
| `uploads/` | Diretório onde as imagens de perfil são salvas. |
