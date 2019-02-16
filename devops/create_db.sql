
CREATE USER 'monitor'@'%' IDENTIFIED BY '4M3wBNCKyE4R4xTYSDVcebJKdg9kk9rw';
CREATE DATABASE IF NOT EXISTS `monitor`;
GRANT ALL PRIVILEGES ON `monitor`.* TO 'monitor'@'%';
GRANT ALL PRIVILEGES ON `monitor\_%`.* TO 'monitor'@'%';
flush privileges;
--
-- CREATE USER 'testauth'@'%' IDENTIFIED BY '4M3wBNCKyE4R4xTYSDVcebJKdg9kk9rw';
-- CREATE DATABASE IF NOT EXISTS `testauth`;
-- GRANT ALL PRIVILEGES ON `testauth`.* TO 'testauth'@'%';
-- GRANT ALL PRIVILEGES ON `testauth\_%`.* TO 'testauth'@'%';
-- flush privileges;
