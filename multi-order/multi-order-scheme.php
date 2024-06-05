<?php
/**
 * Plugin Name: Multi Order system
 * Description: Lar kunder lage flere bestillinger med forskjellige leveringsadresser fra et enkelt grensesnitt.
 * Version: 1.0
 * Author: Gustav Öman
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Include the shortcode function
include_once plugin_dir_path( __FILE__ ) . 'includes/shortcode.php';

// Include the order processing function
include_once plugin_dir_path( __FILE__ ) . 'includes/process-orders.php';

// Enqueue scripts and styles
function mos_enqueue_scripts() {
    wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js', array('jquery'), '4.1.0', true);
    wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css', array(), '4.1.0');
    wp_enqueue_style('mos-custom-styles', plugin_dir_url(__FILE__) . 'includes/style.css', array(), '1.0');
    wp_enqueue_script('mos-custom-scripts', plugin_dir_url(__FILE__) . 'includes/scripts.js', array('jquery', 'select2'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'mos_enqueue_scripts');
?>
