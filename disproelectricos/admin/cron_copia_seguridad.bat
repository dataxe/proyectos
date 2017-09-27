cd C:\
cd C:\xampp\mysql\bin
mysqldump -u disproelectricos -p72f7546c42222a0471e93c73cbbe947c disproelectricos > E:\Bases_cron_txt\disproelectricos.txt
cd C:\
cd C:\Program Files\WinRAR
start winrar a -pdisproelectricos951 -r -dh -df -agyyyy_mm_dd__hh_mm -ibck "E:\Bases_cron\disproelectricos_.rar" "E:\Bases_cron_txt\*.*"