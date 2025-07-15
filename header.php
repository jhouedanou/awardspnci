<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="hero_area">
    <div class="bg-box">
        <?php 
        $hero_image = get_theme_mod('feane_hero_image', get_template_directory_uri() . '/images/hero-bg.jpg');
        ?>
        <img src="<?php echo esc_url($hero_image); ?>" alt="<?php bloginfo('name'); ?>">
    </div>
    
    <!-- Header section -->
    <header class="header_section">
        <div class="container">
            <nav class="navbar navbar-expand-lg custom_nav-container">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    ?>
                    <a class="navbar-brand" href="<?php echo esc_url(home_url('/')); ?>">
                        <span><?php bloginfo('name'); ?></span>
                    </a>
                    <?php
                }
                ?>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class=""></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class' => 'navbar-nav mx-auto',
                        'container' => false,
                        'fallback_cb' => 'feane_fallback_menu',
                        'walker' => new Feane_Walker_Nav_Menu(),
                    ));
                    ?>
                    
                    <div class="user_option">
                        <?php if (is_user_logged_in()): ?>
                            <a href="<?php echo esc_url(admin_url()); ?>" class="user_link">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo esc_url(wp_login_url()); ?>" class="user_link">
                                <i class="fa fa-user" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                        
                        <a class="cart_link" href="<?php echo esc_url(get_permalink(get_page_by_path('cart'))); ?>">
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                                <g><g><path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z"/></g></g>
                                <g><g><path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4C457.728,97.71,450.56,86.958,439.296,84.91z"/></g></g>
                                <g><g><path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z"/></g></g>
                            </svg>
                        </a>
                        
                        <form class="form-inline" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                            <button class="btn my-2 my-sm-0 nav_search-btn" type="submit">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                            <input type="search" class="form-control" placeholder="<?php _e('Rechercher...', 'feane'); ?>" value="<?php echo get_search_query(); ?>" name="s" style="display: none;">
                        </form>
                        
                        <a href="<?php echo esc_url(get_permalink(get_page_by_path('order-online'))); ?>" class="order_online">
                            <?php _e('Commander en ligne', 'feane'); ?>
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </header>
</div>

<?php
// Classe pour le menu de navigation
class Feane_Walker_Nav_Menu extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $output .= '<ul class="dropdown-menu">';
    }
    
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'nav-item';
        
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'active';
        }
        
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        $output .= '<li' . $class_names . '>';
        
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        $attributes .= ' class="nav-link"';
        
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
}

// Menu de fallback
function feane_fallback_menu() {
    echo '<ul class="navbar-nav mx-auto">';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(home_url('/')) . '">' . __('Accueil', 'feane') . '</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(get_permalink(get_page_by_path('menu'))) . '">' . __('Menu', 'feane') . '</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(get_permalink(get_page_by_path('about'))) . '">' . __('À propos', 'feane') . '</a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="' . esc_url(get_permalink(get_page_by_path('book-table'))) . '">' . __('Réserver', 'feane') . '</a></li>';
    echo '</ul>';
}
?> 