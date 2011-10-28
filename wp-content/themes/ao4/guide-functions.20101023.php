<?php

function get_vendorfields(&$vpod) {

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
		"acti" =>	$vpod->get_field('active'),
		"expi" =>	$vpod->get_field('expiration_date'),
		"type" =>	$vpod->get_field('profile_type'),
		"plan" =>	$vpod->get_field('payment_plan'),
		"name" =>	$vpod->get_field('name'),
		"mapname" =>	str_replace("'","&#039;", $vpod->get_field('name')),
		"mapaddr" =>	$mapaddr,
		"showadd" =>	$vpod->get_field('show_address'),
		"is_venue" =>	$is_venue,
		"slug" =>	$vpod->get_field('slug'),
		"tagl" =>	$vpod->get_field('tagline'),
		"desc" =>	htmlspecialchars_decode($vpod->get_field('description')),
		"offe" =>	htmlspecialchars_decode($vpod->get_field('offer')),
		"imag" =>	get_ao_image($vpod),
		"imag_old" =>	get_ao_image_old($vpod),
		"summ" =>	ao_trim_to_length(preg_replace('/\s\s+/', ' ', str_replace('&nbsp;', ' ', strip_tags(htmlspecialchars_decode($vpod->get_field('description'))))), 250),
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
		"lvws" =>	$vpod->get_field('list_views'),
		"pvws" =>	$vpod->get_field('profile_views'),
		"v2id" =>	$vpod->get_field('ao2_guid'),
		//************************************************
		// yeah, yeah, yeah, don't ask....
		//************************************************
		"active" 				=>	$vpod->get_field('active'),
		"expiration_date" 		=>	$vpod->get_field('expiration_date'),
		"profile_type" 			=>	$vpod->get_field('profile_type'),
		"payment_plan" 			=>	$vpod->get_field('payment_plan'),
		//"name" 				=>	$vpod->get_field('name'),
		"show_address" 			=>	$vpod->get_field('show_address'),
		//"slug" 				=>	$vpod->get_field('slug'),
		"tagline" 				=>	$vpod->get_field('tagline'),
		"description" 			=>	htmlspecialchars_decode($vpod->get_field('description')),
		"offer" 				=>	htmlspecialchars_decode($vpod->get_field('offer')),
		"latitude" 				=>	$vpod->get_field('latitude'),
		"longitude" 			=>	$vpod->get_field('longitude'),
		"web_url" 				=>	$vpod->get_field('web_url'),
		"blog_url" 				=>	$vpod->get_field('blog_url'),
		"facebook_url" 			=>	$vpod->get_field('facebook_url'),
		"linkedin_url" 			=>	$vpod->get_field('linkedin_url'),
		"twitter_url" 			=>	$vpod->get_field('twitter_url'),
		"address" 				=>	$vpod->get_field('address'),
		"suite" 				=>	$vpod->get_field('suite'),
		//"city" 				=>	$vpod->get_field('city'),
		"state" 				=>	$vpod->get_field('state'),
		"zipcode" 				=>	$vpod->get_field('zipcode'),
		"county" 				=>	$vpod->get_field('county'),
		"travel_policy" 		=>	$vpod->get_field('travel_policy'),
		"contact_name" 			=>	$vpod->get_field('contact_name'),
		"contact_title" 		=>	$vpod->get_field('contact_title'),
		"contact_email" 		=>	$vpod->get_field('contact_email'),
		"contact_phone1" 		=>	$vpod->get_field('contact_phone1'),
		"contact_phone2" 		=>	$vpod->get_field('contact_phone2'),
		"contact_phone3" 		=>	$vpod->get_field('contact_phone3'),
		"contact_phone1_type"	=>	$vpod->get_field('contact_phone1_type'),
		"contact_phone2_type"	=>	$vpod->get_field('contact_phone2_type'),
		"contact_phone3_type"	=>	$vpod->get_field('contact_phone3_type'),
		"spaces_available" 		=>	$vpod->get_field('spaces_available'),
		"capacity" 				=>	$vpod->get_field('capacity'),
		"square_footage" 		=>	$vpod->get_field('square_footage'),
		"catering" 				=>	$vpod->get_field('catering'),
		"alcohol_permitted" 	=>	$vpod->get_field('alcohol_permitted'),
		"onsite_accomodations" 	=>	$vpod->get_field('onsite_accomodations'),
		"accessible" 			=>	$vpod->get_field('accessible'),
		"accepts_visa" 			=>	$vpod->get_field('accepts_visa'),
		"accepts_mc" 			=>	$vpod->get_field('accepts_mc'),
		"accepts_amex" 			=>	$vpod->get_field('accepts_amex'),
		"accepts_discover" 		=>	$vpod->get_field('accepts_discover'),
		"accepts_checks" 		=>	$vpod->get_field('accepts_checks'),
		"accepts_bank" 			=>	$vpod->get_field('accepts_bank'),
		"accepts_paypal"		=>	$vpod->get_field('accepts_paypal'),
		"category1"				=>	$vpod->get_field('category1'),
		"category2"				=>	$vpod->get_field('category2'),
		"category3"				=>	$vpod->get_field('category3'),
		"category4"				=>	$vpod->get_field('category4'),
		"category5"				=>	$vpod->get_field('category5'),
		"rating" 				=>	$vpod->get_field('rating'),
		"profile_views" 		=>	$vpod->get_field('profile_views'),
		"clicks_web" 			=>	$vpod->get_field('clicks_web'),
		"clicks_blog" 			=>	$vpod->get_field('clicks_blog'),
		"clicks_facebook" 		=>	$vpod->get_field('clicks_facebook'),
		"clicks_linkedin" 		=>	$vpod->get_field('clicks_linkedin'),
		"clicks_twitter" 		=>	$vpod->get_field('clicks_twitter'),
		"video_url"				=>	$vpod->get_field('video_url'),
		"video_first"			=>	$vpod->get_field('video_first'),
		"type1"					=>	$vpod->get_field('type1'),
		"type2"					=>	$vpod->get_field('type2'),
		"type3"					=>	$vpod->get_field('type3'),
		"card_num" 				=>	$vpod->get_field('card_num'),
		"card_exp_month" 		=>	$vpod->get_field('card_exp_month'),
		"card_exp_year" 		=>	$vpod->get_field('card_exp_year'),
		"subscriber_id" 		=>	$vpod->get_field('subscriber_id'),
		"payment_amount" 		=>	$vpod->get_field('payment_amount'),
		"subscription_plan" 	=>	$vpod->get_field('subscription_plan'),
		"renewal_month" 		=>	$vpod->get_field('renewal_month'),
		"renewal_day" 			=>	$vpod->get_field('renewal_day'),
		"authorization_code" 	=>	$vpod->get_field('authorization_code'),
		"transaction_id" 		=>	$vpod->get_field('transaction_id'),
		"ao2_guid" 				=>	$vpod->get_field('ao2_guid')
	);


	if ($a_profile['web_url']		!= '' && substr($a_profile['web_url'], 0, 4) != "http") 		{$a_profile['web_url'] = 'http://' . $a_profile['web_url'];}
	if ($a_profile['blog_url']		!= '' && substr($a_profile['blog_url'], 0, 4) != "http")		{$a_profile['blog_url'] = 'http://' . $a_profile['blog_url'];}
	if ($a_profile['facebook_url']	!= '' && substr($a_profile['facebook_url'], 0, 4) != "http")	{$a_profile['facebook_url'] = 'http://www.facebook.com/' . $a_profile['facebook_url'];}
	if ($a_profile['linkedin_url']	!= '' && substr($a_profile['linkedin_url'], 0, 4) != "http")	{$a_profile['linkedin_url'] = 'http://www.linkedin.com/e/fpf/' . $a_profile['linkedin_url'];}
	if ($a_profile['twitter_url']	!= '' && substr($a_profile['twitter_url'], 0, 4) != "http")		{$a_profile['twitter_url'] = 'http://twitter.com/' . $a_profile['twitter_url'];}
	if ($a_profile['twitter_url']	!= '') 															{$a_profile['twitter_id'] = str_replace('http://twitter.com/', '', $a_profile['twitter_url']);}

	$a_profile['wurl'] = $a_profile['web_url'];
	$a_profile['burl'] = $a_profile['blog_url'];
	$a_profile['furl'] = $a_profile['facebook_url'];
	$a_profile['lurl'] = $a_profile['linkedin_url'];
	$a_profile['turl'] = $a_profile['twitter_url'];
	
	return $a_profile;
}

