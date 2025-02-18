<?php

function get_vendorfields($vpod) {
	
	$mapaddr = "";
	if ($vpod->get_field('show_address')) {
		$mapaddr = $vpod->get_field('address');
		if ($vpod->get_field('suite') <> '') {
			$mapaddr .= '<br />' . $vpod->get_field('suite');
		}
		$mapaddr .= '<br />';
	}
	$is_venue = ( (int)array_intersect(array('venues','dining'), array($vpod->get_field('category1'),$vpod->get_field('category2'),$vpod->get_field('category3'),$vpod->get_field('category4'),$vpod->get_field('category5'))) > 0 );
	
	// set our variables
	$a_profile = array(
		"id" =>	$vpod->get_field('id'),
		"name" =>	$vpod->get_field('name'),
		"mapname" =>	str_replace("'","&#039;", $vpod->get_field('name')),
		"mapaddr" =>	$mapaddr,
		"is_venue" =>	$is_venue,
		"slug" =>	$vpod->get_field('slug'),
		"tagl" =>	$vpod->get_field('tagline'),
		"desc" =>	$vpod->get_field('description'),
		"offe" =>	$vpod->get_field('offer'),
		"imag" =>	get_ao_image($vpod),
		"summ" =>	ao_trim_to_length(preg_replace('/\s\s+/', ' ', str_replace('&nbsp;', ' ', strip_tags($vpod->get_field('description')))), 250),
		"lati" =>	$vpod->get_field('latitude'),
		"long" =>	$vpod->get_field('longitude'),
		"wurl" =>	$vpod->get_field('web_url'),
		"burl" =>	$vpod->get_field('blog_url'),
		"furl" =>	$vpod->get_field('facebook_url'),
		"lurl" =>	$vpod->get_field('linkedin_url'),
		"turl" =>	$vpod->get_field('twitter_url'),
		"addr" =>	$vpod->get_field('address'),
		"suit" =>	$vpod->get_field('suite'),
		"city" =>	$vpod->get_field('city'),
		"stat" =>	$vpod->get_field('state'),
		"zipc" =>	$vpod->get_field('zipcode'),
		"trav" =>	$vpod->get_field('travel_policy'),
		"cont" =>	$vpod->get_field('contact_name'),
		"titl" =>	$vpod->get_field('contact_title'),
		"emai" =>	$vpod->get_field('contact_email'),
		"pho1" =>	$vpod->get_field('contact_phone1'),
		"pho2" =>	$vpod->get_field('contact_phone2'),
		"pho3" =>	$vpod->get_field('contact_phone3'),
		"pho1t" =>	$vpod->get_field('contact_phone1_type'),
		"pho2t" =>	$vpod->get_field('contact_phone2_type'),
		"pho3t" =>	$vpod->get_field('contact_phone3_type'),
		"spac" =>	$vpod->get_field('spaces_available'),
		"capa" =>	$vpod->get_field('capacity'),
		"squa" =>	$vpod->get_field('square_footage'),
		"ctng" =>	$vpod->get_field('catering'),
		"alco" =>	$vpod->get_field('alcohol_permitted'),
		"acco" =>	$vpod->get_field('onsite_accomodations'),
		"acce" =>	$vpod->get_field('accessible'),
		"accv" =>	$vpod->get_field('accepts_visa'),
		"accm" =>	$vpod->get_field('accepts_mc'),
		"acca" =>	$vpod->get_field('accepts_amex'),
		"accd" =>	$vpod->get_field('accepts_discover'),
		"accc" =>	$vpod->get_field('accepts_checks'),
		"accb" =>	$vpod->get_field('accepts_bank'),
		"accp" =>	$vpod->get_field('accepts_paypal'),
		"cat1" =>	$vpod->get_field('category1'),
		"cat2" =>	$vpod->get_field('category2'),
		"cat3" =>	$vpod->get_field('category3'),
		"cat4" =>	$vpod->get_field('category4'),
		"cat5" =>	$vpod->get_field('category5'),
		"rati" =>	$vpod->get_field('rating'),
		"v2id" =>	$vpod->get_field('ao2_guid')
	);
	if ($a_profile['wurl'] != '' && substr($a_profile['wurl'], 0, 4) != "http") {$a_profile['wurl'] = 'http://' . $a_profile['wurl'];}
	if ($a_profile['burl'] != '' && substr($a_profile['burl'], 0, 4) != "http") {$a_profile['burl'] = 'http://' . $a_profile['burl'];}
	if ($a_profile['furl'] != '' && substr($a_profile['furl'], 0, 4) != "http") {$a_profile['furl'] = 'http://www.facebook.com/' . $a_profile['furl'];}
	if ($a_profile['lurl'] != '' && substr($a_profile['lurl'], 0, 4) != "http") {$a_profile['lurl'] = 'http://www.linkedin.com/e/fpf/' . $a_profile['lurl'];}
	if ($a_profile['turl'] != '' && substr($a_profile['turl'], 0, 4) != "http") {$a_profile['turl'] = 'http://twitter.com/' . $a_profile['turl'];}
	
	return $a_profile;
}

