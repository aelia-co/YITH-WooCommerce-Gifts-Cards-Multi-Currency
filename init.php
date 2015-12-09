<?php
/*
Plugin Name: YITH WooCommerce Gift Cards
Plugin URI: https://yithemes.com/themes/plugins/yith-woocommerce-gift-cards/
Description: Allow your users to purchase and give gift cards, an easy and direct way to encourage new sales.
Author: Yithemes
Text Domain: yith-woocommerce-gift-cards
Version: 1.0.6
Author URI: http://yithemes.com/
*/

if ( ! defined ( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists ( 'is_plugin_active' ) ) {
    require_once ( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! function_exists ( 'yith_ywgc_install_woocommerce_admin_notice' ) ) {
    function yith_ywgc_install_woocommerce_admin_notice () {
        ?>
        <div class="error">
            <p><?php _e ( 'YITH WooCommerce Gift Cards is enabled but not effective. It requires WooCommerce in order to work.', 'yit' ); ?></p>
        </div>
        <?php
    }
}

if ( ! function_exists ( 'yith_ywgc_install_free_admin_notice' ) ) {

    function yith_ywgc_install_free_admin_notice () {
        ?>
        <div class="error">
            <p><?php _e ( 'You can\'t activate the free version of YITH WooCommerce Gift Cards while you are using the premium one.', 'yit' ); ?></p>
        </div>
        <?php
    }
}

if ( ! function_exists ( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook ( __FILE__, 'yith_plugin_registration_hook' );

//region    ****    Define constants

if ( ! defined ( 'YITH_YWGC_FREE_INIT' ) ) {
    define ( 'YITH_YWGC_FREE_INIT', plugin_basename ( __FILE__ ) );
}

if ( ! defined ( 'YITH_YWGC_VERSION' ) ) {
    define ( 'YITH_YWGC_VERSION', '1.0.6' );
}

if ( ! defined ( 'YITH_YWGC_DB_VERSION' ) ) {
    define ( 'YITH_YWGC_DB_VERSION', '1.0.0' );
}

if ( ! defined ( 'YITH_YWGC_FILE' ) ) {
    define ( 'YITH_YWGC_FILE', __FILE__ );
}

if ( ! defined ( 'YITH_YWGC_DIR' ) ) {
    define ( 'YITH_YWGC_DIR', plugin_dir_path ( __FILE__ ) );
}

if ( ! defined ( 'YITH_YWGC_URL' ) ) {
    define ( 'YITH_YWGC_URL', plugins_url ( '/', __FILE__ ) );
}

if ( ! defined ( 'YITH_YWGC_ASSETS_URL' ) ) {
    define ( 'YITH_YWGC_ASSETS_URL', YITH_YWGC_URL . 'assets' );
}

if ( ! defined ( 'YITH_YWGC_TEMPLATES_DIR' ) ) {
    define ( 'YITH_YWGC_TEMPLATES_DIR', YITH_YWGC_DIR . 'templates' );
}

if ( ! defined ( 'YITH_YWGC_ASSETS_IMAGES_URL' ) ) {
    define ( 'YITH_YWGC_ASSETS_IMAGES_URL', YITH_YWGC_ASSETS_URL . '/images/' );
}

$wp_upload_dir = wp_upload_dir ();

if ( ! defined ( 'YITH_YWGC_SAVE_DIR' ) ) {
    define ( 'YITH_YWGC_SAVE_DIR', $wp_upload_dir[ 'basedir' ] . '/yith-gift-cards/' );
}

if ( ! defined ( 'YITH_YWGC_SAVE_URL' ) ) {
    define ( 'YITH_YWGC_SAVE_URL', $wp_upload_dir[ 'baseurl' ] . '/yith-gift-cards/' );
}

//endregion

/* Plugin Framework Version Check */
if ( ! function_exists ( 'yit_maybe_plugin_fw_loader' ) && file_exists ( YITH_YWGC_DIR . 'plugin-fw/init.php' ) ) {
    require_once ( YITH_YWGC_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader ( YITH_YWGC_DIR );

require_once ( YITH_YWGC_DIR . 'functions.php' );


function yith_ywgc_init () {

    /**
     * Load text domain and start plugin
     */
    load_plugin_textdomain ( 'yith-woocommerce-gift-cards', false, dirname ( plugin_basename ( __FILE__ ) ) . '/languages/' );

    YWGC_Plugin_FW_Loader::get_instance ();
    global $YWGC, $GIFTS;

    $GIFTS = YWGC_Gift_Cards::get_instance ();
    $YWGC  = YITH_WooCommerce_Gift_Cards::get_instance ();
}

add_action ( 'yith_ywgc_init', 'yith_ywgc_init' );

function yith_ywgc_install () {

    if ( ! function_exists ( 'WC' ) ) {
        add_action ( 'admin_notices', 'yith_ywgc_install_woocommerce_admin_notice' );
    } elseif ( defined ( 'YITH_YWGC_PREMIUM' ) ) {
        add_action ( 'admin_notices', 'yith_ywgc_install_free_admin_notice' );
        deactivate_plugins ( plugin_basename ( __FILE__ ) );
    } else {
        do_action ( 'yith_ywgc_init' );
    }
}

add_action ( 'plugins_loaded', 'yith_ywgc_install', 11 );

//  Init database vars
register_activation_hook ( YITH_YWGC_FILE, 'YITH_WooCommerce_Gift_Cards::init_db' );
