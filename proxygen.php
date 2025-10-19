<?php
/**
 * proxygen.php
 * Sistema de geração e teste de proxies
 * Testa proxies via curl no Google e salva os funcionais
 */

class ProxyGenerator {
    private $workingProxy = null;
    private $proxyFile = 'working_proxy.txt';
    
    // Lista de proxies para testar (você pode adicionar mais)
    private $proxyList = [
        '8.8.8.8:8080',
        '1.1.1.1:3128',
        '185.199.228.220:7300',
        '185.199.229.156:7300',
        '185.199.231.45:8382',
    ];
    
    /**
     * Testa um proxy específico
     */
    private function testProxy($proxy) {
        $ch = curl_init();
        
        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://www.google.com',
            CURLOPT_PROXY => $proxy,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        
        curl_close($ch);
        
        // Verifica se a resposta foi bem-sucedida
        if ($response && $httpCode == 200 && strpos($response, 'google') !== false) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Busca um proxy funcionando
     */
    public function findWorkingProxy() {
        echo "🔍 Iniciando busca por proxy funcionando...\n\n";
        
        foreach ($this->proxyList as $proxy) {
            echo "Testando proxy: $proxy ... ";
            
            if ($this->testProxy($proxy)) {
                echo "✅ FUNCIONANDO!\n";
                $this->workingProxy = $proxy;
                $this->saveProxy($proxy);
                return $proxy;
            } else {
                echo "❌ Falhou\n";
            }
        }
        
        echo "\n⚠️ Nenhum proxy funcionando encontrado.\n";
        return null;
    }
    
    /**
     * Salva o proxy funcionando em arquivo
     */
    private function saveProxy($proxy) {
        file_put_contents($this->proxyFile, $proxy);
        echo "\n💾 Proxy salvo em: {$this->proxyFile}\n\n";
    }
    
    /**
     * Carrega o proxy salvo
     */
    public function loadSavedProxy() {
        if (file_exists($this->proxyFile)) {
            $proxy = trim(file_get_contents($this->proxyFile));
            
            // Testa novamente para garantir que ainda funciona
            if ($this->testProxy($proxy)) {
                $this->workingProxy = $proxy;
                return $proxy;
            } else {
                // Se não funciona mais, busca um novo
                unlink($this->proxyFile);
                return $this->findWorkingProxy();
            }
        }
        
        return $this->findWorkingProxy();
    }
    
    /**
     * Retorna o proxy funcionando atual
     */
    public function getWorkingProxy() {
        if ($this->workingProxy) {
            return $this->workingProxy;
        }
        
        return $this->loadSavedProxy();
    }
}

// Se o arquivo for executado diretamente
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    $generator = new ProxyGenerator();
    $proxy = $generator->findWorkingProxy();
    
    if ($proxy) {
        echo "✨ Proxy pronto para uso: $proxy\n";
    }
}
?>
