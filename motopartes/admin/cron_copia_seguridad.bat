cd C:\
cd C:\xampp\mysql\bin
mysqldump -u partes -p84193db9827ac49a5e0069d9b329f1b8 motopartes > D:\Bases_cron_txt\motopartes.txt
cd C:\
cd C:\Program Files\WinRAR
start winrar a -pmotopartes951 -r -dh -df -agyyyy_mm_dd__hh_mm -ibck "D:\Bases_cron\motopartes_.rar" "D:\Bases_cron_txt\*.*"