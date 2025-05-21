<?php
require_once APP_PATH . '/core/Model.php';

class BlogModel extends Model {

    // Assurez-vous que le nom de la table correspond à votre base de données
    // protected $table = 'blog'; 

    public function __construct() {
        parent::__construct();
    }

    /**
     * Crée un nouvel article de blog.
     *
     * @param array $data Données de l'article (titre, contenu, club_id, user_id, image_url, visibility)
     * @return int|false ID de l'article inséré ou false en cas d'échec.
     */
    public function createBlogArticle($data) {
        $sql = "INSERT INTO blog (club_id, titre, contenu, image_url, visibility, date_creation, user_id) 
                VALUES (:club_id, :titre, :contenu, :image_url, :visibility, NOW(), :user_id)";
        
        $params = [
            ':club_id' => $data['club_id'], 
            ':titre' => $data['titre'],
            ':contenu' => $data['contenu'],
            ':image_url' => $data['image_url'] ?? null,
            ':visibility' => $data['visibility'], 
            ':user_id' => $data['user_id'] 
        ];

        if ($this->execute($sql, $params)) {
            return $this->lastInsertId();
        }
        return false;
    }

    /**
     * Récupère un article de blog par son ID.
     *
     * @param int $articleId ID de l'article.
     * @return array|false Les données de l'article ou false si non trouvé.
     */
    public function getBlogArticleById($articleId) {
        $sql = "SELECT b.*, c.nom as nom_club 
                FROM blog b
                LEFT JOIN club c ON b.club_id = c.id
                WHERE b.id = :article_id";
        return $this->single($sql, [':article_id' => $articleId]);
    }

    /**
     * Récupère les articles de blog visibles par un étudiant.
     * Inclut les articles publics et les articles des clubs dont l'étudiant est membre.
     *
     * @param array $etudiantClubIds Tableau des IDs des clubs dont l'étudiant est membre.
     * @return array Liste des articles.
     */
    public function getVisibleBlogArticlesForEtudiant($etudiantClubIds = []) {
        $params = [];
        $clubSpecificCondition = '';

        if (!empty($etudiantClubIds)) {
            $placeholders = [];
            foreach ($etudiantClubIds as $key => $clubId) {
                $paramName = ':club_id' . $key;
                $placeholders[] = $paramName;
                $params[$paramName] = $clubId;
            }
            $clubSpecificCondition = "OR (b.visibility = 'club' AND b.club_id IN (" . implode(',', $placeholders) . "))";
        }

        $sql = "SELECT b.*, cl.nom as nom_club 
                FROM blog b
                LEFT JOIN club cl ON b.club_id = cl.id 
                WHERE b.visibility = 'public' 
                {$clubSpecificCondition}
                ORDER BY b.date_creation DESC";

        return $this->multiple($sql, $params);
    }
    
    /**
     * Récupère tous les articles de blog (publics et spécifiques au club) pour un club donné.
     * Utilisé par exemple par le responsable de club pour voir tous les articles qu'il a postés ou qui concernent son club.
     * Ou pour un admin qui veut voir tous les articles d'un club.
     *
     * @param int $clubId ID du club.
     * @return array Liste des articles.
     */
    public function getAllBlogArticlesForClub($clubId) {
        $sql = "SELECT b.*, cl.nom as nom_club
                FROM blog b
                LEFT JOIN club cl ON b.club_id = cl.id
                WHERE b.club_id = :club_id OR b.visibility = 'public'
                ORDER BY b.date_creation DESC";
        return $this->multiple($sql, [':club_id' => $clubId]);
    }

    /**
     * Récupère tous les articles de blog postés par un utilisateur spécifique (responsable).
     * Utile pour que le responsable voie uniquement ses propres publications.
     *
     * @param int $userId ID de l'utilisateur (responsable).
     * @return array Liste des articles.
     */
    public function getBlogArticlesByUserId($userId) {
        $sql = "SELECT b.*, cl.nom as nom_club
                FROM blog b
                LEFT JOIN club cl ON b.club_id = cl.id
                WHERE b.user_id = :user_id
                ORDER BY b.date_creation DESC";
        return $this->multiple($sql, [':user_id' => $userId]);
    }


    /**
     * Met à jour un article de blog.
     *
     * @param int $articleId ID de l'article à mettre à jour.
     * @param array $data Données à mettre à jour.
     * @return bool True si succès, false sinon.
     */
    public function updateBlogArticle($articleId, $data) {
        $setClauses = [];
        $params = [':article_id' => $articleId];
        
        $allowedFields = ['titre', 'contenu', 'image_url', 'visibility', 'club_id'];

        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $setClauses[] = "{$field} = :{$field}";
                $params[":{$field}"] = $data[$field];
            }
        }

        if (empty($setClauses)) {
            return false; 
        }

        $sql = "UPDATE blog SET " . implode(', ', $setClauses) . " WHERE id = :article_id";
        
        return $this->execute($sql, $params);
    }

    /**
     * Supprime un article de blog.
     *
     * @param int $articleId ID de l'article à supprimer.
     * @return bool True si succès, false sinon.
     */
    public function deleteBlogArticle($articleId) {
        $sql = "DELETE FROM blog WHERE id = :article_id";
        return $this->execute($sql, [':article_id' => $articleId]);
    }
}
?>
