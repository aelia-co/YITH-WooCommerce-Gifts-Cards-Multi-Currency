<?php
if ( ! defined ( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

/**
 * ******************************************************
 * Include plugin files
 */
require_once ( YITH_YWGC_DIR . 'lib/class.yith-woocommerce-gift-cards.php' );
require_once ( YITH_YWGC_DIR . 'lib/class.ywgc-product-gift-card.php' );
require_once ( YITH_YWGC_DIR . 'lib/class.ywgc-gift-cards.php' );
require_once ( YITH_YWGC_DIR . 'lib/class.ywgc-gift-card.php' );
require_once ( YITH_YWGC_DIR . 'lib/class.ywgc-plugin-fw-loader.php' );

/**
 * ******************************************************
 * Define functions
 */
if ( ! function_exists ( "yith_define" ) ) {
    /**
     * Defined a constant if not already defined
     *
     * @param string $name  The constant name
     * @param mixed  $value The value
     */
    function yith_define ( $name, $value = true ) {
        if ( ! defined ( $name ) ) {
            define ( $name, $value );
        }
    }
}

/**
 * ******************************************************
 * Define constant
 */
yith_define ( 'YWGC_CUSTOM_POST_TYPE_NAME', 'gift_card' );
yith_define ( 'YWGC_AMOUNTS', '_gift_card_amounts' );
yith_define ( 'YWGC_META_GIFT_CARD_AMOUNT', '_gift_card_amount' );
yith_define ( 'YWGC_META_GIFT_CARD_ORDER_ID', '_gift_card_order_id' );
yith_define ( 'YWGC_META_GIFT_CARD_NUMBER', 'gift_card_number' );
yith_define ( 'YWGC_META_GIFT_CARD_POST_ID', '_ywgc_gift_card_post_id' );