// import $ from "jquery"
import * as bootstrap from 'bootstrap'
import $ from 'jquery'
import "./single-product.js"

$(document).ready(function() {
    $('#add-to-cart-form').on('submit', function(e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            url: ajax_url.admin_ajax,
            type: 'POST',
            data: {
                action: 'add_to_cart',
                product_id: $form.find('input[name="product_id"]').val()
            },
            success: function(res) {
                $("#cart-total").html(res.cart_total)
                alert(res.message); // Show success message or update cart count, etc.
            }
        });
    });
    
})
