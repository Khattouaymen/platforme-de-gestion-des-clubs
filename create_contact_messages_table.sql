-- Script pour créer la table des messages de contact
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    sujet ENUM('information', 'adhesion', 'activite', 'technique', 'suggestion', 'autre') NOT NULL,
    message TEXT NOT NULL,
    statut ENUM('non_lu', 'lu', 'traite') DEFAULT 'non_lu',
    date_creation DATETIME NOT NULL,
    date_lecture DATETIME NULL,
    date_traitement DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Index pour améliorer les performances
CREATE INDEX idx_contact_statut ON contact_messages(statut);
CREATE INDEX idx_contact_date ON contact_messages(date_creation);
CREATE INDEX idx_contact_sujet ON contact_messages(sujet);