function load_vendorimages(&$profile, &$a) {
	// *********************************************************
	// IMAGE PROCESSING
	// we go ahead and process all the images since we need a
	// count of the number of slideshow images to determine
	// if we need to generate the flash slideshow code on the
	// profile page at all. We check for images in this order:
	//
	//	1)	Look in the profile tables for images that were
	//		uploaded into the WP Media Library via PodsCMS.
	//
	//	2)	Look up the table of images from the v2 website.
	//
	// *********************************************************

	global $img_base_path;
	global $img_dst_source;
	global $img_dst_large;
	global $img_dst_thumb;
	global $img_dst_logo;
	global $img_web_source;
	global $img_web_large;
	global $img_web_thumb;
	global $img_web_logo;

	$img_num = 0;
	$img_nodes = '';
	$pid = $a['id'];

	$a['imagelist_source'] = array();
	$a['imagelist_large'] = array();
	$a['imagelist_thumb'] = array();
	$a['imagelist_names'] = array();

	//if ($pid == '582' || $pid == '607' || $pid == '608' || $pid == '89' || $pid == '143' || $pid == '593' || $pid == '167') {
	// check to see if their image dir exists
	if (is_dir("$img_dst_thumb/$pid")) {
		$a_entries = scandir("$img_dst_thumb/$pid");
		sort($a_entries);
		foreach ($a_entries as $entry) {
			if (is_file("$img_dst_thumb/$pid/$entry")) {
				$img_num++;
				$a['imagelist_source'][] = "$img_web_source/$pid/$entry";
				$a['imagelist_large'][] = "$img_web_large/$pid/$entry";
				$a['imagelist_thumb'][] = "$img_web_thumb/$pid/$entry";
				$a['imagelist_names'][] = $entry;
				$img_nodes .= "<image id='$img_num' source='$img_web_large/$pid/$entry' title='' description='' thumbnail='$img_web_thumb/$pid/$entry' />\n";
			}
		}
	}
	//}

	// if we DIDN'T get any images from disk, then track down the old v2 images.
	if ($img_num == 0) {
	
		// first figure out which photo to use...
		$aimg = $profile->get_field('slideshow_images');
		if (is_array($aimg)) {
			foreach ($aimg as $v) {
		
				// get the image from the WP Media Library
				$lib_img = wp_get_attachment_image_src($v['ID'], 'large', false);
				$img = $lib_img[0];
				$img_w = $lib_img[1];
				$img_h = $lib_img[2];
	
				$img_alt = $v['guid'];
				//$img_alt = $img;
				
				// get the thumbnail
				$img_alt = str_replace('.jpg', '-125x125.jpg', $img_alt);
				$img_alt = str_replace('.jpeg', '-125x125.jpeg', $img_alt);
				$img_alt = str_replace('.gif', '-125x125.gif', $img_alt);
				$img_alt = str_replace('.png', '-125x125.png', $img_alt);
				$img_alt = str_replace('.tif', '-125x125.tif', $img_alt);
				$img_alt = str_replace('.tiff', '-125x125.tiff', $img_alt);
		
				// chop off the URL, this way we are left with a relative path 
				// so we can check if the file exists on disk later.
				$img_alt = str_replace(get_bloginfo('url') . '/', '', $img_alt);
				$img = str_replace(get_bloginfo('url') . '/', '', $img);
				
				if (!file_exists(ABSPATH . $img_alt)) {
					$img_alt = $img;
				}
				if (file_exists(ABSPATH . $img)) {
					$img_num++;
					
					$a['imagelist_source'][] = '/' . $img;
					$a['imagelist_large'][] = '/' . $img;
					$a['imagelist_thumb'][] = '/' . $img_alt;
					
					$img = get_bloginfo('url') . '/' . $img;
					$img_alt = get_bloginfo('url') . '/' . $img_alt;
					if (strtolower(substr($img, -4)) == '.flv') {
						$img_nodes .= "<video id='$img_num' source='$img' title='' description='' thumbnail='$img_alt' />\n";
					}
					else {
						$img_nodes .= "<image id='$img_num' source='$img' title='' description='' thumbnail='$img_alt' />\n";
					}
				}
			}
		}
	}

	// if we DIDN'T get any images from the v3 fieldset, then track down the old v2 images.
	if ($img_num == 0) {
		$images = new Pod('vendor_imagesv2');
		$images->findRecords( 'id', -1, "t.ao2_userid = '{$a['v2id']}'");
		$total = $images->getTotalRows();
		if( $total > 0 ) {
			$img_num = 0;
			$img_nodes = '';
			while ( $images->fetchRecord() ) {
				// as part of the conversion to v3, all the v2 images were converted to JPGs and
				//	resized to 600px on the longest side, all lower case.
				$img_num++;
				$img_name = strtolower($images->get_field('filename'));
				$img_name = str_replace('.jpeg', '.jpg', $img_name);
				$img_name = str_replace('.gif', '.jpg', $img_name);
				$img_name = str_replace('.png', '.jpg', $img_name);
				$img_name = str_replace('.bmp', '.jpg', $img_name);
				$img_name = str_replace('.tif', '.jpg', $img_name);
				$img_name = str_replace('.tiff', '.jpg', $img_name);
				
				$img_name = str_replace('/v2/', '/v2-600/', $img_name);
				if (file_exists(ABSPATH . $img_name)) {
				
					$a['imagelist_source'][] = $img_name;
					$a['imagelist_large'][] = $img_name;
					$a['imagelist_thumb'][] = $img_name;
					
					$img_name = get_bloginfo('url') . $img_name;
					$img_nodes .= "<image pid='$img_dst_thumb' id='$img_num' source='$img_name' title='' description='' thumbnail='$img_name' />\n";
				}
			}
		}
	}
	$a['image_count'] = $img_num;
	$a['image_xmlnodes'] = $img_nodes;
}

