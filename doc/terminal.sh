#@IgnoreInspection BashAddShebang
#https://certbot.eff.org/all-instructions/#ubuntu-16-04-xenial-apache

#Servidor profinanc
certbot-auto --apache certonly -d sdg.profinanc.com.br
certbot-auto renew

#Servidores PHP (talvez igual o Go)
certbot --apache certonly -d gaido.dev
certbot --nginx -d strello.gaido.dev

#Servidor Go
certbot --authenticator standalone --installer nginx -d strello.gaido.dev --pre-hook "service nginx stop" --post-hook "service nginx start"

certbot renew

#Monitorar uso de memória
free -m
watch -n 5 free -m
ps -p 11333 -o pid,vsz=MEMORY -o user,group=GROUP -o comm,args=ARGS

#Copiar banco de dados
mysqldump -u root -pSemaver13 --routines atlas > atlas.sql
mysqladmin -u root -pSemaver13 create profinanc_teste
mysql -u root -pSemaver13 profinanc_teste < atlas.sql

#Mostrar todos os processos (q pra sair)
ps aux | less

#Rodar CLI em background com saída em arquivo
nohup php /var/www/html/consorcio/cli/atualiza-fipe.php > atualiza-fipe.txt &

#update Ubuntu
apt-get update        # Fetches the list of available updates
apt-get upgrade       # Strictly upgrades the current packages
apt-get dist-upgrade  # Installs updates (new ones)

Rebuta o servidor.
shutdown -r now

Desliga o servidor. Precisa dar power on.
shutdown -P now
shutdown -h now

#Uso do disco
df -h
du -hs /path/to/directory
du -h -d 1 | sort -hr

Dar todos os privilégios a um usuário de banco.
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' IDENTIFIED BY 'wuVDHdHcIv' WITH GRANT OPTION;
FLUSH PRIVILEGES;

#limpar log binário
PURGE BINARY LOGS BEFORE SUBDATE(NOW(), INTERVAL 1 DAY);

getent group profinanc

#Mudando o proprietário user:group de uma pasta.
chown -R root:profinanc fotos

chown -R root:www-data baixas
chown -R root:www-data /var/www/html/simbiosi/downloader


#Permitindo que o usuário crie arquivos com permissão do grupo da pasta.
chmod g+s .
chmod g+s fotos

chmod -R 774 /var/www/html/simbiosi/downloader

#Resumo de permissão de upload. Chegue na pasta correta e execute os três comandos abaixo:
chown -R root:www-data .
chmod g+s .
chmod -R 774 .

#Listar todos os mount bind.
findmnt | grep  "\["

Mudando a home de um usuário
usermod --home /var/www/ username

#Alterando uma pasta para um grupo, e fazendo com que novos arquivos sejam do grupo, e não do usuário que está operando

$ umask 002            # allow group write; everyone must do this
$ chgrp G .            # set directory group to G
$ chmod g+s .          # files created in directory will be in group G

#Mudando umask padrão do servidor pra eu poder fazer isso com o root.
#http://www.cyberciti.biz/tips/understanding-linux-unix-umask-value-usage.html
#Tutorial da Digital Ocean explicando lindamente esse conceito:
#https://www.digitalocean.com/community/tutorials/linux-permissions-basics-and-how-to-use-umask-on-a-vps

#Copiando bases mysql em segundos!
mysqldbcopy --source root:Semaver13@localhost --destination root:Semaver13@localhost circo:circo_copy

./dne &> log.txt &

#compactando e descompactando arquivos no servidor
#fonte: https://www.cyberciti.biz/faq/how-do-i-compress-a-whole-linux-or-unix-directory/
tar -zcvf diretorio.tar.gz diretorio
tar -zxvf arquivo.tar.gz
tar -cvf diretorio.tar diretorio
tar -xvf arquivo.tar

#logar como www-data
su -s /bin/bash www-data

#atualizando versão do Go

#easy mode

#primeira vez
snap install go --classic

#posteriormente
snap refresh go

#hard mode

#primeira vez no servidor abrir arquivo e colocar linha no final
nano .bashrc # ou nano $HOME/.profile
export PATH=$PATH:/usr/local/go/bin

#depois
cd ~
curl -O https://storage.googleapis.com/golang/go1.15.4.linux-amd64.tar.gz
tar xvf go1.15.4.linux-amd64.tar.gz
chown -R root:root ./go
cd /usr/local
rm -R go
cd ~
mv go /usr/local
go version

nano ~/.profile
#no final do arquivo coloque:
export GOPATH=$HOME/work
export PATH=$PATH:/usr/local/go/bin:$GOPATH/bin
#feche, salve
source ~/.profile

#limpar o journal
journalctl --rotate
journalctl --vacuum-time=1s

#deletar regra de firewall
ufw list numbered
ufw delete 36

#linhas de código
find . -name '*.php' | xargs wc -l
find . -name '*.js' | xargs wc -l
find . -name '*.css' | xargs wc -l

systemctl restart php7.3-fpm nginx
systemctl status php7.3-fpm nginx

systemctl restart php7.4-fpm nginx
systemctl status php7.4-fpm nginx


./instagram &> instagram.log &

#files per process (modificado de https://stackoverflow.com/questions/21752067/counting-open-files-per-process)
cd /proc
for pid in [0-9]*
do
    echo "PID = $pid with $(ls /proc/$pid/fd/ | wc -l) file descriptors"
done | sort -rn -k5 | head -n 10 |while read -r _ _ pid _ fdcount _
do
  command=$(ps -o cmd -p "$pid" -hc)
  printf "pid = %5d with %4d fds: %s\n" "$pid" "$fdcount" "$command"
done

#limites
ulimit -a

#setando número de arquivos abertos
ulimit -n 200000

#fonte: https://linuxhint.com/permanently_set_ulimit_value/

# log stdout e stderr powershell:
chcp 65001
.\ncw.exe 2>&1 > log.log