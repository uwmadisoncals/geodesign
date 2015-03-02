<?php 

add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );

function child_manage_woocommerce_styles() {
	remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

//		wp_dequeue_style( 'woocommerce_frontend_styles' );
//		wp_dequeue_style( 'woocommerce_fancybox_styles' );
//		wp_dequeue_style( 'woocommerce_chosen_styles' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		wp_dequeue_style( 'woocommerce_frontend_styles' );
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		wp_dequeue_script( 'prettyPhoto-init' );
//		wp_dequeue_script( 'wc_price_slider' );
//		wp_dequeue_script( 'wc-single-product' );
//		wp_dequeue_script( 'wc-add-to-cart' );
//		wp_dequeue_script( 'wc-cart-fragments' );
//		wp_dequeue_script( 'wc-checkout' );
//		wp_dequeue_script( 'wc-add-to-cart-variation' );
//		wp_dequeue_script( 'wc-single-product' );
//		wp_dequeue_script( 'wc-cart' );
//		wp_dequeue_script( 'wc-chosen' );
//		wp_dequeue_script( 'woocommerce' );
//		wp_dequeue_script( 'prettyPhoto' );
//		wp_dequeue_script( 'prettyPhoto-init' );
//		wp_dequeue_script( 'jquery-blockui' );
//		wp_dequeue_script( 'jquery-placeholder' );
//		wp_dequeue_script( 'fancybox' );
//		wp_dequeue_script( 'jqueryui' );
}


function woocommerce_init_settings()
{
	$catalog = array(
		'width' 	=> '300',	// px
		'height'	=> '360',	// px
		'crop'		=> 1 		// true
	);
 
	$single = array(
		'width' 	=> '600',	// px
		'height'	=> '',	// px
		'crop'		=> 1 		// true
	);
 
	$thumbnail = array(
		'width' 	=> '120',	// px
		'height'	=> '120',	// px
		'crop'		=> 0 		// false
	);
 
	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog ); 		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
	
	wp_register_script('woo-js', get_template_directory_uri() . '/framework/plugins/woocommerce/woocommerce-scripts.js', array('jquery'));			
	wp_enqueue_script('woo-js', '', '', '', true);
	
	wp_register_style('woocommerce-custom', get_template_directory_uri() . '/framework/plugins/woocommerce/woocommerce-styling.css');	
	wp_enqueue_style('woocommerce-custom');

}
add_action('init', 'woocommerce_init_settings', 1);

add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 999; // 3 products per row
	}
}

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);

add_action('woocommerce_before_shop_loop', 'my_theme_wrapper_start', 10);

function my_theme_wrapper_start() {

	$order = $orderby = $output = $current_count = '';
	
	if(isset($_GET['product_order'])) {
		$order = $_GET['product_order'];
	}
	if(isset($_GET['product_orderby'])) {
		$orderby = $_GET['product_orderby'];
	}
	
	
	if(!$orderby) $orderby = "Default Order";
	//
		
	$output .= '<ul class="product-filters"><li class="product-orderby"><span>Sort by <strong>'.ucfirst($orderby).'</strong></span><ul>';

	// OrderBy	
	
	$orderby_list = array('default' => 'Default Order','title' => 'Name','price' => 'Price','date' => 'Date','popularity' => 'Popularity');
		
	foreach($orderby_list as $single_order) {
	
		$params_orderby = array_merge($_GET, array("product_orderby" => array_search($single_order,$orderby_list)));
		$params_orderby_url = http_build_query($params_orderby);
		
		$output .= '<li><a href="?'.$params_orderby_url.'">Sort by '.$single_order.'</a></li>';
	}
	
	$output .= '</ul></li>';
	
	// Order
	
	if(!$order || $order == 'asc') {
		$order_opposite = 'desc';
		$arrow_direction = 'down';
	} else {
		$order_opposite = 'asc';
		$arrow_direction = 'up';
	}
	
	$params_order = array_merge($_GET, array("product_order" => $order_opposite));
	$params_order_url = http_build_query($params_order);
	
	$output .= '<li class="product-order"><a href="?'.$params_order_url.'"><i class="fa fa-chevron-'.$arrow_direction.'"></i></a></li>';
	
	// Product Count	
	if(isset($_GET['product_count'])) {
		$current_count = $_GET['product_count'];
	}
	$products_count = get_option('posts_per_page');
	
	if(!$current_count) $current_count = $products_count;
	
	$output .= '<li class="product-count"><span>Show <strong>'.$current_count.'</strong> products</span><ul>';
	
	
	$count_array = array($products_count,$products_count*2,$products_count*3);
	
	foreach ($count_array as $count) {
		$params_count = array_merge($_GET, array("product_count" => $count));
		$output .= '<li><a href="?'.http_build_query($params_count).'">Show '.$count.' products</a></li>';
	}
	
	$output .= '</ul></li></ul>';
	
	
	echo $output;
}

