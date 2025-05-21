ALTER TABLE etudiant
ADD COLUMN filiere VARCHAR(255) DEFAULT NULL,
ADD COLUMN niveau VARCHAR(255) DEFAULT NULL,
ADD COLUMN numero_etudiant VARCHAR(255) DEFAULT NULL;

-- Optionally, you might want to make them NOT NULL if they are mandatory from the start
-- ALTER TABLE etudiant
-- MODIFY COLUMN filiere VARCHAR(255) NOT NULL,
-- MODIFY COLUMN niveau VARCHAR(255) NOT NULL,
-- MODIFY COLUMN numero_etudiant VARCHAR(255) NOT NULL UNIQUE; -- Assuming student ID should be unique
