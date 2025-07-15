<?php
/**
 * Classe d'administration pour le système de vote
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

class VotingSystem_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'init_admin'));
        add_action('wp_ajax_voting_admin_action', array($this, 'handle_admin_ajax'));
    }
    
    /**
     * Ajouter le menu d'administration
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Votes & Awards', 'voting-system'),
            __('Votes & Awards', 'voting-system'),
            'manage_options',
            'voting-system',
            array($this, 'admin_dashboard'),
            'dashicons-chart-bar',
            30
        );
        
        add_submenu_page(
            'voting-system',
            __('Tableau de bord', 'voting-system'),
            __('Tableau de bord', 'voting-system'),
            'manage_options',
            'voting-system',
            array($this, 'admin_dashboard')
        );
        
        add_submenu_page(
            'voting-system',
            __('Catégories', 'voting-system'),
            __('Catégories', 'voting-system'),
            'manage_options',
            'voting-categories',
            array($this, 'admin_categories')
        );
        
        add_submenu_page(
            'voting-system',
            __('Candidats', 'voting-system'),
            __('Candidats', 'voting-system'),
            'manage_options',
            'voting-candidates',
            array($this, 'admin_candidates')
        );
        
        add_submenu_page(
            'voting-system',
            __('Résultats', 'voting-system'),
            __('Résultats', 'voting-system'),
            'manage_options',
            'voting-results',
            array($this, 'admin_results')
        );
        
        add_submenu_page(
            'voting-system',
            __('Paramètres', 'voting-system'),
            __('Paramètres', 'voting-system'),
            'manage_options',
            'voting-settings',
            array($this, 'admin_settings')
        );
    }
    
    /**
     * Initialiser l'administration
     */
    public function init_admin() {
        // Gérer les actions POST
        if (isset($_POST['voting_action'])) {
            $this->handle_form_submission();
        }
    }
    
    /**
     * Tableau de bord principal
     */
    public function admin_dashboard() {
        $database = new VotingSystem_Database();
        $stats = $database->get_voting_stats();
        $recent_votes = $database->get_vote_history(10);
        
        include VOTING_SYSTEM_PLUGIN_PATH . 'admin/views/dashboard.php';
    }
    
    /**
     * Gestion des catégories
     */
    public function admin_categories() {
        $database = new VotingSystem_Database();
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'add':
            case 'edit':
                $category_id = $_GET['id'] ?? 0;
                $category = $category_id ? $database->get_category($category_id) : null;
                include VOTING_SYSTEM_PLUGIN_PATH . 'admin/views/category-form.php';
                break;
                
            case 'delete':
                $category_id = $_GET['id'] ?? 0;
                if ($category_id && wp_verify_nonce($_GET['_wpnonce'], 'delete_category_' . $category_id)) {
                    $database->delete_category($category_id);
                    wp_redirect(admin_url('admin.php?page=voting-categories&deleted=1'));
                    exit;
                }
                break;
                
            default:
                $categories = $database->get_categories();
                include VOTING_SYSTEM_PLUGIN_PATH . 'admin/views/categories-list.php';
                break;
        }
    }
    
    /**
     * Gestion des candidats
     */
    public function admin_candidates() {
        $database = new VotingSystem_Database();
        $action = $_GET['action'] ?? 'list';
        
        switch ($action) {
            case 'add':
            case 'edit':
                $candidate_id = $_GET['id'] ?? 0;
                $candidate = $candidate_id ? $database->get_candidate($candidate_id) : null;
                $categories = $database->get_categories();
                include VOTING_SYSTEM_PLUGIN_PATH . 'admin/views/candidate-form.php';
                break;
                
            case 'delete':
                $candidate_id = $_GET['id'] ?? 0;
                if ($candidate_id && wp_verify_nonce($_GET['_wpnonce'], 'delete_candidate_' . $candidate_id)) {
                    $database->delete_candidate($candidate_id);
                    wp_redirect(admin_url('admin.php?page=voting-candidates&deleted=1'));
                    exit;
                }
                break;
                
            default:
                $candidates = $this->get_all_candidates_with_categories();
                include VOTING_SYSTEM_PLUGIN_PATH . 'admin/views/candidates-list.php';
                break;
        }
    }
    
    /**
     * Gestion des résultats
     */
    public function admin_results() {
        $database = new VotingSystem_Database();
        $categories = $database->get_categories();
        $selected_category = $_GET['category'] ?? '';
        
        if (!empty($selected_category)) {
            $category = $database->get_category($selected_category);
            $results = $database->get_candidates_with_votes($selected_category);
        } else {
            $category = null;
            $results = array();
        }
        
        include VOTING_SYSTEM_PLUGIN_PATH . 'admin/views/results.php';
    }
    
    /**
     * Paramètres du système
     */
    public function admin_settings() {
        if (isset($_POST['save_settings'])) {
            $this->save_settings();
        }
        
        $settings = get_option('voting_system_settings', array());
        include VOTING_SYSTEM_PLUGIN_PATH . 'admin/views/settings.php';
    }
    
    /**
     * Gérer les soumissions de formulaires
     */
    private function handle_form_submission() {
        $action = $_POST['voting_action'];
        $database = new VotingSystem_Database();
        
        switch ($action) {
            case 'save_category':
                $this->save_category($database);
                break;
                
            case 'save_candidate':
                $this->save_candidate($database);
                break;
                
            case 'export_results':
                $this->export_results($database);
                break;
        }
    }
    
    /**
     * Sauvegarder une catégorie
     */
    private function save_category($database) {
        if (!wp_verify_nonce($_POST['_wpnonce'], 'voting_category_nonce')) {
            wp_die(__('Erreur de sécurité.', 'voting-system'));
        }
        
        $data = array(
            'name' => sanitize_text_field($_POST['name']),
            'slug' => sanitize_title($_POST['slug']),
            'description' => sanitize_textarea_field($_POST['description']),
            'image' => esc_url_raw($_POST['image']),
            'start_date' => sanitize_text_field($_POST['start_date']),
            'end_date' => sanitize_text_field($_POST['end_date']),
            'status' => sanitize_text_field($_POST['status']),
            'max_votes_per_user' => intval($_POST['max_votes_per_user'])
        );
        
        $category_id = $_POST['category_id'] ?? 0;
        
        if ($category_id) {
            $result = $database->update_category($category_id, $data);
            $message = 'updated';
        } else {
            $result = $database->create_category($data);
            $message = 'created';
        }
        
        if ($result) {
            wp_redirect(admin_url("admin.php?page=voting-categories&{$message}=1"));
        } else {
            wp_redirect(admin_url('admin.php?page=voting-categories&error=1'));
        }
        exit;
    }
    
    /**
     * Sauvegarder un candidat
     */
    private function save_candidate($database) {
        if (!wp_verify_nonce($_POST['_wpnonce'], 'voting_candidate_nonce')) {
            wp_die(__('Erreur de sécurité.', 'voting-system'));
        }
        
        $data = array(
            'name' => sanitize_text_field($_POST['name']),
            'photo' => esc_url_raw($_POST['photo']),
            'biography' => sanitize_textarea_field($_POST['biography']),
            'category_id' => intval($_POST['category_id']),
            'gallery' => sanitize_textarea_field($_POST['gallery']),
            'status' => sanitize_text_field($_POST['status']),
            'display_order' => intval($_POST['display_order'])
        );
        
        $candidate_id = $_POST['candidate_id'] ?? 0;
        
        if ($candidate_id) {
            $result = $database->update_candidate($candidate_id, $data);
            $message = 'updated';
        } else {
            $result = $database->create_candidate($data);
            $message = 'created';
        }
        
        if ($result) {
            wp_redirect(admin_url("admin.php?page=voting-candidates&{$message}=1"));
        } else {
            wp_redirect(admin_url('admin.php?page=voting-candidates&error=1'));
        }
        exit;
    }
    
    /**
     * Exporter les résultats
     */
    private function export_results($database) {
        if (!wp_verify_nonce($_POST['_wpnonce'], 'voting_export_nonce')) {
            wp_die(__('Erreur de sécurité.', 'voting-system'));
        }
        
        $category_id = $_POST['category_id'] ?? null;
        $database->export_results_csv($category_id);
    }
    
    /**
     * Sauvegarder les paramètres
     */
    private function save_settings() {
        if (!wp_verify_nonce($_POST['_wpnonce'], 'voting_settings_nonce')) {
            wp_die(__('Erreur de sécurité.', 'voting-system'));
        }
        
        $settings = array(
            'require_login' => isset($_POST['require_login']),
            'allow_multiple_votes' => isset($_POST['allow_multiple_votes']),
            'show_vote_counts' => isset($_POST['show_vote_counts']),
            'email_notifications' => isset($_POST['email_notifications']),
            'admin_email' => sanitize_email($_POST['admin_email']),
            'vote_confirmation_text' => sanitize_text_field($_POST['vote_confirmation_text']),
            'login_required_text' => sanitize_text_field($_POST['login_required_text'])
        );
        
        update_option('voting_system_settings', $settings);
        
        wp_redirect(admin_url('admin.php?page=voting-settings&updated=1'));
        exit;
    }
    
    /**
     * Obtenir tous les candidats avec leurs catégories
     */
    private function get_all_candidates_with_categories() {
        global $wpdb;
        $database = new VotingSystem_Database();
        
        return $wpdb->get_results(
            "SELECT c.*, cat.name as category_name 
             FROM {$database->candidates_table} c
             LEFT JOIN {$database->categories_table} cat ON c.category_id = cat.id
             ORDER BY c.display_order ASC, c.name ASC"
        );
    }
    
    /**
     * Gérer les actions AJAX admin
     */
    public function handle_admin_ajax() {
        if (!current_user_can('manage_options')) {
            wp_die(__('Accès refusé.', 'voting-system'));
        }
        
        $action = $_POST['admin_action'] ?? '';
        $database = new VotingSystem_Database();
        
        switch ($action) {
            case 'get_candidate_details':
                $candidate_id = intval($_POST['candidate_id']);
                $candidate = $database->get_candidate($candidate_id);
                wp_send_json_success($candidate);
                break;
                
            case 'update_candidate_order':
                $candidates = $_POST['candidates'] ?? array();
                foreach ($candidates as $order => $candidate_id) {
                    $database->update_candidate($candidate_id, array('display_order' => $order));
                }
                wp_send_json_success();
                break;
                
            case 'get_voting_stats':
                $category_id = $_POST['category_id'] ?? null;
                $stats = $database->get_voting_stats($category_id);
                wp_send_json_success($stats);
                break;
        }
        
        wp_send_json_error(__('Action non reconnue.', 'voting-system'));
    }
    
    /**
     * Afficher les notifications admin
     */
    public static function admin_notices() {
        if (isset($_GET['created'])) {
            echo '<div class="notice notice-success"><p>' . __('Élément créé avec succès.', 'voting-system') . '</p></div>';
        }
        
        if (isset($_GET['updated'])) {
            echo '<div class="notice notice-success"><p>' . __('Élément mis à jour avec succès.', 'voting-system') . '</p></div>';
        }
        
        if (isset($_GET['deleted'])) {
            echo '<div class="notice notice-success"><p>' . __('Élément supprimé avec succès.', 'voting-system') . '</p></div>';
        }
        
        if (isset($_GET['error'])) {
            echo '<div class="notice notice-error"><p>' . __('Une erreur est survenue.', 'voting-system') . '</p></div>';
        }
    }
}

// Ajouter les notifications admin
add_action('admin_notices', array('VotingSystem_Admin', 'admin_notices')); 