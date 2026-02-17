# Autenticação API - Laravel Sanctum

## Visão Geral

A API utiliza Laravel Sanctum para autenticação via tokens. O sistema suporta dois tipos de usuários:

- **Administrador** (user_type_id: 1): Pode criar, ler, atualizar e deletar registros
- **Usuário** (user_type_id: 2): Pode criar, ler e atualizar registros. Não pode deletar

## Autenticação

### Login

**Endpoint:** `POST /api/auth/login`

**Body:**
```json
{
    "email": "admin@t4tech.com",
    "password": "admin123"
}
```

**Resposta:**
```json
{
    "message": "Login realizado com sucesso",
    "token": "1|xyz123abc456...",
    "user": {
        "id": 1,
        "name": "Administrador",
        "email": "admin@t4tech.com",
        "user_type_id": 1,
        "is_admin": true,
        "can_delete": true
    }
}
```

### Logout

**Endpoint:** `POST /api/auth/logout`

**Headers:**
```
X-Authorization: {token}
```

**Resposta:**
```json
{
    "message": "Logout realizado com sucesso"
}
```

### Obter Usuário Autenticado

**Endpoint:** `GET /api/auth/me`

**Headers:**
```
X-Authorization: Bearer {token}
```

**Resposta:**
```json
{
    "user": {
        "id": 1,
        "name": "Administrador",
        "email": "admin@t4tech.com",
        "user_type_id": 1,
        "is_admin": true,
        "can_delete": true
    }
}
```

## Usuários de Teste

### Administrador
- **Email:** admin@t4tech.com
- **Senha:** admin123
- **Permissões:** CRUD completo (Create, Read, Update, Delete)

### Usuário Regular
- **Email:** user@t4tech.com
- **Senha:** user123
- **Permissões:** Create, Read, Update (sem Delete)

## Usando a API

### Autenticação via Header X-Authorization

Todas as rotas protegidas requerem o token no header `X-Authorization`:

```bash
curl -X GET https://api.example.com/api/teams \
  -H "X-Authorization: 1|xyz123abc456..."
```

### Exemplo de Fluxo Completo

#### 1. Login
```bash
curl -X POST https://api.example.com/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@t4tech.com",
    "password": "admin123"
  }'
```

#### 2. Listar Times
```bash
curl -X GET https://api.example.com/api/teams?limit=15 \
  -H "X-Authorization: {token}"
```

#### 3. Criar Time (requer autenticação)
```bash
curl -X POST https://api.example.com/api/teams \
  -H "X-Authorization: {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "conference": "East",
    "division": "Atlantic",
    "city": "Boston",
    "name": "Celtics",
    "full_name": "Boston Celtics",
    "abbreviation": "BOS"
  }'
```

#### 4. Deletar Time (requer ser Admin)
```bash
curl -X DELETE https://api.example.com/api/teams/1 \
  -H "X-Authorization: {token}"
```

## Tratamento de Erros

### Não Autenticado (401)
```json
{
    "message": "Unauthenticated."
}
```

### Não Autorizado (403)
```json
{
    "message": "This action is unauthorized."
}
```

### Credenciais Inválidas (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": [
            "As credenciais fornecidas estão incorretas."
        ]
    }
}
```

## Instalação e Configuração

### 1. Executar Migrações
```bash
php artisan migrate
```

### 2. Executar Seeders
```bash
php artisan db:seed --class=UserTypeSeeder
php artisan db:seed --class=UserSeeder
```

Ou executar todos os seeders:
```bash
php artisan db:seed
```

### 3. Configurar .env
Certifique-se de que o Laravel Sanctum está configurado:
```env
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
```

## Segurança

- Os tokens são armazenados de forma segura no banco de dados
- As senhas são hasheadas usando bcrypt
- O middleware `x.authorization` converte automaticamente o header `X-Authorization` para o formato padrão do Sanctum
- As policies garantem que apenas usuários com permissões adequadas possam deletar registros
- Logout revoga o token atual do usuário

## Políticas de Autorização

As seguintes políticas estão implementadas:

- **viewAny**: Todos os usuários autenticados
- **view**: Todos os usuários autenticados
- **create**: Todos os usuários autenticados
- **update**: Todos os usuários autenticados
- **delete**: Apenas administradores (user_type_id = 1)
- **restore**: Apenas administradores
- **forceDelete**: Apenas administradores

