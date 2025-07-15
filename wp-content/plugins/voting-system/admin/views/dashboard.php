<?php
/**
 * Vue du tableau de bord d'administration
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('Tableau de bord - Système de Vote', 'voting-system'); ?></h1>
    
    <div class="voting-dashboard">
        <!-- Statistiques générales -->
        <div class="voting-stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <span class="dashicons dashicons-category"></span>
                </div>
                <div class="stat-content">
                    <h3><?php echo count($stats); ?></h3>
                    <p><?php _e('Catégories', 'voting-system'); ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <span class="dashicons dashicons-groups"></span>
                </div>
                <div class="stat-content">
                    <h3><?php echo array_sum(array_column($stats, 'total_candidates')); ?></h3>
                    <p><?php _e('Candidats', 'voting-system'); ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <span class="dashicons dashicons-chart-bar"></span>
                </div>
                <div class="stat-content">
                    <h3><?php echo array_sum(array_column($stats, 'total_votes')); ?></h3>
                    <p><?php _e('Votes totaux', 'voting-system'); ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <span class="dashicons dashicons-admin-users"></span>
                </div>
                <div class="stat-content">
                    <h3><?php echo array_sum(array_column($stats, 'total_voters')); ?></h3>
                    <p><?php _e('Votants uniques', 'voting-system'); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="voting-quick-actions">
            <h2><?php _e('Actions rapides', 'voting-system'); ?></h2>
            <div class="action-buttons">
                <a href="<?php echo admin_url('admin.php?page=voting-categories&action=add'); ?>" class="button button-primary">
                    <span class="dashicons dashicons-plus"></span>
                    <?php _e('Ajouter une catégorie', 'voting-system'); ?>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=voting-candidates&action=add'); ?>" class="button button-secondary">
                    <span class="dashicons dashicons-admin-users"></span>
                    <?php _e('Ajouter un candidat', 'voting-system'); ?>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=voting-results'); ?>" class="button button-secondary">
                    <span class="dashicons dashicons-chart-bar"></span>
                    <?php _e('Voir les résultats', 'voting-system'); ?>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=voting-settings'); ?>" class="button button-secondary">
                    <span class="dashicons dashicons-admin-settings"></span>
                    <?php _e('Paramètres', 'voting-system'); ?>
                </a>
            </div>
        </div>
        
        <!-- Statistiques par catégorie -->
        <div class="voting-category-stats">
            <h2><?php _e('Statistiques par catégorie', 'voting-system'); ?></h2>
            
            <?php if (empty($stats)): ?>
                <p><?php _e('Aucune catégorie trouvée.', 'voting-system'); ?></p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Catégorie', 'voting-system'); ?></th>
                            <th><?php _e('Candidats', 'voting-system'); ?></th>
                            <th><?php _e('Votes', 'voting-system'); ?></th>
                            <th><?php _e('Votants', 'voting-system'); ?></th>
                            <th><?php _e('Actions', 'voting-system'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats as $stat): ?>
                            <tr>
                                <td>
                                    <strong><?php echo esc_html($stat->category_name); ?></strong>
                                </td>
                                <td><?php echo $stat->total_candidates; ?></td>
                                <td><?php echo $stat->total_votes; ?></td>
                                <td><?php echo $stat->total_voters; ?></td>
                                <td>
                                    <a href="<?php echo admin_url('admin.php?page=voting-results&category=' . $stat->category_id); ?>" class="button button-small">
                                        <?php _e('Résultats', 'voting-system'); ?>
                                    </a>
                                    <a href="<?php echo admin_url('admin.php?page=voting-candidates&category=' . $stat->category_id); ?>" class="button button-small">
                                        <?php _e('Candidats', 'voting-system'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        
        <!-- Votes récents -->
        <div class="voting-recent-votes">
            <h2><?php _e('Votes récents', 'voting-system'); ?></h2>
            
            <?php if (empty($recent_votes)): ?>
                <p><?php _e('Aucun vote récent.', 'voting-system'); ?></p>
            <?php else: ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Utilisateur', 'voting-system'); ?></th>
                            <th><?php _e('Candidat', 'voting-system'); ?></th>
                            <th><?php _e('Catégorie', 'voting-system'); ?></th>
                            <th><?php _e('Date', 'voting-system'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_votes as $vote): ?>
                            <tr>
                                <td><?php echo esc_html($vote->user_name); ?></td>
                                <td><?php echo esc_html($vote->candidate_name); ?></td>
                                <td><?php echo esc_html($vote->category_name); ?></td>
                                <td><?php echo date_i18n('d/m/Y H:i', strtotime($vote->voted_at)); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <p class="description">
                    <a href="<?php echo admin_url('admin.php?page=voting-results'); ?>">
                        <?php _e('Voir tous les votes', 'voting-system'); ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.voting-dashboard {
    margin-top: 20px;
}

.voting-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-icon {
    background: #007cba;
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.stat-content h3 {
    margin: 0;
    font-size: 2rem;
    color: #007cba;
}

.stat-content p {
    margin: 5px 0 0 0;
    color: #666;
    font-weight: 500;
}

.voting-quick-actions {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
}

.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.action-buttons .button {
    display: flex;
    align-items: center;
    gap: 5px;
}

.voting-category-stats,
.voting-recent-votes {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 30px;
}

.voting-category-stats h2,
.voting-recent-votes h2 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #23282d;
}

@media (max-width: 768px) {
    .voting-stats-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .button {
        justify-content: center;
    }
}
</style> 