<?php
/**
 * 用 jsEncrypt 前端加密 Ajax 传输给 Php 演示
 * @link https://github.com/Zneiat/jsEncryptAndPhpDemo
 * @author Zneiat <zneiat@163.com>
 */

$op = $_GET['op'];
if($op=='buildKey'){
    /*
    // Php 生成公钥，自己折腾
    echo exec("openssl genrsa 1024 > private.key", $private);
    echo "</br>";
    var_dump($private);
    
    echo exec("openssl rsa -in private.key -pubout > public.key", $public);
    echo "</br>";
    var_dump($public);
    */
}else if($op=='getKey'){
    // 获取公钥
    echo file_get_contents('public.key');
}else if($op=='send'){
    // 接收前端数据
    $data = $_POST['data'];
    $str = decrypt_data($data);
    file_put_contents('sendData.txt', PHP_EOL.PHP_EOL.'======== '.time().' ========'.PHP_EOL.$str, FILE_APPEND);
    echo file_get_contents('sendData.txt');
}

/**
 * 加解密操作
 */
class mycrypt {
    
    public $pubkey;
    public $privkey;
    
    function __construct() {
        $this->pubkey = file_get_contents('./public.key');
        $this->privkey = file_get_contents('./private.key');
    }
    
    public function encrypt($data) {
        if (openssl_public_encrypt($data, $encrypted, $this->pubkey)) {
            $data = base64_encode($encrypted);
        }else {
            throw new Exception('Unable to encrypt data. Perhaps it is bigger than the key size?');
        }
        
        return $data;
    }
    
    public function decrypt($data) {
        if (openssl_private_decrypt(base64_decode($data), $decrypted, $this->privkey)) {
            $data = $decrypted;
        }else {
            $data = '';
        }
        
        return $data;
    }
    
}

// 加密
function encrypt_data($data){
    $rsa = new mycrypt();
    $crypt_res = "";
    for($i=0;$i<((strlen($data) - strlen($data)%117)/117+1); $i++){
        $crypt_res = $crypt_res.($rsa -> encrypt(mb_strcut($data, $i*117, 117, 'utf-8')));
    }
    return $crypt_res;
}

// 解密
function decrypt_data($data){
    $rsa = new mycrypt();
    $decrypt_res = "";
    $datas = explode('=',$data);
    foreach ($datas as $value){
        $decrypt_res = $decrypt_res.$rsa -> decrypt($value);
    }
    return $decrypt_res;
}