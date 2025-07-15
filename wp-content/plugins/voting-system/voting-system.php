<?php
/**
 * Plugin Name: Système de Vote - Awards & Concours
 * Description: Système complet de vote pour awards et concours photo avec gestion backend et frontend
 * Version: 1.0.0
 * Author: Jean Luc Houedanou
 * Author URI: mailto:jeanluc@houedanou.com
 * Text Domain: voting-system
 * Domain Path: /languages
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Définition des constantes
define('VOTING_SYSTEM_VERSION', '1.0.0');
define('VOTING_SYSTEM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VOTING_SYSTEM_PLUGIN_PATH', plugin_dir_path(__FILE__));

/**
 * Classe principale du plugin
 */
class VotingSystem {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialisation du plugin
     */
    public function init() {
        // Charger les classes
        require_once VOTING_SYSTEM_PLUGIN_PATH . 'includes/class-voting-admin.php';
        require_once VOTING_SYSTEM_PLUGIN_PATH . 'includes/class-voting-frontend.php';
        require_once VOTING_SYSTEM_PLUGIN_PATH . 'includes/class-voting-database.php';
        require_once VOTING_SYSTEM_PLUGIN_PATH . 'includes/class-voting-ajax.php';
        
        // Initialiser les classes
        new VotingSystem_Admin();
        new VotingSystem_Frontend();
        new VotingSystem_Ajax();
        
        // Enregistrer les shortcodes
        add_shortcode('voting_system', array($this, 'voting_system_shortcode'));
        add_shortcode('voting_results', array($this, 'voting_results_shortcode'));
        
        // Enregistrer les scripts et styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
    }
    
    /**
     * Charger les traductions
     */
    public function load_textdomain() {
        load_plugin_textdomain('voting-system', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Activation du plugin
     */
    public function activate() {
        $database = new VotingSystem_Database();
        $database->create_tables();
        
        // Créer les pages par défaut
        $this->create_default_pages();
        
        // Flush les règles de réécriture
        flush_rewrite_rules();
    }
    
    /**
     * Désactivation du plugin
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    /**
     * Créer les pages par défaut
     */
    private function create_default_pages() {
        // Page de vote
        if (!get_page_by_path('vote')) {
            wp_insert_post(array(
                'post_title' => __('Vote', 'voting-system'),
                'post_content' => '[voting_system]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => 'vote'
            ));
        }
        
        // Page des résultats
        if (!get_page_by_path('resultats-vote')) {
            wp_insert_post(array(
                'post_title' => __('Résultats du Vote', 'voting-system'),
                'post_content' => '[voting_results]',
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => 'resultats-vote'
            ));
        }
    }
    
    /**
     * Shortcode principal du système de vote
     */
    public function voting_system_shortcode($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'columns' => 3,
            'show_results' => 'false'
        ), $atts);
        
        ob_start();
        
        if (!is_user_logged_in()) {
            echo '<div class="voting-login-required">';
            echo '<p>' . __('Vous devez être connecté pour voter.', 'voting-system') . '</p>';
            echo '<a href="' . wp_login_url(get_permalink()) . '" class="vote-button">' . __('Se connecter', 'voting-system') . '</a>';
            echo '</div>';
            return ob_get_clean();
        }
        
        $database = new VotingSystem_Database();
        $categories = $database->get_categories();
        
        if (empty($categories)) {
            echo '<p>' . __('Aucune catégorie de vote disponible.', 'voting-system') . '</p>';
            return ob_get_clean();
        }
        
        // Afficher les catégories
        echo '<div class="voting-categories">';
        foreach ($categories as $category) {
            if ($category->status === 'active' && $this->is_voting_period_active($category)) {
                $this->display_category($category, $atts);
            }
        }
        echo '</div>';
        
        return ob_get_clean();
    }
    
    /**
     * Shortcode des résultats
     */
    public function voting_results_shortcode($atts) {
        $atts = shortcode_atts(array(
            'category' => '',
            'show_percentage' => 'true',
            'show_graph' => 'true'
        ), $atts);
        
        ob_start();
        
        $database = new VotingSystem_Database();
        
        if (!empty($atts['category'])) {
            $category = $database->get_category_by_slug($atts['category']);
            if ($category) {
                $this->display_results_for_category($category, $atts);
            }
        } else {
            $categories = $database->get_categories();
            foreach ($categories as $category) {
                $this->display_results_for_category($category, $atts);
            }
        }
        
        return ob_get_clean();
    }
    
