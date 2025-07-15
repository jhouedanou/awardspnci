<?php
/**
 * Classe de gestion de la base de données pour le système de vote
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

class VotingSystem_Database {
    
    private $wpdb;
    private $categories_table;
    private $candidates_table;
    private $votes_table;
    
    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->categories_table = $wpdb->prefix . 'vote_categories';
        $this->candidates_table = $wpdb->prefix . 'vote_candidates';
        $this->votes_table = $wpdb->prefix . 'vote_votes';
    }
    
    /**
     * Créer les tables de la base de données
     */
    public function create_tables() {
        $charset_collate = $this->wpdb->get_charset_collate();
        
        // Table des catégories
        $sql_categories = "CREATE TABLE {$this->categories_table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description TEXT,
            image VARCHAR(255),
            start_date DATETIME,
            end_date DATETIME,
            status ENUM('active', 'inactive') DEFAULT 'active',
            max_votes_per_user INT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
        
        // Table des candidats
        $sql_candidates = "CREATE TABLE {$this->candidates_table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            photo VARCHAR(255) NOT NULL,
            biography TEXT,
            category_id INT,
            gallery TEXT,
            status ENUM('active', 'inactive') DEFAULT 'active',
            display_order INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES {$this->categories_table}(id) ON DELETE CASCADE
        ) $charset_collate;";
        
        // Table des votes
        $sql_votes = "CREATE TABLE {$this->votes_table} (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            candidate_id INT NOT NULL,
            category_id INT NOT NULL,
            ip_address VARCHAR(45),
            user_agent TEXT,
            voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES {$this->wpdb->users}(ID) ON DELETE CASCADE,
            FOREIGN KEY (candidate_id) REFERENCES {$this->candidates_table}(id) ON DELETE CASCADE,
            FOREIGN KEY (category_id) REFERENCES {$this->categories_table}(id) ON DELETE CASCADE,
            UNIQUE KEY unique_vote (user_id, category_id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_categories);
        dbDelta($sql_candidates);
        dbDelta($sql_votes);
    }
    
    /**
     * Obtenir toutes les catégories
     */
    public function get_categories() {
        return $this->wpdb->get_results(
            "SELECT * FROM {$this->categories_table} ORDER BY display_order ASC, name ASC"
        );
    }
    
    /**
     * Obtenir une catégorie par ID
     */
    public function get_category($id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->categories_table} WHERE id = %d",
                $id
            )
        );
    }
    
    /**
     * Obtenir une catégorie par slug
     */
    public function get_category_by_slug($slug) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->categories_table} WHERE slug = %s",
                $slug
            )
        );
    }
    
    /**
     * Créer une nouvelle catégorie
     */
    public function create_category($data) {
        $defaults = array(
            'name' => '',
            'slug' => '',
            'description' => '',
            'image' => '',
            'start_date' => '',
            'end_date' => '',
            'status' => 'active',
            'max_votes_per_user' => 1
        );
        
        $data = wp_parse_args($data, $defaults);
        
        // Générer le slug si non fourni
        if (empty($data['slug'])) {
            $data['slug'] = sanitize_title($data['name']);
        }
        
        $result = $this->wpdb->insert(
            $this->categories_table,
            array(
                'name' => sanitize_text_field($data['name']),
                'slug' => sanitize_title($data['slug']),
                'description' => sanitize_textarea_field($data['description']),
                'image' => esc_url_raw($data['image']),
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => $data['status'],
                'max_votes_per_user' => intval($data['max_votes_per_user'])
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d')
        );
        
        return $result ? $this->wpdb->insert_id : false;
    }
    
    /**
     * Mettre à jour une catégorie
     */
    public function update_category($id, $data) {
        return $this->wpdb->update(
            $this->categories_table,
            array(
                'name' => sanitize_text_field($data['name']),
                'slug' => sanitize_title($data['slug']),
                'description' => sanitize_textarea_field($data['description']),
                'image' => esc_url_raw($data['image']),
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'status' => $data['status'],
                'max_votes_per_user' => intval($data['max_votes_per_user'])
            ),
            array('id' => $id),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d'),
            array('%d')
        );
    }
    
    /**
     * Supprimer une catégorie
     */
    public function delete_category($id) {
        return $this->wpdb->delete(
            $this->categories_table,
            array('id' => $id),
            array('%d')
        );
    }
    
    /**
     * Obtenir les candidats d'une catégorie
     */
    public function get_candidates_by_category($category_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->candidates_table} WHERE category_id = %d AND status = 'active' ORDER BY display_order ASC, name ASC",
                $category_id
            )
        );
    }
    
    /**
     * Obtenir un candidat par ID
     */
    public function get_candidate($id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->candidates_table} WHERE id = %d",
                $id
            )
        );
    }
    
    /**
     * Créer un nouveau candidat
     */
    public function create_candidate($data) {
        $defaults = array(
            'name' => '',
            'photo' => '',
            'biography' => '',
            'category_id' => 0,
            'gallery' => '',
            'status' => 'active',
            'display_order' => 0
        );
        
        $data = wp_parse_args($data, $defaults);
        
        $result = $this->wpdb->insert(
            $this->candidates_table,
            array(
                'name' => sanitize_text_field($data['name']),
                'photo' => esc_url_raw($data['photo']),
                'biography' => sanitize_textarea_field($data['biography']),
                'category_id' => intval($data['category_id']),
                'gallery' => $data['gallery'],
                'status' => $data['status'],
                'display_order' => intval($data['display_order'])
            ),
            array('%s', '%s', '%s', '%d', '%s', '%s', '%d')
        );
        
        return $result ? $this->wpdb->insert_id : false;
    }
    
    /**
     * Mettre à jour un candidat
     */
    public function update_candidate($id, $data) {
        return $this->wpdb->update(
            $this->candidates_table,
            array(
                'name' => sanitize_text_field($data['name']),
                'photo' => esc_url_raw($data['photo']),
                'biography' => sanitize_textarea_field($data['biography']),
                'category_id' => intval($data['category_id']),
                'gallery' => $data['gallery'],
                'status' => $data['status'],
                'display_order' => intval($data['display_order'])
            ),
            array('id' => $id),
            array('%s', '%s', '%s', '%d', '%s', '%s', '%d'),
            array('%d')
        );
    }
    
    /**
     * Supprimer un candidat
     */
    public function delete_candidate($id) {
        return $this->wpdb->delete(
            $this->candidates_table,
            array('id' => $id),
            array('%d')
        );
    }
    
    /**
     * Vérifier si un utilisateur a déjà voté dans une catégorie
     */
    public function user_has_voted($user_id, $category_id) {
        $vote = $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->votes_table} WHERE user_id = %d AND category_id = %d",
                $user_id,
                $category_id
            )
        );
        
        return !empty($vote);
    }
    
    /**
     * Obtenir le vote d'un utilisateur dans une catégorie
     */
    public function get_user_vote($user_id, $category_id) {
        return $this->wpdb->get_row(
            $this->wpdb->prepare(
                "SELECT * FROM {$this->votes_table} WHERE user_id = %d AND category_id = %d",
                $user_id,
                $category_id
            )
        );
    }
    
    /**
     * Enregistrer un vote
     */
    public function cast_vote($user_id, $candidate_id, $category_id) {
        // Vérifier si l'utilisateur a déjà voté dans cette catégorie
        if ($this->user_has_voted($user_id, $category_id)) {
            return false;
        }
        
        // Vérifier que le candidat appartient à la catégorie
        $candidate = $this->get_candidate($candidate_id);
        if (!$candidate || $candidate->category_id != $category_id) {
            return false;
        }
        
        $result = $this->wpdb->insert(
            $this->votes_table,
            array(
                'user_id' => $user_id,
                'candidate_id' => $candidate_id,
                'category_id' => $category_id,
                'ip_address' => $this->get_client_ip(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ),
            array('%d', '%d', '%d', '%s', '%s')
        );
        
        return $result ? $this->wpdb->insert_id : false;
    }
    
    /**
     * Supprimer un vote
     */
    public function remove_vote($user_id, $category_id) {
        return $this->wpdb->delete(
            $this->votes_table,
            array(
                'user_id' => $user_id,
                'category_id' => $category_id
            ),
            array('%d', '%d')
        );
    }
    
    /**
     * Obtenir le nombre de votes pour un candidat
     */
    public function get_vote_count($candidate_id) {
        return $this->wpdb->get_var(
            $this->wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->votes_table} WHERE candidate_id = %d",
                $candidate_id
            )
        );
    }
    
    /**
     * Obtenir les candidats avec leurs votes pour une catégorie
     */
    public function get_candidates_with_votes($category_id) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT c.*, 
                        COUNT(v.id) as vote_count,
                        (SELECT COUNT(*) FROM {$this->votes_table} WHERE category_id = %d) as total_votes
                 FROM {$this->candidates_table} c
                 LEFT JOIN {$this->votes_table} v ON c.id = v.candidate_id
                 WHERE c.category_id = %d AND c.status = 'active'
                 GROUP BY c.id
                 ORDER BY vote_count DESC, c.display_order ASC, c.name ASC",
                $category_id,
                $category_id
            )
        );
    }
    
    /**
     * Obtenir les statistiques de vote
     */
    public function get_voting_stats($category_id = null) {
        $where_clause = '';
        if ($category_id) {
            $where_clause = $this->wpdb->prepare("WHERE category_id = %d", $category_id);
        }
        
        return $this->wpdb->get_results(
            "SELECT 
                c.name as category_name,
                c.id as category_id,
                COUNT(DISTINCT v.user_id) as total_voters,
                COUNT(v.id) as total_votes,
                COUNT(DISTINCT cand.id) as total_candidates
             FROM {$this->categories_table} c
             LEFT JOIN {$this->candidates_table} cand ON c.id = cand.category_id
             LEFT JOIN {$this->votes_table} v ON c.id = v.category_id
             {$where_clause}
             GROUP BY c.id
             ORDER BY c.name ASC"
        );
    }
    
    /**
     * Obtenir l'historique des votes
     */
    public function get_vote_history($limit = 50) {
        return $this->wpdb->get_results(
            $this->wpdb->prepare(
                "SELECT v.*, 
                        c.name as candidate_name,
                        cat.name as category_name,
                        u.display_name as user_name
                 FROM {$this->votes_table} v
                 JOIN {$this->candidates_table} c ON v.candidate_id = c.id
                 JOIN {$this->categories_table} cat ON v.category_id = cat.id
                 JOIN {$this->wpdb->users} u ON v.user_id = u.ID
                 ORDER BY v.voted_at DESC
                 LIMIT %d",
                $limit
            )
        );
    }
    
    /**
     * Obtenir l'adresse IP du client
     */
    private function get_client_ip() {
        $ip_keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR');
        
        foreach ($ip_keys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }
    
    /**
     * Nettoyer les anciens votes (maintenance)
     */
    public function cleanup_old_votes($days = 30) {
        $date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        return $this->wpdb->query(
            $this->wpdb->prepare(
                "DELETE FROM {$this->votes_table} WHERE voted_at < %s",
                $date
            )
        );
    }
    
    /**
     * Exporter les résultats en CSV
     */
    public function export_results_csv($category_id = null) {
        $candidates = $this->get_candidates_with_votes($category_id);
        
        $filename = 'voting_results_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // En-têtes
        fputcsv($output, array('Position', 'Nom', 'Catégorie', 'Votes', 'Pourcentage'));
        
        // Données
        foreach ($candidates as $index => $candidate) {
            $position = $index + 1;
            $percentage = $candidate->total_votes > 0 ? round(($candidate->vote_count / $candidate->total_votes) * 100, 1) : 0;
            
            fputcsv($output, array(
                $position,
                $candidate->name,
                $candidate->category_name ?? '',
                $candidate->vote_count,
                $percentage . '%'
            ));
        }
        
        fclose($output);
        exit;
    }
} 