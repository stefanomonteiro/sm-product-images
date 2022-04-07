<?php

/**
 * 
 * Plugin Name: Product Images Carousel
 * Plugin URI: https://stefanomonteiro.com/wp-plugins
 * Author: Stefano Monteiro
 * Author URI: https://stefanomonteiro.com
 * Version: 1.0.0
 * Description: Dislpay Porducts Images
 * Text Domain: sm_
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Basic security, prevents file from being loaded directly.
defined('ABSPATH') or die('Cheatin&#8217; uh?');

if (!function_exists('add_sm_product_images_shortcode')) {
    function add_sm_product_images_shortcode($atts)
    {

        $a = shortcode_atts(array(), $atts);


        if (!is_product()) {
            return 'This is not a product page!';
        }


        global $product;

        // ! Get Variable Images (if Variable Product)
        $variable_images = '';
        // Localized obj
        $sm_product_images_js = array(
            'data-attributes' => [],
        );
        if ($product->is_type('variable')) {
            // var_dump($product->get_available_variations());
            foreach ($product->get_available_variations() as $variation) {
                $assigned_attributes = '';
                foreach ($variation['attributes'] as $key => $value) {
                    if ($value) {
                        // adds data-attribute to localized obj to be used in JS
                        if (!in_array($key, $sm_product_images_js['data-attributes'])) {
                            array_push($sm_product_images_js['data-attributes'], $key);
                        }

                        // create data-attribute to include in image html
                        $assigned_attributes = $assigned_attributes . 'data-' . $key . '="' . $value . '"';
                    }
                }

                $variable_images = $variable_images . '<div class="carousel-cell sm_variable_image" ' . $assigned_attributes . '>
    
                    <img width="' . $variation['image']['full_src_w'] . '" height="' . $variation['image']['full_src_h'] . '" src="' . $variation['image']['src'] . '" class="attachment-large size-large" alt="' . $variation['image']['alt'] . '" srcset="' . $variation['image']['srcset'] . '" />
    
                </div>';
            }
        }



        // ! Get Featured Image and Gallery Images

        $feature_id = $product->get_image_id();
        $gallery_ids = $product->get_gallery_image_ids();

        $product_images = '';
        $product_images = $product_images . '<div class="carousel-cell">' . wp_get_attachment_image($feature_id, 'large') . '</div>';
        foreach ($gallery_ids as $attachment_id) {
            $product_images = $product_images . '<div class="carousel-cell">' . wp_get_attachment_image($attachment_id, 'large') . '</div>';
        }



        $html = '<div class="sm_product-images--container">
                    <div class="sm_product-images-carousel ' . $a['extra_class'] . '">
                        ' . $product_images . '
                        ' . $variable_images . '
                    </div>
                </div>';

        // Enqueue
        if (!wp_style_is('sm_product_images-css', 'enqueued')) {
            wp_enqueue_style('sm_product_images-css');
        }
        if (!wp_script_is('sm_product_images-js', 'enqueued')) {
            wp_localize_script('sm_product_images-js', 'sm_product_images', $sm_product_images_js);
            wp_enqueue_script('sm_product_images-js');
        }

        return $html;
    }
}
add_shortcode('sm_product_images', 'add_sm_product_images_shortcode');

wp_register_style('flickity-css', plugin_dir_url(__FILE__) . 'css/flickity/flickity.min.css', [], '2.2.0');
wp_register_style('sm_product_images-css', plugin_dir_url(__FILE__) . 'css/sm_product_images.css', ['flickity-css'], '1.0.0');
wp_register_script('flickity-js', plugin_dir_url(__FILE__) . 'js/flickity/flickity.pkgd.min.js', [], '2.2.0', true);
wp_register_script('sm_product_images-js', plugin_dir_url(__FILE__) . 'js/sm_product_images.js', ['flickity-js'], '1.0.0', true);

// Enqueue Scripts Elementor Editor
if (!function_exists('sm_product_images_enqueue_styles_elementor_editor')) {
    function sm_product_images_enqueue_styles_elementor_editor()
    {

        if (!wp_style_is('sm_product_images-css', 'enqueued')) {
            wp_enqueue_style('sm_product_images-css');
        }
    }
}

if (!function_exists('sm_product_images_enqueue_scripts_elementor_editor')) {
    function sm_product_images_enqueue_scripts_elementor_editor()
    {

        if (!wp_script_is('sm_product_images-js', 'enqueued')) {
            wp_enqueue_script('sm_product_images-js');
        }
    }
}

// Add Action elementor/preview/enqueue_styles 
add_action('elementor/preview/enqueue_styles', 'sm_product_images_enqueue_styles_elementor_editor');
add_action('elementor/preview/enqueue_scripts', 'sm_product_images_enqueue_scripts_elementor_editor');