function get_thumbnail_block(&$a) {

	$pid = $a['id'];
	if ($a['image_count'] > 0) {
		echo "<p class=\"vendor_desc\">The following images have been uploaded and are already part of your profile.</p>";
		$img_id = 0;
		echo '<div class="vendor_imgblock">';
		foreach ($a['imagelist_thumb'] as $thumb) {
?>
			<div class="vendor_imgthumb" id="img_<?php echo $img_id; ?>">
				<img src="<?php echo $thumb; ?>" /><br />
				<div class="vendor_imgdelete" id="del_<?php echo $img_id; ?>" onclick='$("#del_<?php echo $img_id; ?>").html("Deleting . . .");$.get("<?php echo PAGE_DOIMAGE, '/', $pid; ?>",{cmd:"delete",image:"<?php echo $a['imagelist_names'][$img_id]; ?>"},function(data){if(data == "1"){$("#img_<?php echo $img_id; ?>").remove();}else{alert("There was an error deleting the image.");$("#del_<?php echo $img_id; ?>").html("Delete");}});'>Delete</div>
			</div>
<?php
			$img_id++;
		}
		echo '</div>';
	}
	else {
		echo "<p class=\"vendor_desc\">No images have been uploaded to your profile.</p>";
	}
}

function get_logo_block(&$a) {

	global $img_dst_logo;
	global $img_web_logo;
	$random_int = rand(10000,99999);
	$pid = $a['id'];
	if (file_exists("$img_dst_logo/$pid/logo.jpg")) {
		echo "<p class=\"vendor_desc\">The following logo has been uploaded and is already part of your profile.</p>";
		echo '<div class="vendor_imgblock">';
?>
		<div class="vendor_imgthumb" id="img_logo">
			<img src="<?php echo "$img_web_logo/$pid/logo.jpg?$random_int"; ?>" /><br />
			<div class="vendor_imgdelete" id="del_logo" onclick='$("#del_logo").html("Deleting . . .");$.get("<?php echo PAGE_DOIMAGE, '/', $pid; ?>",{cmd:"deletelogo"},function(data){if(data == "1"){$("#img_logo").remove();}else{alert("There was an error deleting the logo.");$("#del_logo").html("Delete Logo");}});'>Delete Logo</div>
		</div>
<?php
		echo '</div>';
	}
	else {
		echo "<p class=\"vendor_desc\">No profile logo has been uploaded yet.</p>";
	}
}

