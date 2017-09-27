BACKUP_PATH=c:\
set anio=%date:~6,4%
set mes=%date:~3,2%
set dia=%date:~0,2%
set hora=%time:~0,2%
set hora=%hora: =0%
set minuto=%time:~3,2%
set segundo=%time:~6,2% 
set BACKUP_FILE=backup_%fecha%.sql
cd C:\xampp\mysql\bin
mysqldump -u ciclo_center -p3799d0ae024b8f2dca2beae3685d909e ciclo_center > D:\Bases_cron\ciclo_center_%anio%_%mes%_%dia%_%hora%_%minuto%.txt