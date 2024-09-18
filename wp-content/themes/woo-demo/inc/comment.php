<?php

function display_custom_comments($post_id,$cpage=1){
    $comments_per_page = get_option('comments_per_page');
    $_comments = get_comments(array(
        'post_id' => $post_id,
        'status' => 'approve',
        'order'=>'ACS',	
        'number' => $comments_per_page, // 每次加载的评论数量
        'paged' => $cpage,
        'parent'  => 0 ,
        // 'hierarchical' => 'threaded',
    ));
    if ( get_option( 'thread_comments' )  ) {
        $comments_flat = array();
        foreach ( $_comments as $_comment ) {
            $comments_flat[]  = $_comment;
            $comment_children = $_comment->get_children(
                array(
                    'format'  => 'flat',
                    'status' => 'approve',
                    'order'=>'ACS',	
                )
            );
            foreach ( $comment_children as $comment_child ) {
                $comments_flat[] = $comment_child;
            }
           
        }
    } else {
        $comments_flat= $_comments;
    };
    
    $walker = new Walker_Comment();
	if ( get_option( 'thread_comments' ) ) {
		$max_depth = get_option( 'thread_comments_depth' );
	} else {
		$max_depth = -1;
	}
    $args=array(
        'callback' => 'custom_comments_callback',
        'style'    => 'ol',
        'short_ping' => true,
        'avatar_size' => 50,
        'max_depth'=>$max_depth,
    );
	$output = $walker->paged_walk( $comments_flat, $max_depth, 1, $comments_per_page , $args );
    return array(
        'page'=>$cpage,
        'html'=>$output
    );
	// echo $output;
};
function custom_comments_callback( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> <?php comment_class( $args['has_children'] ? 'parent' : '' ); ?> id="comment-<?php comment_ID(); ?>">
    <div id="div-comment-<?php comment_ID(); ?>" class="comment-body">
        <div class="comment-author vcard">
            <?php if ( 0 != $args['avatar_size'] ) echo get_avatar( $comment, $args['avatar_size'] ); ?>
            <?php printf( __( '<cite class="fn">%s - %2$s at %3$s</cite>' ), get_comment_author(),get_comment_date(),  get_comment_time() ); ?>
        </div>
        <?php if ( '0' == $comment->comment_approved ) : ?>
            <em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
            <br />
        <?php endif; ?>

        <div class="comment-rating">
            <?php
            // 获取评论的评分（假设评分存储在评论元数据中）
            
            $rating = get_comment_meta( $comment->comment_ID, 'rating', true );
            if ( $rating ) {
                // 显示星级评分，使用 Bootstrap 的样式
                echo '<div class="rating">';
                for ( $i = 1; $i <= 5; $i++ ) {
                    if ( $i <= $rating ) {
                        echo '<span class="iconfont icon-star checked"></span>';
                    } else {
                        echo '<span class="iconfont icon-star"></span>';
                    }
                }
                echo '</div>';
            }
            ?>
        </div>

        <div class="comment-text"><?php echo esc_html(get_comment_text()); ?></div> 
        <div class="reply">
            <?php comment_reply_link( array_merge( $args, array( 'add_below' => 'div-comment', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
        </div>
    </div>
    <?php
};


function ajax_load_comments() {
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'ajax-comment-nonce')) {
        wp_send_json_error('Invalid nonce');
    }

    $post_id = intval($_POST['post_id']);
    $cpage = intval($_POST['cpage']);

    if ($post_id && $cpage) {


        // 获取评论 HTML
        // ob_start();
        $output = display_custom_comments($post_id,$cpage);
        // $comments_html = ob_get_clean();

        // 获取分页导航 HTML
        // ob_start();
        // paginate_comments_links(array(
        //     'prev_text' => '&laquo; Previous',
        //     'next_text' => 'Next &raquo;',
        // ));
        // $pagination_html = ob_get_clean();

        wp_send_json_success($output);
    } else {
        wp_send_json_error('Invalid post ID or page number');
    }
}
add_action('wp_ajax_nopriv_load_comments', 'ajax_load_comments');
add_action('wp_ajax_load_comments', 'ajax_load_comments');





function ajax_comment_handler() {
    $nonce = $_POST['nonce'];
    if (!wp_verify_nonce($nonce, 'ajax-comment-nonce')) {
        wp_send_json_error('Invalid nonce');
    }
    $parent =  $_POST['comment_parent'];
    if($parent==0){
        $cpage=1;
    }else{
        $cpage = $_POST['cpage'];
    }
    $comment_data = wp_handle_comment_submission(wp_unslash($_POST));
    // error_log(print_r($comment_data,true));
    if (is_wp_error($comment_data)) {
        wp_send_json_error($comment_data->get_error_message());
    } else {
        wp_send_json_success(get_comment_html($_POST['comment_post_ID'], $cpage));
    }
}
add_action('wp_ajax_nopriv_ajaxcomments', 'ajax_comment_handler');
add_action('wp_ajax_ajaxcomments', 'ajax_comment_handler');

function get_comment_html($post_id,$cpage=1) {
    $output = display_custom_comments($post_id,$cpage);
    $output['pagination'] = updatePagination($cpage);
    return $output ;
}

function updatePagination($cpage){
    $_comments = get_comments(array(
        'post_id' => $post_id,
        'status' => 'approve',
        'order'=>'ACS',	
        // 'number' => $comments_per_page, // 每次加载的评论数量
        // 'paged' => $cpage,
        'parent'  => 0 ,
        // 'hierarchical' => 'threaded',
    ));
    $page_total=get_comment_pages_count($_comments ,$comments_per_page);
    $html='<ul class="pagination">';
    $html.='<li class="page-item"><a class="page-link previous" data-page-index="0" href="#" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
				
    foreach (range(1, $page_total) as $page_index) {
        $html.='<li class="page-item"><a class="page-link ';
        $html.=$page_index==$cpage?'active"':'"';
        $html.='data-page-index="';
        $html.=$page_index;
        $html.='"href="#">';
        $html.=$page_index.'</a></li>';
    }
    $html.='<li class="page-item"><a  class="page-link next" data-page-index="-1" href="#" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li></ul>';
    return $html;
}
