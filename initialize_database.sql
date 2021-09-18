-- reset root password
SELECT user,authentication_string FROM mysql.user;
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '';
FLUSH PRIVILEGES;
SELECT user,authentication_string FROM mysql.user;

-- create database
create database adatok;

-- for autocopmplete
\use adatok

-- create table
create table tabla (Sor int, Username varchar(255), Titkos varchar(255), primary key(Sor));

-- insert stuff into table
insert into tabla values (1, "katika@gmail.com", "piros");
insert into tabla values (2, "arpi40@freemail.hu", "zold");
insert into tabla values (3, "zsanettka@hotmail.com", "sarga");
insert into tabla values (4, "hatizsak@protonmail.com", "kek");
insert into tabla values (5, "terpeszterez@citromail.hu", "fekete");
insert into tabla values (6, "nagysanyi@gmail.hu", "feher");