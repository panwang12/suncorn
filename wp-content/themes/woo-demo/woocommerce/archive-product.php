<?php
    get_header();
	if (have_posts()) {
		while (have_posts()) {
			the_post();
			wc_get_template_part( 'product', 'wrapper');
			
			} 
			// 也可以使用 $product 对象访问产品的详细信息
	}else{
	?>
		<div class="not-found-notice">No products found!</div>
	<?php
	}
   get_footer();
?>