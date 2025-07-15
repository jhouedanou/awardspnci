<?php
/**
 * Vue de liste des catégories
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Catégories', 'voting-system'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=voting-categories&action=add'); ?>" class="page-title-action">
        <?php _e('Ajouter une catégorie', 'voting-system'); ?>
    </a>
    <hr class="wp-header-end">
    
    <?php if (empty($categories)): ?>
        <div class="notice notice-info">
            <p><?php _e('Aucune catégorie trouvée.', 'voting-system'); ?></p>
        </div>
    <?php else: ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th scope="col" class="manage-column column-image"><?php _e('Image', 'voting-system'); ?></th>
                    <th scope="col" class="manage-column column-name"><?php _e('Nom', 'voting-system'); ?></th>
                    <th scope="col" class="manage-column column-slug"><?php _e('Slug', 'voting-system'); ?></th>
                    <th scope="col" class="manage-column column-status"><?php _e('Statut', 'voting-system'); ?></th>
                    <th scope="col" class="manage-column column-period"><?php _e('Période de vote', 'voting-system'); ?></th>
                    <th scope="col" class="manage-column column-candidates"><?php _e('Candidats', 'voting-system'); ?></th>
                    <th scope="col" class="manage-column column-actions"><?php _e('Actions', 'voting-system'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td class="column-image">
                            <?php if ($category->image): ?>
                                <img src="<?php echo esc_url($category->image); ?>" alt="<?php echo esc_attr($category->name); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <div style="width: 50px; height: 50px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999;">
                                    <span class="dashicons dashicons-format-image"></span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="column-name">
                            <strong>
                                <a href="<?php echo admin_url('admin.php?page=voting-categories&action=edit&id=' . $category->id); ?>">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            </strong>
                            <?php if ($category->description): ?>
                                <br>
                                <small style="color: #666;"><?php echo esc_html(wp_trim_words($category->description, 10)); ?></small>
                            <?php endif; ?>
                        </td>
                        <td class="column-slug">
                            <code><?php echo esc_html($category->slug); ?></code>
                        </td>
                        <td class="column-status">
                            <?php if ($category->status === 'active'): ?>
                                <span class="status-active"><?php _e('Actif', 'voting-system'); ?></span>
                            <?php else: ?>
                                <span class="status-inactive"><?php _e('Inactif', 'voting-system'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="column-period">
                            <?php if ($category->start_date && $category->end_date): ?>
                                <div>
                                    <strong><?php _e('Du :', 'voting-system'); ?></strong><br>
                                    <?php echo date_i18n('d/m/Y H:i', strtotime($category->start_date)); ?>
                                </div>
                                <div style="margin-top: 5px;">
                                    <strong><?php _e('Au :', 'voting-system'); ?></strong><br>
                                    <?php echo date_i18n('d/m/Y H:i', strtotime($category->end_date)); ?>
                                </div>
                                
                                <?php
                                $now = current_time('timestamp');
                                $start = strtotime($category->start_date);
                                $end = strtotime($category->end_date);
                                
                                if ($now < $start): ?>
                                    <span class="period-status future"><?php _e('À venir', 'voting-system'); ?></span>
                                <?php elseif ($now > $end): ?>
                                    <span class="period-status ended"><?php _e('Terminé', 'voting-system'); ?></span>
                                <?php else: ?>
                                    <span class="period-status active"><?php _e('En cours', 'voting-system'); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span style="color: #999;"><?php _e('Non définie', 'voting-system'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="column-candidates">
                            <?php
                            $database = new VotingSystem_Database();
                            $candidates = $database->get_candidates_by_category($category->id);
                            $candidate_count = count($candidates);
                            ?>
                            <a href="<?php echo admin_url('admin.php?page=voting-candidates&category=' . $category->id); ?>">
                                <?php echo $candidate_count; ?> <?php echo _n('candidat', 'candidats', $candidate_count, 'voting-system'); ?>
                            </a>
                        </td>
                        <td class="column-actions">
                            <div class="row-actions">
                                <span class="edit">
                                    <a href="<?php echo admin_url('admin.php?page=voting-categories&action=edit&id=' . $category->id); ?>">
                                        <?php _e('Modifier', 'voting-system'); ?>
                                    </a> |
                                </span>
                                <span class="view">
                                    <a href="<?php echo admin_url('admin.php?page=voting-results&category=' . $category->id); ?>">
                                        <?php _e('Résultats', 'voting-system'); ?>
                                    </a> |
                                </span>
                                <span class="candidates">
                                    <a href="<?php echo admin_url('admin.php?page=voting-candidates&category=' . $category->id); ?>">
                                        <?php _e('Candidats', 'voting-system'); ?>
                                    </a> |
                                </span>
                                <span class="delete">
                                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=voting-categories&action=delete&id=' . $category->id), 'delete_category_' . $category->id); ?>" 
                                       onclick="return confirm('<?php _e('Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action supprimera également tous les candidats et votes associés.', 'voting-system'); ?>')">
                                        <?php _e('Supprimer', 'voting-system'); ?>
                                    </a>
                                </span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<style>
.status-active {
    background: #d4edda;
    color: #155724;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.status-inactive {
    background: #f8d7da;
    color: #721c24;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
}

.period-status {
    display: inline-block;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 11px;
    font-weight: 500;
    margin-top: 5px;
}

.period-status.future {
    background: #fff3cd;
    color: #856404;
}

.period-status.active {
    background: #d4edda;
    color: #155724;
}

.period-status.ended {
    background: #f8d7da;
    color: #721c24;
}

.column-image {
    width: 80px;
}

.column-status {
    width: 100px;
}

.column-period {
    width: 200px;
}

.column-candidates {
    width: 120px;
}

.column-actions {
    width: 200px;
}

.row-actions {
    font-size: 12px;
}

.row-actions span {
    margin-right: 5px;
}

.row-actions span:last-child {
    margin-right: 0;
}
</style> 