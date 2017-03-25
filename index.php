<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Js 前端进行 RSA 加密并将密文传输给 Php 解密的 演示</title>
    <!-- https://github.com/Zneiat/JsPhpRsaDemo -->
    <script src="jquery.js"></script>
    <!-- http://travistidwell.com/jsencrypt/ -->
    <script src="jsencrypt.min.js"></script>
</head>
<body>
    <h2><a href="https://github.com/Zneiat/JsPhpRsaDemo" target="_blank">JsPhpRsaDemo</a></h2>
    <p>Js 加密插件，官方文档：<a href="http://travistidwell.com/jsencrypt/" target="_blank">http://travistidwell.com/jsencrypt/</a></p>
    <p>演示使用方法：<ol>
        <li>生成 私钥&公钥 <pre><?= dirname(__FILE__).DIRECTORY_SEPARATOR ?>private.key 私钥文件<br><?= dirname(__FILE__).DIRECTORY_SEPARATOR ?>public.key 公钥文件<br><br>在 <?= dirname(__FILE__) ?> 路径下执行命令生成密钥：<br>openssl genrsa 1024 > private.key <br>openssl rsa -in private.key -pubout > public.key<br><br>特别注意：保证加密效果，请勿将 private.key 暴露在外，拒绝用户访问，例设置 .htaccess：<pre><hr><?= file_get_contents('.htaccess') ?><hr></pre></pre></li>
        <li>浏览器控制台执行 <pre>send("Message")</pre></li>
        <li>查看效果 <?= dirname(__FILE__).DIRECTORY_SEPARATOR ?>sendData.txt：<pre id="fileContent"><?= file_get_contents('sendData.txt') ?></pre></li>
    </ol></p>
    <p>Demo Author: <a href="https://github.com/Zneiat">https://github.com/Zneiat</a></p>
    
    <script>
        function send(msg){
            $.get("getData.php?op=getKey", function(publicKey){
                console.log("========= 公钥获取成功 ==========\n"+publicKey);
                var str = encryptData(publicKey, msg);
                console.log("========= 成功加密文本 ==========\n"+str);
                $.ajax({
                    url: "getData.php?op=send",
                    method: "POST",
                    context: document.body,
                    data: {'data': str},
                    success: function(result){
                        $('#fileContent').html(result);
                        console.log("========= 发送密文成功 ==========");
                    }
                });
            });
        }

        function encryptData(publickey, data){
            if(data.length> 2691){return;} // length limit
            var crypt = new JSEncrypt();
            crypt.setPublicKey(publickey);
            var crypt_res = "";
            for(var index = 0; index < (data.length - data.length%117)/117+1; index++){
                var subdata = data.substr(index * 117,117);
                crypt_res += crypt.encrypt(subdata);
            }
            return crypt_res;
        }

        function decryptData(privatekey, data){
            var crypt = new JSEncrypt();
            crypt.setPrivateKey(privatekey);
            var datas = data.split('=');
            var decrypt_res = "";
            var de_res = "";
            datas.forEach(function(item){
                if(item!=""){
                    de_res += crypt.decrypt(item);
                }
            });
            return decrypt_res;
        }
    </script>
</body>
</html>