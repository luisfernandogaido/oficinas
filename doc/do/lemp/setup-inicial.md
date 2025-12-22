## Criar VPS com as configutações necessárias
Azure, AWS, Google Cloud, Digital Ocean, Vultr, Linode, Hostinger.


## Update e senha de root

```
apt-get update
apt-get upgrade
passwd root
```

## Renomear host
hostnamectl set-hostname go

## ufw

Habilite e reinicie o servidor

```
ufw enable
ufw allow ssh
ufw allow http
ufw allow https
ufw allow 3306
ufw allow 9200
ufw allow 27017
ufw status numbered
shutdown -r now
```

Proteja o MongoDB, se for o caso.

```
ufw allow from 177.34.235.86 to any port 27017
ufw allow from 147.182.185.230 to any port 27017
ufw allow from 167.172.155.222 to any port 27017
ufw allow from 159.203.131.226 to any port 27017
ufw reload
```

Acesse novamente o servidor.

## Horário de Brasília

```
timedatectl
timedatectl set-timezone America/Sao_Paulo
timedatectl
```

## Limites

```
nano /etc/security/limits.conf
```

No final do arquivo, adicione:

```
root             soft    nofile          64000
root             hard    nofile          64000
```

```
nano /etc/systemd/system.conf
```

Procure a linha contendo `DefaultLimitNOFILE` e a descomente com:

```
DefaultLimitNOFILE=64000
```

Reinicie system:

```
systemctl reboot
```

## MySQL

Procure o endereço mais atualizado de MySQL pra Linux no site oficial.

```
apt-get install mysql-server
```

Altere a senha do usuário root

```
mysql
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password by 'k1Y#-*wTp%CbYwQb';
ou, mais modernos:
ALTER USER 'root'@'localhost' IDENTIFIED WITH caching_sha2_password BY 'xW3g3JAhsZjE-';
exit;
mysql_secure_installation
```

Modifique o arquivo de configuração

```
nano /etc/mysql/mysql.conf.d/mysqld.cnf
```

Faça as seguintes configurações nos locais correspondentes se existir.

```
[mysqld]
bind-address=0.0.0.0
innodb_ft_min_token_size=2
ft_min_word_len=2
innodb_ft_enable_stopword=OFF
max_allowed_packet=64M
disable_log_bin
sort_buffer_size=1048576
```

A configuração `innodb_ft_enable_stopword` não funciona no WorkBench Windows e precisa ser ajustada manualmente
em `C:\ProgramData\MySQL\MySQL Server 8.0\my.ini`.

Teste o servidor

```
systemctl restart mysql; systemctl status mysql
```

Criando usuário remoto

```
mysql -uroot -pk1Y#-*wTp%CbYwQb
CREATE USER 'root'@'%' IDENTIFIED BY 'k1Y#-*wTp%CbYwQb';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
ALTER USER 'root'@'localhost' IDENTIFIED WITH caching_sha2_password BY 'k1Y#-*wTp%CbYwQb';
ALTER USER 'root'@'%' IDENTIFIED WITH caching_sha2_password BY 'k1Y#-*wTp%CbYwQb';
FLUSH PRIVILEGES;
```

### Dump/Restore:

Pode ser feito sempre usando o mysqldump local independente do sentido, mas é mais rápido rodar no servidor se a base
for grande.

Dump

```
mysqldump.exe gaido --host=gaido.space --user=root --password="xW3g3JAhsZjE-" --result-file=gaido-2025_09_25.sql --complete-insert --routines
```

Restore:

```
mysql --host=localhost --user=root --password=Semaver13
drop database if exists gaido;
create database gaido;
use gaido;
-- remova definers se houver stored procedure.
source gaido-2025_09_25.sql;
exit;
```

Alternativamente a source, mas é preciso rodar no CMD, porque o PowerShell não aceita '<'

```
mysql.exe --host=localhost --user=root --password=Semaver13 --database=gaido < "C:\Users\lfgai\Desktop\gaido-2025_09_25.sql"

```

## MongoDB

Siga
https://docs.mongodb.com/manual/tutorial/install-mongodb-on-ubuntu/ (oficial)

```
wget -qO - https://www.mongodb.org/static/pgp/server-4.4.asc | sudo apt-key add -
echo "deb [ arch=amd64,arm64 ] https://repo.mongodb.org/apt/ubuntu bionic/mongodb-org/4.2 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-4.2.list
sudo apt-get update
sudo apt-get install -y mongodb-org
systemctl start mongod
systemctl status mongod
systemctl enable mongod
```

