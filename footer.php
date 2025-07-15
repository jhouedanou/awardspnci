<!-- Footer section -->
<footer class="footer_section">
    <div class="container">
        <div class="row">
            <div class="col-md-4 footer-col">
                <div class="footer_contact">
                    <h4><?php _e('Contactez-nous', 'feane'); ?></h4>
                    <div class="contact_link_box">
                        <a href="<?php echo esc_url(get_theme_mod('feane_address_link', '#')); ?>">
                            <i class="fa fa-map-marker" aria-hidden="true"></i>
                            <span><?php echo esc_html(get_theme_mod('feane_address', __('Localisation', 'feane'))); ?></span>
                        </a>
                        <a href="tel:<?php echo esc_attr(get_theme_mod('feane_phone', '+01 1234567890')); ?>">
                            <i class="fa fa-phone" aria-hidden="true"></i>
                            <span><?php echo esc_html(get_theme_mod('feane_phone', __('Call +01 1234567890', 'feane'))); ?></span>
                        </a>
                        <a href="mailto:<?php echo esc_attr(get_theme_mod('feane_email', 'demo@gmail.com')); ?>">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            <span><?php echo esc_html(get_theme_mod('feane_email', 'demo@gmail.com')); ?></span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 footer-col">
                <div class="footer_detail">
                    <?php
                    if (has_custom_logo()) {
                        the_custom_logo();
                    } else {
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="footer-logo">
                            <?php bloginfo('name'); ?>
                        </a>
                        <?php
                    }
                    ?>
                    <p><?php echo esc_html(get_theme_mod('feane_footer_description', __('Nécessaire, faisant de ceci le premier vrai générateur sur Internet. Il utilise un dictionnaire de plus de 200 mots latins, combiné avec', 'feane'))); ?></p>
                    <div class="footer_social">
                        <?php if (get_theme_mod('feane_facebook')): ?>
                            <a href="<?php echo esc_url(get_theme_mod('feane_facebook')); ?>">
                                <i class="fa fa-facebook" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (get_theme_mod('feane_twitter')): ?>
                            <a href="<?php echo esc_url(get_theme_mod('feane_twitter')); ?>">
                                <i class="fa fa-twitter" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (get_theme_mod('feane_linkedin')): ?>
                            <a href="<?php echo esc_url(get_theme_mod('feane_linkedin')); ?>">
                                <i class="fa fa-linkedin" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (get_theme_mod('feane_instagram')): ?>
                            <a href="<?php echo esc_url(get_theme_mod('feane_instagram')); ?>">
                                <i class="fa fa-instagram" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (get_theme_mod('feane_pinterest')): ?>
                            <a href="<?php echo esc_url(get_theme_mod('feane_pinterest')); ?>">
                                <i class="fa fa-pinterest" aria-hidden="true"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-4 footer-col">
                <h4><?php _e('Heures d\'ouverture', 'feane'); ?></h4>
                <p><?php echo esc_html(get_theme_mod('feane_opening_days', __('Tous les jours', 'feane'))); ?></p>
                <p><?php echo esc_html(get_theme_mod('feane_opening_hours', __('10.00 Am -10.00 Pm', 'feane'))); ?></p>
            </div>
        </div>
        <div class="footer-info">
            <p>
                &copy; <span id="displayYear"></span> <?php _e('Tous droits réservés par', 'feane'); ?>
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
            </p>
        </div>
    </div>
</footer>

<!-- Scripts -->
<?php wp_footer(); ?>

<script>
// Script pour afficher l'année actuelle
document.getElementById('displayYear').innerHTML = new Date().getFullYear();
</script>

</body>
</html> 