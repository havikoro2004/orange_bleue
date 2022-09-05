/** Supprimer la base de données du nom efa si elle existe **/
DROP SCHEMA IF EXISTS efa;

/** Création de la base de données du nom efa  **/
CREATE SCHEMA IF NOT EXISTS efa CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci ;

/**  Création des tables selon l'ordre  **/

    CREATE TABLE efa.permission (
        id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        name VARCHAR(255) NOT NULL

    ) ENGINE=InnoDB;

    CREATE TABLE efa.client (
        id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        name VARCHAR(255) NOT NULL ,
        active BOOLEAN NOT NULL ,
        short_d VARCHAR(255) ,
        full_d VARCHAR(255) ,
        url VARCHAR(255) ,
        dpo VARCHAR(255) ,
        technical_contact VARCHAR(255) NOT NULL,
        commercial_contact VARCHAR(255) NOT NULL,

    ) ENGINE=InnoDB;

    CREATE TABLE efa.branch (
        id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        adress TEXT NOT NULL ,
        active BOOLEAN NOT NULL ,
        id_client INT(10) NOT NULL,
        FOREIGN KEY (id_client) REFERENCES client(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;


    CREATE TABLE efa.client_permission (
        id_client INT(10),
        id_permission INT(10),
        PRIMARY KEY (id_client,id_permission),
        FOREIGN KEY (id_client) REFERENCES client(id),
        FOREIGN KEY (id_permission) REFERENCES permission(id)
    ) ENGINE=InnoDB;    


    CREATE TABLE efa.branch_permission (
        id_branch INT(10),
        id_permission INT(10),
        PRIMARY KEY (id_branch,id_permission),
        FOREIGN KEY (id_branch) REFERENCES branch(id),
        FOREIGN KEY (id_permission) REFERENCES permission(id)
    ) ENGINE=InnoDB;   


    CREATE TABLE efa.user (
        id INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        email VARCHAR(255) NOT NULL ,
        password VARCHAR(255) NOT NULL ,
        role longtext ,
        id_client INT(10),
        id_branch INT(10),
        FOREIGN KEY (id_client) REFERENCES client(id) ON DELETE CASCADE ,
        FOREIGN KEY (id_branch) REFERENCES branch(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;



