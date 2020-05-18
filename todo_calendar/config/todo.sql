create table users (
  id int NOT NULL auto_increment PRIMARY KEY,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created DATETIME
);
create table tasks (
  id int NOT NULL auto_increment PRIMARY KEY,
  user_id int NOT NULL,
  state tinyint(1) default 0, 
  title text,
  date DATETIME,
  created DATETIME
);

