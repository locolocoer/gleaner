<?php

//namespace Volc\Service\ImageX;

class Signer_123cloud
{
    public $scheme = "http:";
    private $config;
    /**
     * 鉴权方式 A
     */
    public function authA(string $domain, string $path, string $secretKey, string $nonce, string $uid, string $parameterName)
    {
        $ts = time()+1200;
        $signText = sprintf("/%s/%s-%d-%s-%s-%s", $uid, $path, $ts, $nonce, $uid, $secretKey);
        $sign = strtolower(md5($signText));
        $signValue = sprintf("%d-%s-%s-%s", $ts, $nonce, $uid, $sign);
        return sprintf("%s//%s/%s/%s?%s=%s", $this->scheme, $domain,$uid, $path, $parameterName, $signValue);
    }

    private function run($path)
    {
        // echo $this->config;
        $domain = $this->config["123cloud"]["domain"];
        $key =$this->config["123cloud"]["key"];
        $randint = rand(0,10000);
        $uid = $this->config["123cloud"]["uid"];
        $path = 'github_backup/'.$path;
        return $this->authA($domain, $path, $key, $randint, $uid, "auth_key");
        
    }
    private function load_config(){
        $configPath = __DIR__.'/config.ini';

        // 检查文件是否存在
        if (!file_exists($configPath)) {
            throw new Exception("配置文件不存在");
        }
        // 解析 INI 文件
        $this->config = parse_ini_file($configPath, true);  
    }

    public static function main($path) {
        $signer = new static;
        $signer->load_config();
        return $signer->run($path);
    }

}