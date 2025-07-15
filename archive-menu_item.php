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

<!-- Menu archive section -->
<section class="food_section layout_padding-bottom">
    <div class="container">
        <div class="heading_container heading_center">
            <h1><?php _e('Notre Menu', 'feane'); ?></h1>
            <?php if (get_the_archive_description()): ?>
                <div class="archive-description">
                    <?php the_archive_description(); ?>
                </div>
            <?php endif; ?>
        </div>

        <ul class="filters_menu">
            <li class="active" data-filter="*"><?php _e('Tout', 'feane'); ?></li>
            <li data-filter=".burger"><?php _e('Burger', 'feane'); ?></li>
            <li data-filter=".pizza"><?php _e('Pizza', 'feane'); ?></li>
            <li data-filter=".pasta"><?php _e('Pasta', 'feane'); ?></li>
            <li data-filter=".fries"><?php _e('Fries', 'feane'); ?></li>
        </ul>

        <div class="filters-content">
            <div class="row grid">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        $category = get_post_meta(get_the_ID(), '_menu_item_category', true);
                        $price = get_post_meta(get_the_ID(), '_menu_item_price', true);
                        $image = get_the_post_thumbnail_url(get_the_ID(), 'feane-menu');
                        if (!$image) {
                            $image = get_template_directory_uri() . '/images/f1.png';
                        }
                        ?>
                        <div class="col-sm-6 col-lg-4 all <?php echo esc_attr($category); ?>">
                            <div class="box">
                                <div>
                                    <div class="img-box">
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                    </div>
                                    <div class="detail-box">
                                        <h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                        <p><?php echo esc_html(get_the_excerpt()); ?></p>
                                        <div class="options">
                                            <h6><?php echo esc_html($price); ?></h6>
                                            <a href="<?php the_permalink(); ?>">
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
                    endwhile;
                else :
                    ?>
                    <div class="col-12">
                        <p><?php _e('Aucun plat trouvé.', 'feane'); ?></p>
                    </div>
                    <?php
                endif;
                ?>
            </div>
        </div>

        <!-- Pagination -->
        <?php
        the_posts_pagination(array(
            'mid_size' => 2,
            'prev_text' => __('&laquo; Précédent', 'feane'),
            'next_text' => __('Suivant &raquo;', 'feane'),
        ));
        ?>
    </div>
</section>

<?php get_footer(); ?> 