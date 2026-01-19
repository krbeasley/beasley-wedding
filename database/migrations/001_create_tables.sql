CREATE TABLE IF NOT EXISTS tbl_user (
  id INT AUTO_INCREMENT NOT NULL,
  email VARCHAR(256) NOT NULL,
  password VARCHAR(256) NOT NULL,
  deleted BIT DEFAULT 0,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS tbl_guest (
  id INT AUTO_INCREMENT NOT NULL,
  first_name VARCHAR(32) NOT NULL,
  last_name VARCHAR(32) NOT NULL,
  party_id INT DEFAULT NULL,
  allowed_plus_one BIT DEFAULT 0,
  deleted BIT DEFAULT 0,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS tbl_parties (
  id INT AUTO_INCREMENT NOT NULL,
  name VARCHAR(64) NOT NULL,
  size INT(1) NOT NULL,
  deleted BIT DEFAULT 0,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS tbl_rsvp_requests (
  id INT AUTO_INCREMENT NOT NULL,
  guest_id INT NOT NULL,
  party_id INT DEFAULT NULL,
  has_plus_one BIT DEFAULT 0,
  status VARCHAR(16) DEFAULT 'Pending',
  deleted BIT DEFAULT 0,
  PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS tbl_rsvp_plus (
  id INT AUTO_INCREMENT NOT NULL,
  rsvp_request_id INT NOT NULL,
  first_name VARCHAR(32) NOT NULL,
  last_name VARCHAR(32) NOT NULL,
  deleted BIT DEFAULT 0,
  PRIMARY KEY (id)
);