//
// Ordering
//

add_action('woocommerce_get_catalog_ordering_args', 'woocommerce_custom_ordering', 20);

function woocommerce_custom_ordering($args) {

	$order = $orderby = '';
	if(isset($_GET['product_order'])) {
		$order = $_GET['product_order'];
	}
	if(isset($_GET['product_orderby'])) {
		$orderby = $_GET['product_orderby'];
	}
	
	//$_GET['product_count']
	
	if($order) $args['order'] = $order;
	if($orderby && $orderby != 'price' && $orderby != 'popularity') $args['orderby'] = $orderby;
	//$args['meta_key'] = '';	
	
	if($orderby == 'price') {	
		$args['orderby'] = 'meta_value_num';
		$args['meta_key'] = '_price';
	}elseif($orderby == 'popularity'){
		$args['orderby'] = 'meta_value_num';
		$args['meta_key'] = 'total_sales';
	}
	
	return $args;
	
}

//
// Product Count
//

//if(isset($_GET['product_count'])) {
//	$product_count = $_GET['product_count'];
//}
//$product_count = $_GET['product_count'];
//
//if($product_count) {
//	add_filter( 'loop_shop_per_page', create_function( '$cols', 'return '.$product_count.';' ), 20 );
//}

//
// Nav Cart
//

function vntd_woo_nav_cart() {
	global $woocommerce;

	$cart_count = $woocommerce->cart->get_cart_contents_count();
	if($cart_count == 0) $inactive = ' nav-cart-inactive'; ?>

	<div id="woo-nav-cart" class="nav-cart<?php echo $inactive; ?>">
		<div class="nav-cart-content">
			<i class="fa fa-shopping-cart"></i>
			<span class="woo-cart-count"><?php echo $cart_count; ?></span>
		</div>
		<div class="nav-cart-products">
			<div class="widget_shopping_cart"><div class="widget_shopping_cart_content"></div></div>				
		</div>
	</div>

<?php 
}
//
// Product thumbnail
//

add_action('woocommerce_before_shop_loop_item_title', 'product_custom_thumbnail', 10);

function product_custom_thumbnail() {

	global $product;
	
	$thumb_size = 'shop_catalog';
	$product_id = get_the_ID();	
	
	$output = '<div class="product-thumbnail">';
	// Check if there is a gallery
	
	$attachment_ids = $product->get_gallery_attachment_ids();	
	if ($attachment_ids) {
		$hover_image_id = array_shift(array_values($attachment_ids));
		$output .= wp_get_attachment_image($hover_image_id, $thumb_size, '', 'class=product-hover-image');
	}
	
	// Continue
	
	$output .= get_the_post_thumbnail($product_id, $thumb_size);
	$output .= '<div class="product-loading"><i class="fa fa-refresh"></i></div>';
	$output .= '</div>';
	
	
	
	echo $output;
}

add_action('woocommerce_after_shop_loop_item', 'after_product_content', 16);

function after_product_content() {
	
	//woocommerce_frontend_scripts();
	global $product;
	
	$output = '<div class="product-meta">';
	
	ob_start();
	woocommerce_template_loop_add_to_cart();
	$output .= ob_get_clean();
	
	if($product->product_type == 'simple') {
		$output .= '<a class="product-details" href="'.get_permalink($product->id).'">Details</a>';
	}
	
	echo $output;
	
}

//
// Single Product Page
//



remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action( 'woocommerce_before_single_product_summary', 'woo_summary_begin', 25);
add_action( 'woocommerce_after_single_product',  'woo_single_end', 3);
add_action( 'woocommerce_before_single_product_summary', 'woo_gallery_begin', 2);
add_action('woocommerce_single_product_summary','ratings_single',6);

function woo_gallery_begin()
{
	echo '<div class="vntd-row"><div class="span5">';
}

function woo_summary_begin()
{
	echo '</div><div class="span7">';
}

function woo_single_end()
{
	echo '</div></div>';
	
	global $smof_data;
	
	if($smof_data['vntd_woo_related']) {
		$args = array(
			'posts_per_page' => 4,
			'columns' => 4
		);
		echo woocommerce_related_products($args,4);
	}
}

function ratings_single() {
	//global $product;
	//$average = $product->get_average_rating();
	
	//echo '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';

	//echo '<div class="star-rating single-star-rating" title="'.sprintf(__( 'Rated %s out of 5', 'veented_backend' ), $average ).'"><span style="width:'.( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">'.$average.'</strong> '.__( 'out of 5', 'veented_backend' ).'</span></div>';
	
	//echo '</div>';
 
}

add_filter ( 'woocommerce_product_thumbnails_columns', 'xx_thumb_cols' );

function xx_thumb_cols() {
	return 4; // .last class applied to every 4th thumbnail
}
