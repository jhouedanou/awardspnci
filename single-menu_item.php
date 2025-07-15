<?php get_header(); ?>

<div class="hero_area">
    <div class="bg-box">
        <?php 
        $hero_image = get_theme_mod('feane_hero_image', get_template_directory_uri() . '/images/hero-bg.jpg');
        ?>
        <img src="<?php echo esc_url($hero_image); ?>" alt="<?php bloginfo('name'); ?>">
    </div>
    
    <!-- Header section -->
    <?php get_template_part('template-parts/header'); ?>
</div>

<!-- Single menu item -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                while (have_posts()) :
                    the_post();
                    $price = get_post_meta(get_the_ID(), '_menu_item_price', true);
                    $category = get_post_meta(get_the_ID(), '_menu_item_category', true);
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('menu-item-single'); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                            
                            <div class="menu-item-meta">
                                <?php if ($price): ?>
                                    <span class="menu-item-price">
                                        <i class="fa fa-euro"></i>
                                        <?php echo esc_html($price); ?>
                                    </span>
                                <?php endif; ?>
                                
                                <?php if ($category): ?>
                                    <span class="menu-item-category">
                                        <i class="fa fa-tag"></i>
                                        <?php echo esc_html(ucfirst($category)); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </header>

                        <?php if (has_post_thumbnail()): ?>
                            <div class="menu-item-image">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                            </div>
                        <?php endif; ?>

                        <div class="entry-content">
                            <?php
                            the_content();

                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'feane'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>

                        <footer class="entry-footer">
                            <div class="menu-item-actions">
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('book-table'))); ?>" class="btn btn-primary">
                                    <i class="fa fa-calendar"></i>
                                    <?php _e('Réserver une table', 'feane'); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(get_permalink(get_page_by_path('order-online'))); ?>" class="btn btn-success">
                                    <i class="fa fa-shopping-cart"></i>
                                    <?php _e('Commander en ligne', 'feane'); ?>
                                </a>
                            </div>

                            <?php if (get_edit_post_link()) : ?>
                                <div class="edit-link">
                                    <?php
                                    edit_post_link(
                                        sprintf(
                                            wp_kses(
                                                __('Modifier <span class="screen-reader-text">"%s"</span>', 'feane'),
                                                array(
                                                    'span' => array(
                                                        'class' => array(),
                                                    ),
                                                )
                                            ),
                                            wp_kses_post(get_the_title())
                                        ),
                                        '<span class="edit-link">',
                                        '</span>'
                                    );
                                    ?>
                                </div>
                            <?php endif; ?>
                        </footer>
                    </article>

                    <!-- Navigation des plats -->
                    <nav class="navigation post-navigation" role="navigation">
                        <h2 class="screen-reader-text"><?php _e('Navigation des plats', 'feane'); ?></h2>
                        <div class="nav-links">
                            <div class="nav-previous">
                                <?php previous_post_link('%link', '<i class="fa fa-arrow-left"></i> %title'); ?>
                            </div>
                            <div class="nav-next">
                                <?php next_post_link('%link', '%title <i class="fa fa-arrow-right"></i>'); ?>
                            </div>
                        </div>
                    </nav>

                <?php endwhile; ?>
            </div>
            
            <div class="col-md-4">
                <aside class="menu-sidebar">
                    <div class="widget">
                        <h3><?php _e('Autres plats', 'feane'); ?></h3>
                        <?php
                        $related_items = get_posts(array(
                            'post_type' => 'menu_item',
                            'posts_per_page' => 5,
                            'post__not_in' => array(get_the_ID()),
                            'meta_query' => array(
                                array(
                                    'key' => '_menu_item_category',
                                    'value' => $category,
                                    'compare' => '='
                                )
                            )
                        ));
                        
                        if ($related_items):
                            echo '<ul class="related-menu-items">';
                            foreach ($related_items as $item):
                                ?>
                                <li>
                                    <a href="<?php echo get_permalink($item->ID); ?>">
                                        <?php echo get_the_post_thumbnail($item->ID, 'thumbnail'); ?>
                                        <div class="item-info">
                                            <h4><?php echo esc_html($item->post_title); ?></h4>
                                            <span class="price"><?php echo esc_html(get_post_meta($item->ID, '_menu_item_price', true)); ?></span>
                                        </div>
                                    </a>
                                </li>
                                <?php
                            endforeach;
                            echo '</ul>';
                        endif;
                        ?>
                    </div>
                    
                    <div class="widget">
                        <h3><?php _e('Catégories', 'feane'); ?></h3>
                        <ul class="menu-categories">
                            <li><a href="<?php echo esc_url(get_post_type_archive_link('menu_item')); ?>"><?php _e('Tout le menu', 'feane'); ?></a></li>
                            <li><a href="<?php echo esc_url(add_query_arg('category', 'burger', get_post_type_archive_link('menu_item'))); ?>"><?php _e('Burgers', 'feane'); ?></a></li>
                            <li><a href="<?php echo esc_url(add_query_arg('category', 'pizza', get_post_type_archive_link('menu_item'))); ?>"><?php _e('Pizzas', 'feane'); ?></a></li>
                            <li><a href="<?php echo esc_url(add_query_arg('category', 'pasta', get_post_type_archive_link('menu_item'))); ?>"><?php _e('Pâtes', 'feane'); ?></a></li>
                            <li><a href="<?php echo esc_url(add_query_arg('category', 'fries', get_post_type_archive_link('menu_item'))); ?>"><?php _e('Frites', 'feane'); ?></a></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?> 