    /**
     * Afficher une catégorie
     */
    private function display_category($category, $atts) {
        $database = new VotingSystem_Database();
        $candidates = $database->get_candidates_by_category($category->id);
        
        if (empty($candidates)) {
            return;
        }
        
        echo '<div class="voting-category" data-category-id="' . esc_attr($category->id) . '">';
        echo '<h2 class="voting-category-title">' . esc_html($category->name) . '</h2>';
        
        if (!empty($category->description)) {
            echo '<p class="voting-category-description">' . esc_html($category->description) . '</p>';
        }
        
        echo '<div class="voting-grid" style="grid-template-columns: repeat(' . esc_attr($atts['columns']) . ', 1fr);">';
        
        foreach ($candidates as $candidate) {
            if ($candidate->status === 'active') {
                $this->display_candidate_card($candidate, $category);
            }
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Afficher une carte de candidat
     */
    private function display_candidate_card($candidate, $category) {
        $user_id = get_current_user_id();
        $database = new VotingSystem_Database();
        $has_voted = $database->user_has_voted($user_id, $category->id);
        $vote_count = $database->get_vote_count($candidate->id);
        
        echo '<div class="vote-card" data-candidate-id="' . esc_attr($candidate->id) . '">';
        
        // Photo du candidat
        if (!empty($candidate->photo)) {
            echo '<img src="' . esc_url($candidate->photo) . '" alt="' . esc_attr($candidate->name) . '" class="vote-photo" onclick="showCandidateDetails(' . esc_attr($candidate->id) . ')">';
        }
        
        echo '<h3 class="vote-candidate-name">' . esc_html($candidate->name) . '</h3>';
        
        if (!empty($candidate->biography)) {
            echo '<p class="vote-candidate-bio">' . esc_html(wp_trim_words($candidate->biography, 20)) . '</p>';
        }
        
        echo '<div class="vote-actions">';
        
        if ($has_voted) {
            echo '<button class="vote-button voted" disabled>' . __('Voté', 'voting-system') . '</button>';
        } else {
            echo '<button class="vote-button" onclick="castVote(' . esc_attr($candidate->id) . ', ' . esc_attr($category->id) . ')">' . __('Voter', 'voting-system') . '</button>';
        }
        
        echo '<button class="vote-button details" onclick="showCandidateDetails(' . esc_attr($candidate->id) . ')">' . __('Voir détails', 'voting-system') . '</button>';
        echo '</div>';
        
        if ($vote_count > 0) {
            echo '<div class="vote-count">' . sprintf(__('%d vote(s)', 'voting-system'), $vote_count) . '</div>';
        }
        
        echo '</div>';
    }
    
    /**
     * Afficher les résultats pour une catégorie
     */
    private function display_results_for_category($category, $atts) {
        $database = new VotingSystem_Database();
        $candidates = $database->get_candidates_with_votes($category->id);
        
        if (empty($candidates)) {
            return;
        }
        
        // Trier par nombre de votes décroissant
        usort($candidates, function($a, $b) {
            return $b->vote_count - $a->vote_count;
        });
        
        echo '<div class="voting-results-category">';
        echo '<h2 class="voting-results-title">' . esc_html($category->name) . '</h2>';
        
        echo '<div class="voting-results-list">';
        
        foreach ($candidates as $index => $candidate) {
            $position = $index + 1;
            $percentage = $candidate->total_votes > 0 ? round(($candidate->vote_count / $candidate->total_votes) * 100, 1) : 0;
            
            echo '<div class="voting-result-item position-' . $position . '">';
            echo '<div class="result-position">' . $position . '</div>';
            
            if (!empty($candidate->photo)) {
                echo '<img src="' . esc_url($candidate->photo) . '" alt="' . esc_attr($candidate->name) . '" class="result-photo">';
            }
            
            echo '<div class="result-info">';
            echo '<h3 class="result-name">' . esc_html($candidate->name) . '</h3>';
            echo '<div class="result-votes">' . sprintf(__('%d vote(s)', 'voting-system'), $candidate->vote_count) . '</div>';
            
            if ($atts['show_percentage'] === 'true') {
                echo '<div class="result-percentage">' . $percentage . '%</div>';
            }
            
            if ($atts['show_graph'] === 'true') {
                echo '<div class="result-bar">';
                echo '<div class="result-bar-fill" style="width: ' . $percentage . '%"></div>';
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    /**
     * Vérifier si la période de vote est active
     */
    private function is_voting_period_active($category) {
        $now = current_time('timestamp');
        $start = strtotime($category->start_date);
        $end = strtotime($category->end_date);
        
        return $now >= $start && $now <= $end;
    }
    
    /**
     * Enregistrer les scripts et styles frontend
     */
    public function enqueue_scripts() {
        wp_enqueue_style('voting-system', VOTING_SYSTEM_PLUGIN_URL . 'assets/css/voting-system.css', array(), VOTING_SYSTEM_VERSION);
        wp_enqueue_script('voting-system', VOTING_SYSTEM_PLUGIN_URL . 'assets/js/voting-system.js', array('jquery'), VOTING_SYSTEM_VERSION, true);
        
        wp_localize_script('voting-system', 'voting_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('voting_nonce'),
            'strings' => array(
                'vote_success' => __('Vote enregistré avec succès !', 'voting-system'),
                'vote_error' => __('Erreur lors du vote.', 'voting-system'),
                'already_voted' => __('Vous avez déjà voté dans cette catégorie.', 'voting-system'),
                'login_required' => __('Vous devez être connecté pour voter.', 'voting-system')
            )
        ));
    }
    
    /**
     * Enregistrer les scripts et styles admin
     */
    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'voting-system') !== false) {
            wp_enqueue_style('voting-system-admin', VOTING_SYSTEM_PLUGIN_URL . 'assets/css/voting-system-admin.css', array(), VOTING_SYSTEM_VERSION);
            wp_enqueue_script('voting-system-admin', VOTING_SYSTEM_PLUGIN_URL . 'assets/js/voting-system-admin.js', array('jquery'), VOTING_SYSTEM_VERSION, true);
            wp_enqueue_media();
        }
    }
}

// Initialiser le plugin
new VotingSystem(); 