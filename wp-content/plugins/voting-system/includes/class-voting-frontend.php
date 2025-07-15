<?php
/**
 * Classe Frontend pour le système de vote
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

class VotingSystem_Frontend {
    
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'add_voting_modals'));
        add_filter('the_content', array($this, 'maybe_add_voting_content'));
        add_action('wp_head', array($this, 'add_custom_styles'));
    }
    
    /**
     * Charger les scripts et styles frontend
     */
    public function enqueue_scripts() {
        // Styles
        wp_enqueue_style(
            'voting-system-frontend',
            VOTING_SYSTEM_PLUGIN_URL . 'assets/css/frontend.css',
            array(),
            VOTING_SYSTEM_VERSION
        );
        
        // Scripts
        wp_enqueue_script(
            'voting-system-frontend',
            VOTING_SYSTEM_PLUGIN_URL . 'assets/js/frontend.js',
            array('jquery'),
            VOTING_SYSTEM_VERSION,
            true
        );
        
        // Localiser le script
        wp_localize_script('voting-system-frontend', 'votingSystem', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('voting_nonce'),
            'isLoggedIn' => is_user_logged_in(),
            'loginUrl' => wp_login_url(get_permalink()),
            'strings' => array(
                'voteSuccess' => __('Vote enregistré avec succès !', 'voting-system'),
                'voteError' => __('Erreur lors du vote.', 'voting-system'),
                'alreadyVoted' => __('Vous avez déjà voté dans cette catégorie.', 'voting-system'),
                'loginRequired' => __('Vous devez être connecté pour voter.', 'voting-system'),
                'loading' => __('Chargement...', 'voting-system'),
                'confirmVote' => __('Êtes-vous sûr de vouloir voter pour ce candidat ?', 'voting-system'),
                'confirmRemoveVote' => __('Êtes-vous sûr de vouloir supprimer votre vote ?', 'voting-system')
            )
        ));
    }
    
    /**
     * Ajouter les modales de vote
     */
    public function add_voting_modals() {
        ?>
        <!-- Modal Détails Candidat -->
        <div id="candidate-modal" class="voting-modal">
            <div class="voting-modal-content">
                <span class="voting-modal-close">&times;</span>
                <div class="candidate-details">
                    <div class="candidate-header">
                        <img id="candidate-photo" src="" alt="">
                        <div class="candidate-info">
                            <h3 id="candidate-name"></h3>
                            <p id="candidate-category"></p>
                            <div class="vote-count">
                                <span id="candidate-votes">0</span> votes
                            </div>
                        </div>
                    </div>
                    <div class="candidate-biography">
                        <h4><?php _e('Biographie', 'voting-system'); ?></h4>
                        <p id="candidate-bio"></p>
                    </div>
                    <div class="candidate-gallery" id="candidate-gallery">
                        <!-- Galerie d'images -->
                    </div>
                    <div class="candidate-actions">
                        <button id="vote-button" class="vote-btn" style="display: none;">
                            <?php _e('Voter', 'voting-system'); ?>
                        </button>
                        <button id="remove-vote-button" class="remove-vote-btn" style="display: none;">
                            <?php _e('Supprimer mon vote', 'voting-system'); ?>
                        </button>
                        <div id="vote-status-message"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Modal Confirmation Vote -->
        <div id="vote-confirmation-modal" class="voting-modal">
            <div class="voting-modal-content">
                <h3><?php _e('Confirmation de vote', 'voting-system'); ?></h3>
                <p id="vote-confirmation-text"></p>
                <div class="modal-actions">
                    <button id="confirm-vote" class="btn btn-primary">
                        <?php _e('Confirmer', 'voting-system'); ?>
                    </button>
                    <button id="cancel-vote" class="btn btn-secondary">
                        <?php _e('Annuler', 'voting-system'); ?>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Modal Résultats -->
        <div id="results-modal" class="voting-modal">
            <div class="voting-modal-content">
                <span class="voting-modal-close">&times;</span>
                <h3><?php _e('Résultats du vote', 'voting-system'); ?></h3>
                <div id="results-content">
                    <!-- Contenu des résultats -->
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Ajouter du contenu de vote si nécessaire
     */
    public function maybe_add_voting_content($content) {
        global $post;
        
        // Vérifier si c'est une page avec shortcode de vote
        if (has_shortcode($content, 'voting_system') || has_shortcode($content, 'voting_results')) {
            return $content;
        }
        
        // Vérifier si c'est une page de vote spécifique
        $voting_pages = get_option('voting_system_pages', array());
        
        if (in_array($post->ID, $voting_pages)) {
            $shortcode = '[voting_system]';
            if (strpos($content, '[voting_results]') !== false) {
                $shortcode = '[voting_results]';
            }
            return $content . do_shortcode($shortcode);
        }
        
        return $content;
    }
    
    /**
     * Ajouter des styles personnalisés
     */
    public function add_custom_styles() {
        $settings = get_option('voting_system_settings', array());
        $custom_css = get_option('voting_system_custom_css', '');
        
        if (!empty($custom_css)) {
            echo '<style type="text/css">' . $custom_css . '</style>';
        }
    }
    
    /**
     * Afficher la grille des catégories
     */
    public function display_categories_grid() {
        $database = new VotingSystem_Database();
        $categories = $database->get_categories();
        
        if (empty($categories)) {
            return '<p>' . __('Aucune catégorie disponible.', 'voting-system') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="voting-categories-grid">
            <?php foreach ($categories as $category): ?>
                <?php if ($category->status === 'active'): ?>
                    <div class="voting-category-card" data-category-id="<?php echo $category->id; ?>">
                        <div class="category-image">
                            <?php if ($category->image): ?>
                                <img src="<?php echo esc_url($category->image); ?>" alt="<?php echo esc_attr($category->name); ?>">
                            <?php else: ?>
                                <div class="category-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="category-content">
                            <h3><?php echo esc_html($category->name); ?></h3>
                            <p><?php echo esc_html($category->description); ?></p>
                            <div class="category-meta">
                                <span class="category-status">
                                    <?php echo $this->get_category_status_text($category); ?>
                                </span>
                                <span class="candidates-count">
                                    <?php 
                                    $candidates = $database->get_candidates_by_category($category->id);
                                    echo sprintf(_n('%d candidat', '%d candidats', count($candidates), 'voting-system'), count($candidates));
                                    ?>
                                </span>
                            </div>
                            <a href="<?php echo esc_url(add_query_arg('category', $category->id, get_permalink())); ?>" class="category-link">
                                <?php _e('Voir les candidats', 'voting-system'); ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Afficher les candidats d'une catégorie
     */
    public function display_candidates($category_id = null) {
        $database = new VotingSystem_Database();
        
        if (!$category_id) {
            $category_id = $_GET['category'] ?? 0;
        }
        
        if (!$category_id) {
            return '<p>' . __('Catégorie non spécifiée.', 'voting-system') . '</p>';
        }
        
        $category = $database->get_category($category_id);
        if (!$category) {
            return '<p>' . __('Catégorie non trouvée.', 'voting-system') . '</p>';
        }
        
        $candidates = $database->get_candidates_by_category($category_id);
        $user_id = get_current_user_id();
        $has_voted = $user_id ? $database->user_has_voted($user_id, $category_id) : false;
        $user_vote = $has_voted ? $database->get_user_vote($user_id, $category_id) : null;
        
        ob_start();
        ?>
        <div class="voting-category-header">
            <h2><?php echo esc_html($category->name); ?></h2>
            <p><?php echo esc_html($category->description); ?></p>
            <div class="category-info">
                <span class="voting-period">
                    <?php echo $this->get_voting_period_text($category); ?>
                </span>
                <?php if ($has_voted): ?>
                    <span class="voted-status">
                        <?php 
                        $voted_candidate = $database->get_candidate($user_vote->candidate_id);
                        printf(__('Vous avez voté pour : %s', 'voting-system'), $voted_candidate->name);
                        ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="voting-candidates-grid">
            <?php if (empty($candidates)): ?>
                <p><?php _e('Aucun candidat dans cette catégorie.', 'voting-system'); ?></p>
            <?php else: ?>
                <?php foreach ($candidates as $candidate): ?>
                    <div class="voting-candidate-card" data-candidate-id="<?php echo $candidate->id; ?>">
                        <div class="candidate-image">
                            <img src="<?php echo esc_url($candidate->photo); ?>" alt="<?php echo esc_attr($candidate->name); ?>">
                            <?php if ($has_voted && $user_vote->candidate_id == $candidate->id): ?>
                                <div class="voted-badge">
                                    <i class="fas fa-check"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="candidate-content">
                            <h3><?php echo esc_html($candidate->name); ?></h3>
                            <div class="candidate-votes">
                                <?php 
                                $vote_count = $database->get_vote_count($candidate->id);
                                echo sprintf(_n('%d vote', '%d votes', $vote_count, 'voting-system'), $vote_count);
                                ?>
                            </div>
                            <button class="candidate-details-btn" data-candidate-id="<?php echo $candidate->id; ?>">
                                <?php _e('Voir détails', 'voting-system'); ?>
                            </button>
                            <?php if (!$has_voted && $category->status === 'active'): ?>
                                <button class="vote-btn" data-candidate-id="<?php echo $candidate->id; ?>" data-category-id="<?php echo $category_id; ?>">
                                    <?php _e('Voter', 'voting-system'); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Afficher les résultats
     */
    public function display_results($category_id = null) {
        $database = new VotingSystem_Database();
        
        if (!$category_id) {
            $category_id = $_GET['category'] ?? 0;
        }
        
        $categories = $database->get_categories();
        
        ob_start();
        ?>
        <div class="voting-results">
            <?php if (empty($categories)): ?>
                <p><?php _e('Aucune catégorie disponible.', 'voting-system'); ?></p>
            <?php else: ?>
                <div class="results-category-selector">
                    <label for="category-select"><?php _e('Sélectionner une catégorie :', 'voting-system'); ?></label>
                    <select id="category-select">
                        <option value=""><?php _e('Toutes les catégories', 'voting-system'); ?></option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category->id; ?>" <?php selected($category_id, $category->id); ?>>
                                <?php echo esc_html($category->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div id="results-content">
                    <?php if ($category_id): ?>
                        <?php echo $this->display_category_results($category_id); ?>
                    <?php else: ?>
                        <?php echo $this->display_all_results(); ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Afficher les résultats d'une catégorie
     */
    private function display_category_results($category_id) {
        $database = new VotingSystem_Database();
        $category = $database->get_category($category_id);
        $results = $database->get_candidates_with_votes($category_id);
        
        if (!$category) {
            return '<p>' . __('Catégorie non trouvée.', 'voting-system') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="category-results">
            <h2><?php echo esc_html($category->name); ?></h2>
            
            <?php if (empty($results)): ?>
                <p><?php _e('Aucun résultat disponible.', 'voting-system'); ?></p>
            <?php else: ?>
                <div class="results-list">
                    <?php foreach ($results as $index => $candidate): ?>
                        <div class="result-item" data-position="<?php echo $index + 1; ?>">
                            <div class="result-position">
                                <span class="position-number"><?php echo $index + 1; ?></span>
                            </div>
                            <div class="result-candidate">
                                <img src="<?php echo esc_url($candidate->photo); ?>" alt="<?php echo esc_attr($candidate->name); ?>">
                                <div class="candidate-info">
                                    <h3><?php echo esc_html($candidate->name); ?></h3>
                                    <div class="vote-stats">
                                        <span class="vote-count"><?php echo $candidate->vote_count; ?> votes</span>
                                        <?php if ($candidate->total_votes > 0): ?>
                                            <span class="vote-percentage">
                                                (<?php echo round(($candidate->vote_count / $candidate->total_votes) * 100, 1); ?>%)
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="result-bar">
                                <div class="bar-fill" style="width: <?php echo $candidate->total_votes > 0 ? ($candidate->vote_count / $candidate->total_votes) * 100 : 0; ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="results-summary">
                    <p>
                        <?php 
                        $total_votes = array_sum(array_column($results, 'vote_count'));
                        printf(__('Total des votes : %d', 'voting-system'), $total_votes);
                        ?>
                    </p>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Afficher tous les résultats
     */
    private function display_all_results() {
        $database = new VotingSystem_Database();
        $categories = $database->get_categories();
        
        ob_start();
        ?>
        <div class="all-results">
            <h2><?php _e('Résultats de tous les votes', 'voting-system'); ?></h2>
            
            <?php foreach ($categories as $category): ?>
                <?php if ($category->status === 'active'): ?>
                    <div class="category-results-summary">
                        <h3><?php echo esc_html($category->name); ?></h3>
                        <?php echo $this->display_category_results($category->id); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Obtenir le texte de statut d'une catégorie
     */
    private function get_category_status_text($category) {
        $now = current_time('timestamp');
        $start = strtotime($category->start_date);
        $end = strtotime($category->end_date);
        
        if ($now < $start) {
            return sprintf(__('Débute le %s', 'voting-system'), date_i18n('d/m/Y H:i', $start));
        } elseif ($now > $end) {
            return __('Terminé', 'voting-system');
        } else {
            return __('Vote en cours', 'voting-system');
        }
    }
    
    /**
     * Obtenir le texte de la période de vote
     */
    private function get_voting_period_text($category) {
        $start = strtotime($category->start_date);
        $end = strtotime($category->end_date);
        
        return sprintf(
            __('Du %s au %s', 'voting-system'),
            date_i18n('d/m/Y H:i', $start),
            date_i18n('d/m/Y H:i', $end)
        );
    }
    
    /**
     * Générer un graphique des résultats
     */
    public function generate_results_chart($category_id) {
        $database = new VotingSystem_Database();
        $results = $database->get_candidates_with_votes($category_id);
        
        if (empty($results)) {
            return '';
        }
        
        $chart_data = array();
        foreach ($results as $candidate) {
            $chart_data[] = array(
                'name' => $candidate->name,
                'votes' => $candidate->vote_count
            );
        }
        
        // Trier par nombre de votes décroissant
        usort($chart_data, function($a, $b) {
            return $b['votes'] - $a['votes'];
        });
        
        return json_encode($chart_data);
    }
} 