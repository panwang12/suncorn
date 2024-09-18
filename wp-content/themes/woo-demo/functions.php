<?php
require get_theme_file_path('/inc/add-to-cart.php');
require get_theme_file_path('/inc/woo-hook.php');
require get_theme_file_path('/inc/comment.php');


add_action( 'wp_enqueue_scripts', 'theme_slug_enqueue_styles' );

function theme_slug_enqueue_styles() {
	$asset = include get_theme_file_path( 'public/css/screen.asset.php' );
	wp_enqueue_style(
		'screen-style',
		get_theme_file_uri( 'public/css/screen.css' ),
		$asset['dependencies'],
		$asset['version'],
	);
	if (!is_admin()) {
        // Deregister the included library
        wp_deregister_script('jquery');
        // Register the library again from Google's CDN
        wp_register_script('jquery', 'https://code.jquery.com/jquery-3.6.0.min.js', false, '3.6.0');
        // Add the script to the queue
        wp_enqueue_script('jquery');
    }
	wp_enqueue_script('main-index-js',get_theme_file_uri('/public/js/index.js'),array("jquery"),'1.0.0',false);
	wp_localize_script('main-index-js', 'ajax_url', array(
        'admin_ajax' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('ajax-comment-nonce')
    ));
	
	// wp_enqueue_style( 
	// 	'theme-slug-style', 
	// 	get_stylesheet_uri()
	// );
};

function themename_custom_logo_setup() {
	$defaults = array(
		'height'               => 100,
		'width'                => 400,
		'flex-height'          => true,
		'flex-width'           => true,
		'header-text'          => array( 'site-title', 'site-description' ),
		'unlink-homepage-logo' => true, 
	);
	add_theme_support( 'custom-logo', $defaults );
}
add_action( 'after_setup_theme', 'themename_custom_logo_setup' );

function register_my_menus() {
	register_nav_menus(
	  array(
		'header-menu' => __( 'Header Menu' ),
		'banner-menu' => __( 'Banner Menu' ),
		'shop-by-age-menu' => __( 'Shop By Age Menu' )
	   )
	 );
   }
   add_action( 'init', 'register_my_menus' );


// function add_categories_tags_to_pages() {
//     // Add category support to pages
//     register_taxonomy_for_object_type('category', 'page');
//     // Add tag support to pages
//     register_taxonomy_for_object_type('post_tag', 'page');
// }
// // Ensure the function runs on the 'init' hook
// add_action('init', 'add_categories_tags_to_pages');







function your_theme_setup() {
    add_theme_support( 'woocommerce' );
}	
add_action( 'after_setup_theme', 'your_theme_setup' );


// function mytheme_enqueue_woocommerce_styles() {
//     if ( class_exists( 'WooCommerce' ) ) {
//         // 取消注册 WooCommerce 默认样式
//         wp_dequeue_style( 'woocommerce-general' );
//         wp_dequeue_style( 'woocommerce-layout' );
//         wp_dequeue_style( 'woocommerce-smallscreen' );

//         // 注册并加载 WooCommerce 样式
//         wp_enqueue_style( 'woocommerce-general', plugins_url( '/woocommerce/assets/css/woocommerce.css' ) );
//         wp_enqueue_style( 'woocommerce-layout', plugins_url( '/woocommerce/assets/css/woocommerce-layout.css' ) );
//         wp_enqueue_style( 'woocommerce-smallscreen', plugins_url( '/woocommerce/assets/css/woocommerce-smallscreen.css' ), array(), '', 'only screen and (max-width: 768px)' );
//     }
// }
// add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_woocommerce_styles' );


// add_filter('template_include', 'debug_template_loader');
// function debug_template_loader($template) {
//         echo '<div style="background: #f00; color: #fff; padding: 10px;">';
//         echo 'Loaded template: ' . $template;
//         echo '</div>';
    
//     return $template;
// }

add_filter( 'template_include', 'custom_template_include' );

function custom_template_include( $template ) {
    if ( is_tax( 'product_cat' ) ) {
        // 输出当前加载的模板路径
        error_log( 'Loading template: ' . $template );
        // 检查模板路径是否正确
        if ( basename( $template ) == 'taxonomy-product_cat.php' ) {
            error_log( 'Correct template loaded for product category.' );
        } else {
            error_log( 'Incorrect template loaded: ' . basename( $template ) );
        }
    }
    return $template;
};

// add_action( 'woocommerce_before_main_content', 'debug_wc_template', 1 );
// function debug_wc_template() {
//     echo '<!-- Current template: ' . WC_Template_Loader::get_template_part( 'content', 'product' ) . ' -->';
// }


// 将此代码添加到你的主题的 functions.php 文件中或自定义插件中

function alter_comment_template($path){
	return get_stylesheet_directory().'/woocommerce/single-product-comments.php';
};
add_filter( 'comments_template', 'alter_comment_template',11);

function custom_comment_form($args) {
	
    // 自定义提交按钮的 HTML 结构
    $args['submit_button'] = '<button type="submit" class="btn btn-primary">%2$s</button>';

    // 被模板参数覆盖了
	//$args['label_submit'] = __('Send Comment');

    return $args;
}
add_filter('comment_form_defaults', 'custom_comment_form',11);	



function custom_comment_reply_link($link, $args, $comment, $post) {
    $comment_id = $comment->comment_ID; // 获取评论ID
    $post_id = $post->ID; // 获取文章ID
    $reply_link = '<a href="#" class="comment-reply-link" data-commentid="' . $comment_id . '" data-postid="' . $post_id . '">Reply</a>';
    $reply_link.= '<a href="#" style="display:none" class="cancel-reply-link" >Cancel Reply</a>';
    return $reply_link;
}
add_filter('comment_reply_link', 'custom_comment_reply_link', 10, 4);


if ( ! current_user_can( 'administrator' ) ) {
    add_filter( 'show_admin_bar', '__return_false' );
}
