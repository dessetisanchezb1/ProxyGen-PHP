# 🔄 Sistema de Proxy Testado em PHP

Sistema inteligente que testa proxies via cURL no Google e usa automaticamente os que estão funcionando.

## 📋 Como Funciona

### 1. **proxygen.php** - Gerador e Testador de Proxies
- Mantém uma lista de proxies disponíveis
- Testa cada proxy fazendo requisição ao Google
- Salva o proxy funcionando em arquivo (`working_proxy.txt`)
- Valida se o proxy ainda funciona antes de usar

### 2. **index.php** - Aplicação Principal
- Usa `require_once` para importar o `proxygen.php`
- Obtém automaticamente um proxy testado e funcionando
- Faz requisições usando `curl_init()` com o proxy validado

## 🚀 Instalação

```bash
# Clone o repositório
git clone https://github.com/dessetisanchezb/proxygen.git

# Entre na pasta
cd proxygen

# Execute o teste de proxies
php proxygen.php

# Execute a aplicação
php index.php
```

## 💻 Uso Básico

```php
<?php
require_once 'proxygen.php';

// Cria instância do gerador
$generator = new ProxyGenerator();

// Obtém proxy funcionando
$proxy = $generator->getWorkingProxy();

// Usa o proxy em suas requisições
$ch = curl_init();
curl_setopt($ch, CURLOPT_PROXY, $proxy);
// ... outras opções do cURL
```

## 📁 Estrutura do Projeto

```
php-proxy-system/
├── proxygen.php          # Sistema de geração/teste de proxies
├── index.php             # Aplicação principal de exemplo
├── working_proxy.txt     # Proxy funcionando (gerado automaticamente)
└── README.md            # Documentação
```

## 🔧 Fluxo de Funcionamento

1. **Teste Inicial**: `proxygen.php` testa cada proxy da lista
2. **Validação**: Faz requisição ao Google via cURL
3. **Salvamento**: Grava proxy funcionando em arquivo
4. **Uso**: `index.php` importa e usa o proxy testado
5. **Re-validação**: Sempre verifica se proxy ainda funciona

## 📊 Exemplo de Saída

```
🔍 Iniciando busca por proxy funcionando...

Testando proxy: 8.8.8.8:8080 ... ❌ Falhou
Testando proxy: 185.199.228.220:7300 ... ✅ FUNCIONANDO!

💾 Proxy salvo em: working_proxy.txt

🌐 Fazendo requisição para: https://www.google.com
🔄 Usando proxy: 185.199.228.220:7300

✅ Requisição bem-sucedida!
```

## ⚙️ Configuração

Para adicionar seus próprios proxies, edite o array em `proxygen.php`:

```php
private $proxyList = [
    'seu.proxy.com:porta',
    'outro.proxy.com:porta',
    // adicione mais...
];
```

## 🛡️ Recursos

- ✅ Teste automático de proxies
- ✅ Cache de proxy funcionando
- ✅ Re-validação automática
- ✅ Timeout configurável
- ✅ Suporte a HTTPS
- ✅ Fácil integração

## 📝 Requisitos

- PHP 7.0 ou superior
- Extensão cURL habilitada
- Acesso à internet

## 🤝 Contribuindo

1. Fork o projeto
2. Crie uma branch (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 📄 Licença

MIT License - sinta-se livre para usar em seus projetos!

## ⚠️ Aviso Legal

Use proxies de forma responsável e legal. Respeite os termos de serviço dos sites acessados.
