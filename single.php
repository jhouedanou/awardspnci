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

<!-- Single post content -->
<section class="layout_padding">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <?php
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <header class="entry-header">
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                            
                            <div class="entry-meta">
                                <span class="posted-on">
                                    <i class="fa fa-calendar"></i>
                                    <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                        <?php echo esc_html(get_the_date()); ?>
                                    </time>
                                </span>
                                
                                <span class="byline">
                                    <i class="fa fa-user"></i>
                                    <span class="author vcard">
                                        <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                            <?php echo esc_html(get_the_author()); ?>
                                        </a>
                                    </span>
                                </span>
                                
                                <?php if (has_category()): ?>
                                    <span class="cat-links">
                                        <i class="fa fa-folder"></i>
                                        <?php the_category(', '); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </header>

                        <?php if (has_post_thumbnail()): ?>
                            <div class="entry-thumbnail">
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
                            <?php if (has_tag()): ?>
                                <div class="tags-links">
                                    <i class="fa fa-tags"></i>
                                    <?php the_tags('', ', '); ?>
                                </div>
                            <?php endif; ?>

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

                    <!-- Navigation des articles -->
                    <nav class="navigation post-navigation" role="navigation">
                        <h2 class="screen-reader-text"><?php _e('Navigation des articles', 'feane'); ?></h2>
                        <div class="nav-links">
                            <div class="nav-previous">
                                <?php previous_post_link('%link', '<i class="fa fa-arrow-left"></i> %title'); ?>
                            </div>
                            <div class="nav-next">
                                <?php next_post_link('%link', '%title <i class="fa fa-arrow-right"></i>'); ?>
                            </div>
                        </div>
                    </nav>

                    <?php
                    // Si les commentaires sont ouverts ou qu'il y a au moins un commentaire, charger le template de commentaires.
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;
                endwhile;
                ?>
            </div>
            
            <div class="col-md-4">
                <?php get_sidebar(); ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?> 