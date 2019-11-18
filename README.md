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
certbot certonly --manual --preferred-challenges=dns --manual-auth-hook /PATH/PROJECT_ROOT/scripts/alidns/authenticator.sh --manual-cleanup-hook /PATH/PROJECT_ROOT/scripts/alidns/cleanup.sh -d *.example.com --dry-run
# 更新证书
certbot certonly --manual --preferred-challenges=dns --manual-auth-hook /PATH/PROJECT_ROOT/scripts/alidns/authenticator.sh --manual-cleanup-hook /PATH/PROJECT_ROOT/scripts/alidns/cleanup.sh --cert-name example.com --dry-run
```
