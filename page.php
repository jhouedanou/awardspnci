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

<!-- Page content -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                        </header>

                        <div class="entry-content">
                            <?php
                            the_content();

                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', 'feane'),
                                'after'  => '</div>',
                            ));
                            ?>
                        </div>

                        <?php if (get_edit_post_link()) : ?>
                            <footer class="entry-footer">
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
                            </footer>
                        <?php endif; ?>
                    </article>

                    <?php
                    // Si les commentaires sont ouverts ou qu'il y a au moins un commentaire, charger le template de commentaires.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                endwhile;
                ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?> 