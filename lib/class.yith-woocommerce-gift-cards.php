<?php
if ( ! defined ( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


if ( ! class_exists ( 'YITH_WooCommerce_Gift_Cards' ) ) {

    /**
     *
     * @class   YITH_WooCommerce_Gift_Cards
     * @package Yithemes
     * @since   1.0.0
     * @author  Your Inspiration Themes
     */
    class YITH_WooCommerce_Gift_Cards {

        protected static $_db_version = YITH_YWGC_DB_VERSION;

        /**
         * Single instance of the class
         *
         * @since 1.0.0
         */
        protected static $instance;

        /**
         * Returns single instance of the class
         *
         * @since 1.0.0
         */
        public static function get_instance () {
            if ( is_null ( self::$instance ) ) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /** @var YWGC_Gift_Cards $gift_cards_instance */
        private $gift_cards_instance;

				/**
				 * Shop's base currency. Used for caching.
				 * @var string
				 * @since 1.0.7
				 */
				protected static $base_currency;

				/**
				 * Convenience method. Returns WooCommerce base currency.
				 *
				 * @return string
				 * @since 1.0.7
				 */
				public static function base_currency() {
					if(empty(self::$base_currency)) {
						self::$base_currency = get_option('woocommerce_currency');
					}
					return self::$base_currency;
				}

				/**
				 * Basic integration with WooCommerce Currency Switcher, developed by Aelia
				 * (https://aelia.co). This method can be used by any 3rd party plugin to
				 * return prices converted to the active currency.
				 *
				 * @param double amount The source price.
				 * @param string to_currency The target currency. If empty, the active currency
				 * will be taken.
				 * @param string from_currency The source currency. If empty, WooCommerce base
				 * currency will be taken.
				 * @return double The price converted from source to destination currency.
				 * @author Aelia <support@aelia.co>
				 * @link https://aelia.co
				 * @since 1.0.7
				 */
				public static function get_amount_in_currency($amount, $to_currency = null, $from_currency = null) {
					if(empty($from_currency)) {
						$from_currency = self::base_currency();
					}
					if(empty($to_currency)) {
						$to_currency = get_woocommerce_currency();
					}
					return apply_filters('wc_aelia_cs_convert', $amount, $from_currency, $to_currency);
				}

        /**
         * Constructor
         *
         * Initialize plugin and registers actions and filters to be used
         *
         * @since  1.0
         * @author Lorenzo Giuffrida
         */
        protected function __construct () {

            $this->init_plugin_settings ();

            /**
             * Do some stuff on plugin init
             */
            add_action ( 'init', array ( $this, 'on_plugin_init' ) );

            /** Add styles and scripts */
            add_action ( 'wp_enqueue_scripts', array ( $this, 'enqueue_frontend_files' ) );
            add_action ( 'admin_enqueue_scripts', array ( $this, 'enqueue_backend_files' ) );

            /**
             * Add the "Gift card" type to product type list
             */
            add_filter ( 'product_type_selector', array ( $this, 'add_gift_card_product_type' ) );

            /**
             * Append gift card amount generation controls
             */
            add_action ( 'woocommerce_product_options_sku', array ( $this, 'show_gift_card_product_content' ) );

            /**
             * * Save gift card amount when a product is saved
             */
            add_action ( 'save_post', array ( $this, 'save_gift_card' ), 1, 2 );

            /**
             * Ajax call for adding and removing gift card amount
             */
            add_action ( 'wp_ajax_add_gift_card_amount', array ( $this, 'add_gift_card_amount_callback' ) );
            add_action ( 'wp_ajax_remove_gift_card_amount', array ( $this, 'remove_gift_card_amount_callback' ) );

            add_action ( 'woocommerce_gift-card_add_to_cart', array ( $this, 'variable_add_to_cart' ), 30 );

            /*
             * Custom add_to_cart handler for gift card product type
             */
            add_action ( 'woocommerce_add_to_cart_handler_gift-card', array ( $this, 'add_to_cart_handler' ) );

            add_filter ( 'woocommerce_get_cart_item_from_session', array ( $this, 'woocommerce_get_cart_item_from_session' ), 10, 3 );

            add_action ( 'woocommerce_add_order_item_meta', array ( $this, 'add_gift_card_item_meta' ), 10, 3 );

            add_filter ( 'woocommerce_checkout_coupon_message', array ( $this, 'ask_for_coupon_or_gift_card' ) );

            add_action ( 'woocommerce_order_status_completed', array ( $this, 'generate_gift_card_number' ) );

            /*
             * Enable coupons in cart page when this plugin is enable, so a gift code is possibile but
             * don't permit coupon code if coupons are disabled
             */
            add_filter ( 'woocommerce_coupons_enabled', array ( $this, 'show_field_for_gift_code' ) );

            add_filter ( 'woocommerce_attribute_label', array ( $this, 'modify_attribute_label' ), 10, 3 );

            add_filter ( 'woocommerce_hidden_order_itemmeta', array ( $this, 'hide_item_meta' ) );

            /**
             * Verify if a coupon code inserted on cart page or checkout page belong to a valid gift card.
             * In this case, make the gift card working as a temporary coupon
             */
            add_filter ( 'woocommerce_get_shop_coupon_data', array ( $this, 'verify_coupon_code' ), 10, 2 );

            /*
             * Check if a gift card discount code was used and deduct the amount from the gift card.
             */
            add_action ( 'woocommerce_order_add_coupon', array ( $this, 'deduct_amount_from_gift_card' ), 10, 5 );

            /**
             * Prevent more than one order to get the gift card amount applied
             */
            add_action ( 'woocommerce_after_checkout_validation', array ( $this, 'woocommerce_after_checkout_validation' ) );

            add_filter ( 'woocommerce_update_cart_action_cart_updated', array ( $this, 'woocommerce_update_cart_action_cart_updated' ) );

						/* Aelia
						 * Multi-currency support
						 */
						add_filter('wc_aelia_currencyswitcher_product_convert_callback', array ( $this, 'wc_aelia_currencyswitcher_product_convert_callback' ), 10, 2 );
        }


        /**
         * Init plugin settings
         */
        public function init_plugin_settings () {
            global $GIFTS;
            $this->gift_cards_instance = $GIFTS;
        }

        /**
         * Add some data to the options table
         *
         */
        public static function init_db () {
            /**
             * If exists yith_product_vendors_db_version option return null
             */
            if ( get_option ( 'yith_gift_cards_db_version' ) ) {
                return;
            }

            //  Initialize database tables...
            global $wpdb;

            //  Update metakey from YITH Gift Cards 1.0.0
            $query = "Update {$wpdb->prefix}woocommerce_order_itemmeta
                        set meta_key = '" . YWGC_META_GIFT_CARD_POST_ID . "'
                        where meta_key = 'gift_card_post_id'";
            $wpdb->query ( $query );

            add_option ( 'yith_gift_cards_db_version', self::$_db_version );
        }

        /**
         *  Execute all the operation need when the plugin init
         */
        public function on_plugin_init () {
            $this->init_post_type ();
        }

        /**
         * Add frontend style
         *
         * @since  1.0
         * @author Lorenzo Giuffrida
         */
        public function enqueue_frontend_files () {/*
            if (! is_product()) {
                return;
            }*/

            $suffix = defined ( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

            //  register and enqueue ajax calls related script file
            wp_register_script ( "ywgc-frontend", YITH_YWGC_URL . 'assets/js/ywgc-frontend' . $suffix . '.js', array (
                'jquery',
                'woocommerce',
            ) );
            wp_enqueue_script ( "ywgc-frontend" );

            wp_enqueue_style ( 'ywgc-frontend-css', YITH_YWGC_ASSETS_URL . '/css/ywgc-frontend.css' );
        }

        /**
         * Enqueue scripts on administration comment page
         *
         * @param $hook
         */
        function enqueue_backend_files ( $hook ) {
            global $post_type;
            $suffix = defined ( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
            /*
                        if (YWGC_CUSTOM_POST_TYPE_NAME != $post_type) {
                            return;
                        }
            */
            /**
             * Add styles
             */
            wp_enqueue_style ( 'ywgc-backend-css', YITH_YWGC_ASSETS_URL . '/css/ywgc-backend.css' );

            /**
             * Add scripts
             */
            wp_register_script ( "ywgc-backend", YITH_YWGC_URL . 'assets/js/ywgc-backend' . $suffix . '.js', array (
                'jquery',
                'jquery-blockui',
            ) );

            wp_localize_script ( 'ywgc-backend', 'ywgc', array (
                'loader'   => apply_filters ( 'yith_questions_and_answers_loader', YITH_YWGC_ASSETS_URL . '/images/loading.gif' ),
                'ajax_url' => admin_url ( 'admin-ajax.php' ),
            ) );

            wp_enqueue_script ( "ywgc-backend" );
        }

        /**
         * Add the "Gift card" type to product type list
         */
        public function add_gift_card_product_type ( $types ) {
            $types[ 'gift-card' ] = __ ( "Gift card", "yith-woocommerce-gift-cards" );

            return $types;
        }

        /**
         * Show the gift card amounts list
         *
         * @param $product_id gift card product id
         */
        private function show_gift_card_amount_list ( $product_id ) {
            $amounts = $this->gift_cards_instance->get_gift_card_product_amounts ( $product_id );
            ?>
            <p class="form-field _gift_card_amount_field">

                <?php if ( $amounts ): ?>
                    <?php foreach ( $amounts as $amount ) : ?>
                        <span class="variation-amount"><?php echo wc_price ( $amount ); ?>
                            <input type="hidden" name="gift-card-amounts[]" value="<?php _e ( $amount ); ?>">
                        <a href="#" class="remove-amount"></a></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span
                        class="no-amounts"><?php _e ( "You don't have configured any gift card yet", "yith-woocommerce-gift-cards" ); ?></span>
                <?php endif; ?>
            </p>
            <?php
        }

        /**
         * Retrieve the html content that shows the gift card amounts list
         *
         * @param $product_id int gift card product id
         *
         * @return string
         */
        private function gift_card_amount_list_html ( $product_id ) {
            ob_start ();
            $this->show_gift_card_amount_list ( $product_id );
            $html = ob_get_contents ();
            ob_end_clean ();

            return $html;
        }

        /**
         * Show controls on backend product page to let create the gift card price
         */
        public function show_gift_card_product_content () {
            global $post, $thepostid;
            ?>
            <div class="show_if_gift-card">
                <p class="form-field">
                    <label><?php _e ( "Gift card amount", "yith-woocommerce-gift-cards" ); ?></label>
                <span class="wrap add-new-amount-section">
                    <input type="text" id="gift_card-amount" name="gift_card-amount" class="short" style=""
                           placeholder="">
                    <a href="#" class="add-new-amount"><?php _e ( "Add", "yith-woocommerce-gift-cards" ); ?></a>
                </span>
                </p>
                <?php
                $this->show_gift_card_amount_list ( $thepostid );
                ?>
            </div>
            <?php
        }

        /**
         * Save gift card amount when a product is saved
         *
         * @param $post_id
         * @param $post
         *
         * @return mixed
         */
        function save_gift_card ( $post_id, $post ) {

            $product = wc_get_product ( $post_id );
            if ( null == $product ) {
                return;
            }

            if ( ! isset( $_POST[ "product-type" ] ) || ( 'gift-card' != $_POST[ "product-type" ] ) ) {

                return;
            }

            // verify this is not an auto save routine.
            if ( defined ( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return;
            }

            $amounts = isset( $_POST[ "gift-card-amounts" ] ) ? $_POST[ "gift-card-amounts" ] : array ();

            /**
             * Update gift card amounts
             */
            $this->gift_cards_instance->save_gift_card_amounts ( $post_id, $amounts );
        }

        /**
         * Add a new amount to a gift card prdduct
         *
         * @since  1.0
         * @author Lorenzo Giuffrida
         */
        public function add_gift_card_amount_callback () {
            $amount = number_format ( $_POST[ 'amount' ], 2, wc_get_price_decimal_separator (), '' );

            $product_id = intval ( $_POST[ 'product_id' ] );

            $res = $this->gift_cards_instance->add_amount_to_gift_card ( $product_id, $amount );

            wp_send_json ( array ( "code" => $res, "value" => $this->gift_card_amount_list_html ( $product_id ) ) );
        }

        /**
         * Remove amount to a gift card prdduct
         *
         * @since  1.0
         * @author Lorenzo Giuffrida
         */
        public function remove_gift_card_amount_callback () {
            $amount     = number_format ( $_POST[ 'amount' ], 2, wc_get_price_decimal_separator (), '' );
            $product_id = intval ( $_POST[ 'product_id' ] );

            $res = $this->gift_cards_instance->remove_amount_to_gift_card ( $product_id, $amount );

            wp_send_json ( array ( "code" => $res ) );
        }

        public function variable_add_to_cart () {
            global $product;

            // Load the template
            wc_get_template ( 'single-product/add-to-cart/gift-card.php', '', '', trailingslashit ( YITH_YWGC_TEMPLATES_DIR ) );
        }

        /*
         * Custom add_to_cart handler for gift card product type
         */
        public function add_to_cart_handler () {
            $product_id = absint ( $_REQUEST[ 'add-to-cart' ] );
            $quantity   = $_REQUEST[ 'quantity' ];
            $amount     = $_REQUEST[ 'gift_amounts' ];

            for ( $i = 0; $i < $quantity; $i ++ ) {
                $new_gift_card             = new YWGC_Gift_Card();
                $new_gift_card->product_id = $product_id;
                $new_gift_card->set_amount ( $amount );
                WC ()->cart->add_to_cart ( $product_id, 1, 0, array (), (array)$new_gift_card );
            }
            wc_add_to_cart_message ( $product_id );

            return true;
            /*
            if (WC()->cart->add_to_cart($product_id, $quantity, 0, array(), $gift_cards) !== false) {
                wc_add_to_cart_message($product_id);
                return true;
            }
            return false;
*/
        }

        public function woocommerce_get_cart_item_from_session ( $session_data, $values, $key ) {
            if ( ! ( $session_data[ "data" ] instanceof WC_Product_Gift_Card ) ) {
                return $session_data;
            }

            if ( isset( $session_data[ 'amount' ] ) ) {
                $session_data[ 'data' ]->set_price ( $session_data[ 'amount' ] );
            }

            return $session_data;
        }

        public function add_gift_card_item_meta ( $item_id, $values, $cart_item_key ) {
            if ( ! ( $values[ "data" ] instanceof WC_Product_Gift_Card ) ) {
                return $item_id;
            }
        }

        public function ask_for_coupon_or_gift_card ( $text ) {
            return __ ( 'Do you have a coupon or a gift card?', 'yith-woocommerce-gift-cards' ) . ' <a href="#" class="showcoupon">' . __ ( 'Click here to enter your code', 'woocommerce' ) . '</a>';
        }

        /**
         * Register the custom post type
         */
        public function init_post_type () {
            $args = array (
                'label'               => __ ( 'Gift Cards', 'ywqa' ),
                'description'         => __ ( 'Gift Cards', 'ywqa' ),
                //'labels' => $labels,
                // Features this CPT supports in Post Editor
                'supports'            => array (
                    //'title',
                    'editor',
                    //'author',
                ),
                'hierarchical'        => false,
                'public'              => false,
                'show_ui'             => false,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => false,
                'show_in_admin_bar'   => false,
                'menu_position'       => 9,
                'can_export'          => false,
                'has_archive'         => false,
                'exclude_from_search' => true,
                'menu_icon'           => 'dashicons-clipboard',
                'query_var'           => false,
            );

            // Registering your Custom Post Type
            register_post_type ( YWGC_CUSTOM_POST_TYPE_NAME, $args );
        }

        /**
         * When the order is completed, generate a card number for every gift card product
         */
        public function generate_gift_card_number ( $order_id ) {
            // order object (optional but handy)
            $order = new WC_Order( $order_id );

            foreach ( $order->get_items ( 'line_item' ) as $order_item_id => $order_item_data ) {
                $product_id = $order_item_data[ "product_id" ];
                $amount     = $order_item_data[ "line_total" ];

                $product = wc_get_product ( $product_id );

                if ( ! ( $product instanceof WC_Product_Gift_Card ) ) {
                    continue;
                }

                $gift_card = $this->gift_cards_instance->create_gift_card ( $product_id, $order_id, $amount );

                //todo save gift card number on order completed
                wc_update_order_item_meta ( $order_item_id, YWGC_META_GIFT_CARD_NUMBER, $gift_card->gift_card_number );
                wc_update_order_item_meta ( $order_item_id, YWGC_META_GIFT_CARD_POST_ID, $gift_card->ID );
            }
        }

        /*
            * Enable coupons in cart page when this plugin is enable, so a gift code is possibile but
            * don't permit coupon code if coupons are disabled
            */
        public function show_field_for_gift_code ( $coupon_enabled ) {

            if ( is_cart () ) {
                ?>
                <div class="<?php echo apply_filters ( 'yith_ywgc_cart_discount_classes', "coupon" ); ?>">

                    <label
                        for="discount_code"><?php echo apply_filters ( 'yith_ywgc_cart_discount_label', "Discount code:" ); ?></label>
                    <input type="text" name="coupon_code" class="input-text" id="discount_code" value=""
                           placeholder="<?php echo apply_filters ( 'yith_ywgc_cart_discount_placeholder', "Discount code" ); ?>">
                    <input type="submit" class="button" name="apply_coupon"
                           value="<?php echo apply_filters ( 'yith_ywgc_cart_discount_submit_text', "Apply discount" ); ?>">

                </div>
                <?php
                return false;
            }

            return $coupon_enabled;

        }

        public function modify_attribute_label ( $attribute_label, $meta_key, $product = false ) {
            global $pagenow;

            if ( $product && 'post.php' == $pagenow && isset( $_GET[ 'post' ] ) && $order = wc_get_order ( $_GET[ 'post' ] ) ) {
                $line_items = $order->get_items ( 'line_item' );
                foreach ( $line_items as $line_item_id => $line_item ) {
                    if ( $line_item[ 'product_id' ] == $product->id ) {
                        $attribute_label = 'gift_card_number' == $meta_key ? __ ( 'Gift card code', 'yith-woocommerce-gift-cards' ) : $attribute_label;
                    }
                }
            }

            return $attribute_label;
        }

        public function hide_item_meta ( $args ) {
            $args[] = 'gift_card_post_id';

            return $args;
        }

        /**
         * Verify if a coupon code inserted on cart page or checkout page belong to a valid gift card.
         * In this case, make the gift card working as a temporary coupon
         */
        public function verify_coupon_code ( $return_val, $code ) {
            $gift_card = $this->gift_cards_instance->get_gift_card_by_code ( $code );

            if ( null == $gift_card ) {
                return $return_val;
            }

            if ( $gift_card->ID ) {
                $temp_coupon_array = array (
                    'discount_type' => 'fixed_cart',
                    'coupon_amount' => $gift_card->get_amount (),
                );

                return $temp_coupon_array;
            }

            return $return_val;
        }

        /*
          * Check if a gift card discount code was used and deduct the amount from the gift card.
          */
        public function deduct_amount_from_gift_card ( $id, $item_id, $code, $discount_amount, $discount_amount_tax ) {
            $gift = $this->gift_cards_instance->get_gift_card_by_code ( $code );
            if ( $gift != null ) {
                $gift->deduct_amount_from_gift_card ( $discount_amount );
            }
        }

        /**
         * Prevent the current order to completed if the gift card code is no more valid
         */
        public function woocommerce_after_checkout_validation ( $posted ) {

            $gift_cards_used = WC ()->cart->coupon_discount_amounts;

            if ( $gift_cards_used ) {
                foreach ( $gift_cards_used as $code => $amount ) {
                    //  Check if the code belong to a gift card and there is enough credit
                    //  to cover the amount requested.

                    $gift = $this->gift_cards_instance->get_gift_card_by_code ( $code );

                    if ( ( $gift != null ) && ! $gift->has_credit ( $amount ) ) {
                        wc_add_notice ( sprintf ( __ ( "The gift card identified by the code %s has no credit left.", "yith-woocommerce-gift-cards" ), $code ), "error" );
                    }
                }
            }
        }

        public function woocommerce_update_cart_action_cart_updated ( $cart_udated ) {
            //todo check this

            return $cart_udated;
        }

				/**
				 * Callback to support currency conversion of Gift Card products.
				 *
				 * @param callable callback The original callback passed by the Currency
				 * Switcher.
				 * @param WC_Product product The product to convers.
				 * @return callable The callback that will perform the conversion.
				 * @since 1.0.6
				 * @author Aelia <support@aelia.co>
				 */
				public function wc_aelia_currencyswitcher_product_convert_callback($callback, $product) {
					//var_dump($product);die();

					if($product instanceof WC_Product_Gift_Card) {
						$callback = array($this, 'convert_gift_card_prices');
					}
					return $callback;
				}

				/**
				 * Converts the prices of a gift card product to the specified currency.
				 *
				 * @param WC_Product_Gift_Card product A variable product.
				 * @param string currency A currency code.
				 * @return WC_Product_Gift_Card The product with converted prices.
				 * @since 1.0.6
				 * @author Aelia <support@aelia.co>
				 */
				public function convert_gift_card_prices($product, $currency) {
					$product->min_price = $this->get_amount_in_currency($product->min_price);
					$product->max_price = $this->get_amount_in_currency($product->max_price);

					foreach($product->amounts as $idx => $amount) {
						$product->amounts[$idx] = $this->get_amount_in_currency($product->amounts[$idx]);
					}
					if(!empty($product->price)) {
						$product->price = $this->get_amount_in_currency($product->price);
					}

					return $product;
				}
			}
}
