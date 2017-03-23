CREATE TABLE category
(
    id int PRIMARY KEY NOT NULL,
    name varchar(50) NOT NULL,
    slug varchar(50) NOT NULL
);
ALTER TABLE category MODIFY id INT AUTO_INCREMENT;


CREATE TABLE post
(
    id int PRIMARY KEY NOT NULL,
    title varchar(50) NOT NULL,
    slug varchar(50) NOT NULL,
    content text NOT NULL,
    created int NOT NULL,
    category_id int NOT NULL
);
CREATE INDEX post_category_id_index ON post (category_id);
ALTER TABLE post ADD FOREIGN KEY (category_id) REFERENCES category(id);
CREATE UNIQUE INDEX post_slug_category_id_index ON post (slug, category_id);
ALTER TABLE post MODIFY id INT AUTO_INCREMENT;

INSERT INTO category(name, slug) VALUES
('Zend Framework', 'zend-framwork'),
('PHP', 'php'),
('MySQL', 'mysql');


CREATE TABLE user
(
         id int PRIMARY KEY NOT NULL,
         first_name varchar(50) NOT NULL,
         last_name varchar(50) NOT NULL,
         email varchar(100) NOT NULL,
         password char(60) NOT NULL,
         created int NOT NULL,
         user_group int NOT NULL
);
ALTER TABLE user MODIFY id INT AUTO_INCREMENT;
ALTER TABLE post ADD author_id int NULL;
CREATE INDEX post_author_id_index ON post(author_id);
ALTER TABLE post ADD FOREIGN KEY (author_id) REFERENCES user(id);
