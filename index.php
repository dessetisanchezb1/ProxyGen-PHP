<?php
/**
 * index.php
 * Aplicação principal que usa o proxy testado
 */

// Importa o sistema de proxy
require_once 'proxygen.php';

class Application {
    private $proxyGen;
    
    public function __construct() {
        $this->proxyGen = new ProxyGenerator();
    }
    
    /**
     * Faz uma requisição usando o proxy funcionando
     */
    public function makeRequest($url) {
        // Obtém o proxy testado e funcionando
        $proxy = $this->proxyGen->getWorkingProxy();
        
        if (!$proxy) {
            throw new Exception("Nenhum proxy disponível!");
        }
        
        echo "🌐 Fazendo requisição para: $url\n";
        echo "🔄 Usando proxy: $proxy\n\n";
        
        // Inicializa o cURL com o proxy
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_PROXY => $proxy,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        if ($error) {
            throw new Exception("Erro na requisição: $error");
        }
        
        return [
            'status' => $httpCode,
            'content' => $response,
            'proxy_used' => $proxy
        ];
    }
    
    /**
     * Exemplo de uso: Buscar conteúdo de um site
     */
    public function fetchWebsite($url) {
        try {
            $result = $this->makeRequest($url);
            
            echo "✅ Requisição bem-sucedida!\n";
            echo "📊 Status HTTP: {$result['status']}\n";
            echo "📏 Tamanho da resposta: " . strlen($result['content']) . " bytes\n";
            echo "🔌 Proxy usado: {$result['proxy_used']}\n\n";
            
            return $result['content'];
            
        } catch (Exception $e) {
            echo "❌ Erro: " . $e->getMessage() . "\n";
            return null;
        }
    }
}

// Exemplo de uso
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    echo "=== Sistema de Requisições com Proxy ===\n\n";
    
    $app = new Application();
    
    // Testa buscar o Google
    $content = $app->fetchWebsite('https://www.google.com');
    
    if ($content) {
        echo "✨ Aplicação funcionando perfeitamente!\n";
    }
}
?>
