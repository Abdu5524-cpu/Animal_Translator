-- Schema the API expects. Run once on a new MySQL / MariaDB database.
CREATE TABLE IF NOT EXISTS users (
  username VARCHAR(20)  NOT NULL PRIMARY KEY,
  password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS animals (
  purchase_id INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username    VARCHAR(20)  NOT NULL,
  animal      VARCHAR(20)  NOT NULL,
  translation VARCHAR(100) NULL,
  time_date   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  KEY idx_animals_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