Ou siga https://wiki.crowncloud.net/?How_to_Install_MongoDB_on_Ubuntu_21_04 (ubuntu 21)

Crie um usuário:

```
mongosh
use admin
db.createUser(
  {
    user: "gaido",
    pwd: "xW3g3JAhsZjE-",
    roles: [ { role: "root", db: "admin" } ]
  }
)
exit
systemctl restart mongod
systemctl status mongod
```

Criando ou modificando um usuário em uma base específica:

```
use nova_base
db.createUser({
  user: "meu_usuario",
  pwd: "minha_senha_forte", // Use a mesma senha
  roles: [
    { role: "dbOwner", db: "nova_base" } // Ou outros roles
  ]
})
```

Removendo um usuário:

```
use admin
db.dropUser("meu_usuario") // Se o usuário foi criado na base "admin"

use minha_base
db.dropUser("meu_usuario") // Se o usuário foi criado na base "minha_base"

```

Coloque o IP do servidor nas configurações e habilite segurança

```
nano /etc/mongod.conf

bind_ip = 127.0.0.1,your_server_ip
security:
  authorization: "enabled"

systemctl restart mongod
```

dump/restore:

```
cd C:\Users\lfgai\Desktop
mongodump --uri="mongodb://gaido:1000sonhosreais@167.99.55.99:27017/cnc?authSource=admin" --gzip
mongorestore --drop --numInsertionWorkersPerCollection=4 --gzip
```

clonar uma base remota:

```
mongodump --uri="mongodb://gaido:1000sonhosreais@167.99.55.99:27017/cnc?authSource=admin" --gzip
mongorestore --uri="mongodb://gaido:1000sonhosreais@167.99.55.99:27017/?authSource=admin" -d=cnc2 --gzip dump/cnc
```

Reclamar espaço em disco após deleções:

```
db.runCommand({compact:'collectionName'})
```

No banco admin, rotacionar log:

```
db.runCommand({logRotate: 1});
```

## Redis

```
apt install redis-server
```

## Nginx

```
apt install nginx
nano /etc/nginx/nginx.conf
```

Descomente a linha que contém `gzip_types`.
Adicione a linha:

```
limit_req_status 429;
```

Se preciso for, bloqueie específicos IPs:

```
deny 189.35.237.87;
```

Perto dos logs:

```
log_format custom '[$time_local] $remote_addr "$request" $status $body_bytes_sent "$http_referer" '
                  '"$http_user_agent" $sent_http_x_session_id $sent_http_x_user_id $sent_http_x_user_id2';

```

```
systemctl restart nginx; systemctl status nginx;
```

## PHP 8

Dê uma olhada em https://php.watch/.

```
sudo apt update
apt install software-properties-common
add-apt-repository ppa:ondrej/php
add-apt-repository ppa:ondrej/nginx
apt update
apt upgrade
apt install php8.4 php8.4-fpm
apt install php8.4-mbstring php8.4-mysql php8.4-cli php8.4-xml php8.4-curl php8.4-gd php8.4-exif php8.4-zip php8.4-redis php8.4-opcache php8.4-soap
php -v
php-fpm8.4 -v

```

Configure o arquivo `/etc/php/8.4/fpm/php.ini` com nano ou baixando.

```
nano /etc/php/8.4/fpm/php.ini
```

Encontre as seguintes entradas e as modifiquem de acordo:

- display_errors = On
- date.timezone = America/Sao_Paulo
- session.save_handler = redis
- session.save_path = "tcp://127.0.0.1:6379"
- session.gc_maxlifetime = 315360000 #dez anos
- session.cookie_lifetime = 315360000 #dez anos
- session.cookie_secure = On
- session.cookie_samesite = "None"
- upload_max_filesize = 32M
- post_max_size = 32M
- max_file_uploads = 64


```
nano /etc/php/8.4/cli/php.ini
```

Reinicie o PHP:

```
systemctl restart nginx php8.4-fpm; systemctl status nginx php8.4-fpm
```

## Git

Gerando SSH pro git sem passphrase:

Fonte: https://docs.github.com/en/github/authenticating-to-github/connecting-to-github-with-ssh/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent

```
ssh-keygen -t ed25519 -C "luisfernandogaido@gmail.com"
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/id_ed25519

```

