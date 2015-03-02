<?php

// Icon Box Shortcode

function vntd_fancy_text_block($atts, $content = null) {
	extract(shortcode_atts(array(
		"style" => '',
		"title" => '',
		"subtitle" => '',	
		"alttitle" => '',	
		"button_label" => '',
		"button_url" => '',
		"plus_url" => '',
		"text" => ''		
	), $atts));
	
	$t_class = 't-left';
	if($style == 'style3') {
		$t_class = 't-center';
	}

	$output = '';
	$output .= '<div class="inner-portfolio clearfix '.$t_class.' fancy-text-'.$style.'">';
	
	if($style == 'style3') {
	
		if($alttitle) {
			$output .= '<h2 class="number white font-primary uppercase animated" data-animation="fadeInDownBig" data-animation-delay="200">'.$alttitle.'</h2>';
		}
		
		$output .= '<div class="glass-bg part-bg animated" data-animation="fadeInUp" data-animation-delay="300">';
		
		if($plus_url) {
			$output .= '<a href="'.$plus_url.'" class="plus-button round ex-link"></a>';
		}
		
		$output .= '<h1 class="p-head white t-left font-primary uppercase no-margin">'.$title.'</h1></div>';
		
		if($subtitle) {
			$output .= '<h4 class="p-head-two white animated font-secondary" data-animation="fadeInUp" data-animation-delay="400">'.$subtitle.'</h4>';
		}	
		
		if($button_label) {
			$output .= '<a href="'.$button_url.'" class="scroll home-button-white uppercase font-primary semibold animated" data-animation="fadeInUpBig" data-animation-delay="200">'.$button_label.'<i class="fa fa-angle-down"></i></a>';
		}
	
	} else {
	
		if($style == 'style2') {
			$output .= '<div class="inner-second glass-bg clearfix animated" data-animation="fadeInLeftBig" data-animation-delay="100">';
		}
		
		if($plus_url) {
			$output .= '<div class="f-left p-part"><a href="'.$plus_url.'" class="plus-button round animated ex-link" data-animation="fadeInLeftBig" data-animation-delay="100"></a></div>';
		}
		
		$output .= '<div class="f-left p-part t-shadow">';
		
		if($alttitle) {
			$output .= '<h2 class="number white font-primary uppercase animated" data-animation="fadeInUp" data-animation-delay="100">'.$alttitle.'</h2>';
		}
		
		$output .= '<h1 class="p-head white font-primary uppercase animated" data-animation="fadeInUp" data-animation-delay="200">'.$title.'</h1>';
		
		if($subtitle) {
			$output .= '<h4 class="white animated font-secondary" data-animation="fadeInUp" data-animation-delay="300">'.$subtitle.'</h4>';
		}
		
		$output .= '<p class="white animated" data-animation="fadeInUp" data-animation-delay="400">'.$text.'</p>';
						
		if($button_label) {
			$output .= '<a href="'.$button_url.'" class="scroll home-button-white uppercase font-primary semibold animated" data-animation="fadeInUp" data-animation-delay="500">'.$button_label.'<i class="fa fa-angle-down"></i></a>';
		}
		
		if($style == 'style2') {
			$output .= '</div>';
		}
	
		$output .= '</div>';
	
	}
	
	$output .= '</div>';
	
	
	return $output;
	
}
remove_shortcode('fancy_text_block');
add_shortcode('fancy_text_block', 'vntd_fancy_text_block');