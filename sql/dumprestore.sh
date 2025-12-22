#dump produção, restore local
mysqldump.exe gaido --host=gaido.space --user=root --password=xW3g3JAhsZjE- --result-file=dump.sql --complete-insert --routines --verbose
mysql --host=localhost --user=root --password=Semaver13
drop database if exists gaido; create database gaido; use gaido; source dump.sql;
exit;

#Processo inverso: dump local, restore produção
mysqldump.exe gaido --host=localhost --user=root --password=Semaver13 --result-file=dump.sql --complete-insert --routines --verbose
mysql --host=gaido.dev --user=root --password=1000sonhosreais
#ou
mysql --host=gaido.space --user=root --password=xW3g3JAhsZjE-
drop database if exists gaido;
create database gaido;
use gaido;
source dump.sql;
exit;

# data only: --no-create-info
#dump específicas tabelas: após o nome do banco, informe entre espaços as tabelas.
mysqldump.exe gaido conta usuario --host=localhost --user=root --password=Semaver13 --result-file=dump.sql --complete-insert --no-create-info
# dentro do dump, depois do cabeçalho:
TRUNCATE TABLE `conta`;
TRUNCATE TABLE `usuario`;

