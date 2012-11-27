DROP TABLE IF EXISTS users;
CREATE TABLE users (
    login   varchar(255) COLLATE utf8_general_ci NOT NULL PRIMARY KEY,
    role    varchar(255) COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS states;
CREATE TABLE states (
    id      int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name    varchar(255) COLLATE utf8_general_ci,
    hostid  int NOT NULL,
    itemid  int NOT NULL,
    warning varchar(255) COLLATE utf8_general_ci NOT NULL,
    alert   varchar(255) COLLATE utf8_general_ci NOT NULL,
    coeff   int NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS snmp;
CREATE TABLE snmp (
    id      int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ip      varchar(255) COLLATE utf8_general_ci NOT NULL,
    name    varchar(255) COLLATE utf8_general_ci NOT NULL,
    community    varchar(255) NOT NULL,
    `oid`   varchar(255) COLLATE utf8_general_ci NOT NULL,
    warning int NOT NULL,
    alert   int NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS snmp_input;
CREATE TABLE snmp_input (
    id      int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    snmp_id int NOT NULL,
    value   int,
    `date`  date
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO users values ('paris_n', 'admin');
INSERT INTO users values ('lesouef_e', 'admin');