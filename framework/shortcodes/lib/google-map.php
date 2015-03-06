<?php

// Google Map Shortcode


function vntd_gmap($atts, $content = null) {
	extract(shortcode_atts(array(
		"height" => '400',
		"zoom" 	=> '15',		
		"label" => '',
		"fullscreen" => '',	
		"lat" 	=> '',
		"long" 	=> '',
		"marker1_title"	=> '',
		"marker1_text"	=> '',
		"marker1_location"	=> '',
		"marker1_location_custom"	=> '',
		"marker2_title"	=> '',
		"marker2_text"	=> '',
		"marker2_location"	=> '',
	), $atts));
	
	$rand_id = rand(1,9999);
	
	wp_enqueue_script('google-map-sensor', '', '', '', true);
	
	if(!$lat || !$long) {
		return 'Error: no location lat and/or long data found';
	}
	
	$map_center = $lat.','.$long;
	
	$marker1_center = $map_center;
	
	if($marker1_location == 'custom') {
		$marker1_center = $marker1_location_custom;
	}		

	ob_start();	
	?>
	<script type="text/javascript">
	
	jQuery(document).ready(function() {
	
		'use strict';

		// Map Coordination

		var latlng = new google.maps.LatLng(<?php echo $map_center; ?>);

		// Map Options
		var myOptions = {
			zoom: <?php echo $zoom; ?>,
			center: latlng,
			mapTypeId: google.maps.MapTypeId.SATELLITE,
			disableDefaultUI: true,
			scrollwheel: false
		};

		var map = new google.maps.Map(document.getElementById('google-map'), myOptions);

		// Marker Image
		var image = '<?php echo get_template_directory_uri() . '/img/marker.png'; ?>';
		
	  	/* ========= First Marker ========= */

	  	// First Marker Coordination
		
		var myLatlng = new google.maps.LatLng(<?php echo $marker1_center; ?>);

		// Your Texts 

		 var contentString = '<div id="content">'+
		  '<div id="siteNotice">'+
		  '</div>'+
		  '<h4>' +

		  '<?php echo $marker1_title; ?>'+

		  '</h4>'+
		  <?php if($marker1_text) { ?>
		  	
		  '<p>' +
		 		
  		  '<?php echo $marker1_text; ?>' +
  
  		  '</p>'+
		  	
		  <?php	} ?>
		  
		  '</div>';
		

		var marker = new google.maps.Marker({
			  position: myLatlng,
			  map: map,
			  title: '<?php echo $marker1_title; ?>',
			  icon: image
		  });


		var infowindow = new google.maps.InfoWindow({
		  content: contentString
		  });

		  
		 google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		  });

		 /* ========= End First Marker ========= */



		<?php if($marker2_title) { ?>

		 /* ========= Second Marker ========= */

		 // Second Marker Coordination

		 var myLatlngSecond = new google.maps.LatLng(<?php echo $marker2_location; ?>);

		 // Your Texts

		 var contentStringSecond = '<div id="content">'+
		  '<div id="siteNotice">'+
		  '</div>'+
		  '<h4>' +

		  '<?php echo $marker2_title; ?>'+

		  '</h4>'+
		  <?php if($marker2_text) { ?>
		  		  	
  		  '<p>' +
  		 		
  		  '<?php echo $marker2_text; ?>' +
  
  		  '</p>'+
  		  	
  		  <?php	} ?>
		  '</div>';

		  var infowindowSecond = new google.maps.InfoWindow({
			  content: contentStringSecond,
			  });

		 var markerSecond = new google.maps.Marker({
			  position: myLatlngSecond,
			  map: map,
			  title: '<?php echo $marker2_title; ?>',
			  icon: image
		  });

		 google.maps.event.addListener(markerSecond, 'click', function() {
			infowindowSecond.open(map,markerSecond);
		  });
		  
		  

		 /* ========= End Second Marker ========= */
	
		<?php } ?>
	});
	
	</script>
	<div class="vntd-gmap">	

	    <div id="google-map" style="height:<?php echo str_replace('px','',$height); ?>px;"></div>
	    
	</div>
	<?php
	
	$content = ob_get_contents();
	ob_end_clean();
	
	return $content;
	
}
remove_shortcode('gmap');
add_shortcode('gmap', 'vntd_gmap');