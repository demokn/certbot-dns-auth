## Certbot DNS Auth

### 使用

1. 克隆仓库到执行 `Certbot` 的机器上
```bash
git clone https://github.com/demokn/certbot-dns-auth
cd certbot-dns-auth
```

2. 使用 `composer` 安装依赖
```bash
composer install --no-dev
```

3. 复制并修改脚本文件
```bash
cp scripts/alidns/authenticator.sh-example scripts/alidns/authenticator.sh
cp scripts/alidns/cleanup.sh-example scripts/alidns/cleanup.sh
# 编辑文件, 填写自己阿里云的 AccessKeyID 和 AccessKeySecret
```

4. 使用 `certbot` 生成或更新证书
```bash
# 生成证书
certbot certonly --manual --preferred-challenges=dns --manual-auth-hook /PATH/PROJECT_ROOT/scripts/alidns/authenticator.sh --manual-cleanup-hook /PATH/PROJECT_ROOT/scripts/alidns/cleanup.sh -d *.example.com -m YOUR_EMAIL_ADDRESS --dry-run
# 更新证书
# 更新证书时会自动读取 `/etc/letsencrypt/renewal/example.com.conf` 配置文件, 故生成证书时的配置参数这里就不需要再次手动设定了
certbot renew --cert-name example.com --dry-run
```

5. 配置 `nginx`
```text
ssl on;
ssl_certificate /etc/letsencrypt/live/example.com/fullchain.pem;
ssl_certificate_key /etc/letsencrypt/live/example.com/privkey.pem;
ssl_session_timeout 5m;
ssl_ciphers ECDHE-RSA-AES128-GCM-SHA256:ECDHE:ECDH:AES:HIGH:!NULL:!aNULL:!MD5:!ADH:!RC4;
ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
ssl_prefer_server_ciphers on;
```

### Troubleshooting

#### 1. CentOS 7 安装 certbot 运行时报错 `ImportError: No module named 'requests.packages.urllib3'`
参见: [certbot/certbot #5104](https://github.com/certbot/certbot/issues/5104)
```bash
pip uninstall requests
pip uninstall urllib3
yum remove python-urllib3
yum remove python-requests
yum install python-urllib3
yum install python-requests
yum install certbot
```
