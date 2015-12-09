<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!class_exists('YWGC_Gift_Cards')) {

    /**
     *
     * @class   YWGC_Gift_Cards
     * @package Yithemes
     * @since   1.0.0
     * @author  Your Inspiration Themes
     */
    class YWGC_Gift_Cards
    {

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
        public static function get_instance()
        {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        /**
         * Constructor
         *
         * Initialize plugin and registers actions and filters to be used
         *
         * @since  1.0
         * @author Lorenzo Giuffrida
         */
        protected function __construct()
        {

        }

        public function get_gift_card_product_amounts($product_id)
        {
            $metas = get_post_meta($product_id, YWGC_AMOUNTS, true);
            return is_array($metas) ? $metas : array();
        }

        public function save_gift_card_amounts($product_id, $amounts = array())
        {
            update_post_meta($product_id, YWGC_AMOUNTS, $amounts);
        }

        /**
         * Add a new amount to a gift card
         *
         * @param $product_id int   the gift card product id
         * @param $amount int       the amount to add
         * @return bool amount added, false if the same value still exists
         */
        public function add_amount_to_gift_card($product_id, $amount)
        {
            $amounts = $this->get_gift_card_product_amounts($product_id);

            if (!in_array($amount, $amounts)) {

                $amounts[] = $amount;
                sort($amounts, SORT_NUMERIC);
                $this->save_gift_card_amounts($product_id, $amounts);
                return true;
            }

            return false;
        }

        /**
         * Remove an amount to a gift card
         *
         * @param $product_id int   the gift card product id
         * @param $amount int       the amount to remove
         * @return bool amount added, false if the same value still exists
         */
        public function remove_amount_to_gift_card($product_id, $amount)
        {
            $amounts = $this->get_gift_card_product_amounts($product_id);

            if (in_array($amount, $amounts)) {
                if (($key = array_search($amount, $amounts)) !== false) {
                    unset($amounts[$key]);
                }


                $this->save_gift_card_amounts($product_id, $amounts);
                return true;
            }

            return false;
        }

        public function create_gift_card($product_id, $order_id, $amount)
        {
            $gift_card = new YWGC_Gift_Card();
            $gift_card->generate_gift_card_code();
            $gift_card->set_amount($amount);
            $gift_card->product_id = $product_id;
            $gift_card->order_id = $order_id;

            $gift_card->save();

            return $gift_card;
        }

        /**
         * Retrieve a gift card for a specific code
         *
         * @param $code string the gift card code to search for
         *
         * @return YWGC_Gift_Card
         * @author Lorenzo Giuffrida
         * @since  1.0.0
         */
        public function get_gift_card_by_code($code)
        {
            $object = get_page_by_title($code, OBJECT, YWGC_CUSTOM_POST_TYPE_NAME);
            if (null == $object) {
                return null;
            }
            return new YWGC_Gift_Card($object);
        }
    }
}