function get_eventfields(&$epod) {
	
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
		"desc" =>	htmlspecialchars_decode($epod->get_field('description')),
		"summ" =>	ao_trim_to_length(preg_replace('/\s\s+/', ' ', str_replace('&nbsp;', ' ', strip_tags($epod->get_field('description')))), 250),
		"keyw" =>	$epod->get_field('keywords'),
		"phon" =>	$epod->get_field('phone'),
		"type" =>	$epod->get_field('event_type'),
		"appr" =>	$epod->get_field('approved'),
		"v2id" =>	$epod->get_field('ao2_companyguid'),
		"lati" =>	$epod->get_field('latitude'),
		"long" =>	$epod->get_field('longitude'),

		"vendor" 			=>	$epod->get_field('vendor'),
		"date_start"		=>	$epod->get_field('date_start'),
		"date_end" 			=>	$epod->get_field('date_end'),
		"hours" 			=>	$epod->get_field('hours'),
		"location" 			=>	$epod->get_field('location'),
		"address" 			=>	$epod->get_field('address'),
		"city" 				=>	$epod->get_field('city'),
		"state" 			=>	$epod->get_field('state'),
		"zipcode" 			=>	$epod->get_field('zipcode'),
		"county" 			=>	$epod->get_field('county'),
		"web_url" 			=>	$epod->get_field('web_url'),
		"cost" 				=>	$epod->get_field('cost'),
		"description" 		=>	htmlspecialchars_decode($epod->get_field('description')),
		"summary" 			=>	ao_trim_to_length(preg_replace('/\s\s+/', ' ', str_replace('&nbsp;', ' ', strip_tags($epod->get_field('description')))), 250),
		"keywords" 			=>	$epod->get_field('keywords'),
		"phone" 			=>	$epod->get_field('phone'),
		"event_type" 		=>	$epod->get_field('event_type'),
		"approved" 			=>	$epod->get_field('approved'),
		"ao2_companyguid"	=>	$epod->get_field('ao2_companyguid'),
		"latitude" 			=>	$epod->get_field('latitude'),
		"longitude" 		=>	$epod->get_field('longitude')
	);
}

