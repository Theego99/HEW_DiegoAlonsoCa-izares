create table users (
  id int(11) not null auto_increment primary key,
  name varchar(128) not null,
  email varchar(255) not null,
  password_hash varchar(255) default null,
  created_at timestamp not null default current_timestamp,
  unique key email (email)
);

CREATE TABLE shortcuts (
  id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  point_a VARCHAR(128) NOT NULL,
  point_b VARCHAR(128) NOT NULL,
  address VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  shortcut_name VARCHAR(128) DEFAULT NULL,
  comments VARCHAR(248) DEFAULT NULL,
  user_id INT(11) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id)
);
