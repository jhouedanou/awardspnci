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
    
    <!-- Slider section -->
    <section class="slider_section">
        <div id="customCarousel1" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php
                $slides = get_theme_mod('feane_slides', array());
                if (!empty($slides)) {
                    $first = true;
                    foreach ($slides as $slide) {
                        $active_class = $first ? 'active' : '';
                        ?>
                        <div class="carousel-item <?php echo $active_class; ?>">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-7 col-lg-6">
                                        <div class="detail-box">
                                            <h1><?php echo esc_html($slide['title']); ?></h1>
                                            <p><?php echo esc_html($slide['description']); ?></p>
                                            <div class="btn-box">
                                                <a href="<?php echo esc_url($slide['button_url']); ?>" class="btn1">
                                                    <?php echo esc_html($slide['button_text']); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $first = false;
                    }
                } else {
                    // Slide par défaut
                    ?>
                    <div class="carousel-item active">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-7 col-lg-6">
                                    <div class="detail-box">
                                        <h1><?php bloginfo('name'); ?></h1>
                                        <p><?php bloginfo('description'); ?></p>
                                        <div class="btn-box">
                                            <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="btn1">
                                                <?php _e('Commander maintenant', 'feane'); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
            
            <?php if (!empty($slides) && count($slides) > 1): ?>
            <div class="container">
                <ol class="carousel-indicators">
                    <?php for ($i = 0; $i < count($slides); $i++): ?>
                    <li data-target="#customCarousel1" data-slide-to="<?php echo $i; ?>" <?php echo $i === 0 ? 'class="active"' : ''; ?>></li>
                    <?php endfor; ?>
                </ol>
            </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<!-- Offer section -->
<section class="offer_section layout_padding-bottom">
    <div class="offer_container">
        <div class="container">
            <div class="row">
                <?php
                $offers = get_theme_mod('feane_offers', array());
                if (!empty($offers)) {
                    foreach ($offers as $offer) {
                        ?>
                        <div class="col-md-6">
                            <div class="box">
                                <div class="img-box">
                                    <img src="<?php echo esc_url($offer['image']); ?>" alt="<?php echo esc_attr($offer['title']); ?>">
                                </div>
                                <div class="detail-box">
                                    <h5><?php echo esc_html($offer['title']); ?></h5>
                                    <h6><span><?php echo esc_html($offer['discount']); ?></span> <?php _e('Off', 'feane'); ?></h6>
                                    <a href="<?php echo esc_url($offer['link']); ?>">
                                        <?php _e('Commander maintenant', 'feane'); ?>
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                                            <g><g><path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z"/></g></g>
                                            <g><g><path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4C457.728,97.71,450.56,86.958,439.296,84.91z"/></g></g>
                                            <g><g><path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z"/></g></g>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    // Offres par défaut
                    ?>
                    <div class="col-md-6">
                        <div class="box">
                            <div class="img-box">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/o1.jpg" alt="Tasty Thursdays">
                            </div>
                            <div class="detail-box">
                                <h5><?php _e('Tasty Thursdays', 'feane'); ?></h5>
                                <h6><span>20%</span> <?php _e('Off', 'feane'); ?></h6>
                                <a href="#">
                                    <?php _e('Commander maintenant', 'feane'); ?>
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                                        <g><g><path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z"/></g></g>
                                        <g><g><path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4C457.728,97.71,450.56,86.958,439.296,84.91z"/></g></g>
                                        <g><g><path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z"/></g></g>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="box">
                            <div class="img-box">
                                <img src="<?php echo get_template_directory_uri(); ?>/images/o2.jpg" alt="Pizza Days">
                            </div>
                            <div class="detail-box">
                                <h5><?php _e('Pizza Days', 'feane'); ?></h5>
                                <h6><span>15%</span> <?php _e('Off', 'feane'); ?></h6>
                                <a href="#">
                                    <?php _e('Commander maintenant', 'feane'); ?>
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 456.029 456.029" style="enable-background:new 0 0 456.029 456.029;" xml:space="preserve">
                                        <g><g><path d="M345.6,338.862c-29.184,0-53.248,23.552-53.248,53.248c0,29.184,23.552,53.248,53.248,53.248c29.184,0,53.248-23.552,53.248-53.248C398.336,362.926,374.784,338.862,345.6,338.862z"/></g></g>
                                        <g><g><path d="M439.296,84.91c-1.024,0-2.56-0.512-4.096-0.512H112.64l-5.12-34.304C104.448,27.566,84.992,10.67,61.952,10.67H20.48C9.216,10.67,0,19.886,0,31.15c0,11.264,9.216,20.48,20.48,20.48h41.472c2.56,0,4.608,2.048,5.12,4.608l31.744,216.064c4.096,27.136,27.648,47.616,55.296,47.616h212.992c26.624,0,49.664-18.944,55.296-45.056l33.28-166.4C457.728,97.71,450.56,86.958,439.296,84.91z"/></g></g>
                                        <g><g><path d="M215.04,389.55c-1.024-28.16-24.576-50.688-52.736-50.688c-29.696,1.536-52.224,26.112-51.2,55.296c1.024,28.16,24.064,50.688,52.224,50.688h1.024C193.536,443.31,216.576,418.734,215.04,389.55z"/></g></g>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- Food section -->
<section class="food_section layout_padding-bottom">
    <div class="container">
        <div class="heading_container heading_center">
            <h2><?php _e('Notre Menu', 'feane'); ?></h2>
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
                $menu_items = feane_get_menu_items('', 8);
                if (!empty($menu_items)) {
                    foreach ($menu_items as $item) {
                        $category = get_post_meta($item->ID, '_menu_item_category', true);
                        $price = get_post_meta($item->ID, '_menu_item_price', true);
                        $image = get_the_post_thumbnail_url($item->ID, 'feane-menu');
                        if (!$image) {
                            $image = get_template_directory_uri() . '/images/f1.png';
                        }
                        ?>
                        <div class="col-sm-6 col-lg-4 all <?php echo esc_attr($category); ?>">
                            <div class="box">
                                <div>
                                    <div class="img-box">
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($item->post_title); ?>">
                                    </div>
                                    <div class="detail-box">
                                        <h5><?php echo esc_html($item->post_title); ?></h5>
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

<!-- About section -->
<section class="about_section layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="img-box">
                    <?php 
                    $about_image = get_theme_mod('feane_about_image', get_template_directory_uri() . '/images/about-img.png');
                    ?>
                    <img src="<?php echo esc_url($about_image); ?>" alt="<?php bloginfo('name'); ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="detail-box">
                    <div class="heading_container">
                        <h2><?php echo get_theme_mod('feane_about_title', __('Nous sommes Feane', 'feane')); ?></h2>
                    </div>
                    <p><?php echo get_theme_mod('feane_about_text', __('Il existe de nombreuses variations de passages de Lorem Ipsum disponibles, mais la majorité ont subi une altération sous une forme ou une autre, par humour injecté, ou des mots aléatoires qui ne semblent même pas légèrement crédibles.', 'feane')); ?></p>
                    <a href="<?php echo get_permalink(get_page_by_path('about')); ?>"><?php _e('Lire plus', 'feane'); ?></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Book section -->
<section class="book_section layout_padding">
    <div class="container">
        <div class="heading_container">
            <h2><?php _e('Réserver une table', 'feane'); ?></h2>
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
            <div class="col-md-6">
                <div class="map_container">
                    <div id="googleMap"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Client section -->
<section class="client_section layout_padding-bottom">
    <div class="container">
        <div class="heading_container heading_center psudo_white_primary mb_45">
            <h2><?php _e('Ce que disent nos clients', 'feane'); ?></h2>
        </div>
        <div class="carousel-wrap row">
            <div class="owl-carousel client_owl-carousel">
                <?php
                $testimonials = feane_get_testimonials(6);
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

<?php get_footer(); ?> 