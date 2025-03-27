create table user (
id int auto_increment Primary key,
login tinytext,
mdp tinytext,
name tinytext,
status int,
);

create table game (
idgame int auto_increment Primary key,
iduser1 int,
iduser2 int,
winner int, 
);

Insert into user (id, login, mdp, name, status) values 
(1, 'maxirrx', '23032005', 'Maxirrx', 0), 
(2, 'yohan', '1234', 'Yohan', 0);