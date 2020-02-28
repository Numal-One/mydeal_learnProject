-- для сервера
-- CREATE DATABASE u0857553_mydealsDB;

-- для локального
CREATE DATABASE mydealsDB;

CREATE TABLE user(
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    email VARCHAR(128) NOT NULL UNIQUE,
    password VARCHAR(128) NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP

);

CREATE TABLE project(
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    name VARCHAR(128) NOT NULL,
    user_id INT,
    FOREIGN KEY(user_id) REFERENCES user(id)
);


CREATE table task(
    id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    project_id INT,
    user_id INT,
    name VARCHAR(128) NOT NULL,
    creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deadline DATE, 
    status INT NOT NULL DEFAULT 0,
    file TEXT,
    FOREIGN KEY(user_id) REFERENCES user(id),
    FOREIGN KEY(project_id) REFERENCES project(id),
    FULLTEXT KEY (name)
);