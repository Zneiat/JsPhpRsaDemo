# jsEncryptAndPhpDemo

用 jsEncrypt 前端加密 Ajax 传输给 Php 演示

Demo Author: https://github.com/Zneiat

## 演示使用方法

>生成 私钥&公钥
private.key 私钥保存文件
public.key 公钥保存文件

>执行命令生成密钥：<pre><code>openssl genrsa 1024 > private.key
openssl rsa -in private.key -pubout > public.key</code></pre>

>特别注意：保证加密效果，请勿将 private.key 暴露在外，拒绝用户访问，例设置 .htaccess：<pre><code># 拒绝用户访问 private.key
RewriteEngine on
RewriteRule ^private\.key$ - [R=404,L]</code></pre>

>浏览器控制台执行 <pre><code>send("Message")</code></pre>

> 查看效果 sendData.txt
 <div class="footer">
 
> 其它的自己看代码去折腾...