create database blog;

create table admins(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username_admin VARCHAR(50) UNIQUE,
  firstname VARCHAR(50),
  lastname VARCHAR(50),
  email VARCHAR(100),
  password VARCHAR(200),
  created_at TIMESTAMP
  
);

create table users(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE,
  firstname VARCHAR(50),
  lastname VARCHAR(50),
  email VARCHAR(100),
  password VARCHAR(200),
  created_at TIMESTAMP,
  title VARCHAR(100),
  address VARCHAR(100),
  profile_pic VARCHAR(100)  UNIQUE
);

create table posts(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(100),
  description text,
  image VARCHAR(100),
  date_create TIMESTAMP,
  category VARCHAR(10) default('Front-end'),
  user_post VARCHAR(100),
  user_post_image VARCHAR(100), 
  FOREIGN KEY (user_post) REFERENCES users(username),  
  FOREIGN KEY (user_post_image) REFERENCES users(profile_pic)
);

create table comments(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  comment_text VARCHAR(200),
  comment_time TIMESTAMP,
  comment_owner VARCHAR(100),
  comment_owner_img VARCHAR(100), 
  post_commented_id INT(100),
  FOREIGN KEY (comment_owner) REFERENCES users(username),
  FOREIGN KEY (comment_owner_img) REFERENCES users(profile_pic),
  FOREIGN KEY (post_commented_id) REFERENCES posts(id) ON DELETE CASCADE
);

create table likes(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  like_post INT(10) default(0),
  like_owner VARCHAR(100),
  like_owner_img VARCHAR(100),
  post_liked_id INT(100),
  FOREIGN KEY (like_owner) REFERENCES users(username),
  FOREIGN KEY (like_owner_img) REFERENCES users(profile_pic),
  FOREIGN KEY (post_liked_id) REFERENCES posts(id) ON DELETE CASCADE
);

create table favorites(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  status VARCHAR(10) default(0),
  fave_date TIMESTAMP,
  post_creator VARCHAR(100),
  post_title VARCHAR(100),
  post_descripe text,
  post_img VARCHAR(100),
  favorit_post_id INT,
  favorites_owner VARCHAR(100),
  FOREIGN KEY (favorites_owner) REFERENCES users(username),
  FOREIGN KEY (post_creator) REFERENCES posts(user_post)  ON DELETE CASCADE,
  FOREIGN KEY (favorit_post_id) REFERENCES posts(id) ON DELETE CASCADE
);

create table show_posts_by_category(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  category_active VARCHAR(100),
  category_owner VARCHAR(100)
);

create table notifications(
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  content VARCHAR(200),
  date_noti TIMESTAMP,
  readed VARCHAR(10) default('false'),
  noti_sender VARCHAR(100),
  noti_sender_img VARCHAR(100),
  noti_receiver VARCHAR(100),
  post_reacted_id INT(100),
  post_reacted_title VARCHAR(100),
  FOREIGN KEY (noti_receiver) REFERENCES users(username),
  FOREIGN KEY (post_reacted_id) REFERENCES posts(id) ON DELETE CASCADE
);


