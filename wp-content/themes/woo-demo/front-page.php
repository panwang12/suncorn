<?php
    get_header();
   while(have_posts()){
    the_post();?>
     <div class="front-banner">
        <a href="<?php echo '/'?>">
            <h2>
                <img class="outdoor-toys-logo-desktop" src="<?php echo esc_url(get_theme_file_uri('assets/img/banner-top.gif'))?>" alt="Join our Summer Adventure">
            </h2>
        </a>
        <ul class="outdoor-sub-cats">
        <?php
            $menu_name = 'banner-menu'; 
            $locations = get_nav_menu_locations();
            $menu_id = $locations[$menu_name];
            $menu_items = wp_get_nav_menu_items($menu_id);

            foreach ($menu_items as $menu_item) {
            ?>
            <li>
                <?php 
                $thumbnail_id = get_term_meta($menu_item->object_id, 'thumbnail_id', true);
                $image_url = wp_get_attachment_url($thumbnail_id);
                ?>

                <a href="<?php echo esc_url($menu_item->url)?>">
                    <img src="<?php echo esc_url($image_url)
                     ?>" alt="">
                </a>
            </li>
             
            <?php
            }
            ?>
            
        </ul>
     </div>
     <div class="shop-by-age-menu">
        <h2>Shop By Age</h2>
        <ul class="outdoor-sub-cats">
            <?php
                $age_menu_locations_ = get_nav_menu_locations();
                $age_menu_id = $age_menu_locations_['shop-by-age-menu'];
                $age_menu_items = wp_get_nav_menu_items($age_menu_id);

                foreach ($age_menu_items as $age_menu_item) {
                ?>
                <li>
                    <?php 
                    $age_menu_thumbnail_id = get_term_meta($age_menu_item->object_id, 'thumbnail_id', true);
                    $age_menu_image_url = wp_get_attachment_url( $age_menu_thumbnail_id);
                    ?>

                    <a href="<?php echo esc_url($age_menu_item->url)?>">
                        <img src="<?php echo esc_url( $age_menu_image_url)
                        ?>" alt="">
                    </a>
                </li>
             
            <?php
            }
            ?>
            
        </ul>
     </div>
     <div class="top-pick-product">
        <div class="carousel-container">
            <div class="top-pick-title-wrapper">
                <h2 class="title-content">Top Picks</h2>
            </div>
            <div id="topPickCarousel" class="carousel slide">
                <div class="carousel-outer">
                    <div class="carousel-inner">
                        <?php
                            $top_pick_products = new WP_Query(
                                array(
                                    'post_type' => 'product',
                                    'posts_per_page' => -1,
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'product_tag',
                                            'field' => 'slug',
                                            'terms' => 'top-pick',
                                        ),
                                    ),
                                )
                            );
                            if ($top_pick_products->have_posts()) {
                                $count = 0;
                                while ($top_pick_products->have_posts()) {  
                                    $top_pick_products->the_post();
                                    if($count%4==0){
                                        if($count==0){
                                            echo '<div class="carousel-item active">';
                                        }else{
                                            echo '<div class="carousel-item">';
                                        }
                                    }
                                    wc_get_template_part( 'product', 'wrapper');

                                    if($count%4==3)echo '</div>';
                                    $count++;
                                    // 你也可以使用 $product 对象访问产品的详细信息
                                }
                                if($count%4!=3)echo '</div>';
                            } else {
                        ?>
                            <div class="not-found-notice">No products found!</div>
                        <?php
                            }
                        ?>
                    </div>
                    
                    <button class="carousel-control-prev" type="button" data-bs-target="#topPickCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#topPickCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
     </div>
     <?php wp_reset_postdata();?>
   
    <br/>
    <?php
   }
   get_footer();
?>