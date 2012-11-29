
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
    coeff   float NOT NULL
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
('10.0.72.1', 'Renater download', 'public', '.1.3.6.1.2.1.2.2.1.10.1', 50, 70, 1),
('10.0.72.1', 'Renater upload', 'public', '.1.3.6.1.2.1.2.2.1.16.1', 5, 70, 1),
('10.0.72.9', 'Adista download', 'public', '.1.3.6.1.2.1.2.2.1.10.1', 50, 70, 1),
('10.0.72.9', 'Adista upload', 'public', '.1.3.6.1.2.1.2.2.1.16.1', 50, 70, 1);

INSERT INTO `state` (`name`, `hostid`, `itemid`, `warning`, `alert`, `coeff`) VALUES
('Espace Libre Bureautique', 10143, 70404, '50', '70', 1),
('description perso, load5 Xen10', 10278, 77265, '0.2', '0.8', 1),
('Charge du processeur du Courrier', 10206, 71381, '5', '10', 1),
('Charge sur Auth', 10149, 66623, ' 0.016580', ' 0.02', 1);