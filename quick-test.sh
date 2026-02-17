#!/bin/bash

# TESTE RÁPIDO - API T4Tech
# Execute este script para testar rapidamente a API

echo "=========================================="
echo "TESTE RÁPIDO - API T4Tech"
echo "=========================================="
echo ""

# Fazer login
echo "1. Fazendo login..."
LOGIN=$(curl -s -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@t4tech.com","password":"admin123"}')

# Extrair token
TOKEN=$(echo $LOGIN | grep -o '"token":"[^"]*' | sed 's/"token":"//')

if [ -z "$TOKEN" ]; then
    echo "❌ ERRO: Não foi possível fazer login!"
    echo "Resposta: $LOGIN"
    echo ""
    echo "Certifique-se de executar:"
    echo "  php artisan migrate"
    echo "  php artisan db:seed"
    exit 1
fi

echo "✅ Login OK!"
echo "Token: ${TOKEN:0:30}..."
echo ""

# Testar GET /api/teams
echo "2. Testando GET /api/teams..."
TEAMS=$(curl -s -X GET http://localhost:8000/api/teams \
  -H "X-Authorization: $TOKEN")

echo "Resposta: $TEAMS"
echo ""

echo "=========================================="
echo "✅ Teste concluído!"
echo "=========================================="
echo ""
echo "Use este comando para chamar a API:"
echo ""
echo "curl -X GET http://localhost:8000/api/teams \\"
echo "  -H \"X-Authorization: $TOKEN\""
echo ""

