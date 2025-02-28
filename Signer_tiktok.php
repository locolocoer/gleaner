<?php

//namespace Volc\Service\ImageX;

class Signer
{
    public $scheme = "https:";
    private $config;
    /**
     * 鉴权方式 A
     */
    public function authA(string $domain, string $path, string $secretKey, string $nonce, string $uid, string $parameterName)
    {
        $ts = time();
        $signText = sprintf("%s-%d-%s-%s-%s", $path, $ts, $nonce, $uid, $secretKey);
        $sign = strtolower(md5($signText));
        $signValue = sprintf("%d-%s-%s-%s", $ts, $nonce, $uid, $sign);
        return sprintf("%s//%s%s?%s=%s", $this->scheme, $domain, $path, $parameterName, $signValue);
    }

    /**
     * 鉴权方式 B
     */
    public function authB(string $domain, string $path, string $secretKey)
    {
        $ts = time();
        $tStr = date("YmdHis", $ts);
        $signText = sprintf("%s%s%s", $secretKey, $tStr, $path);
        $sign = strtolower(md5($signText));
        $signPath = sprintf("/%s/%s", $tStr, $sign);
        return sprintf("%s//%s%s%s", $this->scheme, $domain, $signPath, $path);
    }

    /**
     * 鉴权方式 C
     */
    public function authC(string $domain, string $path, string $secretKey)
    {
        $ts = time();
        $signText = sprintf("%s%s%x", $secretKey, $path, $ts);
        $sign = strtolower(md5($signText));
        $signPath = sprintf("/%s/%x", $sign, $ts);
        return sprintf("%s//%s%s%s", $this->scheme, $domain, $signPath, $path);
    }

    /**
     * 鉴权方式 D
     */
    public function authD(string $domain, string $path, string $secretKey, string $signParameterName, bool $timestampFormatInHex, string $timestampParameterName)
    {
        $ts = time();
        if ($timestampFormatInHex) {
            $tsStr = sprintf("%08x", $ts);
        } else {
            $tsStr = sprintf("%d", $ts);
        }
        $signText = sprintf("%s%s%s", $secretKey, $path, $tsStr);
        $sign = strtolower(md5($signText));
        return sprintf("%s//%s%s?%s=%s&%s=%s", $this->scheme, $domain, $path, $signParameterName, $sign, $timestampParameterName, $tsStr);
    }

    private function run($path,$type)
    {
        $domain = $this->config["tiktok"]["domain"];
        $key = $this->config["tiktok"]["key"];
        //$path = "/dm/dm282.jpg~tplv-ocfscsxaet-image.image";
        $randint = rand(0,10000);
        if($type=="A"){
            return $this->authA($domain, $path, $key, $randint, "imagex", "sign");
        }elseif($type=="B"){
            return $this->authB($domain, $path, $key);
        }elseif($type=="C"){
            return $this->authC($domain, $path, $key);
        }elseif($type=="D"){
            return $this->authD($domain, $path, $key, "sign", false, "time");
        } 
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
    public static function main($path,$type="A") {
        $signer = new static;
        $signer->load_config();
        $signedPath=$signer->run($path,$type);
        return $signedPath;
    }

}
