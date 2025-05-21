-- Ajout du champ Poster_URL à la table demandeactivite
ALTER TABLE `demandeactivite` ADD `Poster_URL` VARCHAR(255) NULL AFTER `date_creation`;

-- Le champ nombre_max existe déjà mais n'est pas utilisé, nous allons nous assurer qu'il est correctement configuré
ALTER TABLE `demandeactivite` MODIFY COLUMN `nombre_max` INT DEFAULT NULL COMMENT 'Nombre maximum de participants';