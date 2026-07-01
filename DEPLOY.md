# 📦 Guia de Deploy - INLAUDO ERP v1.1

## 🎯 Resumo das Correções

Este patch corrige o erro crítico no fluxo de autenticação pós-login onde o Dashboard não carregava após o redirecionamento. As seguintes correções foram implementadas:

### Problemas Corrigidos

1. **Sintaxe Quebrada na View do Dashboard** ✅
   - Arquivo: `/app/Views/dashboard/index.php`
   - Problema: Uso de `'\'/layout/erp_header.php\'` em vez de `'/layout/erp_header.php'`
   - Solução: Corrigida a sintaxe das chamadas de `require_once`

2. **Falta de Try-Catch Global no Router** ✅
   - Arquivo: `/app/Core/Router.php`
   - Problema: Exceções não capturadas causavam erro 500 sem contexto
   - Solução: Adicionado try-catch global com logs detalhados

3. **Falta de Logs Estratégicos** ✅
   - Arquivo: `/app/Core/Logger.php`
   - Problema: Logs insuficientes para debug em produção
   - Solução: Adicionados logs com stack traces, detalhes de requisição e contexto

4. **Falta de Validações no Bootstrap** ✅
   - Arquivo: `/app/bootstrap.php`
   - Problema: Não validava carregamento de .env e sessão
   - Solução: Adicionadas validações e logs em cada etapa

5. **Falta de Logs no Controller do Dashboard** ✅
   - Arquivo: `/app/Controllers/DashboardController.php`
   - Problema: Impossível debugar problemas de sessão
   - Solução: Adicionadas validações e logs de sessão

6. **Falta de Tratamento de Erros na View** ✅
   - Arquivo: `/app/Core/View.php`
   - Problema: Erros ao renderizar views não eram capturados
   - Solução: Adicionado try-catch com logs detalhados

---

## 📋 Pré-requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Extensões PHP: PDO, PDO_MySQL, JSON, MBString
- Acesso SSH ou FTP ao HostGator
- Permissões de escrita no diretório `/storage/logs`

---

## 🚀 Instruções de Deploy

### Passo 1: Fazer Backup do Sistema Atual

```bash
# Via SSH no HostGator
cd /home/seu_usuario/public_html
tar -czf inlaudo_backup_$(date +%Y%m%d_%H%M%S).tar.gz .
```

### Passo 2: Extrair o Patch

```bash
# Extrair o arquivo ZIP fornecido
unzip inlaudov1_patch.zip -d /home/seu_usuario/public_html/
```

### Passo 3: Configurar o Arquivo .env

O arquivo `.env` já está configurado, mas você pode ajustar conforme necessário:

```bash
# Editar o arquivo .env
nano /home/seu_usuario/public_html/.env
```

**Variáveis críticas a verificar:**

```env
APP_ENV=prod                          # Modo de produção
APP_DEBUG=false                       # Debug desabilitado
DB_HOST=localhost                     # Host do banco de dados
DB_PORT=3306                          # Porta do MySQL
DB_DATABASE=inlaud99_saas_inlaudo    # Nome do banco
DB_USERNAME=inlaud99_admin            # Usuário do banco
DB_PASSWORD=Admin259087@              # Senha do banco
APP_URL=https://erp.inlaudo.com.br   # URL da aplicação
```

### Passo 4: Ajustar Permissões

```bash
# Dar permissão de escrita ao diretório de logs
chmod -R 755 /home/seu_usuario/public_html/storage/logs

# Dar permissão de leitura ao arquivo .env
chmod 644 /home/seu_usuario/public_html/.env

# Dar permissão de execução aos diretórios
chmod -R 755 /home/seu_usuario/public_html/app
chmod -R 755 /home/seu_usuario/public_html/routes
chmod -R 755 /home/seu_usuario/public_html/config
```

### Passo 5: Verificar a Configuração

1. Abra o navegador e acesse: `https://erp.inlaudo.com.br/check_env.php`
2. Verifique se todas as verificações passaram (marcadas com ✓)
3. Se houver erros, corrija conforme indicado
4. **Remova o arquivo `check_env.php` após a verificação bem-sucedida**

```bash
rm /home/seu_usuario/public_html/check_env.php
```

### Passo 6: Testar o Login

1. Acesse: `https://erp.inlaudo.com.br/login`
2. Faça login com suas credenciais
3. Verifique se o Dashboard carrega sem erros
4. Verifique os logs para confirmar que tudo está funcionando

---

## 📊 Verificação de Logs

Os logs agora são detalhados e podem ser consultados em:

