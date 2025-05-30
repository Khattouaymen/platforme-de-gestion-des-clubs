<?php
require_once APP_PATH . '/core/Model.php';

/**
 * Modèle pour la gestion des messages de contact
 */
class ContactModel extends Model {
    
    /**
     * Enregistre un nouveau message de contact
     * 
     * @param array $data Données du message
     * @return bool True si succès, false sinon
     */
    public function createMessage($data) {
        try {
            $sql = "INSERT INTO contact_messages (nom, email, sujet, message, date_creation, statut) 
                    VALUES (:nom, :email, :sujet, :message, NOW(), 'non_lu')";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':nom' => $data['nom'],
                ':email' => $data['email'],
                ':sujet' => $data['sujet'],
                ':message' => $data['message']
            ]);
        } catch (Exception $e) {
            error_log("Erreur lors de l'enregistrement du message de contact: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère tous les messages de contact
     * 
     * @return array Liste des messages
     */
    public function getAllMessages() {
        try {
            $sql = "SELECT * FROM contact_messages ORDER BY date_creation DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des messages: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Récupère un message par son ID
     * 
     * @param int $id ID du message
     * @return array|false Message ou false si non trouvé
     */
    public function getMessageById($id) {
        try {
            $sql = "SELECT * FROM contact_messages WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération du message: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Marque un message comme lu
     * 
     * @param int $id ID du message
     * @return bool True si succès, false sinon
     */
    public function markAsRead($id) {
        try {
            $sql = "UPDATE contact_messages SET statut = 'lu', date_lecture = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            error_log("Erreur lors du marquage du message comme lu: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Marque un message comme traité
     * 
     * @param int $id ID du message
     * @return bool True si succès, false sinon
     */
    public function markAsProcessed($id) {
        try {
            $sql = "UPDATE contact_messages SET statut = 'traite', date_traitement = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            error_log("Erreur lors du marquage du message comme traité: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Supprime un message
     * 
     * @param int $id ID du message
     * @return bool True si succès, false sinon
     */
    public function deleteMessage($id) {
        try {
            $sql = "DELETE FROM contact_messages WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression du message: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Compte les messages non lus
     * 
     * @return int Nombre de messages non lus
     */
    public function getUnreadCount() {
        try {
            $sql = "SELECT COUNT(*) as count FROM contact_messages WHERE statut = 'non_lu'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (Exception $e) {
            error_log("Erreur lors du comptage des messages non lus: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Récupère les statistiques des messages
     * 
     * @return array Statistiques
     */
    public function getStatistics() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN statut = 'non_lu' THEN 1 ELSE 0 END) as non_lu,
                        SUM(CASE WHEN statut = 'lu' THEN 1 ELSE 0 END) as lu,
                        SUM(CASE WHEN statut = 'traite' THEN 1 ELSE 0 END) as traite
                    FROM contact_messages";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur lors de la récupération des statistiques: " . $e->getMessage());
            return [
                'total' => 0,
                'non_lu' => 0,
                'lu' => 0,
                'traite' => 0
            ];
        }
    }
}
?>
