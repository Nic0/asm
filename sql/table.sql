
-- USERS
DROP TABLE IF EXISTS user;
CREATE TABLE user (
    login   varchar(255) COLLATE utf8_general_ci NOT NULL PRIMARY KEY,
    role    varchar(255) COLLATE utf8_general_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- STATES
DROP TABLE IF EXISTS state;
CREATE TABLE state (
    id      int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name    varchar(255) COLLATE utf8_general_ci,
    hostid  int NOT NULL,
    itemid  int NOT NULL,
    warning varchar(255) COLLATE utf8_general_ci NOT NULL,
    alert   varchar(255) COLLATE utf8_general_ci NOT NULL,
    coeff   float NOT NULL,
    group_id int NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- GROUP
DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
    id      int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name    varchar(255) COLLATE utf8_general_ci,
    logo    varchar(255) COLLATE utf8_general_ci,
    coeff   float NOT NULL,
    group_id int
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- SNMP
DROP TABLE IF EXISTS snmp;
CREATE TABLE snmp (
    id      int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ip      varchar(255) COLLATE utf8_general_ci NOT NULL,
    name    varchar(255) COLLATE utf8_general_ci NOT NULL,
    community    varchar(255) NOT NULL,
    `oid`   varchar(255) COLLATE utf8_general_ci NOT NULL,
    warning int NOT NULL,
    alert   int NOT NULL,
    coeff   float NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- SNMP_INPUT
DROP TABLE IF EXISTS snmp_input;
CREATE TABLE snmp_input (
    snmp_id int NOT NULL,
    value   bigint,
    `date`  timestamp
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


--
--     DATA
--

INSERT INTO user values ('paris_n', 'admin');
INSERT INTO user values ('elesouef', 'admin');

INSERT INTO `snmp` (`ip`, `name`, `community`, `oid`, `warning`, `alert`, `coeff`) VALUES
('10.0.72.9', 'Adista download', 'public', '.1.3.6.1.2.1.2.2.1.10.1', 50, 70, 1),
('10.0.72.9', 'Adista upload', 'public', '.1.3.6.1.2.1.2.2.1.16.1', 50, 70, 1);

INSERT INTO `state` (`name`, `hostid`, `itemid`, `warning`, `alert`, `coeff`, `group_id`) VALUES
('Charge CPU', 10207, 71405, '1', '1.5', 1, 3),
('Espace RAM libre', 10207, 71422, '700000000', '500000000', 1, 3),
('Espace libre sur C:', 10207, 71417, '20', '5', 1.5, 3),
('Charge du processeur', 10143, 65970, '1', '1.5', 1, 4),
('charge du processeur de Xen10', 10278, 77265, '1', '1.5', 1, 5),
('charge du processeur de Xen9', 10277, 77234, '1', '1.5', 1, 5),
('charge du processeur de Xen8', 10274, 77072, '1', '1.5', 1.5, 5),
('Traffic entrant par eth0', 10257, 76200, '150', '200', 2, 6),
('Memoire Libre', 10257, 76289, '8000000000', '2000000000', 1, 6);

INSERT INTO `group` (`name`, `logo`, `coeff`, `group_id`) VALUES
('Applications bureautiques', 'bureaux.png', 1, null),
('Applications Serveurs', 'serveurs.png', 1, null),
('Etat du service courrier', 'mail.png', 1, 1),
('Etat de Gamelle', 'gamelle.png', 1, 1),
('Etat des Xen', 'xen.png', 1, 2),
('Etat de la virtualisation', 'virtualbox.png', 1, 2)