<?php
/**
 * Formulaire d'ajout/modification de catégorie
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

$is_edit = !empty($category);
$title = $is_edit ? __('Modifier la catégorie', 'voting-system') : __('Ajouter une catégorie', 'voting-system');
?>

<div class="wrap">
    <h1><?php echo $title; ?></h1>
    
    <form method="post" action="" enctype="multipart/form-data">
        <?php wp_nonce_field('voting_category_nonce'); ?>
        <input type="hidden" name="voting_action" value="save_category">
        <?php if ($is_edit): ?>
            <input type="hidden" name="category_id" value="<?php echo $category->id; ?>">
        <?php endif; ?>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="name"><?php _e('Nom de la catégorie', 'voting-system'); ?> *</label>
                </th>
                <td>
                    <input type="text" id="name" name="name" class="regular-text" 
                           value="<?php echo $is_edit ? esc_attr($category->name) : ''; ?>" required>
                    <p class="description"><?php _e('Le nom de la catégorie tel qu\'il apparaîtra aux utilisateurs.', 'voting-system'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="slug"><?php _e('Slug', 'voting-system'); ?></label>
                </th>
                <td>
                    <input type="text" id="slug" name="slug" class="regular-text" 
                           value="<?php echo $is_edit ? esc_attr($category->slug) : ''; ?>">
                    <p class="description"><?php _e('L\'identifiant unique de la catégorie (URL-friendly). Laissez vide pour auto-générer.', 'voting-system'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="description"><?php _e('Description', 'voting-system'); ?></label>
                </th>
                <td>
                    <textarea id="description" name="description" rows="4" class="large-text"><?php echo $is_edit ? esc_textarea($category->description) : ''; ?></textarea>
                    <p class="description"><?php _e('Une description de la catégorie pour informer les utilisateurs.', 'voting-system'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="image"><?php _e('Image de la catégorie', 'voting-system'); ?></label>
                </th>
                <td>
                    <div class="image-upload-container">
                        <input type="url" id="image" name="image" class="regular-text" 
                               value="<?php echo $is_edit ? esc_url($category->image) : ''; ?>" 
                               placeholder="<?php _e('URL de l\'image ou cliquez pour sélectionner', 'voting-system'); ?>">
                        <button type="button" class="button" id="select-image">
                            <?php _e('Sélectionner une image', 'voting-system'); ?>
                        </button>
                        <button type="button" class="button" id="remove-image" style="display: none;">
                            <?php _e('Supprimer', 'voting-system'); ?>
                        </button>
                    </div>
                    
                    <div id="image-preview" class="image-preview" style="display: none;">
                        <img src="" alt="" style="max-width: 200px; max-height: 200px; border-radius: 4px;">
                    </div>
                    
                    <p class="description"><?php _e('Une image représentative de la catégorie (recommandé : 400x300px).', 'voting-system'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="start_date"><?php _e('Date de début du vote', 'voting-system'); ?></label>
                </th>
                <td>
                    <input type="datetime-local" id="start_date" name="start_date" 
                           value="<?php echo $is_edit && $category->start_date ? date('Y-m-d\TH:i', strtotime($category->start_date)) : ''; ?>">
                    <p class="description"><?php _e('Date et heure de début de la période de vote.', 'voting-system'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="end_date"><?php _e('Date de fin du vote', 'voting-system'); ?></label>
                </th>
                <td>
                    <input type="datetime-local" id="end_date" name="end_date" 
                           value="<?php echo $is_edit && $category->end_date ? date('Y-m-d\TH:i', strtotime($category->end_date)) : ''; ?>">
                    <p class="description"><?php _e('Date et heure de fin de la période de vote.', 'voting-system'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="status"><?php _e('Statut', 'voting-system'); ?></label>
                </th>
                <td>
                    <select id="status" name="status">
                        <option value="active" <?php echo $is_edit && $category->status === 'active' ? 'selected' : ''; ?>>
                            <?php _e('Actif', 'voting-system'); ?>
                        </option>
                        <option value="inactive" <?php echo $is_edit && $category->status === 'inactive' ? 'selected' : ''; ?>>
                            <?php _e('Inactif', 'voting-system'); ?>
                        </option>
                    </select>
                    <p class="description"><?php _e('Le statut détermine si la catégorie est visible et accessible pour le vote.', 'voting-system'); ?></p>
                </td>
            </tr>
            
            <tr>
                <th scope="row">
                    <label for="max_votes_per_user"><?php _e('Votes maximum par utilisateur', 'voting-system'); ?></label>
                </th>
                <td>
                    <input type="number" id="max_votes_per_user" name="max_votes_per_user" min="1" max="10" 
                           value="<?php echo $is_edit ? esc_attr($category->max_votes_per_user) : '1'; ?>">
                    <p class="description"><?php _e('Nombre maximum de votes qu\'un utilisateur peut effectuer dans cette catégorie.', 'voting-system'); ?></p>
                </td>
            </tr>
        </table>
        
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" 
                   value="<?php echo $is_edit ? __('Mettre à jour la catégorie', 'voting-system') : __('Ajouter la catégorie', 'voting-system'); ?>">
            <a href="<?php echo admin_url('admin.php?page=voting-categories'); ?>" class="button button-secondary">
                <?php _e('Annuler', 'voting-system'); ?>
            </a>
        </p>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Auto-génération du slug
    $('#name').on('input', function() {
        if (!$('#slug').val()) {
            var slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            $('#slug').val(slug);
        }
    });
    
    // Gestion de l'upload d'image
    $('#select-image').on('click', function(e) {
        e.preventDefault();
        
        var image = wp.media({
            title: '<?php _e('Sélectionner une image', 'voting-system'); ?>',
            button: {
                text: '<?php _e('Utiliser cette image', 'voting-system'); ?>'
            },
            multiple: false
        }).on('select', function() {
            var attachment = image.state().get('selection').first().toJSON();
            $('#image').val(attachment.url);
            $('#image-preview img').attr('src', attachment.url);
            $('#image-preview').show();
            $('#remove-image').show();
        }).open();
    });
    
    // Supprimer l'image
    $('#remove-image').on('click', function(e) {
        e.preventDefault();
        $('#image').val('');
        $('#image-preview').hide();
        $('#remove-image').hide();
    });
    
    // Afficher l'aperçu de l'image existante
    if ($('#image').val()) {
        $('#image-preview img').attr('src', $('#image').val());
        $('#image-preview').show();
        $('#remove-image').show();
    }
    
    // Validation du formulaire
    $('form').on('submit', function(e) {
        var name = $('#name').val().trim();
        if (!name) {
            alert('<?php _e('Le nom de la catégorie est requis.', 'voting-system'); ?>');
            $('#name').focus();
            e.preventDefault();
            return false;
        }
        
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        
        if (startDate && endDate && new Date(startDate) >= new Date(endDate)) {
            alert('<?php _e('La date de fin doit être postérieure à la date de début.', 'voting-system'); ?>');
            e.preventDefault();
            return false;
        }
    });
    
    // Validation en temps réel des dates
    $('#start_date, #end_date').on('change', function() {
        var startDate = $('#start_date').val();
        var endDate = $('#end_date').val();
        
        if (startDate && endDate) {
            if (new Date(startDate) >= new Date(endDate)) {
                $(this).addClass('error');
                $('<div class="error-message" style="color: #dc3232; font-size: 12px; margin-top: 5px;"><?php _e('La date de fin doit être postérieure à la date de début.', 'voting-system'); ?></div>').insertAfter($(this));
            } else {
                $(this).removeClass('error');
                $(this).siblings('.error-message').remove();
            }
        }
    });
});
</script>

<style>
.image-upload-container {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 10px;
}

.image-preview {
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #f9f9f9;
    display: inline-block;
}

.form-table input[type="datetime-local"] {
    width: 250px;
}

.form-table input[type="number"] {
    width: 100px;
}

.error {
    border-color: #dc3232 !important;
}

.error-message {
    color: #dc3232;
    font-size: 12px;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .image-upload-container {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .form-table input[type="datetime-local"] {
        width: 100%;
    }
}
</style> 