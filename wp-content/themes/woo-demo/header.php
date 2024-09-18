<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="wide=device-width initial-scale=1">
    <?php wp_head();?>
  </head>
  <body>
    <?php if ( !is_admin_bar_showing()):?> 
        
    <div>admin bar</div>
    <?php endif;?>

    <div class="container-xxl header-container">
        <div class="row align-items-center">
            <div class="col-3">
                <a class="header-logo" href="<?php echo site_url()?>">
                    <?php
                        $custom_logo_id = get_theme_mod( 'custom_logo' );
                        $logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
                        if ( has_custom_logo() ) {
                            echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '">';
                        } else {
                            echo '<h1>' . get_bloginfo('name') . '</h1>';
                        }
                    ?>
                    <span id="site-name">Suncorn</span>
                </a>
            </div>
            <div class="col-6">
                <div class="input-group">
                    <i class="input-group-text iconfont icon-search">
                    </i>
                    <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                    <!-- <span  id="basic-addon1">@</span> -->
                    <button class="input-group-text btn btn-danger" type="button">Go</button>
                </div>
            </div>
            <div class="col-3 my-basket-bar">
                <a href="<?php echo wc_get_cart_url()?>">
                    <i style="font-size:2rem" class="iconfont icon-cart"></i>
                    <b>My Basket</b>
                    <span id="cart-total"><?php echo WC()->cart->get_cart_total()?></span>
                </a>
                <a href="<?php echo wc_get_page_permalink( 'myaccount' ); ?>">
                    返回我的账户
                </a>
            </div>
        
        </div>
    </div>
    <?php
        wp_nav_menu(
            array(
                'theme_location' => 'header-menu',
                'container'      => 'nav',
                'container_class' => 'primary-menu-class',
                'menu_class'     => 'nav-menu',
            )
        );
    ?>
    <div class="breadcrumb-warpper">
        <?php if ( function_exists('woocommerce_breadcrumb') ) {
            woocommerce_breadcrumb();
        } ?>
    </div>

    <div class="toast-container position-fixed top-20 end-0 p-3">
    <div id="customToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
        <!-- <img src="..." class="rounded me-2" alt="..."> -->
        <strong class="me-auto">Suncorn</strong>
        <small>latest tips</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div id="toast-body" class="toast-body">
            Hello, world! This is a toast message.
        </div>
    </div>
    </div>
