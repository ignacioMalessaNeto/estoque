# API de Gerenciamento de estoque

Esta é uma API desenvolvida em Laravel 11 para gerenciar um sistema de estoque.

## Requisitos

- PHP 8.2 ou superior
- Composer
- MySQL

## Instalação

### Backend (Laravel)

1. Clone o repositório:
    ```bash
    git clone https://github.com/ignacioMalessaNeto/estoque.git 
    cd estoque
    ```

2. Instale as dependências do PHP com o Composer:
    ```bash
    composer install
    ```

3. Copie o arquivo `.env.example` para `.env` e configure as variáveis de ambiente, especialmente o banco de dados:
    ```bash
    cp .env.example .env
    ```

4. Execute as migrações para criar as tabelas no banco de dados:
    ```bash
    php artisan migrate
    ```

5. (Opcional) Popule o banco de dados com dados fictícios:
    ```bash
    php artisan db:seed
    ```

6. Inicie o servidor de desenvolvimento:
    ```bash
    php artisan serve
    ```

## Endpoints da API

### Autenticação

- **Login**
    ```http
    POST /api/loginSubmit
    ```
    Body:
    ```json
    {
        "email": "seu-email@example.com",
        "password": "sua-senha"
    }
    ```

- **Registro**
    ```http
    POST /api/signUpSubmit
    ```
    Body:
    ```json
    {   
      "name": "seu-nome",
      "email": "seu-email@example.com",
      "password": "sua-senha",
      "level_access": "0"
    }
    ```

- **Logout**
    ```http
    POST /api/logout/{id_user}
    ```
    Authorization:
    ```
    Bearer token
    ```

### Itens

- **Listar todos os itens**
    ```http
    GET /api/itens
    ```
    Authorization:
    ```
    Bearer token
    ```

- **Mostrar um item específico**
    ```http
    GET /api/itens/{id}
    ```
    Authorization:
    ```
    Bearer token
    ```

- **Criar um novo item**
    ```http
    POST /api/itens
    ```
    Body:
    ```json
    {
    "name" : "nome-item"
    }
    ```
    Authorization:
    ```
    Bearer token
    ```

- **Atualizar um item existente**
    ```http
    POST /api/itens/{id}
    ```
    Body:
    ```json
    {
    "name" : "nome-item"
    }
    ```
    Authorization:
    ```
    Bearer token
    ```

- **Deletar um item**
    ```http
    DELETE /api/itens/{id}
    ```
    Authorization:
    ```
    Bearer token
    ```
### Endereços

- **Listar todos os endereços**
    ```http
    GET /address
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Mostrar um endereço específico**
    ```http
    GET /address/{id}
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Criar um novo endereço**
    ```http
    POST /address
    ```
    Body:
    ```json
    {
    "name" : "endereço"
    }
    ```
    Authorization:
    ```
    Bearer token
    ```

- **Atualizar um endereço existente**
    ```http
    PUT /address/{id}
    ```
    Body:
    ```json
    {
    "name" : "endereço"
    }
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Deletar um endereço**
    ```http
    DELETE /address/{id}
    ```
    Authorization:
    ```
    Bearer token
    ```
### Categorias

- **Listar todas as categoria**
    ```http
    GET /api/category
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Mostrar uma categoria específica**
    ```http
    GET /api/category/{id}
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Criar uma nova categoria**
    ```http
    POST /api/category
    ```
    categoria:
    ```json
    {
    "name" : "sua-categoria"
    }
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Atualizar uma categoria existente**
    ```http
    PUT /api/category/{id}
    ```
    Body:
    ```json
    {
    "name" : "categoria-atualizada"
    }
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Deletar uma categoria**
    ```http
    DELETE /api/category/{id}
    ```
    Authorization:
    ```
    Bearer token
    ```
### Estoque


- **Listar todos os cadastros**
    ```http
    GET /api/stock
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Mostrar um cadastro no estoque específico**
    ```http
    GET /api/stock/{id}
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Criar um novo cadastro**
    ```http
    POST /api/stock
    ```
    Body:
    ```json
    {
    "quantity" : 5,
    "id_item" : 1,
    "id_category" : 1,
    "id_address" : 1
    }
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Atualizar um cadastro existente**
    ```http
    PUT /api/stock/{id}
    ```
    Body:
    ```json
    {    
      "quantity" : 5,
      "id_item" : 1,
      "id_category" : 1,
      "id_address" : 1
    }
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Deletar um cadastro no estoque**
    ```http
    DELETE /api/stock/{id}
    ```
    Authorization:
    ```
    Bearer token
    ```
### Saídas
- **Listar todas as saídas**
    ```http
    GET /api/out
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Mostrar uma saída no estoque específico**
    ```http
    GET /api/out/{id}
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Criar uma nova saída**
    ```http
    POST /out/stock
    ```
    Body:
    ```json
    {
    "quantity" : 1,
    "id_stock" : 1,
    "id_sender" : 1
    }
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Atualizar uma saída existente**
    ```http
    PUT /api/out/{id}
    ```
    Body:
    ```json
    {    
    "quantity" : 1,
    "id_stock" : 1,
    "id_sender" : 1
    }
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Deletar uma saída no estoque**
    ```http
    DELETE /api/out/{id}
    ```
    Authorization:
    ```
    Bearer token
    ```
### Saídas
- **Listar todos os movimentos**
    ```http
    GET /api/moviments
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Mostrar um movimento no estoque específico**
    ```http
    GET /api/moviments/{id}
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Criar um novo movimento**
    ```http
    POST /moviments/stock
    ```
    Body:
    ```json
    {    
    "quantity" : "4",
    "type_moviment" : "Entrada||Saída",
    "id_entrie" : "id da entrada se for cadastro estoque",
    "id_out" : "id da saída se for cadastrada uma saída"
    }
    ```
    Authorization:
    ```
    Bearer token
    ```
- **Atualizar um movimento existente**
    ```http
    PUT /api/moviments/{id}
    ```
    Body:
    ```json
    {    
    "quantity" : "4",
    "type_moviment" : "Entrada||Saída",
    "id_entrie" : "id da entrada se for cadastro estoque",
    "id_out" : "id da saída se for cadastrada uma saída"
    }
    ```
    Authorization:
    ```
    Bearer token
    ```

## Licença

Este projeto está licenciado sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
