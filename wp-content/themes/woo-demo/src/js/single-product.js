// import $ from "jquery"
import $ from 'jquery'
import {InputSpinner} from 'bootstrap-input-spinner/src/InputSpinner.js';
import { showToast } from './utility';
$(document).ready(function() {
    $('#add-to-cart-form-single').on('submit', function(e) {
        var $form = $(this);
        e.preventDefault();
        $.ajax({
            url: ajax_url.admin_ajax,
            type: 'POST',
            data: {
                action: 'add_to_cart',
                product_id: $form.find('input[name="product_id"]').val(),
                quantity: $form.find('input[name="quantity"]').val()
            },
            success: function(res) {
                $("#cart-total").html(res.cart_total)
                alert(res.message); // Show success message or update cart count, etc.
            }
        });
    });

    const inputSpinnerElements = document.querySelectorAll("input[type='number']")
    for (const inputSpinnerElement of inputSpinnerElements) {
        new InputSpinner(inputSpinnerElement)
    }
    const thumbnailWrapper = $('.thumbnail-wrapper');
    const thumbnails = $('.thumbnails');
    const thumbnailWidth = $('.thumbnails img').outerWidth(true);
    const visibleThumbnails = Math.floor(thumbnailWrapper.width() / thumbnailWidth);
    let currentIndex = 0;
    
    function updateThumbnails() {
            $('#arrow-left').removeClass('can-not-click')
            $('#arrow-right').removeClass('can-not-click')
          
            const newTransform = -currentIndex * thumbnailWidth;
            thumbnails.css('transform', 'translateX(' + newTransform + 'px)');
            if(currentIndex==0){
                $('#arrow-left').addClass('can-not-click')
            }
            if(currentIndex>=thumbnails.children().length - visibleThumbnails){
                $('#arrow-right').addClass('can-not-click')
            }
    }

    $('#arrow-left').on('click', function() {
        if (currentIndex > 0) {
            currentIndex--;
            updateThumbnails();
        }
    });

    $('#arrow-right').on('click', function() {
        if (currentIndex < thumbnails.children().length - visibleThumbnails) {
            currentIndex++;
            updateThumbnails();
        }
    });

 
    document.querySelectorAll('.carousel-indicators img').forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            $('#productCarousel').carousel(index);
        });
    });
    
    $('#productCarousel').on('slide.bs.carousel', function (e) {
        var index = $(e.relatedTarget).index();
        $('.thumbnails img').removeClass('active');
        $('.thumbnails img').eq(index).addClass('active');
        
        if (index <= thumbnails.children().length - visibleThumbnails) {
            currentIndex = index;
            updateThumbnails();
        }else{
            currentIndex = thumbnails.children().length - visibleThumbnails;
        }
        updateThumbnails();
    });
    // Click thumbnail to slide
    $('.thumbnails div').on('click', function() {
        const index = $(this).index();
        $('#productCarousel').carousel(index);
    });
    // $('body').on( 'init', '#custom-rating', function() {
	// 		$( '#custom-rating' )
	// 			.hide()
	// 			.before(
	// 				'<p class="stars">\
	// 					<span>\
	// 						<a class="star-1 iconfont icon-star" href="#">1</a>\
	// 						<a class="star-2 iconfont icon-star" href="#">2</a>\
	// 						<a class="star-3 iconfont icon-star" href="#">3</a>\
	// 						<a class="star-4 iconfont icon-star" href="#">4</a>\
	// 						<a class="star-5 iconfont icon-star" href="#">5</a>\
	// 					</span>\
	// 				</p>'
	// 			);
	// 	} ) 

        // 当点击星星时
        $('.star').on('click', function() {
            var rating = $(this).closest( '#respond' ).find( '#custom-rating' );
            rating.val($(this).data('rating'));
            // 清除所有星星的 active 状态
            $('.star').removeClass('active');
            
            // 设置当前星星及之前的星星为 active 状态
            $(this).addClass('active');
            $(this).prevAll('.star').addClass('active');
        });
        
        // 当鼠标移入星星时
        $('.star').on('mouseover', function() {
            // 鼠标移入时暂时改变星星颜色
            $(this).addClass('hover');
            $(this).prevAll('.star').addClass('hover');
        }).on('mouseout', function() {
            // 鼠标移出时恢复星星颜色
            $(this).removeClass('hover');
            $(this).prevAll('.star').removeClass('hover');
        });
        
        $('#commentform').on('submit', function(e) {
            e.preventDefault();
            let alert = $(".alert-massage")
            if(alert){
                alert.remove()
            }
            var $form = $(this);
            var formdata = $form.serialize();
            var dataArr = $form.serializeArray();
            let cpage=$('#comment-paginate .active').data('page-index');
            var invalidValue=false
            dataArr.forEach((obj)=>{
                let target = $("#commentform").find('[name="'+obj.name+'"]');
                if(target){
                    if(!obj.value){
                        target.parent().append(`<div class="alert-massage">*${obj.name} is required</div>`);
                        setTimeout(function() {
                            target.parent().find(".alert-massage").fadeOut(function() {
                                $(this).remove(); // 完全移除元素
                            });
                        }, 3000);
                        invalidValue=true;
                    }
                }
            })
            if(invalidValue) return;
            $.ajax({
                url: ajax_url.admin_ajax,
                type: 'POST',
                data: formdata + '&action=ajaxcomments&nonce=' + ajax_url.nonce+'&cpage='+cpage,
                success: function(response) {
                    if(response.success) {
                        showToast('Thank you for your comment!');
                        // $('#commentform').before('<p class="success">Thank you for your comment!</p>');
                        resetCommentForm()
                        $('#comments-container').html(response.data.html);
                        $('#comment-paginate').html(response.data.pagination);
                        $('.custom-stars-rating .active').removeClass('active');
                       
                    } else {
                        $('#commentform').before('<p class="error">' + response.data + '</p>');
                    }
                },
                error: function(response) {
                    $('#commentform').before('<p class="error">Something went wrong. Please try again.</p>');
                }
            });
        });
       
        $('#comment-paginate').on('click','.page-link',function(e){
            e.preventDefault();
            let cpage=$('#comment-paginate .active').data('page-index');
            let $this= $(this)
            let postId =$this.closest('#comment-paginate').data('post-id');
            let maxPage =$this.closest('#comment-paginate').data('page-account');
            let index = $this.data('page-index');
            if(index==0){
                cpage--
            }else if(index==-1){
                cpage++
            }else{
                cpage=index
            }
            if(cpage>maxPage){
                cpage=maxPage
                return;
            }
            if(cpage<1){
                cpage=1
                return;
            }
            $.ajax({
                url: ajax_url.admin_ajax, // WordPress 提供的 AJAX URL
                type: 'POST',
                data: {
                    action: 'load_comments', // AJAX 动作名
                    cpage: cpage, // 发送当前页码
                    post_id: postId, // 发送文章 ID
                    nonce: ajax_url.nonce // 传递 nonce
                },
                success: function(response) {
                    if (response) {
                        resetCommentForm()
                        $('#comments-container').html(response.data.html); // 将新评论添加到评论列表
                        $('#comment-paginate .active').removeClass('active');
                        $(`[data-page-index=${cpage}]`).addClass('active');
                       
                        if (cpage >= 1) {
                            // $('#load-more-comments').hide(); // 到达最大页码时隐藏按钮
                        }
                        if (cpage >= maxPage) {
                            // $('#load-more-comments').hide(); // 到达最大页码时隐藏按钮
                        }
                    } else {
                        // $('#load-more-comments').hide(); // 没有更多评论时隐藏按钮
                    }
                    $('html, body').animate({
                        scrollTop: $('#reviews').offset().top
                      }, 100); // 500毫秒的动画时间，可以根据需要调整
                }
            });

        })
        $('pre-page').on('click',function(){
            let index = $('#comment-paginate .active').data('page-index');
        })

        // 捕获点击事件
        $("#comments-container").on('click', '.comment-reply-link', function(e) {
            e.preventDefault(); // 阻止默认的跳转行为
            $('.cancel-reply-link').css("display","none")
            $(this).closest('.reply').find('.cancel-reply-link').css("display","inline-block")
            $('.comment-reply-link').css("display","inline-block")
            $(this).css("display","none")
            var commentID = $(this).data('commentid'); // 获取评论ID
            // var postID = $(this).data('postid'); // 获取文章ID
            $('.custom-stars-rating .active').removeClass('active');
            $('#commentform')[0].reset()
            // 将评论表单移动到该评论下面
            var commentForm = $('#respond'); // 获取评论表单
            $('#comment_parent').val(commentID); // 设置comment_parent隐藏字段的值为当前评论ID
            $(this).closest('.reply').append(commentForm); // 将表单移动到当前评论下
        });
        $("#comments-container").on('click', '.cancel-reply-link', function(e) {
            e.preventDefault(); // 阻止默认的跳转行为
            $('.cancel-reply-link').css("display","none")
            $('.comment-reply-link').css("display","inline-block")
            resetCommentForm()
        });
        function resetCommentForm(){
            $('.custom-stars-rating .active').removeClass('active');
            $('#commentform')[0].reset();
            var commentForm = $('#respond');
            $('#comment_parent').val(0);
            $("#review_form").append(commentForm)
        }
    
});

