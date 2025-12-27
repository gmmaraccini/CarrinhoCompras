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


PARTE 2

Com certeza! Documentar as variáveis de ambiente (`.env`) é essencial para que outros desenvolvedores (ou você no futuro) saibam como configurar o projeto sem ter que adivinhar.

Aqui está o texto completo, formatado para você copiar e colar no seu `README.md`. Eu incluí a parte técnica da integração e o exemplo do `.env`.

---

### Texto para o README.md

```markdown
## ⚙️ Configuração e Variáveis de Ambiente (.env)

Para que a integração com o Stripe funcione, é necessário configurar as chaves de API no arquivo `.env`.

**Passo a passo:**
1. Crie uma conta no [Stripe Dashboard](https://dashboard.stripe.com/).
2. Ative o "Test Mode" (Modo de Teste).
3. Em "Developers" > "API Keys", copie suas chaves pública e secreta.
4. Adicione as seguintes linhas ao seu arquivo `.env`:

```env
# Configurações do Stripe
# PK = Publishable Key (Pública)
# SK = Secret Key (Secreta)

STRIPE_PK_KEY=pk_test_sua_chave_publica_aqui...
STRIPE_SK_KEY=sk_test_sua_chave_secreta_aqui...

```

> **Nota de Segurança:** O arquivo `.env` nunca é enviado para o GitHub (está no `.gitignore`) para proteger suas credenciais. O exemplo acima serve apenas como referência das variáveis necessárias.

---

## 💻 Detalhes da Implementação Técnica (Parte 2)

Nesta etapa, elevamos o nível do projeto integrando um Gateway de Pagamento real. Abaixo, os detalhes da arquitetura utilizada:

### 1. Biblioteca Oficial (SDK)

Utilizamos o pacote oficial `stripe/stripe-php` via Composer. Isso garante que estamos seguindo as melhores práticas de segurança recomendadas pela documentação da API.

### 2. Fluxo de Checkout (Hosted Session)

Optamos pelo modelo de **Checkout Session**. Ao invés de manipular dados sensíveis de cartão de crédito diretamente no nosso servidor (o que exigiria conformidade PCI-DSS complexa), nós:

1. Criamos um pedido com status `pendente` no banco de dados.
2. Enviamos os itens do carrinho para a API do Stripe.
3. Redirecionamos o usuário para uma página segura hospedada pelo Stripe.
4. Aguardamos o retorno do usuário para confirmar a transação.

### 3. Validação Robusta

Para evitar fraudes (ex: usuário acessar a URL de sucesso sem pagar), implementamos uma verificação dupla no Controller:

```php
// Recuperamos a sessão direto da API do Stripe para confirmar o status real
$session = Session::retrieve($sessionId);

if ($session->payment_status === 'paid') {
    // Só agora liberamos o pedido no banco
}

```

### 4. Correção de Mass Assignment (Bug Fix)

Um desafio técnico encontrado foi a persistência do `user_id`. O Laravel protege o banco de dados contra inserção em massa.

* **O Erro:** Pedidos eram salvos, mas ficavam sem "dono" (user_id = null).
* **A Solução:** Foi necessário atualizar a propriedade `$fillable` no Model `Order.php`:
```php
protected $fillable = ['user_id', 'total_price', 'status'];

```



---

## 🧪 Como Testar

Para simular pagamentos, utilize os dados de teste fornecidos pelo Stripe:

* **Número do Cartão:** `4242 4242 4242 4242`
* **Validade:** Qualquer data futura (ex: 12/30)
* **CVC:** Qualquer número (ex: 123)

```

---

### Dica Profissional para o Portfólio:
Ao colocar isso no GitHub, você mostra que:
1.  Sabe proteger dados (não subiu as chaves reais).
2.  Sabe explicar a arquitetura (Backend -> API -> Database).
3.  Sabe resolver bugs comuns do framework (o caso do `$fillable`).

```