Configurando o SSH na conta do github:
(Fonte:
Fonte: https://docs.github.com/en/github/authenticating-to-github/connecting-to-github-with-ssh/adding-a-new-ssh-key-to-your-github-account)

Copie o conteúdo do arquivo SSH:

```
nano ~/.ssh/id_ed25519.pub
```

Add the SSH key to your account on GitHub:

![Settings](https://docs.github.com/assets/images/help/settings/userbar-account-settings.png)

![SSH and GPG keys](https://docs.github.com/assets/images/help/settings/settings-sidebar-ssh-keys.png)

![New SSH key](https://docs.github.com/assets/images/help/settings/ssh-add-ssh-key.png)

Clone seus repositórios com SSH.

### Comandos úteis:

Configurando outro servidor para se conectar via SSH com esse servidor novo:

- Copie o conteúdo do arquivo SSH, conforme linhas acima.
- Conecte-se ao servidor de destino com `nano ~/.ssh/authorized_keys`
- cole a chave no final do arquivo e salve.
- acesse do servidor de origem com ssh como esse: `root@atlas.profinanc.com.br`
- Lindo! Você pode dar comandos remotos de um servidor a outro, como por exemplo `rsync`.

Limpando uma pasta que foi "surrada" por FTP:

```
git clean -d -f
```

Checkout específico commit:

```
git checkout d3626158
```

fetch all remote branches, list branches available for checkout, pull changes from a remote branch

```
git fetch origin
git branch -a
git checkout -b tarefa origin/tarefa

```

Retornando pra um branch:

```
git checkout main
```

## Certbot

```

snap install --classic certbot

```

## Virtual Hosts

Baixando o repo e configurando permissões de pasta:

```
cd /var/www/html
git clone git@github.com:luisfernandogaido/gaido.git
cd gaido
chown -R root:www-data .
chmod g+s .
chmod -R 774 .
git reset --hard
cd ..
```

Coloque o IP do VPS num domínio ou subdomínio.

Exemplo de conteúdo do arquivo `/etc/nginx/sites-available/default:

```


server {
    listen 80 default_server;
    server_name _;
    root /var/www/html;
    index index.html index.php;
    location / {
        try_files $uri $uri/ =404;
    }
}

server {
    listen 80 ;
    server_name gaido.dev;
    root /var/www/html/gaido;
    index index.html index.php;
    client_max_body_size 20M;
    location / {
        try_files $uri $uri/ =404;
        try_files $uri $uri/ /app/minidocs/response.php;
        # use $_SERVER['REQUEST_URI'] para URLs personalizadas
        # referências a arquivos estáticos a partir desta página devem seguir o seguinte formato:
        # <script type="module" src="<?= SITE ?>/app/minidocs/response.js"></script>        
    }
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        access_log /var/log/nginx/gaido-access.log combined;
        error_log /var/log/nginx/gaido-error.log;
        
        fastcgi_param EMAIL_USER "luisfernandogaido@gmail.com";
        fastcgi_param EMAIL_PASS "wvzyyxmswjqunbrx";
        fastcgi_param EMAIL_NAME "Luís Fernando Gaido";
        fastcgi_param GAIDO_MYSQL_HOST "localhost";
        fastcgi_param GAIDO_MYSQL_USER "root";
        fastcgi_param GAIDO_MYSQL_PASS "1000sonhosreais";
        fastcgi_param GAIDO_MYSQL_BASE "gaido";
    }
    location ~* \.(js|css|png|jpg|jpeg|gif|svg|ico)$ {
        expires 15m;
        add_header Cache-Control "public, no-transform";
    }
    rewrite ^/home/(.*)$ /app/home.php?u=$1 last;
}

```

Equivalente a `rewrite ^/home/(.*)$ /app/home.php?u=$1 last;` no apache do meu windows:

```
RewriteEngine on
ReWriteRule ^/gaido/home/(.*)$ /gaido/app/home.php?u=$1 [NC]
```

Reinicie o php + nginx:

```

systemctl restart nginx php8.4-fpm; systemctl status nginx php8.4-fpm

```

Copie a pasta `E:\wamp\Apache24\htdocs\lib` para  `/var/www/html` usando FTP.

### Criando simlink

```

sudo ln -s /etc/nginx/sites-available/profinanc /etc/nginx/sites-enabled/

systemctl restart nginx php8.4-fpm; systemctl status nginx php8.4-fpm

```

## Exemplo de https

NÃO teste o endereço https. Não carece.

```

certbot --nginx -d gaido.dev

```

## Variáveis de sistema para rodar em cli:

```
nano /etc/environment
```

```
EMAIL_USER="luisfernandogaido@gmail.com"
EMAIL_PASS="wvzyyxmswjqunbrx"
EMAIL_NAME="Luís Fernando Gaido"

ou

EMAIL_USER="lfgaido@hotmail.com"
EMAIL_PROVIDER="microsoft"

GAIDO_MYSQL_HOST="localhost"
GAIDO_MYSQL_USER="root"
GAIDO_MYSQL_PASS="xW3g3JAhsZjE-"
GAIDO_MYSQL_BASE="gaido"

EMPRESAS_MYSQL_HOST="localhost"
EMPRESAS_MYSQL_USER="root"
EMPRESAS_MYSQL_PASS="xW3g3JAhsZjE-"
EMPRESAS_MYSQL_BASE="empresas"

BESTSELLER_MYSQL_HOST="localhost"
BESTSELLER_MYSQL_USER="root"
BESTSELLER_MYSQL_PASS="xW3g3JAhsZjE-"
BESTSELLER_MYSQL_BASE="bestseller"
```

## Go

Se a máquina tiver apenas 512MB ou 1GB:

```
apt-get install build-essential
sudo fallocate -l 2G /swapfile
sudo chmod 600 /swapfile
sudo mkswap /swapfile
sudo swapon /swapfile
echo '/swapfile none swap sw 0 0' | sudo tee -a /etc/fstab
```

Além disso, é possível que para compilar algumas coisas, seja preciso subir para 1GB provisoriamente.

### Setup inicial

Altere o PATH:

```
nano ~/.bashrc
export PATH=$PATH:/usr/local/go/bin:/root/go/bin
```

Aproveite que está no bashrc e evite a necessidade de dar Enter ao final de cada lote de comandos colados:

```
bind 'set enable-bracketed-paste off'
```

Feche a sessão e abra outra.

Instalando ou atualizando o compilador:

```
cd ~
curl -O https://storage.googleapis.com/golang/go1.25.2.linux-amd64.tar.gz
tar xvf go1.25.2.linux-amd64.tar.gz
chown -R root:root ./go
cd /usr/local
rm -R go
cd ~
mv go /usr/local
go version
```

Instale net stat:

```

apt install net-tools

```

### Instalando um programa

Criando um serviço de exemplo chamado **estudos**. Procurando e liberando temporariamente uma porta.

```

netstat -peanut | grep 4000
ufw allow 4000

``` 

Na pasta do fonte:

```

go install

```

ou

```

go build

```

para CLI ou serviços, respectivamente.

Crie um serviço:

```

nano /lib/systemd/system/estudos.service

```

Cole o seguinte código dentro do arquivo:

```

[Unit]
Description=estudos
After=network.target

[Service]
Type=simple
ExecStart=/var/www/html/estudos/estudos
WorkingDirectory=/var/www/html/estudos
Restart=on-failure
RestartSec=10

Environment=KEY=value

[Install]
WantedBy=multi-user.target

```

Comandos úteis para a administração de serviços:

```

systemctl enable estudos.service
systemctl restart estudos.service
systemctl status estudos.service

systemctl disable estudos.service
systemctl edit --full --force estudos.service
systemctl daemon-reload

```

Ao executar um comando, **estudos.service** pode ser encurtado por **estudos**.

Log:

```

journalctl -u estudos.service journalctl -b -u estudos.service journalctl -u estudos -n 40

```

Crie um novo registro de domínio/subdomínio em Networking da DO.

Crie um virtual host em  `/etc/nginx/sites-available/default`:

```
#limit_req_zone $binary_remote_addr zone=estudos:10m rate=1r/s;
limit_req_zone $binary_remote_addr zone=estudos:10m rate=12r/m;
#limit_req_zone $http_authorization zone=mylimit:10m rate=6r/m;
server {
    listen 80;
    server_name estudos.gaido.dev;
    location / {
        #sem burst: qualquer "too soon" é descartado
        #com burst: permite rajadas, acumula numa fila, entrega "slow pass" qualquer "too soon" e descarta excedentes        
        #com burst com nodelay: não entrega "slow pass" qualquer "too soon", mas descarta excedentes.        
        limit_req zone=estudos burst=6 nodelay;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $remote_addr;
        proxy_set_header Host $host;
        proxy_pass http://127.0.0.1:4000;
        access_log /var/log/nginx/estudos-access.log combined;
        error_log /var/log/nginx/estudos-error.log;
    }
}

```

Reinicie o Nginx:

```

nginx -t
systemctl restart nginx; systemctl status nginx

```

NÃO teste o endereço https. Não carece.

Instale o certificado https:

```

certbot --nginx -d estudos.gaido.space

```
Feche a porta aberta temporariamente:

```

ufw status numbered
ufw delete <numero>

```

No dia-a-dia, para reiniciar o serviço, depois de um git pull ou upload via FTP, faço algo como:

```

go build /var/www/html/estudos && systemctl restart estudos

```

ou

```

go install /var/www/html/estudos

```

dependendo se for serviço ou CLI, respectivamente.

### ffmpeg

Frequentemente precisamos manipular compressão de vídeos com Go ou Python. Não existem bibliotecas
melhores que um próprio CLI programa: ffmpeg.

```
apt install ffmpeg
```

## ElasticSearch

Procure por "Install Elasticsearch with Debian Package".
E dentro por "Download and install the Debian package manually".

Depois de

wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-8.17.1-amd64.deb
wget https://artifacts.elastic.co/downloads/elasticsearch/elasticsearch-8.17.1-amd64.deb.sha512
shasum -a 512 -c elasticsearch-8.17.1-amd64.deb.sha512
sudo dpkg -i elasticsearch-8.17.1-amd64.deb

pegue a senha do usuário elastic, copie e não perca. Atualmente é NZ9*uXCLbCReu16tcEGs.

nano /etc/elasticsearch/elasticsearch.yml

xpack.security.transport.ssl e xpack.security.http.ssl devem ser explicitamente marcados como false,
visto que eu não quero as complicações de certificado, e já tenho uma senha linda ali para enviar via
https. Tá bom demais, eu acho...
Além disso network.host: 0.0.0.0 e http.port: 9200 são fundamentais

node.name: elasticsearch
network.host: 0.0.0.0
http.port: 9200
discovery.seed_hosts: ["127.0.0.1", "159.203.81.220"] #aqui o ip do servidor
cluster.initial_master_nodes: ["elasticsearch"]

systemctl start elasticsearch

Configure um Virtual Host analogamente a serviços Go, com a exceção de que o serviço do ElasticSearch é único,
na porta 9200.

```
server {
    listen 80;
    server_name es.gaido.dev;
    client_max_body_size 16M;
    location / {
        #sem burst: qualquer "too soon" é descartado
        #com burst: permite rajadas, acumula numa fila, entrega "slow pass" qualquer "too soon" e descarta excedentes
        #com burst com nodelay: não entrega "slow pass" qualquer "too soon", mas descarta excedentes.
        #limit_req zone=estudos burst=6 nodelay;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $remote_addr;
        proxy_set_header Host $host;
        proxy_pass http://127.0.0.1:9200;
        access_log /var/log/nginx/es-access.log combined;
        error_log /var/log/nginx/es-error.log;
    }
}
```

Reinicie o Nginx:

```

nginx -t
systemctl restart nginx; systemctl status nginx

```

NÃO teste o endereço https. Não carece.

Instale o certificado https:

```

certbot --nginx -d es.gaido.dev

```

### Utilitários extras

#### pdftotext

```
apt install poppler-utils
```

No windows: https://github.com/oschwartz10612/poppler-windows/releases

#### zip

Instalando:

```

apt install unzip
apt-get install zip

```

Zipar arquivo:

```

zip arquivo.zip arquivo.txt arquivo2.txt

```

Zipar pasta:

```

zip –r pasta.zip pasta

```

Unzipar:

```

unzip arquivo.zip

```

#### Docker windows

Reiniciar wsl:

```

wsl --shutdown

```

Arquivo de configuração:

```

C:\Users\lfgai\.wslconfig

```

Conteúdo:

```
[wsl2]
memory=1GB
```

#### Oracle sem C

Driver Oracle em Go puro, mais nada.

```

github.com/sijms/go-ora/v2

```

#### Problemas com terminal no GoLand

Dentro da IDE:
Menu Help -> Find Action -> Registry...

Procure a chave `terminal.use.conpty.on.windows` e a desmarque.

#### Remover reformat on action save no GoLand

Dentro da IDE:
Ctrl + Alt + S
Pesquisa "Actions on Save"
Desmarque "Reformat code"

#### Instalar Scoop e GCC no Windows:

```
Set-ExecutionPolicy RemoteSigned -Scope CurrentUser
irm get.scoop.sh | iex
scoop install mingw
gcc -v
```

Referências:

https://stackoverflow.com/questions/73673813/cgo-c-compiler-gcc-not-found-exec-gcc-executable-file-not-found-in-path

https://scoop.sh/

#### Debug Chrome celular

https://developer.chrome.com/docs/devtools/remote-debugging/local-server/

#### date.timezone no wamp:

Procure por `date.timezone` no php.ini, descomente, e coloque o valor `America/Sao_Paulo`.

#### ifconfig

Ajuda a ver o tráfego desde que a máquina está ligada.