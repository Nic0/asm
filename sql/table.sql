DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS states;

CREATE TABLE users (
    login   varchar(255) NOT NULL PRIMARY KEY,
    role    varchar(255) NOT NULL
);

CREATE TABLE states (
    id      int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name    varchar(255),
    hostid  int NOT NULL,
    itemid  int NOT NULL,
    warning varchar(255) NOT NULL,
    alert   varchar(255) NOT NULL,
    coeff   int NOT NULL
);


INSERT INTO users values ('paris_n', 'admin');
INSERT INTO users values ('lesouef_e', 'admin');