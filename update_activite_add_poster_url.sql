-- Ajout du champ Poster_URL à la table activite
ALTER TABLE `activite` ADD `Poster_URL` VARCHAR(255) NULL AFTER `responsable_notifie`;

-- Ajout du champ nombre_max à la table activite s'il n'existe pas déjà
ALTER TABLE `activite` ADD COLUMN IF NOT EXISTS `nombre_max` INT DEFAULT NULL COMMENT 'Nombre maximum de participants';
