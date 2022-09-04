/** sauvegarde **/

mysqldump -u root -p efa > C:\Users\Najib\Desktop\backup.sql

/** Restauration  **/

mysql -u root -p efa < C:\Users\Najib\Desktop\backup.sql