function show_googlemap($amap_items, $center_element = -1, $zoom_level = 9, $show_map = false, $show_button = true) {

	if ($show_button) {
?>
	<div id="map_hide" onclick="maps_swap(); return false;">Show On Map</div>
<?php
	}
?>
	<div id="collapsing_map" class="CollapsiblePanel">
		<div style="visibility: hidden;" class="CollapsiblePanelTab"></div>
		<div class="CollapsiblePanelContent">
			<div id="map_canvas" style="width:600px; height:600px"></div>
		</div>
	</div>
		
	<script type="text/javascript">
	
<?php
	if ($show_map) {
?>
		var collapsing_map = new Spry.Widget.CollapsiblePanel("collapsing_map", { contentIsOpen: true });
		var map_initialized = true;
		maps_initialize();
<?php
	}
	else {
?>
		var collapsing_map = new Spry.Widget.CollapsiblePanel("collapsing_map", { contentIsOpen: false });
		var map_initialized = false;
<?php
	}
?>

		
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
	global $img_dst_logo;
	global $img_web_logo;

	// first figure out which photo to use...
	$pid = $o->get_field('id');
	if (file_exists("$img_dst_logo/$pid/logo.jpg")) {
		return "$img_web_logo/$pid/logo.jpg";
	}
	else {
		return "";
	}
}

