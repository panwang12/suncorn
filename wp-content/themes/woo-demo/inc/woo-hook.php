<?php
// remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
// remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

// add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
// add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

// function my_theme_wrapper_start() {
//     echo '<section id="main-wang">';
// }

// function my_theme_wrapper_end() {
//     echo '</section>';
// }

add_action( 'woocommerce_shop_loop', 'custom_shop_loop_content',1000);

function custom_shop_loop_content() {
    echo '<div class="custom-banner">Your Custom Content Here</div>';
}



