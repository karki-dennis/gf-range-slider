<?php
/*
Plugin Name: Gravity Forms – Slider Field
Description: Adds a “Slider” field under Advanced Fields with configurable min, max & step.
Version:     1.1
Author:      Digital Roo
*/

add_action( 'gform_loaded', 'gf_slider_field_register', 5 );
function gf_slider_field_register() {
    if ( class_exists( 'GF_Fields' ) ) {
        require_once __DIR__ . '/class-gf-field-slider.php';
    }
}


add_action( 'wp_enqueue_scripts', 'gf_slider_enqueue_styles' );
function gf_slider_enqueue_styles() {
    wp_enqueue_style(
        'gf-slider-field',
        plugin_dir_url( __FILE__ ) . 'assets/main.css',
        [],
        '1.0'
    );
}