```
/storage/logs/
├── bootstrap.log      # Logs de inicialização
├── router.log         # Logs de roteamento
├── auth.log           # Logs de autenticação
├── view.log           # Logs de renderização de views
├── error.log          # Logs de erros
└── debug.log          # Logs de debug (apenas em dev)
```

### Exemplo de Log de Sucesso

```
[2026-02-01 10:30:45] [IP: 192.168.1.100] [User: 1] [Method: GET] [URI: /dashboard] - DashboardController::index() concluído com sucesso
```

### Exemplo de Log de Erro

```
[2026-02-01 10:31:12] [IP: 192.168.1.100] [User: -] [Method: POST] [URI: /login] - Erro ao renderizar Dashboard | Context: {"exception":"Exception","message":"View 'dashboard/index' não encontrada"}
Stack Trace:
  #0 App\Core\View::render() called at [/home/ubuntu/inlaudov1/app/Controllers/DashboardController.php:45]
  #1 App\Controllers\DashboardController::index() called at [/home/ubuntu/inlaudov1/app/Core/Router.php:90]
```

---

## 🔍 Troubleshooting

### Problema: "Ocorreu um erro inesperado"

**Solução:**
1. Verifique o arquivo `/storage/logs/error.log` para detalhes
2. Confirme que o arquivo `.env` está configurado corretamente
3. Verifique permissões de escrita em `/storage/logs/`
4. Verifique conexão com o banco de dados

### Problema: Dashboard não carrega

**Solução:**
1. Verifique `/storage/logs/router.log` para logs de roteamento
2. Verifique `/storage/logs/view.log` para logs de renderização
3. Confirme que a sessão do usuário está intacta
4. Verifique se o arquivo `/app/Views/dashboard/index.php` existe

### Problema: Erro de conexão com banco de dados

**Solução:**
1. Verifique credenciais no arquivo `.env`
2. Confirme que o MySQL está rodando
3. Verifique se o usuário do banco tem permissões corretas
4. Teste a conexão via SSH: `mysql -h DB_HOST -u DB_USERNAME -p`

### Problema: Permissão negada em `/storage/logs/`

**Solução:**
```bash
chmod -R 755 /home/seu_usuario/public_html/storage/logs
chmod -R 755 /home/seu_usuario/public_html/storage
```

---

## 🔐 Segurança

### Checklist de Segurança Pós-Deploy

- [ ] Arquivo `.env` não é acessível via web (verificar `.htaccess`)
- [ ] Arquivo `check_env.php` foi removido
- [ ] `APP_ENV=prod` está configurado
- [ ] `APP_DEBUG=false` está configurado
- [ ] Logs não contêm informações sensíveis
- [ ] Permissões de arquivo estão corretas (644 para .env, 755 para diretórios)
- [ ] HTTPS está ativado
- [ ] Cookies de sessão têm flags `secure` e `httponly`

---

## 📝 Notas Importantes

1. **Remova o arquivo `check_env.php` após a verificação** - Este arquivo é apenas para diagnóstico
2. **Monitore os logs regularmente** - Verifique `/storage/logs/` para identificar problemas
3. **Faça backup regularmente** - Antes de qualquer atualização
4. **Teste em staging primeiro** - Se possível, teste as alterações em um ambiente de teste
5. **Mantenha o .env seguro** - Nunca compartilhe ou exponha credenciais

---

## 📞 Suporte

Se encontrar problemas após o deploy:

1. Verifique os logs em `/storage/logs/`
2. Execute o script de verificação: `https://erp.inlaudo.com.br/check_env.php`
3. Verifique a documentação de diagnóstico: `DIAGNOSTICO.md`
4. Contate o suporte técnico com os logs relevantes

---

## 📅 Histórico de Versões

### v1.1 (2026-02-01)
- ✅ Corrigida sintaxe quebrada em dashboard/index.php
- ✅ Adicionado try-catch global no Router
- ✅ Melhorado sistema de logs com stack traces
- ✅ Adicionadas validações no bootstrap
- ✅ Adicionados logs no DashboardController
- ✅ Melhorado tratamento de erros na View
- ✅ Criado script de verificação pós-upload

### v1.0 (2026-01-XX)
- Versão inicial do sistema

---

## ✅ Checklist de Deploy

- [ ] Backup do sistema atual realizado
- [ ] Arquivo ZIP extraído
- [ ] Arquivo `.env` configurado
- [ ] Permissões de arquivo ajustadas
- [ ] Script de verificação executado com sucesso
- [ ] Arquivo `check_env.php` removido
- [ ] Login testado com sucesso
- [ ] Dashboard carrega sem erros
- [ ] Logs verificados e sem erros críticos
- [ ] Segurança verificada (HTTPS, .env protegido, etc.)

---

Desenvolvido com ❤️ para INLAUDO ERP