function get_eventfields($epod) {
	
	// set our variables
	return array(
		"id" =>	$epod->get_field('id'),
		"name" =>	$epod->get_field('name'),
		"mapname" =>	str_replace("'","&#039;", $epod->get_field('name')),
		"mapaddr" =>	$epod->get_field('address') . '<br />',
		"slug" =>	$epod->get_field('slug'),
		"vend" =>	$epod->get_field('vendor'),
		"dsta" =>	$epod->get_field('date_start'),
		"dend" =>	$epod->get_field('date_end'),
		"hour" =>	$epod->get_field('hours'),
		"loca" =>	$epod->get_field('location'),
		"addr" =>	$epod->get_field('address'),
		"city" =>	$epod->get_field('city'),
		"stat" =>	$epod->get_field('state'),
		"zipc" =>	$epod->get_field('zipcode'),
		"coun" =>	$epod->get_field('county'),
		"wurl" =>	$epod->get_field('web_url'),
		"cost" =>	$epod->get_field('cost'),
		"desc" =>	$epod->get_field('description'),
		"summ" =>	ao_trim_to_length(preg_replace('/\s\s+/', ' ', str_replace('&nbsp;', ' ', strip_tags($epod->get_field('description')))), 250),
		"keyw" =>	$epod->get_field('keywords'),
		"phon" =>	$epod->get_field('phone'),
		"type" =>	$epod->get_field('event_type'),
		"appr" =>	$epod->get_field('approved'),
		"v2id" =>	$epod->get_field('ao2_companyguid'),
		"lati" =>	$epod->get_field('latitude'),
		"long" =>	$epod->get_field('longitude')
	);
}

