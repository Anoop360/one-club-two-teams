

CREATE TABLE IF NOT EXISTS admins (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS players (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sport ENUM('cricket','football') NOT NULL,
  full_name VARCHAR(120) NOT NULL,
  jersey_number SMALLINT UNSIGNED NULL,
  role VARCHAR(80) NOT NULL DEFAULT '',
  phone VARCHAR(30) NULL,
  bio TEXT NULL,
  photo VARCHAR(255) NULL,
  status ENUM('active','inactive') NOT NULL DEFAULT 'active',
  matches_played INT UNSIGNED NOT NULL DEFAULT 0,
  runs_scored INT UNSIGNED NOT NULL DEFAULT 0,
  wickets_taken INT UNSIGNED NOT NULL DEFAULT 0,
  goals_scored INT UNSIGNED NOT NULL DEFAULT 0,
  assists INT UNSIGNED NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_players_sport_status (sport, status)
);

CREATE TABLE IF NOT EXISTS officials (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sport ENUM('club','cricket','football') NOT NULL DEFAULT 'club',
  full_name VARCHAR(120) NOT NULL,
  position VARCHAR(100) NOT NULL,
  phone VARCHAR(30) NULL,
  photo VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS matches (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  sport ENUM('cricket','football') NOT NULL,
  opponent VARCHAR(120) NOT NULL,
  venue VARCHAR(180) NULL,
  match_date DATETIME NOT NULL,
  competition VARCHAR(120) NULL,
  our_score VARCHAR(50) NULL,
  opponent_score VARCHAR(50) NULL,
  result VARCHAR(120) NULL,
  status ENUM('upcoming','completed') NOT NULL DEFAULT 'upcoming',
  notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_matches_date (match_date)
);

CREATE TABLE IF NOT EXISTS announcements (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  body TEXT NOT NULL,
  sport ENUM('club','cricket','football') NOT NULL DEFAULT 'club',
  published TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS gallery (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  image_path VARCHAR(255) NOT NULL,
  sport ENUM('club','cricket','football') NOT NULL DEFAULT 'club',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
