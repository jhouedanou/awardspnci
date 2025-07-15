<?php
/**
 * Fonctions du thème Feane
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Configuration du thème
function feane_setup() {
    // Support des fonctionnalités WordPress
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Support Gutenberg
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    
    // Menus
    register_nav_menus(array(
        'primary' => __('Menu Principal', 'feane'),
        'footer' => __('Menu Footer', 'feane'),
    ));
    
    // Tailles d'images personnalisées
    add_image_size('feane-hero', 1920, 600, true);
    add_image_size('feane-menu', 400, 300, true);
    add_image_size('feane-offer', 600, 400, true);
}
add_action('after_setup_theme', 'feane_setup');

// Enregistrement des scripts et styles
function feane_scripts() {
    // Styles
    wp_enqueue_style('feane-bootstrap', get_template_directory_uri() . '/css/bootstrap.css');
    wp_enqueue_style('feane-fontawesome', get_template_directory_uri() . '/css/font-awesome.min.css');
    wp_enqueue_style('feane-style', get_template_directory_uri() . '/css/style.css');
    wp_enqueue_style('feane-responsive', get_template_directory_uri() . '/css/responsive.css');
    
    // Scripts
    wp_enqueue_script('jquery');
    wp_enqueue_script('feane-bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array('jquery'), '', true);
    wp_enqueue_script('feane-custom', get_template_directory_uri() . '/js/custom.js', array('jquery'), '', true);
    
    // Scripts externes
    wp_enqueue_script('owl-carousel', 'https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js', array('jquery'), '', true);
    wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3.0.4/dist/isotope.pkgd.min.js', array('jquery'), '', true);
    wp_enqueue_script('nice-select', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-nice-select/1.1.0/js/jquery.nice-select.min.js', array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'feane_scripts');

// Enregistrement des widgets
function feane_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar Principale', 'feane'),
        'id' => 'sidebar-1',
        'description' => __('Widgets de la sidebar principale', 'feane'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer 1', 'feane'),
        'id' => 'footer-1',
        'description' => __('Première colonne du footer', 'feane'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer 2', 'feane'),
        'id' => 'footer-2',
        'description' => __('Deuxième colonne du footer', 'feane'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer 3', 'feane'),
        'id' => 'footer-3',
        'description' => __('Troisième colonne du footer', 'feane'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4>',
        'after_title' => '</h4>',
    ));
}
add_action('widgets_init', 'feane_widgets_init');

// Custom Post Types
function feane_custom_post_types() {
    // Menu Items
    register_post_type('menu_item', array(
        'labels' => array(
            'name' => __('Plats', 'feane'),
            'singular_name' => __('Plat', 'feane'),
            'add_new' => __('Ajouter un plat', 'feane'),
            'add_new_item' => __('Ajouter un nouveau plat', 'feane'),
            'edit_item' => __('Modifier le plat', 'feane'),
            'new_item' => __('Nouveau plat', 'feane'),
            'view_item' => __('Voir le plat', 'feane'),
            'search_items' => __('Rechercher des plats', 'feane'),
            'not_found' => __('Aucun plat trouvé', 'feane'),
            'not_found_in_trash' => __('Aucun plat trouvé dans la corbeille', 'feane'),
        ),
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-food',
        'rewrite' => array('slug' => 'menu'),
    ));
    
    // Testimonials
    register_post_type('testimonial', array(
        'labels' => array(
            'name' => __('Témoignages', 'feane'),
            'singular_name' => __('Témoignage', 'feane'),
            'add_new' => __('Ajouter un témoignage', 'feane'),
            'add_new_item' => __('Ajouter un nouveau témoignage', 'feane'),
            'edit_item' => __('Modifier le témoignage', 'feane'),
            'new_item' => __('Nouveau témoignage', 'feane'),
            'view_item' => __('Voir le témoignage', 'feane'),
            'search_items' => __('Rechercher des témoignages', 'feane'),
            'not_found' => __('Aucun témoignage trouvé', 'feane'),
            'not_found_in_trash' => __('Aucun témoignage trouvé dans la corbeille', 'feane'),
        ),
        'public' => true,
        'has_archive' => false,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-format-quote',
    ));
}
add_action('init', 'feane_custom_post_types');

// Taxonomies pour les plats
function feane_taxonomies() {
    register_taxonomy('menu_category', 'menu_item', array(
        'labels' => array(
            'name' => __('Catégories de menu', 'feane'),
            'singular_name' => __('Catégorie de menu', 'feane'),
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'menu-category'),
    ));
}
add_action('init', 'feane_taxonomies');

// Meta boxes pour les plats
function feane_add_meta_boxes() {
    add_meta_box(
        'menu_item_details',
        __('Détails du plat', 'feane'),
        'feane_menu_item_meta_box',
        'menu_item',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'feane_add_meta_boxes');

function feane_menu_item_meta_box($post) {
    wp_nonce_field('feane_save_meta_box_data', 'feane_meta_box_nonce');
    
    $price = get_post_meta($post->ID, '_menu_item_price', true);
    $category = get_post_meta($post->ID, '_menu_item_category', true);
    
    echo '<table class="form-table">';
    echo '<tr><th><label for="menu_item_price">' . __('Prix', 'feane') . '</label></th>';
    echo '<td><input type="text" id="menu_item_price" name="menu_item_price" value="' . esc_attr($price) . '" /></td></tr>';
    echo '<tr><th><label for="menu_item_category">' . __('Catégorie', 'feane') . '</label></th>';
    echo '<td><select id="menu_item_category" name="menu_item_category">';
    echo '<option value="">' . __('Sélectionner une catégorie', 'feane') . '</option>';
    echo '<option value="burger"' . selected($category, 'burger', false) . '>' . __('Burger', 'feane') . '</option>';
    echo '<option value="pizza"' . selected($category, 'pizza', false) . '>' . __('Pizza', 'feane') . '</option>';
    echo '<option value="pasta"' . selected($category, 'pasta', false) . '>' . __('Pasta', 'feane') . '</option>';
    echo '<option value="fries"' . selected($category, 'fries', false) . '>' . __('Fries', 'feane') . '</option>';
    echo '</select></td></tr>';
    echo '</table>';
}

function feane_save_meta_box_data($post_id) {
    if (!isset($_POST['feane_meta_box_nonce'])) {
        return;
    }
    
    if (!wp_verify_nonce($_POST['feane_meta_box_nonce'], 'feane_save_meta_box_data')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (isset($_POST['post_type']) && 'menu_item' == $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    
    if (isset($_POST['menu_item_price'])) {
        update_post_meta($post_id, '_menu_item_price', sanitize_text_field($_POST['menu_item_price']));
    }
    
    if (isset($_POST['menu_item_category'])) {
        update_post_meta($post_id, '_menu_item_category', sanitize_text_field($_POST['menu_item_category']));
    }
}
add_action('save_post', 'feane_save_meta_box_data');

// Fonctions utilitaires
function feane_get_menu_items($category = '', $limit = -1) {
    $args = array(
        'post_type' => 'menu_item',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
    );
    
    if (!empty($category)) {
        $args['meta_query'] = array(
            array(
                'key' => '_menu_item_category',
                'value' => $category,
                'compare' => '='
            )
        );
    }
    
    return get_posts($args);
}

function feane_get_testimonials($limit = -1) {
    return get_posts(array(
        'post_type' => 'testimonial',
        'posts_per_page' => $limit,
        'post_status' => 'publish',
    ));
}

// Inclure les fichiers d'incrémentation
require get_template_directory() . '/inc/blocks.php';
require get_template_directory() . '/inc/customizer.php'; 