function show_googlemap($amap_items, $center_element = -1, $zoom_level = 9) {
?>

	<div id="map_hide" onclick="maps_swap(); return false;">Show On Map</div>
	
	<div id="collapsing_map" class="CollapsiblePanel">
		<div style="visibility: hidden;" class="CollapsiblePanelTab"></div>
		<div class="CollapsiblePanelContent">
			<div id="map_canvas" style="width:600px; height:600px"></div>
		</div>
	</div>
		
	<script type="text/javascript">
	
		var collapsing_map = new Spry.Widget.CollapsiblePanel("collapsing_map", { contentIsOpen: false });
		var map_initialized = false;
		
		function maps_swap() {
		
			e=document.getElementById('map_hide');
			if (e.innerHTML == 'Hide Map') {
				e.innerHTML = 'Show On Map';
				collapsing_map.close();
			}
			else {
				e.innerHTML = 'Hide Map';
				collapsing_map.open();
				if (!map_initialized) {
					maps_initialize();
					map_initialized = true;
				}
			}
		}
		
		function maps_initialize() {
			<?php
				if (($center_element == -1) || ($amap_items[$center_element]['lati'] == '') || ($amap_items[$center_element]['long'] == '')) {
					// ATLANTA
					echo 'var city_latlng = new google.maps.LatLng(33.7490,-84.3880);';
				}
				else {
					echo 'var city_latlng = new google.maps.LatLng(', $amap_items[$center_element]['lati'], ',', $amap_items[$center_element]['long'], ');';
				}
			?>
			var city_options = {
				zoom: <?php echo $zoom_level; ?>,
				center: city_latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
		
			var ao_map = new google.maps.Map(document.getElementById("map_canvas"), city_options);
		
			var vendor_infowindow = new google.maps.InfoWindow({
				content: ""
			});
			
			google.maps.event.addListener(ao_map, "click", function(){ 
				vendor_infowindow.close(); 
			}); 
		
<?php
			$i = 0;
			foreach($amap_items as $vid => $fields) {
				$i++;
				if ($fields["lati"] <> "" && $fields["long"] <> "") {
				echo <<<HEREDOC
				var vendor_latlng$i = new google.maps.LatLng({$fields["lati"]},{$fields["long"]});
				var vendor_marker$i = new google.maps.Marker({
					position: vendor_latlng$i, 
					map: ao_map
				});
				
				var vendor_details$i = '<table><tr><td><a href="/profile/{$fields["slug"]}"><img style="max-width: 100px; max-height: 100px;" src="{$fields["imag"]}"/></a></td><td><p><b>{$fields["mapname"]}</b><br />{$fields["mapaddr"]}{$fields["city"]}, {$fields["stat"]} {$fields["zipc"]}<br />{$fields["pho1"]}<br /><a href="/profile/{$fields["slug"]}">View Details</a></p></td></tr></table>';
				google.maps.event.addListener(vendor_marker$i, 'click', function() {
					vendor_infowindow.setContent(vendor_details$i);
					vendor_infowindow.open(ao_map,vendor_marker$i);
				});
HEREDOC;
				}
			}
?>
		}
	</script>
	
<?php
}

function get_ao_image($o) {

	// $o is a PODSCMS $pod object

	// first figure out which photo to use...
	$aimg = $o->get_field('profile_image');
	if ( !empty($aimg) ) {
		$img = $aimg[0]['guid'];
		
		$img_alt = $img;

		// get the thumbnail
		$img_alt = str_replace('.jpg', '-125x125.jpg', $img_alt);
		$img_alt = str_replace('.jpeg', '-125x125.jpeg', $img_alt);
		$img_alt = str_replace('.gif', '-125x125.gif', $img_alt);
		$img_alt = str_replace('.png', '-125x125.png', $img_alt);
		$img_alt = str_replace('.tif', '-125x125.tif', $img_alt);
		$img_alt = str_replace('.tiff', '-125x125.tiff', $img_alt);

		// chop off the URL, this way we are left with a relative path 
		// so we can check if the file exists on disk later.
		$img_alt = str_replace(get_bloginfo('url'), '', $img_alt);
		$img = str_replace(get_bloginfo('url'), '', $img);
		
		if (file_exists(ABSPATH . $img_alt)) {
			$img = $img_alt;
		}
	}
	else {
		$img = strtolower($o->get_field('v2_profile_image_sm'));
		$img = str_replace('.jpeg', '.jpg', $img);
		$img = str_replace('.gif', '.jpg', $img);
		$img = str_replace('.png', '.jpg', $img);
		$img = str_replace('.tif', '.jpg', $img);
		$img = str_replace('.tiff', '.jpg', $img);
	}
	
	if ($img == "" || !file_exists(ABSPATH . $img)) {
		return '/media/images/vendor.gif';
	}
	else {
		return $img;
	}
};

function check_ao_image($img) {
	// return false if we are using the default "no image available" image
	return (substr($img, -11) != '/vendor.gif');
}

function image_is_square($img) {
	list($width, $height) = getimagesize(ABSPATH . $img);
	return (($width == $height) && (check_ao_image($img)));
}

function get_ratingbox($o) {

	// create our rating box
	$rating_data = '';
	if ( ($o["rati"] != '') && ((int)$o["rati"] > 0) ) {
		$rating_data = '<div id="pro_rating">';
		$j = 0;
		for ($i = 1; $i <= (int)$o["rati"]; $i++) {
			$rating_data .= '<img src="/media/images/star.png" />';
			$j++;
		}
		if ((int)$o["rati"] != ((int)($o["rati"] + 0.50))) {
			$rating_data .= '<img src="/media/images/star-half.png" />';
			$j++;
		}
		for ( ; $j < 5; $j++) {
			$rating_data .= '<img src="/media/images/star-sad.png" />';
		}
		$rating_data .= '</div>';
	}
	return $rating_data;
}

function featuredvendors($title = '<h2>Featured Vendors</h2>', $cat = "", $num_rows = 1, $min = 4) {
	return;
	$featpod = new Pod('vendor_profiles');

	// leave this here for REFERENCE ONLY, it is not used
	$sql = "SELECT wp_pod_tbl_vendor_profiles.id AS vid, wp_pod_tbl_vendor_profiles.name AS name, wp_pod_tbl_vendor_profiles.slug AS slug, ".
		"wp_pod_tbl_vendor_profiles.description AS description, v2_profile_image_sm FROM wp_pod_tbl_vendor_profiles ".
		"JOIN wp_pod ON (wp_pod_tbl_vendor_profiles.id = wp_pod.tbl_row_id) ".
		"JOIN wp_pod_rel ON (wp_pod.id = wp_pod_rel.pod_id) ".
		"JOIN wp_pod_tbl_categories ON (wp_pod_rel.tbl_row_id = wp_pod_tbl_categories.id) ".
		"WHERE ".
		"wp_pod_rel.field_id = 110 ".
		"AND profile_type = 'Platinum' ";

	if ($cat == 'services') {
		// leave this here for REFERENCE ONLY, it is not used
		$sql .= "AND wp_pod_tbl_categories.slug NOT IN ('venues','dining', '')";

		$sqlwhere = "profile_type = 'platinum' ".
			"AND ((category1 NOT IN ('venues','dining', 'uncategorized', '')) ".
			"OR (category2 NOT IN ('venues','dining', 'uncategorized', '')) ".
			"OR (category3 NOT IN ('venues','dining', 'uncategorized', '')) ".
			"OR (category4 NOT IN ('venues','dining', 'uncategorized', '')) ".
			"OR (category5 NOT IN ('venues','dining', 'uncategorized', '')) ".
			")";
	
	}
	elseif ($cat != '') {
		// leave this here for REFERENCE ONLY, it is not used
		$sql .= "AND wp_pod_tbl_categories.slug = '$cat'";

		$sqlwhere = "profile_type = 'platinum' ".
			"AND ((category1 = '$cat') ".
			"OR (category2 = '$cat') ".
			"OR (category3 = '$cat') ".
			"OR (category4 = '$cat') ".
			"OR (category5 = '$cat') ".
			")";
	}
	else {
	}

	$featpod->findRecords( 'id', -1, $sqlwhere);
	$total_vps = $featpod->getTotalRows();

	$avp = array();

	// there has to be at least $min vendors to show the featured block
	if( $total_vps >= $min ) {
		while ( $featpod->fetchRecord() ) {
			// make sure we have an ACTUAL image file
			$img = get_ao_image($featpod);
			
			// make sure the image is square (only square images can be displayed here)
			if (image_is_square($img)) {

				$avp[$featpod->get_field('id')] = get_vendorfields($featpod);

			}
		}
	}
	else {
		return false;
	}
	
	// randomize the vendor list
	shuffle($avp);

	// now pull the first x vendors
	$avp = array_slice($avp, 0, ($num_rows * 4));
	
	echo $title;
	
	$i = 0;
	echo '<div class="featured_block">';
	foreach($avp as $vid => $fields) {
		$i++;
		
		// end the block and start a new one if needed
		if ($i == 5) {
			echo '</div><div class="featured_block">';
			$i = 1;
			echo '<br clear="all" />';
		}
		
		// spit out the image
		echo <<<HEREDOC
		<div class="featured_wrap">
			<div class="featured_image"><a href="/profile/{$fields["slug"]}"><img src="{$fields["imag"]}" title="{$fields["name"]}" alt="{$fields["name"]}"/></a></div>
			<div class="featured_name"><a href="/profile/{$fields["slug"]}">{$fields["name"]}</a></div>
		</div>
HEREDOC;
	}
	echo '</div><br clear="all" />';
}

?>