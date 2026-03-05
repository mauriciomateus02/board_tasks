# Board Tasks - Gerenciador de Tarefas

Um aplicativo completo para gerenciamento de tarefas com notificações. O projeto utiliza Vue.js 3 no frontend e Laravel 11 no backend, com PostgreSQL como banco de dados.

## 📋 Índice

- [Introdução](#introdução)
- [Pré-requisitos](#pré-requisitos)
- [Instalação](#instalação)
- [Como Usar](#como-usar)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [APIs Disponíveis](#apis-disponíveis)
- [Autenticação](#autenticação)
- [Dados de Exemplo](#dados-de-exemplo)
- [Tecnologias](#tecnologias)
- [Troubleshooting](#troubleshooting)

## 📖 Introdução

**Board Tasks** é uma aplicação de gerenciamento de tarefas que permite:

- ✅ Criar, editar, excluir e marcar tarefas como completas
- 📅 Definir datas de vencimento para tarefas
- 🔔 Configurar notificações por email sobre tarefas próximas do vencimento
- 🔐 Autenticação segura com Laravel Sanctum
- 📱 Interface responsiva com Bootstrap

## 🔧 Pré-requisitos

### Com Docker (Recomendado)
- Docker (versão 20.10+)
- Docker Compose (versão 1.29+)

### Sem Docker
- Node.js (versão 18+)
- PHP (versão 8.2+)
- PostgreSQL (versão 15+)
- Composer (para gerenciar dependências PHP)
- npm (gerenciador de pacotes Node.js)

## 📦 Instalação

### Opção 1: Com Docker (Recomendado)

1. **Clonar o repositório:**
   ```bash
   git clone <seu-repositorio>
   cd board_tasks
   ```

2. **Criar arquivo `.env` para a API** (copie o exemplo e adicione suas credenciais):
   ```bash
   cp board_tasks_api/.env.example board_tasks_api/.env
   ```
   - O `.env` é usado para configurar o banco de dados, o serviço de email e outras variáveis de ambiente.  
   - Preencha pelo menos as variáveis de mail para que a aplicação possa enviar notificações:
     ```ini
     MAIL_MAILER=smtp
     MAIL_HOST=sua-servidor-smtp
     MAIL_PORT=587
     MAIL_USERNAME=usuario
     MAIL_PASSWORD=senha
     MAIL_ENCRYPTION=tls
     MAIL_FROM_ADDRESS=seu@endereco.com
     MAIL_FROM_NAME="Board Tasks"
     ```
   - Se quiser gerar a chave do aplicativo manualmente, use o comando PHP abaixo (ou rode o `php artisan`):
     ```bash
     # dentro do contêiner ou na sua máquina com PHP instalado
     php -r "echo 'base64:'.base64_encode(random_bytes(32)).PHP_EOL;"
     ```
     Copie o valor exibido e cole em `APP_KEY` no `.env`.

3. **Gerar a chave da aplicação Laravel:**
   ```bash
   docker-compose run api php artisan key:generate
   ```
   (este comando também coloca a chave em `board_tasks_api/.env` automaticamente)

4. **Iniciar os containers:**
   ```bash
   docker-compose up --build
   ```

5. **Acessar a aplicação:**
   - Frontend: http://localhost:4173
   - Backend API: http://localhost:8000
   - MailHog (emails): http://localhost:8025
   - PostgreSQL: localhost:5432 (user: `postgres`, password: `postgres`)

Os containers irão:
- Criar e popular o banco de dados automaticamente
- Executar migrations
- Fazer seed com dados de exemplo

### Opção 2: Sem Docker (Desenvolvimento Local)

#### 1. **Backend (Laravel)**

```bash
# Entrar no diretório da API
cd board_tasks_api

# Instalar dependências
composer install

# Copiar arquivo de ambiente
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate

# Configurar banco de dados no .env:
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=board_tasks
# DB_USERNAME=postgres
# DB_PASSWORD=postgres

# Executar migrations
php artisan migrate

# Executar seeds (dados de exemplo)
php artisan db:seed

# Iniciar servidor
php artisan serve
```

#### 2. **Frontend (Vue.js)**

```bash
# Entrar no diretório do frontend
cd board_task_front

# Instalar dependências
npm install

# Iniciar servidor de desenvolvimento
npm run dev
```

A aplicação estará disponível em http://localhost:5173

## 🚀 Como Usar

### 1. **Login**

Acesse http://localhost:5173/login (ou http://localhost:4173/login com Docker)

Use as credenciais de exemplo:
- **Email:** `user1@email.com` ou `user2@email.com`
- **Senha:** `password`

### 2. **Gerenciar Tarefas**

Após fazer login, você verá a página de tarefas onde pode:

- **Criar tarefa:** Clique em "Criar Tarefa"
  - Preencha: Título, Descrição (opcional), Data de Vencimento (opcional)
  
- **Editar tarefa:** Clique no botão "Editar" na linha da tarefa
  
- **Marcar como completa:** Clique em "Concluir" (apenas para tarefas pendentes)
  
- **Excluir tarefa:** Clique em "Excluir" e confirme

### 3. **Configurar Notificações**

- Clique em "Configurações de Notificação"
- Defina quantos dias antes da data de vencimento deseja receber notificações
- Salve as configurações

Exemplo: Se configurar "3 dias", receberá notificações por email 3 dias antes do vencimento.

### 4. **Paginação**

As tarefas são exibidas com paginação de 10 itens por página. Use os botões de navegação para ir para próxima ou página anterior.

## 📁 Estrutura do Projeto

```
board_tasks/
├── board_task_front/               # Frontend Vue.js
│   ├── src/
│   │   ├── App.vue                # Componente raiz
│   │   ├── main.ts                # Entrada da aplicação
│   │   ├── router/                # Configuração de rotas
│   │   ├── components/            # Componentes reutilizáveis
│   │   │   ├── Toast.vue
│   │   │   ├── TaskModal.vue
│   │   │   ├── ConfirmModal.vue
│   │   │   └── NotificationModal.vue
│   │   ├── core/                  # Serviços e configurações
│   │   │   ├── services/          # Serviços da API
│   │   │   ├── guards/            # Guards de rota
│   │   │   ├── interceptors/      # Interceptadores HTTP
│   │   │   └── dto/               # Data Transfer Objects
│   │   ├── modules/               # Módulos por feature
│   │   │   ├── tasks/
│   │   │   └── users/
│   │   ├── types/                 # Tipos TypeScript
│   │   └── assets/                # Estilos
│   ├── package.json
│   ├── Dockerfile
│   └── vite.config.ts
│
├── board_tasks_api/               # Backend Laravel
│   ├── app/
│   │   ├── Http/Controllers/     # Controladores
│   │   ├── Models/                # Modelos (Eloquent)
│   │   ├── Policies/              # Policies de autorização
│   │   └── Mail/                  # Notificações de email
│   ├── database/
│   │   ├── migrations/            # Migrations do BD
│   │   └── seeders/               # Seeders para dados iniciais
│   ├── routes/
│   │   └── api.php               # Rotas da API
│   ├── config/
│   │   ├── cors.php              # Configuração CORS
│   │   └── app.php               # Configuração geral
│   ├── .env.example
│   ├── Dockerfile
│   ├── composer.json
│   └── artisan                    # CLI do Laravel
│
├── docker-compose.yml             # Orquestração de containers
├── .gitignore
└── README.md
```

## 🔌 APIs Disponíveis

### Autenticação
```
POST /api/login
POST /api/logout
GET /api/me
```

### Tarefas
```
GET /api/tasks?page=1           # Listar tarefas (paginado)
POST /api/tasks                 # Criar tarefa
PUT /api/tasks/{id}            # Atualizar tarefa
DELETE /api/tasks/{id}         # Deletar tarefa
PATCH /api/tasks/{id}/complete # Marcar como completa
```

### Configurações de Notificação
```
GET /api/notification-settings         # Obter configuração
POST /api/notification-settings        # Criar/Atualizar configuração
PUT /api/notification-settings/{id}   # Atualizar (alternativo)
```

## 🔐 Autenticação

O projeto usa **Laravel Sanctum** para autenticação. Todos os requests devem incluir o token Bearer no header:

```
Authorization: Bearer <seu_token>
```

Os interceptadores HTTP do frontend fazem isso automaticamente.

## 📊 Dados de Exemplo

O projeto vem com seed de dados incluindo:

**Usuários:**
- `user1@email.com` / `password`
- `user2@email.com` / `password`

**Tarefas:**
- 20 tarefas distribuídas entre os usuários
- Datas de vencimento entre 1-7 dias no futuro
- Descrições de exemplo

**Configurações de Notificação:**
- Usuário 1: Notificar 3 dias antes
- Usuário 2: Notificar 2 dias antes

Para resetar os dados:
```bash
# Com Docker
docker-compose exec api php artisan migrate:refresh --seed

# Sem Docker
php artisan migrate:refresh --seed
```

## 🛠️ Tecnologias

### Frontend
- **Vue.js 3** - Framework JavaScript reativo
- **TypeScript** - Tipagem estática
- **Vite** - Bundler rápido
- **Vue Router** - Roteamento
- **Bootstrap 5** - Framework CSS
- **Axios** - Cliente HTTP

### Backend
- **Laravel 11** - Framework PHP
- **PHP 8.2** - Linguagem
- **PostgreSQL** - Banco de dados
- **Laravel Sanctum** - Autenticação API
- **Eloquent ORM** - Manipulação de dados

### DevOps
- **Docker** - Containerização
- **Docker Compose** - Orquestração
- **MailHog** - Mock de servidor SMTP

## 🐛 Troubleshooting

### Erro 409 (Conflict) ao compilar
**Solução:** Limpe o cache do node
```bash
rm -rf node_modules package-lock.json
npm install
```

### Erro de conexão com banco de dados
**Verificar:**
- PostgreSQL está rodando
- Variáveis de ambiente corretas no `.env`
- Credenciais de acesso

```bash
# Testar conexão PostgreSQL
psql -h localhost -U postgres -d board_tasks
```

### Erro CORS ao fazer requests
**Solução:** Restart do servidor Laravel
```bash
# Sem Docker
php artisan serve

# Com Docker
docker-compose restart api
```

### Token inválido / Sessão expirada
**Solução:** Faça login novamente. O aplicativo redireciona automaticamente para o login quando o token expira.

### MailHog não está recebendo emails
**Verificar:**
- MailHog está rodando em `http://localhost:8025`
- Variáveis de email no `.env` estão corretas:
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=mailhog
  MAIL_PORT=1025
  ```

### Permissão negada (403) ao acessar recurso
**Causa:** Falta de autorização para aquele recurso
**Solução:** Verifique se está acessando apenas seus próprios recursos ou se perdeu a sessão

## 📝 Variáveis de Ambiente

### Frontend (`.env` do frontend)
```
VITE_API_URL=http://localhost:8000/api
```

### Backend (`.env` da API)
```
APP_KEY=base64:...
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=board_tasks
DB_USERNAME=postgres
DB_PASSWORD=postgres

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
```

## Suporte

Para dúvidas ou problemas, verifique os logs:

```bash
# Backend
docker-compose logs -f api

# Frontend (no terminal de desenvolvimento)
# Veja a saída do terminal onde npm run dev está rodando

# PostgreSQL
docker-compose logs -f postgres
```
