# Contexte :
Notre client est une grande marque de salle de sport et souhaite la création d’une interface simple à destination de ses équipes qui gèrent les droits d'accès à ses applications web de ses franchisés et partenaires qui possèdent des salles de sport. Ainsi, lorsqu'une salle de sport ouvre et prend la franchise de cette marque, on lui donne accès à un outil de gestion en ligne. En fonction de ce qu’il va reverser à la marque et de son contrat, il a droit à des options ou modules supplémentaires. Par exemple, un onglet “faire son mailing” ou encore "gérer le planning équipe" ou bien “promotion de la salle" ou encore “vendre des boissons” peut être activé ou désactivé.
Le projet a donc pour but la création et la construction d’une interface cohérente et ergonomique afin d’aider leurs équipes à ouvrir des accès aux modules de leur API auprès des franchisés/partenaires.
L’interface devra permettre de donner de la visibilité́ sur les partenaires/franchisés utilisant l’API et quels modules sont accessibles par ces partenaires. Elle doit faciliter l'ajout, la modification ou la suppression des permissions aux modules de chaque partenaire/franchisé.
# Guide d'installation du projet en Localhost 
## ``` Important : si la configuration Mailer du fichier .env n'est pas faite certains boutons de changement de permissions ne fonctionneront pas car ces fonctionalités sont liés à des envoie de mail ```
## Configuration du projet : 
1. Symfony 6.1
2. Maria Db 10.6.5
3. PHP 8.1 >=
4. Composer 2.4.0 
5. Yarn 1.22 
6. Git Bash
7. symfony Cli 

## Installation :
### Importantion du projet via Git Bash :
Cloner le projet en executant la commande : git clone https://github.com/havikoro2004/energy_fit_academy.git 
### Modification du fichier .env ou créer .env.local :
Créer un fichier .env.local ou modifier le fichier .env en ajoutant le code suivant :
  * APP_ENV=dev
  * APP_SECRET=4e7e53d5f41d49da941414360241b58c
  * DATABASE_URL="mysql://root@127.0.0.1:3306/efa?serverVersion=mariadb-10.6.5"
  * et commenté la ligne (DATABASE_URL="postgresql://app:!ChangeMe!@127.0.0.1:5432/app?serverVersion=14&charset=utf8")
  * MAILER_DSN=sendgrid://votrekeyapisendgrid@default
 ### Mise à jour du projet :
 * Taper les commandes suivantes :
 * composer install => pour installer les dépendances depuis composer.lock
 * symfony console doctrine database:create => pour créer la base de donnée 
 * yarn install => pour installer les dépendances depuis le fichier package.json
 * yarn run build pour mettre à jour webpack 
 ### Importation de la base de données :
 * Le backup de la base de données se trouve dans le dossier : Livrables\Database\backup.sql
 * Pour importer la base de donnée vous avez 2 solutions :
 1. En utilisant le MySQLdump avec la commande 
  * mysql -u root -p efa < chemin_de_la_sauvegarde\backup.sql par ex mysql -u root -p efa < C:\Users\Najib\Desktop\backup.sql
 2. Soit depuis l'interface phpmyadmin en allant sur la base de données efa et cliquer sur importer et parcourir le fichier backup.sql
 ### Démarrer le serveur :
 * En tapant la commande : symfony serve ou symfony server:start ou php bin/console server:start
