<?php
/**
 * Personnalisation du thème Feane
 */

// Empêcher l'accès direct
if (!defined('ABSPATH')) {
    exit;
}

function feane_customize_register($wp_customize) {
    // Section Hero
    $wp_customize->add_section('feane_hero_section', array(
        'title' => __('Section Hero', 'feane'),
        'priority' => 30,
    ));

    // Image de fond du hero
    $wp_customize->add_setting('feane_hero_image', array(
        'default' => get_template_directory_uri() . '/images/hero-bg.jpg',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'feane_hero_image', array(
        'label' => __('Image de fond du hero', 'feane'),
        'section' => 'feane_hero_section',
        'settings' => 'feane_hero_image',
    )));

    // Section À propos
    $wp_customize->add_section('feane_about_section', array(
        'title' => __('Section À propos', 'feane'),
        'priority' => 35,
    ));

    // Titre de la section à propos
    $wp_customize->add_setting('feane_about_title', array(
        'default' => __('Nous sommes Feane', 'feane'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('feane_about_title', array(
        'label' => __('Titre de la section à propos', 'feane'),
        'section' => 'feane_about_section',
        'type' => 'text',
    ));

    // Texte de la section à propos
    $wp_customize->add_setting('feane_about_text', array(
        'default' => __('Il existe de nombreuses variations de passages de Lorem Ipsum disponibles, mais la majorité ont subi une altération sous une forme ou une autre, par humour injecté, ou des mots aléatoires qui ne semblent même pas légèrement crédibles.', 'feane'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('feane_about_text', array(
        'label' => __('Texte de la section à propos', 'feane'),
        'section' => 'feane_about_section',
        'type' => 'textarea',
    ));

    // Image de la section à propos
    $wp_customize->add_setting('feane_about_image', array(
        'default' => get_template_directory_uri() . '/images/about-img.png',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'feane_about_image', array(
        'label' => __('Image de la section à propos', 'feane'),
        'section' => 'feane_about_section',
        'settings' => 'feane_about_image',
    )));

    // Section Contact
    $wp_customize->add_section('feane_contact_section', array(
        'title' => __('Informations de contact', 'feane'),
        'priority' => 40,
    ));

    // Adresse
    $wp_customize->add_setting('feane_address', array(
        'default' => __('Localisation', 'feane'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('feane_address', array(
        'label' => __('Adresse', 'feane'),
        'section' => 'feane_contact_section',
        'type' => 'text',
    ));

    // Lien de l'adresse
    $wp_customize->add_setting('feane_address_link', array(
        'default' => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('feane_address_link', array(
        'label' => __('Lien de l\'adresse (Google Maps)', 'feane'),
        'section' => 'feane_contact_section',
        'type' => 'url',
    ));

    // Téléphone
    $wp_customize->add_setting('feane_phone', array(
        'default' => __('Call +01 1234567890', 'feane'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('feane_phone', array(
        'label' => __('Téléphone', 'feane'),
        'section' => 'feane_contact_section',
        'type' => 'text',
    ));

    // Email
    $wp_customize->add_setting('feane_email', array(
        'default' => 'demo@gmail.com',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('feane_email', array(
        'label' => __('Email', 'feane'),
        'section' => 'feane_contact_section',
        'type' => 'email',
    ));

    // Section Réseaux sociaux
    $wp_customize->add_section('feane_social_section', array(
        'title' => __('Réseaux sociaux', 'feane'),
        'priority' => 45,
    ));

    // Facebook
    $wp_customize->add_setting('feane_facebook', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('feane_facebook', array(
        'label' => __('Facebook', 'feane'),
        'section' => 'feane_social_section',
        'type' => 'url',
    ));

    // Twitter
    $wp_customize->add_setting('feane_twitter', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('feane_twitter', array(
        'label' => __('Twitter', 'feane'),
        'section' => 'feane_social_section',
        'type' => 'url',
    ));

    // LinkedIn
    $wp_customize->add_setting('feane_linkedin', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('feane_linkedin', array(
        'label' => __('LinkedIn', 'feane'),
        'section' => 'feane_social_section',
        'type' => 'url',
    ));

    // Instagram
    $wp_customize->add_setting('feane_instagram', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('feane_instagram', array(
        'label' => __('Instagram', 'feane'),
        'section' => 'feane_social_section',
        'type' => 'url',
    ));

    // Pinterest
    $wp_customize->add_setting('feane_pinterest', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('feane_pinterest', array(
        'label' => __('Pinterest', 'feane'),
        'section' => 'feane_social_section',
        'type' => 'url',
    ));

    // Section Heures d'ouverture
    $wp_customize->add_section('feane_hours_section', array(
        'title' => __('Heures d\'ouverture', 'feane'),
        'priority' => 50,
    ));

    // Jours d'ouverture
    $wp_customize->add_setting('feane_opening_days', array(
        'default' => __('Tous les jours', 'feane'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('feane_opening_days', array(
        'label' => __('Jours d\'ouverture', 'feane'),
        'section' => 'feane_hours_section',
        'type' => 'text',
    ));

    // Heures d'ouverture
    $wp_customize->add_setting('feane_opening_hours', array(
        'default' => __('10.00 Am -10.00 Pm', 'feane'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('feane_opening_hours', array(
        'label' => __('Heures d\'ouverture', 'feane'),
        'section' => 'feane_hours_section',
        'type' => 'text',
    ));

    // Section Footer
    $wp_customize->add_section('feane_footer_section', array(
        'title' => __('Footer', 'feane'),
        'priority' => 55,
    ));

    // Description du footer
    $wp_customize->add_setting('feane_footer_description', array(
        'default' => __('Nécessaire, faisant de ceci le premier vrai générateur sur Internet. Il utilise un dictionnaire de plus de 200 mots latins, combiné avec', 'feane'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('feane_footer_description', array(
        'label' => __('Description du footer', 'feane'),
        'section' => 'feane_footer_section',
        'type' => 'textarea',
    ));
}
add_action('customize_register', 'feane_customize_register');

// Charger les scripts de personnalisation
function feane_customize_preview_js() {
    wp_enqueue_script('feane-customizer', get_template_directory_uri() . '/js/customizer.js', array('customize-preview'), '1.0.0', true);
}
add_action('customize_preview_init', 'feane_customize_preview_js'); 