DROP TABLE IF EXISTS user;

CREATE TABLE user (
    login   varchar(255) NOT NULL PRIMARY KEY,
    role    varchar(255) NOT NULL
);

INSERT INTO user values ('paris_n', 'admin');
INSERT INTO user values ('lesouef_e', 'admin');