<?php
/**
 * Blocs Gutenberg personnalisés pour le thème Feane
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

// Enregistrer les blocs Gutenberg
function feane_register_blocks() {
    // Bloc Hero Section
    register_block_type('feane/hero-section', array(
        'editor_script' => 'feane-blocks-editor',
        'editor_style' => 'feane-blocks-editor-style',
        'style' => 'feane-blocks-style',
        'render_callback' => 'feane_hero_section_render',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => __('Fast Food Restaurant', 'feane'),
            ),
            'description' => array(
                'type' => 'string',
                'default' => __('Doloremque, itaque aperiam facilis rerum, commodi, temporibus sapiente ad mollitia laborum quam quisquam esse error unde.', 'feane'),
            ),
            'buttonText' => array(
                'type' => 'string',
                'default' => __('Commander maintenant', 'feane'),
            ),
            'buttonUrl' => array(
                'type' => 'string',
                'default' => '#',
            ),
            'backgroundImage' => array(
                'type' => 'string',
                'default' => get_template_directory_uri() . '/images/hero-bg.jpg',
            ),
        ),
    ));

    // Bloc Menu Grid
    register_block_type('feane/menu-grid', array(
        'editor_script' => 'feane-blocks-editor',
        'editor_style' => 'feane-blocks-editor-style',
        'style' => 'feane-blocks-style',
        'render_callback' => 'feane_menu_grid_render',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => __('Notre Menu', 'feane'),
            ),
            'category' => array(
                'type' => 'string',
                'default' => '',
            ),
            'postsPerPage' => array(
                'type' => 'number',
                'default' => 6,
            ),
            'showFilters' => array(
                'type' => 'boolean',
                'default' => true,
            ),
        ),
    ));

    // Bloc Testimonials
    register_block_type('feane/testimonials', array(
        'editor_script' => 'feane-blocks-editor',
        'editor_style' => 'feane-blocks-editor-style',
        'style' => 'feane-blocks-style',
        'render_callback' => 'feane_testimonials_render',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => __('Ce que disent nos clients', 'feane'),
            ),
            'postsPerPage' => array(
                'type' => 'number',
                'default' => 6,
            ),
            'showCarousel' => array(
                'type' => 'boolean',
                'default' => true,
            ),
        ),
    ));

    // Bloc Booking Form
    register_block_type('feane/booking-form', array(
        'editor_script' => 'feane-blocks-editor',
        'editor_style' => 'feane-blocks-editor-style',
        'style' => 'feane-blocks-style',
        'render_callback' => 'feane_booking_form_render',
        'attributes' => array(
            'title' => array(
                'type' => 'string',
                'default' => __('Réserver une table', 'feane'),
            ),
            'showMap' => array(
                'type' => 'boolean',
                'default' => true,
            ),
        ),
    ));
}
add_action('init', 'feane_register_blocks');

// Enregistrer les scripts et styles pour les blocs
function feane_blocks_scripts() {
    wp_register_script(
        'feane-blocks-editor',
        get_template_directory_uri() . '/js/blocks-editor.js',
        array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'),
        '1.0.0',
        true
    );

    wp_register_style(
        'feane-blocks-editor-style',
        get_template_directory_uri() . '/css/blocks-editor.css',
        array('wp-edit-blocks'),
        '1.0.0'
    );

    wp_register_style(
        'feane-blocks-style',
        get_template_directory_uri() . '/css/blocks.css',
        array(),
        '1.0.0'
    );
}
add_action('init', 'feane_blocks_scripts');

// Fonctions de rendu des blocs

function feane_hero_section_render($attributes) {
    $title = $attributes['title'] ?? __('Fast Food Restaurant', 'feane');
    $description = $attributes['description'] ?? '';
    $button_text = $attributes['buttonText'] ?? __('Commander maintenant', 'feane');
    $button_url = $attributes['buttonUrl'] ?? '#';
    $background_image = $attributes['backgroundImage'] ?? get_template_directory_uri() . '/images/hero-bg.jpg';

    ob_start();
    ?>
    <div class="hero_area">
        <div class="bg-box">
            <img src="<?php echo esc_url($background_image); ?>" alt="<?php echo esc_attr($title); ?>">
        </div>
        <section class="slider_section">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 col-lg-6">
                        <div class="detail-box">
                            <h1><?php echo esc_html($title); ?></h1>
                            <p><?php echo esc_html($description); ?></p>
                            <div class="btn-box">
                                <a href="<?php echo esc_url($button_url); ?>" class="btn1">
                                    <?php echo esc_html($button_text); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php
    return ob_get_clean();
}

function feane_menu_grid_render($attributes) {
    $title = $attributes['title'] ?? __('Notre Menu', 'feane');
    $category = $attributes['category'] ?? '';
    $posts_per_page = $attributes['postsPerPage'] ?? 6;
    $show_filters = $attributes['showFilters'] ?? true;

    $args = array(
        'post_type' => 'menu_item',
        'posts_per_page' => $posts_per_page,
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

    $menu_items = get_posts($args);

    ob_start();
    ?>
    <section class="food_section layout_padding-bottom">
        <div class="container">
            <div class="heading_container heading_center">
                <h2><?php echo esc_html($title); ?></h2>
            </div>

            <?php if ($show_filters): ?>
            <ul class="filters_menu">
                <li class="active" data-filter="*"><?php _e('Tout', 'feane'); ?></li>
                <li data-filter=".burger"><?php _e('Burger', 'feane'); ?></li>
                <li data-filter=".pizza"><?php _e('Pizza', 'feane'); ?></li>
                <li data-filter=".pasta"><?php _e('Pasta', 'feane'); ?></li>
                <li data-filter=".fries"><?php _e('Fries', 'feane'); ?></li>
            </ul>
            <?php endif; ?>

            <div class="filters-content">
                <div class="row grid">
                    <?php
                    if (!empty($menu_items)) {
                        foreach ($menu_items as $item) {
                            $item_category = get_post_meta($item->ID, '_menu_item_category', true);
                            $price = get_post_meta($item->ID, '_menu_item_price', true);
                            $image = get_the_post_thumbnail_url($item->ID, 'feane-menu');
                            if (!$image) {
                                $image = get_template_directory_uri() . '/images/f1.png';
                            }
                            ?>
                            <div class="col-sm-6 col-lg-4 all <?php echo esc_attr($item_category); ?>">
                                <div class="box">
                                    <div>
                                        <div class="img-box">
                                            <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($item->post_title); ?>">
                                        </div>
                                        <div class="detail-box">
                                            <h5><a href="<?php echo get_permalink($item->ID); ?>"><?php echo esc_html($item->post_title); ?></a></h5>
                                            <p><?php echo esc_html($item->post_excerpt); ?></p>
                                            <div class="options">
                                                <h6><?php echo esc_html($price); ?></h6>
                                                <a href="<?php echo get_permalink($item->ID); ?>">
                                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                                                        <g><g><path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z"/></g></g>
                                                        <g><g><path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4C457.728,97.71,450.56,86.958,439.296,84.91z"/></g></g>
                                                        <g><g><path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z"/></g></g>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

function feane_testimonials_render($attributes) {
    $title = $attributes['title'] ?? __('Ce que disent nos clients', 'feane');
    $posts_per_page = $attributes['postsPerPage'] ?? 6;
    $show_carousel = $attributes['showCarousel'] ?? true;

    $testimonials = get_posts(array(
        'post_type' => 'testimonial',
        'posts_per_page' => $posts_per_page,
        'post_status' => 'publish',
    ));

    ob_start();
    ?>
    <section class="client_section layout_padding-bottom">
        <div class="container">
            <div class="heading_container heading_center psudo_white_primary mb_45">
                <h2><?php echo esc_html($title); ?></h2>
            </div>
            <div class="carousel-wrap row">
                <div class="owl-carousel client_owl-carousel">
                    <?php
                    if (!empty($testimonials)) {
                        foreach ($testimonials as $testimonial) {
                            $image = get_the_post_thumbnail_url($testimonial->ID);
                            if (!$image) {
                                $image = get_template_directory_uri() . '/images/client1.jpg';
                            }
                            ?>
                            <div class="item">
                                <div class="box">
                                    <div class="detail-box">
                                        <p><?php echo esc_html($testimonial->post_content); ?></p>
                                        <h6><?php echo esc_html($testimonial->post_title); ?></h6>
                                        <p><?php echo esc_html(get_post_meta($testimonial->ID, '_testimonial_position', true)); ?></p>
                                    </div>
                                    <div class="img-box">
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($testimonial->post_title); ?>" class="box-img">
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
}

function feane_booking_form_render($attributes) {
    $title = $attributes['title'] ?? __('Réserver une table', 'feane');
    $show_map = $attributes['showMap'] ?? true;

    ob_start();
    ?>
    <section class="book_section layout_padding">
        <div class="container">
            <div class="heading_container">
                <h2><?php echo esc_html($title); ?></h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form_container">
                        <form>
                            <div>
                                <input type="text" class="form-control" placeholder="<?php _e('Votre nom', 'feane'); ?>" />
                            </div>
                            <div>
                                <input type="text" class="form-control" placeholder="<?php _e('Numéro de téléphone', 'feane'); ?>" />
                            </div>
                            <div>
                                <input type="email" class="form-control" placeholder="<?php _e('Votre email', 'feane'); ?>" />
                            </div>
                            <div>
                                <select class="form-control nice-select wide">
                                    <option value="" disabled selected><?php _e('Combien de personnes ?', 'feane'); ?></option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div>
                                <input type="date" class="form-control">
                            </div>
                            <div class="btn_box">
                                <button><?php _e('Réserver maintenant', 'feane'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php if ($show_map): ?>
                <div class="col-md-6">
                    <div class="map_container">
                        <div id="googleMap"></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
    return ob_get_clean();
} 