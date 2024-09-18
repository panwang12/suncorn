<?php
defined( 'ABSPATH' ) || exit;

// 自定义分页函数
function custom_paginate_comments_links() {
    // 获取分页链接
    $pagination_links = paginate_comments_links(array(
        'echo' => false, // 不直接输出，而是返回结果
        'type' => 'array', // 将结果作为数组返回
		// 'prev_text'=>'Previous',
		// 'next_text'=>'Next',
	));
    // 如果有分页链接，构建带有 Bootstrap 类的列表

    if ($pagination_links) {
        echo '<ul class="pagination ">'; // Bootstrap 的分页类
        foreach ($pagination_links as $link) {
            if (strpos($link, 'current') !== false) {
                echo '<li class="page-item active">' . str_replace('current', 'page-link', $link) . '</li>';
            } else {
                echo '<li class="page-item">' . str_replace('page-numbers', 'page-link', $link) . '</li>';
            }
        };
        echo '</ul>';
    }
}

global $product;
   // 获取当前产品的评价
//    $GLOBALS['post'] = get_post( $product->get_id() );
   // 初始化评论循环
//    $comments = get_comments( array(
//        'post_id' => $product->get_id(),
//        'status'  => 'approve',
       
//    ) );
$comments_per_page = get_option('comments_per_page');
$cpage = get_query_var( 'cpage' );
// $comment_query = new WP_Comment_Query( array(
// 	'post_id' => get_the_ID(),
// 	'status' => 'all',
// 	'order'=>'ACS',	
// 	'number' => $comments_per_page, // 每次加载的评论数量
// 	'paged' => 3
// ));
// echo '<pre>';
// print_r($comment_query);
// echo '</pre>';
if ( ! comments_open() ) {
	return;
}
?>
<div id="reviews" class="woocommerce-Reviews">
	<div id="comments">
		<h2 class="woocommerce-Reviews-title">
			<?php
			// $count = $product->get_review_count();
			$count = get_comments_number();
			if ( $count && wc_review_ratings_enabled() ) {
				/* translators: 1: reviews count 2: product name */
				$reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'woocommerce' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
				echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // WPCS: XSS ok.
			} else {
				esc_html_e( 'Reviews', 'woocommerce' );
			}
			?>
		</h2>
			<ol  id="comments-container" class="commentlist">
				 <?php 
					$output = display_custom_comments(get_the_ID(),1);
					echo $output['html']
				?>
				
			</ol> 
            
			<?php
            // wp_reset_postdata();
			$_comments = get_comments(array(
				'post_id' => get_the_ID(),
				'status' => 'approve',
				'order'=>'ACS',	
				// 'number' => $comments_per_page, // 每次加载的评论数量
				// 'paged' => $cpage,
				'parent'  => 0 ,
				// 'hierarchical' => 'threaded',
			));
			$page_total=get_comment_pages_count($_comments ,$comments_per_page);
			if ( $page_total > 1 && get_option( 'page_comments' ) ) :?>
			<nav id="comment-paginate" data-page-account="<?php echo $page_total?>" data-post-id="<?php echo get_the_ID()?>" aria-label="Page navigation example">
			  <ul class="pagination">
				<li class="page-item">
				<a class="page-link previous" data-page-index='0' href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
				</li>
				<?php foreach (range(1, $page_total) as $page_index) {?>
				<li class="page-item"><a class="page-link <?php echo $page_index==$output['page']?'active':''?>" data-page-index="<?php echo $page_index ?>" href="#"><?php echo $page_index?></a></li>
				<?php }?>
	  			<li class="page-item">
					<a  class="page-link next" data-page-index='-1' href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
				</li>
			   </ul>
			</nav>
	  		<?php endif;?>
	</div>

	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
		<div id="review_form_wrapper">
			<div id="review_form">
				<?php
				$commenter    = wp_get_current_commenter();

				$comment_form = array(
                    'id_form' => 'commentform',
					/* translators: %s is product title */
					// 'title_reply'         => have_comments() ? esc_html__( 'Add a review', 'woocommerce' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'woocommerce' ), get_the_title() ),
					'title_reply'         => '',
					/* translators: %s is product title */
					'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'woocommerce' ),
					'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
					'title_reply_after'   => '</span>',
					'comment_notes_after' => '',
					'label_submit'        => esc_html__( 'Submit', 'woocommerce' ),
					'submit_button' => '<button type="submit" class="btn btn-primary">%2$s</button>',
					'logged_in_as'        => '',
					'comment_field'       => '',
				);

				$name_email_required = (bool) get_option( 'require_name_email', 1 );
				$fields = array(
					'author' => array(
						'label'    => __( 'Name', 'woocommerce' ),
						'type'     => 'text',
						'value'    => $commenter['comment_author'],
						'required' => $name_email_required,
					),
					'email'=> array(
						'label'    => __( 'Email', 'woocommerce' ),
						'type'     => 'email',
						'value'    => $commenter['comment_author_email'],
						'required' => $name_email_required,
					),
				);

				$comment_form['fields'] = array();

				foreach ( $fields as $key => $field ) {
					$field_html  = '<p class="comment-form-' . esc_attr( $key ) . '">';
					$field_html .= '<label for="' . esc_attr( $key ) . '">' . esc_html( $field['label'] );

					if ( $field['required'] ) {
						$field_html .= '&nbsp;<span class="required">*</span>';
					}

					$field_html .= '</label><input id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" type="' . esc_attr( $field['type'] ) . '" value="' . esc_attr( $field['value'] ) . '" size="30" ' . ( $field['required'] ? 'required' : '' ) . ' /></p>';

					$comment_form['fields'][ $key ] = $field_html;
				}

				$account_page_url = wc_get_page_permalink( 'myaccount' );
				if ( $account_page_url ) {
					/* translators: %s opening and closing link tags respectively */
					$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'woocommerce' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
				}

				if ( wc_review_ratings_enabled() ) {
					$comment_form['comment_field'] = '<div class="comment-form-rating"><label for="custom-rating">' . esc_html__( 'Product Ratings', 'woocommerce' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label>
                    <p class="custom-stars-rating">
							<span class="star iconfont icon-star" data-rating="1" ></span>
							<span class="star iconfont icon-star" data-rating="2" ></span>
							<span class="star iconfont icon-star" data-rating="3"></span>
							<span class="star iconfont icon-star" data-rating="4"></span>
							<span class="star iconfont icon-star" data-rating="5"></span>
					</p>
                    <select name="rating" id="custom-rating" style="display:none">
						<option value="">' . esc_html__( 'Rate&hellip;', 'woocommerce' ) . '</option>
						<option value="5">' . esc_html__( 'Perfect', 'woocommerce' ) . '</option>
						<option value="4">' . esc_html__( 'Good', 'woocommerce' ) . '</option>
						<option value="3">' . esc_html__( 'Average', 'woocommerce' ) . '</option>
						<option value="2">' . esc_html__( 'Not that bad', 'woocommerce' ) . '</option>
						<option value="1">' . esc_html__( 'Very poor', 'woocommerce' ) . '</option>
					</select></div>';
				}

				$comment_form['comment_field'] .= '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Your review', 'woocommerce' ) . '&nbsp;<span class="required">*</span></label><textarea id="comment" name="comment" cols="45" rows="8"></textarea></p>';

				comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
			<pre>

			</pre>
		</div>
	<?php else : ?>
		<p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'woocommerce' ); ?></p>
	<?php endif; ?>

	<div class="clear"></div>
</div>
