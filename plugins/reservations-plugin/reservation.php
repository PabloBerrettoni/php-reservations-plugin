<?php
/**
 * @package Reservations 
 * @version 0.2
 */
/*
Plugin Name: Reservations Plugin
Plugin URI: http://wordpress.org/plugins/
Description: test
Author: PukaraIT
Version: 0.1
Author URI: http://www.pukara.es
*/

/**
 * include create tables of database
 */
include('create-tables.php');

/**
 * include the necessary functions
 */
include('functions.php');

/**
 * include admin menu "Restaurant information"
 */
include('admin-menu.php');

/**
 * include admin menu "Reservations"
 */
include('reserves-menu.php');

/**
 * include admin menu "Reserves Options"
 */
include('reserves-options.php');

/**
 * enqueue scripts y styles
 */
function utm_user_scripts() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'reservartion_style',  $plugin_url . "inc/assets/css/reservations.css");
}
add_action( 'wp_enqueue_scripts', 'utm_user_scripts' );

function shortcode_enqueue_script() {   
    wp_enqueue_script( 'reservation_script', plugin_dir_url( __FILE__ ) . 'inc/assets/js/reservations.js' );
}
add_action('wp_enqueue_scripts', 'shortcode_enqueue_script');

/**
 * enqueue jquery ui
 */
wp_register_script('prefix_jquery', 'https://code.jquery.com/jquery-3.6.0.js');
wp_enqueue_script('prefix_jquery');

wp_register_script('prefix_js_jquery_ui', 'https://code.jquery.com/ui/1.13.2/jquery-ui.js');
wp_enqueue_script('prefix_js_jquery_ui');

wp_register_style('prefix_css_jquery', 'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css');
wp_enqueue_style('prefix_css_jquery');

/**
 * include shortcode
 */
include('inc/shortcodes.php');
?>
