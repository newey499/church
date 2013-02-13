-- ================================================
-- CDN 13/10/10
--
-- Create the mysql user the website expects
-- ================================================

CREATE USER 'bobiles_apache'@'localhost' IDENTIFIED BY 'charlton';

GRANT CREATE, CREATE_TMP_TABLE, DROP, INSERT, UPDATE, DELETE, SELECT PRIVILEGES 
	ON *.* TO 'bobiles_apache'@'localhost';

CREATE USER 'bobiles_apache'@'%' IDENTIFIED BY 'charlton';

GRANT CREATE, CREATE_TMP_TABLE, DROP, INSERT, UPDATE, DELETE, SELECT PRIVILEGES 
	ON *.* TO 'bobiles_apache'@'%';
