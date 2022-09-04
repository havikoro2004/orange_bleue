/** sauvegarde **/

mysqldump -u root -p orange > C:\Users\Najib\Desktop\backup.sql

/** Restauration  **/

mysql -u root -p orange < C:\Users\Najib\Desktop\backup.sql