/*
 * ---------------------------------------------------------------- 
 *  
 *  WooCommerce custom javascripts
 *  
 * ----------------------------------------------------------------  
 */


jQuery(document).ready(function($) {

	add_to_cart_action();
	add_to_cart_data();
	
	
});

var currentItem = '';
var count;
function add_to_cart_action() {

	jQuery('.cart-extra').fadeOut();
	var cart_count = jQuery('.woo-cart-count');
	
	
	jQuery('body').on('click', '.add_to_cart_button', function()
	{
		currentLoader = jQuery(this).closest('li.product').find('.product-loading');
		jQuery(currentLoader).fadeIn();
		count = parseInt(cart_count.text());
		
	});
	
	jQuery('body').bind('added_to_cart', function()
	{
		
		if(count == 0) {
			jQuery('.nav-cart').animate({'opacity':1, 'width':33}).find('.woo-cart-count').delay(500).fadeIn();
		}
		
		jQuery(currentLoader).find('i').removeClass('fa fa-refresh').addClass('fa fa-check').closest('div').delay(1600).fadeOut().closest('li.product').addClass('product-added');
		
		jQuery(cart_count).text(count+1);
		// Display popup
		//jQuery('.nav-cart-popup').fadeIn().delay(2400).fadeOut();
	});
}

var newWooProduct = {};
function add_to_cart_data()
{
	jQuery('body').on('click','.add_to_cart_button', function()
	{	
		var productContainer	= jQuery(this).parents('.product').eq(0), product = {};
			product.name		= productContainer.find('h3').text();
			product.img		 	= productContainer.find('.product-thumbnail img.wp-post-imag');
			product.price	 	= productContainer.find('.price .amount').last().text().replace(/[^0-9\.]+/g, '');
			
			//if(product.image.length) product.image = "<img class='added-product-image' src='" + product.image.get(0).src + "' title='' alt='' />";
			
			newWooProduct = product;
			
			//alert(product.permalink);
	});
}