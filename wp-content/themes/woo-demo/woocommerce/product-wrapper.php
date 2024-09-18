<?php 
    global $product;
    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    $regular_price = $product->get_regular_price(); // 原价
    $sale_price = $product->get_sale_price(); // 折扣价格
    $price = $product->get_price(); // 当前价格（考虑折扣）
?>
<div class="product-item-wrapper">
    <a href="<?php echo get_permalink($product->get_id()); ?>">
        <div class="thumb">
            <?php if($price!=$regular_price){?>
            <div class="img-badge">
                <div class="badge-content">
                    <span><?php echo intval($price/$regular_price*100)?></span>
                    <img src="<?php echo esc_url(get_theme_file_uri('assets/img/percent-bg.webp'))?>" alt="Percent off">
                </div>
            </div>
            <?php }?>
            <img src="<?php echo esc_url($thumbnail_url) ?>" alt="<?php echo esc_attr(get_the_title()) ?>" />
        </div>
        <div class="product-title"><?php echo get_the_title() ?></div>

        <div class="price-container">
            <span><?php echo wc_price($price)?></span>
            <?php if($price!=$regular_price){?>
                <span class="regular-price">
                    <del>
                        <?php   echo wc_price($regular_price)?>
                    </del>
                </span>
            <?php }?>
        </div>
    </a>

    <div class="addToCar-wrapper"> 
        <form id="add-to-cart-form" action="" method="post">
            <input type="hidden" name="product_id" value="<?php echo $product->get_id(); ?>">
            <button  type="submit">Add to Cart</button>
        </form>
    </div>
</div>
