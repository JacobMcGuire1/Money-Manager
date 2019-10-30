
drop table users;
CREATE TABLE users(id integer NOT NULL primary key, username varchar(50) NOT NULL, email varchar(50) NOT NULL, passwordhash varchar(255) NOT NULL, salt varchar(255) NOT NULL); 

drop table bills;
CREATE TABLE bills(id integer NOT NULL primary key, name varchar(50) NOT NULL, creator_id integer NOT NULL, foreign key(creator_id) references users(id));

drop table groups;
CREATE TABLE groups(id integer NOT NULL primary key, name varchar(50) NOT NULL, creator_id integer NOT NULL, foreign key(creator_id) references users(id));

drop table owage;
CREATE TABLE owage(id integer NOT NULL primary key, bill_id integer NOT NULL, user_id integer NOT NULL, cost real NOT NULL, paid boolean NOT NULL, foreign key(bill_id) references bills(id), foreign key(user_id) references users(id));

drop table groupage;
CREATE TABLE groupage(id integer NOT NULL primary key, user_id integer NOT NULL, group_id integer NOT NULL, foreign key(group_id) references groups(id), foreign key(user_id) references users(id));

drop table invites;
CREATE TABLE invites(id integer NOT NULL primary key, user_id integer NOT NULL, group_id integer NOT NULL, foreign key(group_id) references groups(id), foreign key(user_id) references users(id));