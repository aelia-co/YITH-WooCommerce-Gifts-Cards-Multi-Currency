<?php
/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

$general_options = array(

    'general' => array(

        'section_general_settings' => array(
            'name' => __('General settings', 'yith-woocommerce-gift-cards'),
            'type' => 'title',
            'id' => 'ywgc_section_general'
        ),
        'section_general_settings_end' => array(
            'type' => 'sectionend',
            'id' => 'ywgc_section_general_end'
        )
    )
);

return $general_options;

