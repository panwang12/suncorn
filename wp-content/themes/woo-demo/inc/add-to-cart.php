<?php
// Add action to handle the add to cart form submission
// function handle_add_to_cart() {
//     if (isset($_POST['add-to-cart'])) {
//         $product_id = intval($_POST['add-to-cart']);
//         $quantity = 0; // You can customize this value or retrieve it from another form input

//         // Add product to cart
//         WC()->cart->add_to_cart($product_id, $quantity);

//         // Redirect to the cart page or stay on the same page
//         wp_safe_redirect(wc_get_cart_url());
//         exit;
//     }
// }
// add_action('template_redirect', 'handle_add_to_cart');

function handle_ajax_add_to_cart() {
    if (isset($_POST['product_id'])) {
        $product_id = intval($_POST['product_id']);
        if($_POST['quantity']){
            $quantity=$_POST['quantity'];
        }else{
            $quantity = 1; // You can customize this value or retrieve it from another form input
        }
        

        // Add product to cart
        $added = WC()->cart->add_to_cart($product_id, $quantity);
		$cart_total = WC()->cart->get_cart_total();
        if ($added) {
            $response = array(
                'status' => 'success',
				'cart_total' => $cart_total ,
                'message' => __('Product added to cart!', 'your-text-domain')
            );
        } else {
            $response = array(
                'status' => 'error',
                'message' => __('Failed to add product to cart.', 'your-text-domain')
            );
        }

        wp_send_json($response);
    }

    wp_die(); // Always include this to terminate the execution properly
}
add_action('wp_ajax_add_to_cart', 'handle_ajax_add_to_cart');
add_action('wp_ajax_nopriv_add_to_cart', 'handle_ajax_add_to_cart');
