<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header( ); ?>

<div class="container text-center single-product">
	<div class="row">
	<?php 
		while ( have_posts() ) :
		the_post(); 
		if ( ! function_exists( 'wc_get_product' ) ) {
			return;
		}
		$image_ids = array();
		if ( $product->get_image_id() ) {
			$image_ids[] = $product->get_image_id();
		}
		$gallery_image_ids = $product->get_gallery_image_ids();
		if ( ! empty( $gallery_image_ids ) ) {
			$image_ids = array_merge( $image_ids, $gallery_image_ids );
		}
		// var_dump($image_ids) ;
	?>
		<div class="col">
			<div id="productCarousel" class="carousel slide">
			<div class="carousel-inner">
				<?php 
				foreach ( $image_ids as $key => $image_id){
					$image_url = wp_get_attachment_url( $image_id);
				?>
					<div class="carousel-item <?php echo ($key==0?'active':'')?>">
						<img src="<?php echo  esc_url( $image_url)?>" class="d-block w-100" alt="...">
					</div>
				<?php
				}
				?>	
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
				<span class="iconfont icon-arrow-left" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
				<span class="iconfont icon-arrow-right" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>
			</div>
			<div class="thumbnail-content">
				<span id="arrow-left" class="can-not-click arrow iconfont icon-arrow-left" aria-hidden="true"></span>
				<div class="thumbnail-wrapper">
					<div class="thumbnails">
						<?php 
							foreach (  $image_ids as $key => $image_id){
								$image_url = wp_get_attachment_url( $image_id);
						?>
							<div data-bs-target="#productCarousel"  data-bs-slide-to="<?php echo $key?>" >
								<img  src="<?php echo  esc_url( $image_url )?>"  class="d-block <?php echo ($key==0?'active':'')?>"  alt="...">
							</div>
						<?php
							}
						?>
					</div>
				</div>
				<span id="arrow-right"  class="arrow iconfont icon-arrow-right" aria-hidden="true"></span>
			</div>
		</div>
		<div class="col">
			<div class="product-name">
				<h2><?php echo get_the_title()?></h2>
			</div>
			<?php 
				$regular_price = $product->get_regular_price(); // 原价
				$sale_price = $product->get_sale_price(); // 折扣价格
				$price = $product->get_price(); // 当前价格（考虑折扣）
			?>
			
			<div class="price-container">
				<span class="price"><?php echo wc_price($price)?></span>
				<?php if($price!=$regular_price){?>
					<span class="regular-price">
						<del>
							<?php   echo wc_price($regular_price)?>
						</del>
					</span>
				<?php }?>
			</div>
			<div class="container">
				<form id="add-to-cart-form-single"  action="">
				<input type="hidden" name="product_id" value="<?php echo $product->get_id(); ?>">
					<div class="row">
						<div class="col"><input name="quantity" type="number" value="1" min="0" max="100" step="1"/></div>
						<div class="col"><button id="addToCart" type="submit" class="btn btn-primary">Add to Card</button></div>
					</div>
				</form>
				
				
			</div>
		</div>
	
	<?php endwhile; // end of the loop. ?>
	</div>
</div>
<div class="product-detail-container">
		<ul class="nav nav-tabs justify-content-center"  >
			<li class="nav-item" >
				<button data-bs-target="#detail-tab-pane" data-bs-toggle="tab" class="nav-link active">Detail</button>
			</li>
			<li class="nav-item" >
				<button data-bs-target="#review-tab-pane" data-bs-toggle="tab" class="nav-link " >Review</button>
			</li>
			<li class="nav-item" >
				<button data-bs-target="#shipping-tab-pane" data-bs-toggle="tab" class="nav-link" >Shiping</button>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane fade show active" id="detail-tab-pane"  >
				<p><?php echo  nl2br($product->get_description());?></p>
			</div>
			<div class="tab-pane fade  " id="review-tab-pane" >
				<?php
				
				if (comments_open() || get_comments_number()) :
					// comments_template();  // 调用 comments_template() 来加载评论部分
					wc_get_template_part( 'single-product','comments');
				endif;
				?>
			</div>
			<div class="tab-pane fade" id="shipping-tab-pane" >Shipping</div>	
		</div>
	</div>
<?php
get_footer( );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
