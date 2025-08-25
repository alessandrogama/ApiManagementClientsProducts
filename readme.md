# API para Gerenciamento de Clientes e Produtos Favoritos

API RESTful para gerenciar clientes e seus produtos favoritos.  
Utilizar o **Laravel 12** como base, com autenticação via **Sanctum**, integração com a **Fake Store API** para produtos, persistência local, **queues** para sincronização diária.

Utilizar o **Docker** com **PostgreSQL**, o que facilita a configuração em diferentes ambientes. Escolhi essa abordagem para tornar o desenvolvimento mais ágil e escalável, evitando chamadas externas frequentes e garantindo uma boa segurança básica.

A ideia veio de um desafio para criar uma API pública com autenticação, validações e integração externa, pensando em alta demanda. Adicionei melhorias como rate limiting e expiração de tokens para torná-la mais robusta.

---

## ⚡ Funcionalidades Principais
- Autenticação de usuários com tokens (registro e login).
- CRUD completo para clientes (criar, ler, atualizar e deletar).
- Associação de produtos favoritos a clientes, com verificação de duplicatas.
- Sincronização automática de produtos da Fake Store API à meia-noite, usando queues.
- Listagem de favoritos com detalhes dos produtos salvos localmente.
- Validações de dados e segurança básica (auth).

---

## 🖥️ Requisitos
- Docker e Docker Compose instalados.

---

## ⚙️ Instalação e Configuração

1. **Clone o projeto**:
   ```bash
    git clone https://github.com/alessandrogama/ApiManagementClientsProducts.git 
   ```
---

2. **Configure o Docker e rode o build**:
    ```bash
        docker-compose up -d --build
    ```
---
3. **Instale as dependências do Composer**:
    ```bash
        docker exec -it laravel-app composer install --dev
    ```
  ---
4. **Gere a chave da aplicação**:    
    ```bash
        docker exec -it laravel-app php artisan key:generate
    ```
  ---
5. **Publique o Sanctum para autenticação**:   
    ```bash
    docker exec -it laravel-app php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    ```
  ---
6. **Migration**:    
    ```bash
        docker exec -it laravel-app php artisan migrate
    ```
      ---
7. **Worker da queue**:  
    ```bash
       docker exec -it laravel-app php artisan queue:work
    ```
    ---
8. **Gerar a Documentação**:     
    ```bash
       docker exec -it laravel-app php artisan l5-swagger:generate
     ```
    ---
8. **Acessar e Testar a Documentação**:           
     ```bash
       http://localhost:8000/api/documentation
     ```