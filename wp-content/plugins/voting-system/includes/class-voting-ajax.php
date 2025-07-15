<?php
/**
 * Classe AJAX pour le système de vote
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

class VotingSystem_Ajax {
    
    public function __construct() {
        add_action('wp_ajax_cast_vote', array($this, 'cast_vote'));
        add_action('wp_ajax_nopriv_cast_vote', array($this, 'cast_vote'));
        add_action('wp_ajax_get_candidate_details', array($this, 'get_candidate_details'));
        add_action('wp_ajax_nopriv_get_candidate_details', array($this, 'get_candidate_details'));
        add_action('wp_ajax_remove_vote', array($this, 'remove_vote'));
        add_action('wp_ajax_get_vote_status', array($this, 'get_vote_status'));
        add_action('wp_ajax_nopriv_get_vote_status', array($this, 'get_vote_status'));
    }
    
    /**
     * Enregistrer un vote
     */
    public function cast_vote() {
        // Vérifier le nonce
        if (!wp_verify_nonce($_POST['nonce'], 'voting_nonce')) {
            wp_send_json_error(__('Erreur de sécurité.', 'voting-system'));
        }
        
        // Vérifier que l'utilisateur est connecté
        if (!is_user_logged_in()) {
            wp_send_json_error(__('Vous devez être connecté pour voter.', 'voting-system'));
        }
        
        $candidate_id = intval($_POST['candidate_id']);
        $category_id = intval($_POST['category_id']);
        $user_id = get_current_user_id();
        
        // Validation des données
        if (!$candidate_id || !$category_id) {
            wp_send_json_error(__('Données invalides.', 'voting-system'));
        }
        
        $database = new VotingSystem_Database();
        
        // Vérifier si l'utilisateur a déjà voté dans cette catégorie
        if ($database->user_has_voted($user_id, $category_id)) {
            wp_send_json_error(__('Vous avez déjà voté dans cette catégorie.', 'voting-system'));
        }
        
        // Vérifier que la période de vote est active
        $category = $database->get_category($category_id);
        if (!$category || $category->status !== 'active') {
            wp_send_json_error(__('Cette catégorie n\'est pas disponible pour le vote.', 'voting-system'));
        }
        
        $now = current_time('timestamp');
        $start = strtotime($category->start_date);
        $end = strtotime($category->end_date);
        
        if ($now < $start || $now > $end) {
            wp_send_json_error(__('La période de vote n\'est pas active.', 'voting-system'));
        }
        
        // Enregistrer le vote
        $vote_id = $database->cast_vote($user_id, $candidate_id, $category_id);
        
        if ($vote_id) {
            // Déclencher l'action après vote
            do_action('voting_system_vote_cast', $vote_id, $user_id, $candidate_id, $category_id);
            
            // Envoyer une notification email si activée
            $this->send_vote_notification($user_id, $candidate_id, $category_id);
            
            // Obtenir les nouvelles statistiques
            $vote_count = $database->get_vote_count($candidate_id);
            $candidate = $database->get_candidate($candidate_id);
            
            wp_send_json_success(array(
                'message' => __('Vote enregistré avec succès !', 'voting-system'),
                'vote_count' => $vote_count,
                'candidate_name' => $candidate->name,
                'category_name' => $category->name
            ));
        } else {
            wp_send_json_error(__('Erreur lors de l\'enregistrement du vote.', 'voting-system'));
        }
    }
    
    /**
     * Obtenir les détails d'un candidat
     */
    public function get_candidate_details() {
        // Vérifier le nonce
        if (!wp_verify_nonce($_POST['nonce'], 'voting_nonce')) {
            wp_send_json_error(__('Erreur de sécurité.', 'voting-system'));
        }
        
        $candidate_id = intval($_POST['candidate_id']);
        
        if (!$candidate_id) {
            wp_send_json_error(__('ID de candidat invalide.', 'voting-system'));
        }
        
        $database = new VotingSystem_Database();
        $candidate = $database->get_candidate($candidate_id);
        
        if (!$candidate) {
            wp_send_json_error(__('Candidat non trouvé.', 'voting-system'));
        }
        
        // Obtenir la catégorie
        $category = $database->get_category($candidate->category_id);
        
        // Vérifier le statut de vote de l'utilisateur
        $user_id = get_current_user_id();
        $has_voted = $user_id ? $database->user_has_voted($user_id, $candidate->category_id) : false;
        $vote_count = $database->get_vote_count($candidate_id);
        
        // Préparer les données de la galerie
        $gallery = array();
        if (!empty($candidate->gallery)) {
            $gallery = json_decode($candidate->gallery, true);
            if (!is_array($gallery)) {
                $gallery = array();
            }
        }
        
        $response_data = array(
            'id' => $candidate->id,
            'name' => $candidate->name,
            'photo' => $candidate->photo,
            'biography' => $candidate->biography,
            'category_name' => $category->name,
            'category_id' => $category->id,
            'gallery' => $gallery,
            'vote_count' => $vote_count,
            'has_voted' => $has_voted,
            'can_vote' => !$has_voted && is_user_logged_in() && $category->status === 'active'
        );
        
        wp_send_json_success($response_data);
    }
    
    /**
     * Supprimer un vote
     */
    public function remove_vote() {
        // Vérifier le nonce
        if (!wp_verify_nonce($_POST['nonce'], 'voting_nonce')) {
            wp_send_json_error(__('Erreur de sécurité.', 'voting-system'));
        }
        
        // Vérifier que l'utilisateur est connecté
        if (!is_user_logged_in()) {
            wp_send_json_error(__('Vous devez être connecté.', 'voting-system'));
        }
        
        $category_id = intval($_POST['category_id']);
        $user_id = get_current_user_id();
        
        if (!$category_id) {
            wp_send_json_error(__('ID de catégorie invalide.', 'voting-system'));
        }
        
        $database = new VotingSystem_Database();
        
        // Vérifier que l'utilisateur a bien voté dans cette catégorie
        $vote = $database->get_user_vote($user_id, $category_id);
        if (!$vote) {
            wp_send_json_error(__('Aucun vote trouvé pour cette catégorie.', 'voting-system'));
        }
        
        // Supprimer le vote
        $result = $database->remove_vote($user_id, $category_id);
        
        if ($result) {
            // Déclencher l'action après suppression
            do_action('voting_system_vote_removed', $vote->id, $user_id, $vote->candidate_id, $category_id);
            
            wp_send_json_success(__('Vote supprimé avec succès.', 'voting-system'));
        } else {
            wp_send_json_error(__('Erreur lors de la suppression du vote.', 'voting-system'));
        }
    }
    
    /**
     * Obtenir le statut de vote d'un utilisateur
     */
    public function get_vote_status() {
        // Vérifier le nonce
        if (!wp_verify_nonce($_POST['nonce'], 'voting_nonce')) {
            wp_send_json_error(__('Erreur de sécurité.', 'voting-system'));
        }
        
        $category_id = intval($_POST['category_id']);
        
        if (!$category_id) {
            wp_send_json_error(__('ID de catégorie invalide.', 'voting-system'));
        }
        
        $database = new VotingSystem_Database();
        $user_id = get_current_user_id();
        
        if (!$user_id) {
            wp_send_json_success(array(
                'has_voted' => false,
                'can_vote' => false,
                'message' => __('Vous devez être connecté pour voter.', 'voting-system')
            ));
        }
        
        $has_voted = $database->user_has_voted($user_id, $category_id);
        $vote = null;
        
        if ($has_voted) {
            $vote = $database->get_user_vote($user_id, $category_id);
        }
        
        $category = $database->get_category($category_id);
        $can_vote = $category && $category->status === 'active';
        
        $response_data = array(
            'has_voted' => $has_voted,
            'can_vote' => $can_vote,
            'category_active' => $category ? $category->status === 'active' : false
        );
        
        if ($vote) {
            $candidate = $database->get_candidate($vote->candidate_id);
            $response_data['voted_for'] = array(
                'candidate_id' => $vote->candidate_id,
                'candidate_name' => $candidate ? $candidate->name : '',
                'voted_at' => $vote->voted_at
            );
        }
        
        wp_send_json_success($response_data);
    }
    
    /**
     * Envoyer une notification email après vote
     */
    private function send_vote_notification($user_id, $candidate_id, $category_id) {
        $settings = get_option('voting_system_settings', array());
        
        if (empty($settings['email_notifications'])) {
            return;
        }
        
        $database = new VotingSystem_Database();
        $user = get_userdata($user_id);
        $candidate = $database->get_candidate($candidate_id);
        $category = $database->get_category($category_id);
        
        if (!$user || !$candidate || !$category) {
            return;
        }
        
        $admin_email = $settings['admin_email'] ?? get_option('admin_email');
        
        // Email à l'administrateur
        $admin_subject = sprintf(__('Nouveau vote - %s', 'voting-system'), get_bloginfo('name'));
        $admin_message = sprintf(
            __('Un nouveau vote a été enregistré :
            
Utilisateur : %s (%s)
Candidat : %s
Catégorie : %s
Date : %s

Voir les résultats : %s', 'voting-system'),
            $user->display_name,
            $user->user_email,
            $candidate->name,
            $category->name,
            current_time('Y-m-d H:i:s'),
            admin_url('admin.php?page=voting-results')
        );
        
        wp_mail($admin_email, $admin_subject, $admin_message);
        
        // Email de confirmation à l'utilisateur
        $user_subject = sprintf(__('Confirmation de vote - %s', 'voting-system'), get_bloginfo('name'));
        $user_message = sprintf(
            __('Bonjour %s,

Votre vote pour "%s" dans la catégorie "%s" a été enregistré avec succès.

Merci de votre participation !

Cordialement,
L\'équipe %s', 'voting-system'),
            $user->display_name,
            $candidate->name,
            $category->name,
            get_bloginfo('name')
        );
        
        wp_mail($user->user_email, $user_subject, $user_message);
    }
    
    /**
     * Valider les données de vote
     */
    private function validate_vote_data($candidate_id, $category_id) {
        $errors = array();
        
        if (!$candidate_id || !is_numeric($candidate_id)) {
            $errors[] = __('ID de candidat invalide.', 'voting-system');
        }
        
        if (!$category_id || !is_numeric($category_id)) {
            $errors[] = __('ID de catégorie invalide.', 'voting-system');
        }
        
        return $errors;
    }
    
    /**
     * Vérifier les permissions de vote
     */
    private function check_vote_permissions($user_id, $category_id) {
        $database = new VotingSystem_Database();
        $category = $database->get_category($category_id);
        
        if (!$category) {
            return array('error' => __('Catégorie non trouvée.', 'voting-system'));
        }
        
        if ($category->status !== 'active') {
            return array('error' => __('Cette catégorie n\'est pas active.', 'voting-system'));
        }
        
        // Vérifier la période de vote
        $now = current_time('timestamp');
        $start = strtotime($category->start_date);
        $end = strtotime($category->end_date);
        
        if ($now < $start) {
            return array('error' => __('La période de vote n\'a pas encore commencé.', 'voting-system'));
        }
        
        if ($now > $end) {
            return array('error' => __('La période de vote est terminée.', 'voting-system'));
        }
        
        // Vérifier si l'utilisateur a déjà voté
        if ($database->user_has_voted($user_id, $category_id)) {
            return array('error' => __('Vous avez déjà voté dans cette catégorie.', 'voting-system'));
        }
        
        return array('success' => true);
    }
    
    /**
     * Logger les actions de vote
     */
    private function log_vote_action($action, $user_id, $candidate_id, $category_id, $result) {
        $log_entry = array(
            'action' => $action,
            'user_id' => $user_id,
            'candidate_id' => $candidate_id,
            'category_id' => $category_id,
            'result' => $result,
            'ip_address' => $this->get_client_ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'timestamp' => current_time('mysql')
        );
        
        // Sauvegarder dans les logs WordPress
        $logs = get_option('voting_system_logs', array());
        $logs[] = $log_entry;
        
        // Garder seulement les 1000 dernières entrées
        if (count($logs) > 1000) {
            $logs = array_slice($logs, -1000);
        }
        
        update_option('voting_system_logs', $logs);
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
} 