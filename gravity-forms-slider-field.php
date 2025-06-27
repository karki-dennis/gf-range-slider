<?php
/*
Plugin Name: Gravity Forms – Slider Field
Description: Adds a "Slider" field under Advanced Fields with configurable min, max & step.
Version:     1.1
Author:      Digital Roo
Author URI:  https://digitalroo.co.uk
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html  
Text Domain: gravity-forms-slider-field
Developer:   Dennish Karki
*/
defined('ABSPATH') || exit;

add_action( 'gform_loaded', 'gf_slider_field_register', 5 );
function gf_slider_field_register() {
    if ( class_exists( 'GF_Fields' ) ) {
        require_once __DIR__ . '/class-gf-field-slider.php';
    }
}

// Function to check if current page has a form with slider
function has_form_with_slider() {
    // If in admin, return true to load scripts
    if (is_admin()) {
        return true;
    }

    // If GFAPI is not available, return false
    if (!class_exists('GFAPI')) {
        return false;
    }

    // Get all active forms
    $forms = GFAPI::get_forms();
    
    // If no forms, return false
    if (empty($forms)) {
        return false;
    }

    // Check if any form has a slider field
    foreach ($forms as $form) {
        foreach ($form['fields'] as $field) {
            if ($field->type === 'slider') {
                // Found a slider field, now check if this form is on the current page
                if (
                    // Check shortcode in content
                    has_shortcode(get_the_content(), 'gravityform') ||
                    // Check for Gutenberg blocks
                    has_block('gravityforms/form') ||
                    // Check if we're in preview mode
                    doing_action('gform_preview_init')
                ) {
                    return true;
                }
            }
        }
    }

    return false;
}

// Function to enqueue CSS styles
function gf_slider_enqueue_styles() {
    // Only load if we have a form with slider
    if (!has_form_with_slider()) {
        return;
    }


    // Custom CSS
    wp_enqueue_style(
        'gf-slider-styles',
        plugins_url('assets/main.css', __FILE__),
        array(),
        '1.0.0'
    );
}

// Function to enqueue JavaScript
function gf_slider_enqueue_scripts() {
    // Only load if we have a form with slider
    if (!has_form_with_slider()) {
        return;
    }

    // noUiSlider JS
    wp_enqueue_script(
        'nouislider',
        'https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js',
        array(),
        '15.7.1',
        true
    );

    // Init Script (depends on jQuery + nouislider)
    wp_enqueue_script(
        'gf-slider-init',
        plugins_url('assets/slider-init.js', __FILE__),
        array('jquery', 'nouislider'),
        '1.0.0',
        true
    );
}

// Hook styles into footer
add_action('wp_footer', 'gf_slider_enqueue_styles', 5);

// Hook scripts into footer
add_action('wp_footer', 'gf_slider_enqueue_scripts', 10);

// Hook into admin and preview
add_action('admin_enqueue_scripts', 'gf_slider_enqueue_styles');
add_action('admin_enqueue_scripts', 'gf_slider_enqueue_scripts');
add_action('gform_preview_init', 'gf_slider_enqueue_styles');
add_action('gform_preview_init', 'gf_slider_enqueue_scripts');

