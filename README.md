# CarrinhoCompras
Projeto2026 - Carrinho Compras - Projeto 4

4. Carrinho de Compras Básico
   O que faz: Uma galeria de produtos. O usuário pode adicionar produtos a um carrinho, ver o carrinho e "finalizar a compra" (um formulário de pedido simples).
   Habilidades que demonstra: Gerenciamento de estado e sessões ($_SESSION), lógica de negócios e estrutura de dados (arrays de produtos).


   
```markdown
# 🛒 Loja Tech - Carrinho de Compras em Laravel

Este projeto é um sistema de e-commerce simplificado desenvolvido com **PHP** e **Laravel**. O objetivo principal foi estudar o ciclo de vida de uma venda, desde a seleção de produtos na vitrine, gerenciamento de estado (sessão) no carrinho, até a persistência do pedido no banco de dados com autenticação de usuários.

---

## 🚀 Funcionalidades

- **Vitrine de Produtos:** Listagem dinâmica de produtos vindos do banco de dados.
- **Carrinho de Compras:**
  - Adicionar itens.
  - Controle de quantidade (incremento automático).
  - Remoção de itens.
  - Cálculo de subtotal e total em tempo real.
- **Autenticação (Laravel Breeze):**
  - Cadastro e Login de usuários.
  - Proteção de rotas (apenas usuários logados podem finalizar compra).
- **Checkout e Histórico:**
  - Conversão do carrinho (sessão) em Pedido (banco de dados).
  - Relacionamento de tabelas (`users` -> `orders` -> `order_items` -> `products`).
  - Página "Meus Pedidos" para consulta de histórico.

---

## 🛠️ Tecnologias Utilizadas

- **Back-end:** PHP 8.2+, Laravel 11.
- **Front-end:** Blade Templates, Bootstrap 5 (para interface da loja) e Tailwind CSS (para autenticação).
- **Banco de Dados:** SQLite (padrão) / MySQL.
- **Versionamento:** Git & GitHub.

---

## 📂 Estrutura do Projeto

Os principais arquivos desenvolvidos neste projeto:

- `routes/web.php`: Definição de rotas públicas (loja) e protegidas (dashboard/pedidos).
- `app/Http/Controllers/CartController.php`: Controlador responsável por toda a lógica de compra (adicionar, remover, checkout).
- `app/Models/Order.php` & `OrderItem.php`: Modelos que gerenciam o relacionamento 1:N dos pedidos.
- `database/migrations/`: Estrutura das tabelas (`products`, `orders`, `order_items`).
- `resources/views/`:
  - `shop.blade.php`: Página inicial.
  - `cart.blade.php`: Visualização do carrinho.
  - `my_orders.blade.php`: Histórico de compras do usuário.

---

## 🧠 Desafios e Aprendizados

Durante o desenvolvimento (duração aprox.: 3 horas), enfrentamos e superamos os seguintes desafios técnicos:

### 1. Conflito de Scripts no Composer
- **Problema:** Ao instalar dependências, ocorria um erro mencionando `artisan boost:update`.
- **Solução:** Identificamos que era um script residual no `composer.json` que não pertencia à instalação padrão. Removemos a linha problemática e rodamos `composer dump-autoload`.

### 2. Controle de Fluxo (Redirects)
- **Problema:** O Laravel Breeze redirecionava nativamente para `/dashboard` após o login, o que quebrava a experiência de compra do usuário.
- **Solução:** Editamos os controladores `AuthenticatedSessionController` e `RegisteredUserController` para redirecionar o usuário de volta para a loja (`/`) ou carrinho (`/carrinho`) após autenticar.

### 3. Persistência de Pedidos "Órfãos"
- **Problema:** Inicialmente, era possível finalizar uma compra sem estar logado, gerando um erro ou um pedido sem dono (`user_id = null`).
- **Solução:** Implementamos uma verificação `Auth::check()` no método `checkout`. Se o usuário não estiver logado, ele é redirecionado para o login e, graças ao método `intended()`, retorna automaticamente ao checkout após entrar.

### 4. Git Non-Fast-Forward
- **Problema:** Ao enviar o código para o GitHub, houve conflito com o arquivo README criado automaticamente lá.
- **Solução:** Aprendemos a lidar com conflitos de histórico, optando por forçar o push inicial (`git push -f`) para garantir que o código local (mais atualizado) prevalecesse.

---

## ⚙️ Como Rodar o Projeto

1. **Clone o repositório:**
   ```bash
   git clone [https://github.com/gmmaraccini/CarrinhoCompras.git](https://github.com/gmmaraccini/CarrinhoCompras.git)
   cd CarrinhoCompras

```

2. **Instale as dependências:**
```bash
composer install
npm install && npm run build

```


3. **Configure o ambiente:**
* Copie o arquivo `.env.example` para `.env`.
* Gere a chave da aplicação:
```bash
php artisan key:generate

```




4. **Banco de Dados:**
```bash
php artisan migrate:fresh --seed

```


*(Isso criará as tabelas e populará a loja com produtos de teste)*
5. **Inicie o servidor:**
```bash
php artisan serve

```


Acesse: `http://localhost:8000`

---

Desenvolvido por **Gabriel Maraccini** como projeto de estudo de arquitetura MVC e Laravel.

```

```

Arquivo do youtube mostrando

https://youtu.be/zyw5cSKzPaE
