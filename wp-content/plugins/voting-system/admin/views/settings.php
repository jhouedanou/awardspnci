<?php
/**
 * Page des paramètres du système de vote
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

$default_settings = array(
    'require_login' => true,
    'allow_multiple_votes' => false,
    'show_vote_counts' => true,
    'email_notifications' => true,
    'admin_email' => get_option('admin_email'),
    'vote_confirmation_text' => __('Votre vote a été enregistré avec succès !', 'voting-system'),
    'login_required_text' => __('Vous devez être connecté pour voter.', 'voting-system')
);

$settings = wp_parse_args($settings, $default_settings);
?>

<div class="wrap">
    <h1><?php _e('Paramètres - Système de Vote', 'voting-system'); ?></h1>
    
    <form method="post" action="">
        <?php wp_nonce_field('voting_settings_nonce'); ?>
        
        <div class="voting-settings-container">
            <!-- Paramètres généraux -->
            <div class="voting-settings-section">
                <h2><?php _e('Paramètres généraux', 'voting-system'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="require_login"><?php _e('Connexion requise', 'voting-system'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" id="require_login" name="require_login" 
                                   value="1" <?php checked($settings['require_login']); ?>>
                            <label for="require_login"><?php _e('Les utilisateurs doivent être connectés pour voter', 'voting-system'); ?></label>
                            <p class="description"><?php _e('Si activé, seuls les utilisateurs connectés pourront voter.', 'voting-system'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="allow_multiple_votes"><?php _e('Votes multiples', 'voting-system'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" id="allow_multiple_votes" name="allow_multiple_votes" 
                                   value="1" <?php checked($settings['allow_multiple_votes']); ?>>
                            <label for="allow_multiple_votes"><?php _e('Permettre plusieurs votes par utilisateur', 'voting-system'); ?></label>
                            <p class="description"><?php _e('Si activé, les utilisateurs pourront voter plusieurs fois (attention aux abus).', 'voting-system'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="show_vote_counts"><?php _e('Afficher les compteurs', 'voting-system'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" id="show_vote_counts" name="show_vote_counts" 
                                   value="1" <?php checked($settings['show_vote_counts']); ?>>
                            <label for="show_vote_counts"><?php _e('Afficher le nombre de votes en temps réel', 'voting-system'); ?></label>
                            <p class="description"><?php _e('Affiche le nombre de votes pour chaque candidat.', 'voting-system'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Notifications -->
            <div class="voting-settings-section">
                <h2><?php _e('Notifications', 'voting-system'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="email_notifications"><?php _e('Notifications email', 'voting-system'); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" id="email_notifications" name="email_notifications" 
                                   value="1" <?php checked($settings['email_notifications']); ?>>
                            <label for="email_notifications"><?php _e('Envoyer des notifications par email', 'voting-system'); ?></label>
                            <p class="description"><?php _e('Envoie des emails de confirmation aux utilisateurs et notifications aux administrateurs.', 'voting-system'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="admin_email"><?php _e('Email administrateur', 'voting-system'); ?></label>
                        </th>
                        <td>
                            <input type="email" id="admin_email" name="admin_email" class="regular-text" 
                                   value="<?php echo esc_attr($settings['admin_email']); ?>">
                            <p class="description"><?php _e('Email pour recevoir les notifications de nouveaux votes.', 'voting-system'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Messages personnalisés -->
            <div class="voting-settings-section">
                <h2><?php _e('Messages personnalisés', 'voting-system'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="vote_confirmation_text"><?php _e('Message de confirmation', 'voting-system'); ?></label>
                        </th>
                        <td>
                            <textarea id="vote_confirmation_text" name="vote_confirmation_text" rows="3" class="large-text"><?php echo esc_textarea($settings['vote_confirmation_text']); ?></textarea>
                            <p class="description"><?php _e('Message affiché après un vote réussi.', 'voting-system'); ?></p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="login_required_text"><?php _e('Message de connexion requise', 'voting-system'); ?></label>
                        </th>
                        <td>
                            <textarea id="login_required_text" name="login_required_text" rows="3" class="large-text"><?php echo esc_textarea($settings['login_required_text']); ?></textarea>
                            <p class="description"><?php _e('Message affiché quand la connexion est requise pour voter.', 'voting-system'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- CSS personnalisé -->
            <div class="voting-settings-section">
                <h2><?php _e('CSS personnalisé', 'voting-system'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="custom_css"><?php _e('CSS personnalisé', 'voting-system'); ?></label>
                        </th>
                        <td>
                            <textarea id="custom_css" name="custom_css" rows="10" class="large-text code"><?php echo esc_textarea(get_option('voting_system_custom_css', '')); ?></textarea>
                            <p class="description"><?php _e('CSS personnalisé pour modifier l\'apparence du système de vote.', 'voting-system'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Maintenance -->
            <div class="voting-settings-section">
                <h2><?php _e('Maintenance', 'voting-system'); ?></h2>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php _e('Actions de maintenance', 'voting-system'); ?></th>
                        <td>
                            <div class="maintenance-actions">
                                <button type="button" class="button" id="cleanup-votes">
                                    <?php _e('Nettoyer les anciens votes', 'voting-system'); ?>
                                </button>
                                <button type="button" class="button" id="export-data">
                                    <?php _e('Exporter toutes les données', 'voting-system'); ?>
                                </button>
                                <button type="button" class="button button-secondary" id="reset-settings">
                                    <?php _e('Réinitialiser les paramètres', 'voting-system'); ?>
                                </button>
                            </div>
                            <p class="description"><?php _e('Actions de maintenance pour nettoyer et exporter les données.', 'voting-system'); ?></p>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <p class="submit">
            <input type="submit" name="save_settings" id="submit" class="button button-primary" 
                   value="<?php _e('Enregistrer les paramètres', 'voting-system'); ?>">
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Nettoyer les anciens votes
    $('#cleanup-votes').on('click', function() {
        if (confirm('<?php _e('Êtes-vous sûr de vouloir nettoyer les anciens votes ? Cette action ne peut pas être annulée.', 'voting-system'); ?>')) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'voting_admin_action',
                    admin_action: 'cleanup_votes',
                    nonce: '<?php echo wp_create_nonce('voting_admin_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert('<?php _e('Nettoyage terminé avec succès.', 'voting-system'); ?>');
                    } else {
                        alert('<?php _e('Erreur lors du nettoyage.', 'voting-system'); ?>');
                    }
                },
                error: function() {
                    alert('<?php _e('Erreur lors du nettoyage.', 'voting-system'); ?>');
                }
            });
        }
    });
    
    // Exporter les données
    $('#export-data').on('click', function() {
        window.location.href = '<?php echo admin_url('admin-ajax.php?action=voting_admin_action&admin_action=export_all_data&nonce=' . wp_create_nonce('voting_admin_nonce')); ?>';
    });
    
    // Réinitialiser les paramètres
    $('#reset-settings').on('click', function() {
        if (confirm('<?php _e('Êtes-vous sûr de vouloir réinitialiser tous les paramètres ? Cette action ne peut pas être annulée.', 'voting-system'); ?>')) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'voting_admin_action',
                    admin_action: 'reset_settings',
                    nonce: '<?php echo wp_create_nonce('voting_admin_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('<?php _e('Erreur lors de la réinitialisation.', 'voting-system'); ?>');
                    }
                },
                error: function() {
                    alert('<?php _e('Erreur lors de la réinitialisation.', 'voting-system'); ?>');
                }
            });
        }
    });
    
    // Prévisualisation CSS
    var cssPreview = $('<div id="css-preview" style="display: none; margin-top: 10px; padding: 10px; border: 1px solid #ddd; background: #f9f9f9;"><h4><?php _e('Prévisualisation CSS', 'voting-system'); ?></h4><div id="preview-content"></div></div>');
    $('#custom_css').after(cssPreview);
    
    $('#custom_css').on('input', function() {
        var css = $(this).val();
        if (css) {
            $('#preview-content').html('<style>' + css + '</style><div class="voting-system"><div class="voting-category-card"><div class="category-content"><h3>Exemple de catégorie</h3><p>Description de la catégorie</p></div></div></div>');
            cssPreview.show();
        } else {
            cssPreview.hide();
        }
    });
});
</script>

<style>
.voting-settings-container {
    max-width: 800px;
}

.voting-settings-section {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

.voting-settings-section h2 {
    margin-top: 0;
    margin-bottom: 20px;
    color: #23282d;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}

.maintenance-actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.maintenance-actions .button {
    margin: 0;
}

.form-table textarea.code {
    font-family: 'Courier New', monospace;
    font-size: 13px;
}

#css-preview {
    border-radius: 4px;
}

#css-preview h4 {
    margin-top: 0;
    margin-bottom: 10px;
    color: #23282d;
}

@media (max-width: 768px) {
    .maintenance-actions {
        flex-direction: column;
    }
    
    .maintenance-actions .button {
        width: 100%;
    }
}
</style> 