function get_ao_image_old($o) {

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

function get_ratingbox($rating) {

	// create our rating box
	$rating_data = '';
	if ( ($rating != '') && ((int)$rating > 0) ) {
		$rating_data = '<div id="pro_rating">';
		$j = 0;
		for ($i = 1; $i <= (int)$rating; $i++) {
			$rating_data .= '<img src="/media/images/star.png" />';
			$j++;
		}
		if ((int)$rating != ((int)($rating + 0.50))) {
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

function write_csv(&$handle, $fields = array(), $delimiter = ',', $enclosure = '"') {

	// Sanity Check
	if (!is_resource($handle)) {
		trigger_error('write_csv() expects parameter 1 to be resource, ' .
			gettype($handle) . ' given', E_USER_WARNING);
		return false;
	}
	
	if ($delimiter!=NULL) {
		if( strlen($delimiter) < 1 ) {
			trigger_error('delimiter must be a character', E_USER_WARNING);
			return false;
		}elseif( strlen($delimiter) > 1 ) {
			trigger_error('delimiter must be a single character', E_USER_NOTICE);
		}
	
		/* use first character from string */
		$delimiter = $delimiter[0];
	}
	
	if( $enclosure!=NULL ) {
		 if( strlen($enclosure) < 1 ) {
			trigger_error('enclosure must be a character', E_USER_WARNING);
			return false;
		}elseif( strlen($enclosure) > 1 ) {
			trigger_error('enclosure must be a single character', E_USER_NOTICE);
		}
	
		/* use first character from string */
		$enclosure = $enclosure[0];
	}
	
	$i = 0;
	$csvline = '';
	$escape_char = '\\';
	$field_cnt = count($fields);
	$enc_is_quote = in_array($enclosure, array('"',"'"));
	reset($fields);
	
	foreach( $fields AS $field ) {
	
		/* enclose a field that contains a delimiter, an enclosure character, or a newline */
		if( is_string($field) && ( 
			strpos($field, $delimiter)!==false ||
			strpos($field, $enclosure)!==false ||
			strpos($field, $escape_char)!==false ||
			strpos($field, "\n")!==false ||
			strpos($field, "\r")!==false ||
			strpos($field, "\t")!==false ||
			strpos($field, ' ')!==false ) ) {
	
			$field_len = strlen($field);
			$escaped = 0;
	
			$csvline .= $enclosure;
			for( $ch = 0; $ch < $field_len; $ch++ )    {
				if( $field[$ch] == $escape_char && $field[$ch+1] == $enclosure && $enc_is_quote ) {
					continue;
				}elseif( $field[$ch] == $escape_char ) {
					$escaped = 1;
				}elseif( !$escaped && $field[$ch] == $enclosure ) {
					$csvline .= $enclosure;
				}else{
					$escaped = 0;
				}
				$csvline .= $field[$ch];
			}
			$csvline .= $enclosure;
		} else {
			$csvline .= $field;
		}
	
		if( $i++ != $field_cnt ) {
			$csvline .= $delimiter;
		}
	}
	
	$csvline .= "\n";
	
	return fwrite($handle, $csvline);
}

function get_twitterfeed(&$profile) {

	global $twitter_cache;

	if ($profile['twitter_id'] == '') {
		return '';
	}
	
	$pid = $profile['id'];
	$username = $profile['twitter_id'];
	$num = 10;

	// make sure the twitter cache dir exists
	if (!is_dir("$twitter_cache/$pid")) {mkdir("$twitter_cache/$pid", 0755);}
 
	$feed = "http://search.twitter.com/search.json?q=from:" . $username . "&amp;rpp=" . $num;
	$newfile = "$twitter_cache/$pid/twitternew.json";
	$file = "$twitter_cache/$pid/twitter.json";
	 
	copy($feed, $newfile);
	 
	$oldcontent = @file_get_contents($file);
	$newcontent = @file_get_contents($newfile);
	 
	if($oldcontent != $newcontent) {
		copy($newfile, $file);
	}
	$tweets = @file_get_contents($file);
	$tweets = json_decode($tweets);
	 
	$s = '<div id="pro_tweets" class="pro_tweets">';
	$s .= "<p id=\"pro_tweets_title\"><a target=\"_blank\" href=\"{$profile['twitter_url']}\">@{$profile['twitter_id']}</a>'s latest tweets...</p>";
	for($x = 0; $x < $num; $x++) {
		$str = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]","<a target=\"_blank\" href=\"\\0\">\\0</a>", $tweets->results[$x]->text);
		$pattern = '/[#|@][^\s]*/';
		preg_match_all($pattern, $str, $matches);	
		 
		foreach($matches[0] as $keyword) {
			$keyword = str_replace(")","",$keyword);
			$link = str_replace("#","%23",$keyword);
			$link = str_replace("@","",$keyword);
			if(strstr($keyword,"@")) {
				$search = "<a target=\"_blank\" href=\"http://twitter.com/$link\">$keyword</a>";
			}
			else {
				$link = urlencode($link);
				$search = "<a target=\"_blank\" href=\"http://twitter.com/#search?q=$link\" class=\"grey\">$keyword</a>";
			}
			$str = str_replace($keyword, $search, $str);
		}
		$s .= "<p>".$str."</p>\n";
	}
	$s .= "<p><a target=\"_blank\" href=\"{$profile['twitter_url']}\"><i>more...</i></a></p>";
	$s .= "</div>";
	return $s;	
}

?>