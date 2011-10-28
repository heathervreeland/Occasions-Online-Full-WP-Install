<?php

/* Template Name: Vendor Profile */

include_once($img_base_path . '/media/js/class.upload.php');

// let all of WP know that we are in the guide area
ao_set_in_guide(true);

// this is the profile ID
$pid = pods_url_variable(2);

// this is the "effective" user id, which is not neccessarily the user that is logged in, in the
//		case of admins, wo are able to mascarade as other users.
$active_user_id = get_active_user_id();

// array to hold our profile fields
$a = array();



// *******************************************************************
// TEMPORARY CRAP
// *******************************************************************
if (1 == 2) {
	if (pods_url_variable(3) == 'list') {
	
		get_header();
		$profile = new Pod('vendor_profiles');
		$profile->findRecords( 'name', -1, "t.profile_type = 'Platinum'");
		$total = $profile->getTotalRows();
		echo "<p>";
		$i = 0;
		while ($profile->fetchRecord()) {
			$a = get_vendorfields($profile);
			load_vendorimages($profile, $a);
			if ($a['image_count'] > 8) {
				$i++;
				$url = "https://www.atlantaoccasions.com/vendors/profile/{$a['id']}/pa";
				echo '<input name="accepts_paypal" type="checkbox" value="1" /> ', "$i: <a href='$url'>{$a['name']}</a> ({$a['image_count']})<br />\n";
			}
		}
		echo "</p>";
		get_footer();
		exit;
	}
	
	if (pods_url_variable(3) == 'pa') {
	
		get_header();
	
		$profile = new Pod('vendor_profiles');
		$active_user_id = get_active_user_id();
		$profile->findRecords( 'id', -1, "t.id = '$pid' AND t.vendor = $active_user_id");
		//$profile->findRecords( 'id', -1, "t.id = '$pid'");
		$total = $profile->getTotalRows();
		if( $total > 0 ) {
			$profile->fetchRecord();
			$a = get_vendorfields($profile);
			load_vendorimages($profile, $a);
		}
		else {
			exit;
		}
		
		// first make our directories
		if (!is_dir("$img_dst_source/{$a['id']}")) {mkdir("$img_dst_source/{$a['id']}", 0755);}
		if (!is_dir("$img_dst_large/{$a['id']}")) {mkdir("$img_dst_large/{$a['id']}", 0755);}
		if (!is_dir("$img_dst_thumb/{$a['id']}")) {mkdir("$img_dst_thumb/{$a['id']}", 0755);}
		if (!is_dir("$img_dst_logo/{$a['id']}")) {mkdir("$img_dst_logo/{$a['id']}", 0755);}
		
		if ($a['image_count'] > 0) {
			foreach ($a['imagelist_large'] as $img) {
				$path_parts = pathinfo($img_base_path . $img);
				$file_name = $path_parts['basename'];
				
				copy($img_base_path . $img, "$img_dst_source/{$a['id']}/$file_name");
	
				$foo = new upload($img_base_path . $img);
				//$foo->file_overwrite		= true;
				//$foo->image_convert 		= 'jpg';
				//$foo->image_resize			= false;
				//$foo->Process("$img_dst_source/{$a['id']}");
				//if (!$foo->processed) {
				//	echo 'error : ' . $foo->error;
				//}
				////chmod("$img_dst_source/{$a['id']}/{$foo->file_dst_name}", 0755);
				
				$foo->file_overwrite		= true;
				$foo->image_convert 		= 'jpg';
				$foo->image_resize			= true;
				$foo->image_ratio_crop		= false;
				$foo->image_ratio			= true;
				$foo->image_x				= 600;  
				$foo->image_y				= 400;
				$foo->Process("$img_dst_large/{$a['id']}");
				if (!$foo->processed) {
					echo 'error : ' . $foo->error;
				}
				//chmod("$img_web_large/{$a['id']}/{$foo->file_dst_name}", 0755);
				echo "<p><img src='$img_web_large/{$a['id']}/{$foo->file_dst_name}' /></p>";
				
				$foo->file_overwrite		= true;
				$foo->image_convert 		= 'jpg';
				$foo->image_resize			= true;
				$foo->image_ratio			= false;
				$foo->image_ratio_crop		= true;
				$foo->image_y				= 125;
				$foo->image_x				= 125;
				$foo->Process("$img_dst_thumb/{$a['id']}");
				if (!$foo->processed) {
					echo 'error : ' . $foo->error;
				}
				//chmod("$img_web_thumb/{$a['id']}/{$foo->file_dst_name}", 0755);
				echo "<p><img src='$img_web_thumb/{$a['id']}/{$foo->file_dst_name}' /></p>";
				
			}
		}
		
		get_footer();
		exit;
	}
}

// *******************************************************************
// INITIALIZATION, so to speak
// *******************************************************************

// Basically, if we have a $pid, go ahead an get the data from the database, We might end up
//		replacing that data with data the user is trying to save, but this will make sure we have
//		all the images and related data.... a good baseline, so to speak
if ($pid != '' && $pid != 'new') {
	$profile = new Pod('vendor_profiles');
	$profile->findRecords( 'id', -1, "t.id = '$pid' and t.vendor = $active_user_id");
	$total = $profile->getTotalRows();
	if( $total > 0 ) {
		$profile->fetchRecord();
		$a = get_vendorfields($profile);
		load_vendorimages($profile, $a);
	}
	$title = 'Edit Profile';
}
else {
	$pid = 'new';
	$title = 'New Profile';
}


// *******************************************************************
// IS THE USER TRYING TO SAVE THE PROFILE???
// *******************************************************************
if ($_POST['submitted'] == "1") {

	$success = false;
	
	// pull everything off of $_POST into our $a array.
	// we're specifically NOT using $_REQUEST here for a tiny bit of security.
	foreach($_POST as $key => $value) {
		$a[$key] = htmlspecialchars(stripslashes($value));
	}

	/// special case: we want the memo field data to have the quotes intact
	$a['description'] = htmlspecialchars(stripslashes($_POST['description']), ENT_NOQUOTES);
	$a['offer'] = htmlspecialchars(stripslashes($_POST['offer']), ENT_NOQUOTES);
	
	// since the unchecked input checkboxes don't submit ANY $_POST data when unchecked,
	//		we have to explicitly set those values from $_POST since the "stale" data from the
	//		database may contain "checked" values that the user intends to be "un-checked".
	$a['accepts_visa'] = iif($_POST['accepts_visa'] == '1', '1', '0');
	$a['accepts_mc'] = iif($_POST['accepts_mc'] == '1', '1', '0');
	$a['accepts_amex'] = iif($_POST['accepts_amex'] == '1', '1', '0');
	$a['accepts_discover'] = iif($_POST['accepts_discover'] == '1', '1', '0');
	$a['accepts_checks'] = iif($_POST['accepts_checks'] == '1', '1', '0');
	$a['accepts_bank'] = iif($_POST['accepts_bank'] == '1', '1', '0');
	$a['accepts_paypal'] = iif($_POST['accepts_paypal'] == '1', '1', '0');
	$a['show_address'] = iif($_POST['show_address'] == '1', '1', '0');
	$a['alcohol_permitted'] = iif($_POST['alcohol_permitted'] == '1', '1', '0');
	$a['onsite_accomodations'] = iif($_POST['onsite_accomodations'] == '1', '1', '0');
	$a['accessible'] = iif($_POST['accessible'] == '1', '1', '0');
	$a['video_first'] = iif($_POST['video_first'] == '1', '1', '0');


	// now we validate the data the user entered... super easy
	if ($a['name'] == '') {
		$err[] = 'You must enter a valid profile name.';
	}

	if ($a['category1'] == '' || $a['category1'] == '0') {
		$err[] = 'You must select at least one category.';
	}

	if (empty($err)) {

		/* these fields are not used or not updated by the user
		ao2_guid
		v2_profile_image_sm
		v2_profile_image_lg
		profile_image
		slideshow_images
		categories
		rating
		profile_views
		clicks_web
		clicks_blog
		clicks_facebook
		clicks_linkedin
		clicks_twitter
		*/
		
		// these are all the fields we are going to save
		$a_fields = array(
			"name",
			"tagline",
			"description",
			"offer",
			"contact_name",
			"contact_title",
			"contact_email",
			"web_url",
			"blog_url",
			"twitter_url",
			"facebook_url",
			"linkedin_url",
			"accepts_visa",
			"accepts_mc",
			"accepts_amex",
			"accepts_discover",
			"accepts_checks",
			"accepts_bank",
			"accepts_paypal",
			"travel_policy",
			"show_address",
			"address",
			"suite",
			"city",
			"state",
			"zipcode",
			"county",
			"contact_phone1",
			"contact_phone1_type",
			"contact_phone2",
			"contact_phone2_type",
			"contact_phone3",
			"contact_phone3_type",
			"spaces_available",
			"capacity",
			"square_footage",
			"catering",
			"alcohol_permitted",
			"onsite_accomodations",
			"accessible",
			"category1",
			"category2",
			"category3",
			"category4",
			"category5",
			"video_url",
			"video_first"
		);
		
		$profile_data = array();
		
		// now move all the field data into our new array
		foreach ($a_fields as $field) {
			$profile_data[$field] = addslashes($a[$field]);
		}
		
		// always generate the new slug in case they changed their name
		$profile_data['slug'] = strtolower(sanitize_file_name($a['name']));
		
		// if we don't have them already, get the geo lat/log pair from google
		if (($profile_data['latitude'] == '') || ($profile_data['longitude'] == '')) {
		
			$adresse = $profile_data['address']; // grab the content of the "adresse" field in your pod
			$ville = $profile_data['city']; // grab the content of the "ville" field in your pod, ville is french for town
			$state = $profile_data['state']; // grab the content of the "ville" field in your pod, ville is french for town
			$zip = $profile_data['zipcode']; // grab the content of the "ville" field in your pod, ville is french for town
			
			$badstrings = array (" ","'","-","<br>"); // strings we shouldn't pass in the url
			$goodstrings = array ("+","",",",""); // strings we are going to replace them with
				
			$niceadresse = str_replace($badstrings,$goodstrings,$adresse); // convert address to url compatible address
			$niceville =  str_replace($badstrings,$goodstrings,$ville); // convert town to a url compatible town
			$nicestate =  str_replace($badstrings,$goodstrings,$state); // convert town to a url compatible town
			$nicezip =  str_replace($badstrings,$goodstrings,$zip); // convert town to a url compatible town
			$rawurl = $niceadresse.",".$niceville.",".$nicestate.",".$nicezip; // add them all together
			$niceurl = strtolower($rawurl); // make sure it is all lowercase, not sure why i did this :)
			
			$geourl = "http://maps.google.com/maps/api/geocode/json?address=".$niceurl."&sensor=false";
			$geoinfo = file_get_contents($geourl); // get the geocoded info back in json format into the variable you could use curl for better performance but this is more compatible
			$decoded = json_decode($geoinfo); // decode json geoinfo into an object
			
			// make sure value is returned and allow manual change
			if ($decoded->status == "OK") {
				$profile_data['latitude'] = $decoded->results[0]->geometry->location->lat; // copy lat into the field called lat in your pod
				$profile_data['longitude'] = $decoded->results[0]->geometry->location->lng; // copy long into the field called long in your pod
			}
		}
	
		// all clear to save the data to the database
		$api = new PodAPI();
		
		// safety cleansing
		pods_sanitize($profile_data);
	
		if ($pid == 'new') {
		
			// since we are saving a new profile, these fields need initializing this one time only
			$profile_data['vendor'] = get_active_user_id();
			$profile_data['active'] = '0';
			$profile_data['profile_type'] = 'Free';
			$profile_data['expiration_date'] = '0000-00-00 00:00:00';
			$profile_data['payment_plan'] = 'NA';
			$profile_data['payment_amount'] = '0';
	
			$params = array(
				'datatype' => 'vendor_profiles', 
				'columns' => $profile_data
			);
			// create the item
			$api->save_pod_item($params);
			$success = true;
		}
		else {
		
			// SCREW PODSCMS... just do a plain ole SQL update
			$sql = "UPDATE wp_pod_tbl_vendor_profiles SET ";
			$sql_fields = array();
			foreach ($profile_data as $key => $val) {
				$sql_fields[] .= "$key='$val'";
			}
			$sql .= implode(', ', $sql_fields);
			$sql .= " WHERE id=$pid" ;
			
			pod_query($sql);
	
			//// saving an existing profile
			//$params = array(
			//	'datatype' => 'vendor_profiles', 
			//	'pod_id' => $pid,
			//	'columns' => $profile_data
			//);
			// create the item
			//$api->save_pod_item($params);
			$success = true;
		}
		
		if ($success) {
			// redirect to the profiles page
			header("Location: ". PAGE_PROFILES);
			exit;
		}
		else {
			// let execution continue so that the errors can be displayed
			
		}

	}
}


// *******************************************************************
// START PREP TO DISLAY THE PAGE
// *******************************************************************
$cats = new Pod('categories');
$cats->findRecords( '', 0, '', 'SELECT id, slug, name FROM wp_pod_tbl_categories ORDER BY name');
$s1 = '<select name="category1" id="category1" class="vendor_select">';
$s2 = '<select name="category2" id="category2" class="vendor_select">';
$s3 = '<select name="category3" id="category3" class="vendor_select">';
$s4 = '<select name="category4" id="category4" class="vendor_select">';
$s5 = '<select name="category5" id="category5" class="vendor_select">';

$s1 .= '<option value="0"';
$s2 .= '<option value="0"';
$s3 .= '<option value="0"';
$s4 .= '<option value="0"';
$s5 .= '<option value="0"';

if( $a['category1'] == "0" ) {
    $s1 .= "selected ";
}
if( $a['category2'] == "0" ) {
    $s2 .= "selected ";
}
if( $a['category3'] == "0" ) {
    $s3 .= "selected ";
}
if( $a['category4'] == "0" ) {
    $s4 .= "selected ";
}
if( $a['category5'] == "0" ) {
    $s5 .= "selected ";
}

$s1 .= '>&lt;none&gt;</option>';
$s2 .= '>&lt;none&gt;</option>';
$s3 .= '>&lt;none&gt;</option>';
$s4 .= '>&lt;none&gt;</option>';
$s5 .= '>&lt;none&gt;</option>';

while ( $cats->fetchRecord() ) {
	$s1 .= '<option value="' . $cats->get_field('slug') . '" ';
	$s2 .= '<option value="' . $cats->get_field('slug') . '" ';
	$s3 .= '<option value="' . $cats->get_field('slug') . '" ';
	$s4 .= '<option value="' . $cats->get_field('slug') . '" ';
	$s5 .= '<option value="' . $cats->get_field('slug') . '" ';
	
	if( $a['category1'] == $cats->get_field('slug') ) { $s1 .= "selected "; }
	if( $a['category2'] == $cats->get_field('slug') ) { $s2 .= "selected "; }
	if( $a['category3'] == $cats->get_field('slug') ) { $s3 .= "selected "; }
	if( $a['category4'] == $cats->get_field('slug') ) { $s4 .= "selected "; }
	if( $a['category5'] == $cats->get_field('slug') ) { $s5 .= "selected "; }

	$s1 .= '>' . $cats->get_field('name') . '</option>';
	$s2 .= '>' . $cats->get_field('name') . '</option>';
	$s3 .= '>' . $cats->get_field('name') . '</option>';
	$s4 .= '>' . $cats->get_field('name') . '</option>';
	$s5 .= '>' . $cats->get_field('name') . '</option>';
}
$s1 .= '</select>';
$s2 .= '</select>';
$s3 .= '</select>';
$s4 .= '</select>';
$s5 .= '</select>';

// *******************************************************************
// START SPITTIN' OUT THE PAGE
// *******************************************************************
get_header();
?>

	<h2><?php echo $title; ?></h2>

	<form action="<?php echo PAGE_PROFILE, "/$pid"; ?>" method="post" name="profileForm" id="profileForm" >

		<p class="vendor_txt_r"><input name="doSave" type="submit" id="vendor_submit" value="Save Profile Changes" /></p>
<?php
if(!empty($err))  {
	echo '<p class="error_msg">';
	foreach ($err as $e) {
		echo "$e<br />";
	}
	echo "</p>";
}
?>
		<div id="TabbedPanels3" class="TabbedPanels"> 
			<div class="TabbedPanelsTabGroup"> 
				<p class="TabbedPanelsTab"> Profile </p>
				<p class="TabbedPanelsTab"> Descriptions </p>
				<p class="TabbedPanelsTab"> Contact </p>
				<p class="TabbedPanelsTab"> Venues </p>
				<p class="TabbedPanelsTab"> Images </p>
				<p class="TabbedPanelsTab"> Video </p>
				<p class="TabbedPanelsTab"> Account </p>
<?php
if (checkAdmin()) {
?>
				<p class="TabbedPanelsTab"> Admin </p>
<?php
}
?>
			</div> 
			<div class="TabbedPanelsContentGroup"> 
				<div class="TabbedPanelsContent">

					<p class="vendor_label"><label for="v_name">Profile Name</label> <span class="required"><font color="#CC0000">*</font></span></p>
					<p class="vendor_txt"><input name="name" type="text" id="v_name" size="40" class="required" value="<?php echo $a['name']; ?>" /></p>
			
					<p class="vendor_label"><label for="v_tagline">Tag line</label></p>
					<p class="vendor_desc">This optional text will appear directly below your profile name.</p>
					<p class="vendor_txt"><input name="tagline" type="text" id="v_tagline" size="40" class="required" value="<?php echo $a['tagline']; ?>" /></p>
					
					<p class="vendor_label"><label for="category1">Categories</label></p>
					<p class="vendor_desc">You can select up to two categories for your profile. These categories must reflect the nature of your business. The second category is optional.</p>
					<p class="vendor_txt"><?php echo $s1; ?> <span class="required"><font color="#CC0000">*</font></span></p>
					<p class="vendor_txt"><?php echo $s2; ?></p>
<?php
if (checkAdmin()) {
?>
					<p class="vendor_desc">Additional categories (viewable by administrators only).</p>
					<p class="vendor_txt"><?php echo $s3; ?></p>
					<p class="vendor_txt"><?php echo $s4; ?></p>
					<p class="vendor_txt"><?php echo $s5; ?></p>
<?php
}
?>
					<p class="vendor_label"><label for="accepts_visa">Payment Acceptance</label></p>
					<p class="vendor_desc">Check all forms of payment you accept for service.</p>
					<p class="vendor_txt">
						<input class="vendor_checkbox" name="accepts_visa" type="checkbox" id="accepts_visa" value="1" <?php echo iif($a['accepts_visa'] == '1', 'checked', ''); ?> /> <label for="accepts_visa">Visa</label><br />
						<input class="vendor_checkbox" name="accepts_mc" type="checkbox" id="accepts_mc" value="1" <?php echo iif($a['accepts_mc'] == '1', 'checked', ''); ?> /> <label for="accepts_mc">Mastercard</label><br />
						<input class="vendor_checkbox" name="accepts_amex" type="checkbox" id="accepts_amex" value="1" <?php echo iif($a['accepts_amex'] == '1', 'checked', ''); ?> /> <label for="accepts_amex">American Express</label><br />
						<input class="vendor_checkbox" name="accepts_discover" type="checkbox" id="accepts_discover" value="1" <?php echo iif($a['accepts_discover'] == '1', 'checked', ''); ?> /> <label for="accepts_discover">Discover</label><br />
						<input class="vendor_checkbox" name="accepts_checks" type="checkbox" id="accepts_checks" value="1" <?php echo iif($a['accepts_checks'] == '1', 'checked', ''); ?> /> <label for="accepts_checks">Checks</label><br />
						<input class="vendor_checkbox" name="accepts_bank" type="checkbox" id="accepts_bank" value="1" <?php echo iif($a['accepts_bank'] == '1', 'checked', ''); ?> /> <label for="accepts_bank">Bank Drafts</label><br />
						<input class="vendor_checkbox" name="accepts_paypal" type="checkbox" id="accepts_paypal" value="1" <?php echo iif($a['accepts_paypal'] == '1', 'checked', ''); ?> /> <label for="accepts_paypal">Paypal</label>
					</p>

					<p>&nbsp;</p>

				</div> 
				<div class="TabbedPanelsContent">

					<p class="vendor_label"><label for="description">Profile Description</label></p>
					<p class="vendor_desc">Use this area to describe your services or business.</p>
					<p class="vendor_txt"><textarea name="description"><?php echo $a['description']; ?></textarea></p>
					
					<p class="vendor_label"><label for="offer"><br />Promotional Offer</label></p>
					<p class="vendor_desc">Enter an optional promotional offer or incentive you are currently offering. It is helpful for determining the effectiveness of your profile if you ask the client to mention Occasions Magazine when inquiring about your offer.</p>
					<p class="vendor_txt"><textarea name="offer"><?php echo $a['offer']; ?></textarea></p>

					<p>&nbsp;</p>

				</div> 
				<div class="TabbedPanelsContent">
					<p class="vendor_label"><label for="contact_name">Contact Name</label></p>
					<p class="vendor_txt"><input name="contact_name" type="text" id="contact_name" size="40" value="<?php echo $a['contact_name']; ?>" /></p>
					
					<p class="vendor_label"><label for="contact_title">Contact Title</label></p>
					<p class="vendor_txt"><input name="contact_title" type="text" id="contact_title" size="40" value="<?php echo $a['contact_title']; ?>" /></p>
					
					<p class="vendor_label"><label for="contact_email">Contact Email</label></p>
					<p class="vendor_txt"><input name="contact_email" type="text" id="contact_email" size="40" value="<?php echo $a['contact_email']; ?>" /> ex. "name@domain.com"</p>
					
					<p>&nbsp;</p>

					<p class="vendor_label"><label for="show_address">Show Address in Profile</label></p>
					<p class="vendor_desc">If checked, the address you specify below will appear in your public profile.</p>
					<p class="vendor_txt"><?php // NOTE the reverse logic below for checking the box. This way, the default for the checkbox is checked. ?>
						<input class="vendor_checkbox" name="show_address" type="checkbox" id="show_address" value="1" <?php echo iif($a['show_address'] != '1', '', 'checked'); ?> /> <label for="show_address">Show address</label><br />
					</p>

					<p class="vendor_label"><label for="address">Address</label></p>
					<p class="vendor_txt"><input name="address" type="text" id="address" size="40" value="<?php echo $a['address']; ?>" /></p>
					
					<p class="vendor_label"><label for="suite">Suite</label></p>
					<p class="vendor_txt"><input name="suite" type="text" id="suite" size="40" value="<?php echo $a['suite']; ?>" /></p>
					
					<p class="vendor_label"><label for="city">City / State / Zipcode</label></p>
					<p class="vendor_txt"><input name="city" type="text" id="city" size="40" value="<?php echo $a['city']; ?>" /> <input name="state" type="text" id="state" size="40" value="<?php echo $a['state']; ?>" /> <input name="zipcode" type="text" id="zipcode" size="40" value="<?php echo $a['zipcode']; ?>" /></p>
					
					<p class="vendor_label"><label for="county">County</label></p>
					<p class="vendor_txt"><input name="county" type="text" id="county" size="40" value="<?php echo $a['county']; ?>" /></p>
					
					<p class="vendor_label"><label for="travel_policy">Travel Policy</label></p>
					<p class="vendor_desc">Indicate your travel policy.</p>
					<p class="vendor_txt">
						<select class="vendor_select" name="travel_policy" id="travel_policy">
						<?php
						$a_travel = array(
							'NA'=>'&lt;none&gt;',
							'Locally'=>'Locally',
							'State Wide'=>'State Wide',
							'Regionally'=>'Regionally',
							'Nationally'=>'Nationally',
							'Internationally'=>'Internationally'
						);
						foreach ($a_travel as $key => $val) {
							if ($a['travel_policy'] == $key) {
								echo '<option selected="true" value="', $key, '">', $val, '</option>';
							} else {
								echo '<option value="', $key, '">', $val, '</option>';
							}
						}
						?>
						</select>
					</p>

					<p>&nbsp;</p>

					<p class="vendor_label"><label for="contact_phone1">Phone Numbers</label></p>
					<p class="vendor_desc">Enter the number and select the type of number for each. These will appear in your profile in the order they are listed here.</p>
					<p class="vendor_txt">
						<input name="contact_phone1" type="text" id="contact_phone1" size="40" value="<?php echo $a['contact_phone1']; ?>" />
						<select class="vendor_select" name="contact_phone1_type" id="contact_phone1_type">
						<?php
						$a_phonetypes = array(
							''=>'',
							'Mobile'=>'Mobile',
							'Toll-Free'=>'Toll-Free',
							'Work'=>'Work',
							'Work FAX'=>'Work FAX',
							'Home'=>'Home',
							'Home FAX'=>'Home FAX',
							'Sales'=>'Sales',
							'Mgmt'=>'Management'
						);
						foreach ($a_phonetypes as $key => $val) {
							if ($a['contact_phone1_type'] == $key) {
								echo '<option selected="true" value="', $key, '">', $val, '</option>';
							} else {
								echo '<option value="', $key, '">', $val, '</option>';
							}
						}
						?>
						</select><br />
						<input name="contact_phone2" type="text" id="contact_phone2" size="40" value="<?php echo $a['contact_phone2']; ?>" />
						<select class="vendor_select" name="contact_phone2_type" id="contact_phone2_type">
						<?php
						foreach ($a_phonetypes as $key => $val) {
							if ($a['contact_phone2_type'] == $key) {
								echo '<option selected="true" value="', $key, '">', $val, '</option>';
							} else {
								echo '<option value="', $key, '">', $val, '</option>';
							}
						}
						?>
						</select><br />
						<input name="contact_phone3" type="text" id="contact_phone3" size="40" value="<?php echo $a['contact_phone3']; ?>" />
						<select class="vendor_select" name="contact_phone3_type" id="contact_phone3_type">
						<?php
						foreach ($a_phonetypes as $key => $val) {
							if ($a['contact_phone3_type'] == $key) {
								echo '<option selected="true" value="', $key, '">', $val, '</option>';
							} else {
								echo '<option value="', $key, '">', $val, '</option>';
							}
						}
						?>
						</select>
					</p>
					
					<p>&nbsp;</p>

					<p class="vendor_label"><label for="web_url">Website Address</label></p>
					<p class="vendor_desc">Enter your complete website address, beginning with <i>"http://"</i>...</p>
					<p class="vendor_txt"><input name="web_url" type="text" id="web_url" size="40" value="<?php echo $a['web_url']; ?>" /></p>
					
					<p class="vendor_label"><label for="blog_url">Blog Address</label></p>
					<p class="vendor_desc">Enter your complete blog address, beginning with <i>"http://"</i>...</p>
					<p class="vendor_txt"><input name="blog_url" type="text" id="blog_url" size="40" value="<?php echo $a['blog_url']; ?>" /></p>
					
					<p class="vendor_label"><label for="facebook_url">Facebook Page</label></p>
					<p class="vendor_desc">Enter address to your Facebook Page, beginning with <i>"http://"</i>...</p>
					<p class="vendor_txt"><input name="facebook_url" type="text" id="facebook_url" size="40" value="<?php echo $a['facebook_url']; ?>" /></p>
					
					<p class="vendor_label"><label for="linkedin_url">LinkedIn Profile</label></p>
					<p class="vendor_desc">Enter address to your LinkedIn Profile, beginning with <i>"http://"</i>...</p>
					<p class="vendor_txt"><input name="linkedin_url" type="text" id="linkedin_url" size="40" value="<?php echo $a['linkedin_url']; ?>" /></p>
					
					<p class="vendor_label"><label for="twitter_url">Twitter ID</label></p>
					<p class="vendor_desc">Enter your twitter ID, not including the "@" sign.</p>
					<p class="vendor_txt"><input name="twitter_url" type="text" id="twitter_url" size="40" value="<?php echo $a['twitter_url']; ?>" /></p>
					
					<p>&nbsp;</p>

				</div> 
				<div class="TabbedPanelsContent">

					<p class="vendor_label"><label for="spaces_available">Number of Spaces Available</label></p>
					<p class="vendor_desc">Enter the number of spaces your venue has available.</p>
					<p class="vendor_txt"><input name="spaces_available" type="text" id="spaces_available" size="40" value="<?php echo (int) $a['spaces_available']; ?>" /></p>
					
					<p class="vendor_label"><label for="capacity">Capacity</label></p>
					<p class="vendor_desc">Enter the service capacity for your venue(s).</p>
					<p class="vendor_txt"><input name="capacity" type="text" id="capacity" size="40" value="<?php echo $a['capacity']; ?>" /></p>
					
					<p class="vendor_label"><label for="square_footage">Square Footage</label></p>
					<p class="vendor_desc">Enter the square footage of your venue(s).</p>
					<p class="vendor_txt"><input name="square_footage" type="text" id="square_footage" size="40" value="<?php echo $a['square_footage']; ?>" /></p>
					
					<p class="vendor_label"><label for="catering">Catering Policy</label></p>
					<p class="vendor_desc">Enter your venue(s) catering policy.</p>
					<p class="vendor_txt"><input name="catering" type="text" id="catering" size="40" value="<?php echo $a['catering']; ?>" /></p>
					
					<p class="vendor_label"><label for="alcohol_permitted">Alcohol Policy</label></p>
					<p class="vendor_txt"><input class="vendor_checkbox" name="alcohol_permitted" type="checkbox" id="alcohol_permitted" value="1" <?php echo iif($a['alcohol_permitted'] == '1', 'checked', ''); ?> /> <label for="alcohol_permitted">Yes, outside alcohol vendors are permitted</label><br />
					</p>
					
					<p class="vendor_label"><label for="onsite_accomodations">Onsite Accomodations</label></p>
					<p class="vendor_txt"><input class="vendor_checkbox" name="onsite_accomodations" type="checkbox" id="onsite_accomodations" value="1" <?php echo iif($a['onsite_accomodations'] == '1', 'checked', ''); ?> /> <label for="onsite_accomodations">Yes, onsite accomodations are available at this venue</label><br />
					</p>
					
					<p class="vendor_label"><label for="accessible">Handicap Accessible</label></p>
					<p class="vendor_txt"><input class="vendor_checkbox" name="accessible" type="checkbox" id="accessible" value="1" <?php echo iif($a['accessible'] == '1', 'checked', ''); ?> /> <label for="accessible">Yes, this venue is handicap accessible</label><br />
					</p>
					
					<p>&nbsp;</p>

				</div> 
				<div class="TabbedPanelsContent">
					<p class="vendor_label">Profile Images</p>
<?php 
	if ($pid == 'new') {
?>
					<p class="vendor_desc">Your profile must be saved before uploading images.
<?php 
	}
	else {
?>
					<p class="vendor_desc">Your images must meet the following specifications:
					<p class="vendor_desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; <b>JPG/JPEG, GIF, PNG or BMP</b> format<br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; <b>No larger than 4000 pixels</b> on the longest side<br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; <b>Less than 8MB</b> in file size<br />&nbsp;
					</p>
					<p class="vendor_desc">NOTE: Images added/deleted here will appear in your profile <b>IMMEDIATELY</b> after upload.<br />&nbsp;</p>
					<p class="vendor_uploadflash"><span id="spanButtonPlaceholder"></span></p>
					<div id="divFileProgressContainer"></div>
					<script type="text/javascript">
						var swfu;
						window.onload = function () {
							swfu = new SWFUpload({
								// Backend Settings
								upload_url: '<?php echo PAGE_DOIMAGE, '/', $a['id']; ?>',
								post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
				
								// File Upload Settings
								file_size_limit : "8 MB",	// 2MB
								file_types : "*.jpg;*.jpeg;*.gif;*.png",
								file_types_description : "Readable Images Formats",
								file_upload_limit : "0",
				
								// Event Handler Settings - these functions as defined in swfhandlers.js
								//  The handlers are not part of SWFUpload but are part of my website and control how
								//  my website reacts to the SWFUpload events.
								file_queue_error_handler : fileQueueError,
								file_dialog_complete_handler : fileDialogComplete,
								upload_progress_handler : uploadProgress,
								upload_error_handler : uploadError,
								upload_success_handler : uploadSuccess,
								upload_complete_handler : uploadComplete,
				
								// Button Settings
								button_placeholder_id : "spanButtonPlaceholder",
								button_width: 120,
								button_height: 18,
								button_text : '<span class="swf_button">UPLOAD IMAGES</span>',
								button_text_style : '.swf_button { font-family: Verdana; color: #666666; font-weight: bold; font-size: 10pt; }',
								button_text_top_padding: 0,
								button_text_left_padding: 8,
								button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
								button_cursor: SWFUpload.CURSOR.HAND,
								
								// Flash Settings
								flash_url : "/media/flash/swfupload.swf",
				
								custom_settings : {
									upload_target : "divFileProgressContainer"
								},
								
								// Debug Settings
								debug: false
							});
						};
					</script>

					<div id="thumbnail_block">
<?php
get_thumbnail_block($a);
?>
					</div>
<?php 
	}
?>
				</div> 
				<div class="TabbedPanelsContent">

					<p class="vendor_label"><label for="video_url">Video URL</label></p>
					<p class="vendor_desc">Enter the website address for your video, for example:
					<p class="vendor_desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; YouTube: <b>http://www.youtube.com/watch?v=FRscebgg5C0</b><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Vimeo: <b>http://vimeo.com/7809605</b><br />&nbsp;
					</p>
					<p class="vendor_txt"><input name="video_url" type="text" id="video_url" size="40" value="<?php echo $a['video_url']; ?>" /></p>
					
					<p class="vendor_label"><label for="video_first">Video Location</label></p>
					<p class="vendor_desc">If checked, your video will display above the slideshow.</p>
					<p class="vendor_txt"><input class="vendor_checkbox" name="video_first" type="checkbox" id="video_first" value="1" <?php echo iif($a['video_first'] == '1', 'checked', ''); ?> /> <label for="video_first">Show my video above the slideshow</label><br />
					</p>

				</div> 
				<div class="TabbedPanelsContent">
				</div> 
<?php
if (checkAdmin()) {
?>
				<div class="TabbedPanelsContent">
					<p class="vendor_label"><label for="spaces_available">Statistics</label></p>
					<p class="vendor_desc">Profile views</p>
					<p class="vendor_txt"><input name="profile_views" type="text" id="profile_views" size="5" value="<?php echo (int) $a['profile_views']; disabled ?>" /></p>
					<p class="vendor_desc">Website clicks</p>
					<p class="vendor_txt"><input name="clicks_web" type="text" id="clicks_web" size="5" value="<?php echo (int) $a['clicks_web']; disabled ?>" /></p>
					<p class="vendor_desc">Blog clicks</p>
					<p class="vendor_txt"><input name="clicks_blog" type="text" id="clicks_blog" size="5" value="<?php echo (int) $a['clicks_blog']; disabled ?>" /></p>
					<p class="vendor_desc">Facebook Page clicks</p>
					<p class="vendor_txt"><input name="clicks_facebook" type="text" id="clicks_facebook" size="5" value="<?php echo (int) $a['clicks_facebook']; disabled ?>" /></p>
					<p class="vendor_desc">LinkedIn Profile clicks</p>
					<p class="vendor_txt"><input name="clicks_linkedin" type="text" id="clicks_linkedin" size="5" value="<?php echo (int) $a['clicks_linkedin']; disabled ?>" /></p>
					<p class="vendor_desc">Twitter Feed clicks</p>
					<p class="vendor_txt"><input name="clicks_twitter" type="text" id="clicks_twitter" size="5" value="<?php echo (int) $a['clicks_twitter']; disabled ?>" /></p>
				</div> 
<?php
}
?>
 			</div> 
		</div> 

		
		<input type="hidden" name="pid" value="<?php echo $pid; ?>" />
		<input type="hidden" name="submitted" value="1" />
		<p class="vendor_txt_r">&nbsp;<br /><input name="doSave" type="submit" id="vendor_submit" value="Save Profile Changes" /></p>

	</form>
	<p>&nbsp;</p>

<?php
// *******************************************************************
// IT'S JAVASCRIPT FROM HERE ON DOWN
// *******************************************************************
?>

<script language="JavaScript" type="text/javascript"> 
	var TabbedPanels3 = new Spry.Widget.TabbedPanels("TabbedPanels3");
	CKEDITOR.replace( 'description' );
	CKEDITOR.replace( 'offer' );
	
	function fileQueueError(file, errorCode, message) {
		try {
			var imageName = "error.gif";
			var errorName = "";
			if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
				errorName = "You have attempted to queue too many files.";
			}
	
			if (errorName !== "") {
				alert("Queue Error Specified: " + errorName);
				return;
			}
	
			switch (errorCode) {
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
				imageName = "zerobyte.gif";
				break;
			case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
				imageName = "toobig.gif";
				break;
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			default:
				alert("Queue Error: " + message);
				break;
			}
	
			//addImage("images/" + imageName);
	
		} catch (ex) {
			this.debug(ex);
		}
	
	}
	
	function fileDialogComplete(numFilesSelected, numFilesQueued) {
		try {
			if (numFilesQueued > 0) {
				this.startUpload();
			}
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadProgress(file, bytesLoaded) {
	
		try {
			var percent = Math.ceil((bytesLoaded / file.size) * 100);
	
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setProgress(percent);
			if (percent === 100) {
				progress.setStatus("Resizing image...");
				progress.toggleCancel(false, this);
			} else {
				progress.setStatus("Uploading...");
				progress.toggleCancel(true, this);
			}
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadSuccess(file, serverData) {
		try {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
	
			if (serverData.substring(0, 1) === "1") {
				//addImage("thumbnail.php?id=" + serverData.substring(7));
				progress.setStatus("Upload complete.");
				progress.toggleCancel(false);
			} else {
				//addImage("images/error.gif");
				progress.setStatus("Error.");
				progress.toggleCancel(false);
				alert("uS Error:: " + serverData.substring(0,100));
	
			}
	
	
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadComplete(file) {
		try {
			/*  I want the next upload to continue automatically so I'll call startUpload here */
			if (this.getStats().files_queued > 0) {
				this.startUpload();
			} else {
				var progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setComplete();
				progress.setStatus("");
				progress.toggleCancel(false);
				$(".progressName").html('Image upload finished.');
				$("#thumbnail_block").html('<p class="vendor_desc">Reloading thumbnails . . .</p>');
				$.get(
					"/vendors/doimage/<?php echo $pid; ?>",
					{cmd:"get_thumbnail_block"},
					function(data){
						$("#thumbnail_block").html(data);
					}
				);
			}
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadError(file, errorCode, message) {
		var imageName =  "error.gif";
		var progress;
		try {
			switch (errorCode) {
			case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
				try {
					progress = new FileProgress(file,  this.customSettings.upload_target);
					progress.setCancelled();
					progress.setStatus("Cancelled");
					progress.toggleCancel(false);
				}
				catch (ex1) {
					this.debug(ex1);
				}
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
				try {
					progress = new FileProgress(file,  this.customSettings.upload_target);
					progress.setCancelled();
					progress.setStatus("Stopped");
					progress.toggleCancel(true);
				}
				catch (ex2) {
					this.debug(ex2);
				}
			case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
				imageName = "uploadlimit.gif";
				break;
			default:
				alert("uE Error: " + message);
				break;
			}
	
			//addImage("images/" + imageName);
	
		} catch (ex3) {
			this.debug(ex3);
		}
	
	}
	
	
	function fadeIn(element, opacity) {
		var reduceOpacityBy = 5;
		var rate = 30;	// 15 fps
	
	
		if (opacity < 100) {
			opacity += reduceOpacityBy;
			if (opacity > 100) {
				opacity = 100;
			}
	
			if (element.filters) {
				try {
					element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
				} catch (e) {
					// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
					element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
				}
			} else {
				element.style.opacity = opacity / 100;
			}
		}
	
		if (opacity < 100) {
			setTimeout(function () {
				fadeIn(element, opacity);
			}, rate);
		}
	}
	
	
	
	/* ******************************************
	 *	FileProgress Object
	 *	Control object for displaying file info
	 * ****************************************** */
	
	function FileProgress(file, targetID) {
		this.fileProgressID = "divFileProgress";
	
		this.fileProgressWrapper = document.getElementById(this.fileProgressID);
		if (!this.fileProgressWrapper) {
			this.fileProgressWrapper = document.createElement("div");
			this.fileProgressWrapper.className = "progressWrapper";
			this.fileProgressWrapper.id = this.fileProgressID;
	
			this.fileProgressElement = document.createElement("div");
			this.fileProgressElement.className = "progressContainer";
	
			var progressCancel = document.createElement("a");
			progressCancel.className = "progressCancel";
			progressCancel.href = "#";
			progressCancel.style.visibility = "hidden";
			progressCancel.appendChild(document.createTextNode(" "));
	
			var progressText = document.createElement("div");
			progressText.className = "progressName";
			progressText.appendChild(document.createTextNode(file.name));
	
			var progressBar = document.createElement("div");
			progressBar.className = "progressBarInProgress";
	
			var progressStatus = document.createElement("div");
			progressStatus.className = "progressBarStatus";
			progressStatus.innerHTML = "&nbsp;";
	
			this.fileProgressElement.appendChild(progressCancel);
			this.fileProgressElement.appendChild(progressText);
			this.fileProgressElement.appendChild(progressStatus);
			this.fileProgressElement.appendChild(progressBar);
	
			this.fileProgressWrapper.appendChild(this.fileProgressElement);
	
			document.getElementById(targetID).appendChild(this.fileProgressWrapper);
			fadeIn(this.fileProgressWrapper, 0);
	
		} else {
			this.fileProgressElement = this.fileProgressWrapper.firstChild;
			this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
		}
	
		this.height = this.fileProgressWrapper.offsetHeight;
	
	}
	FileProgress.prototype.setProgress = function (percentage) {
		this.fileProgressElement.className = "progressContainer green";
		this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
		this.fileProgressElement.childNodes[3].style.width = percentage + "%";
	};
	FileProgress.prototype.setComplete = function () {
		this.fileProgressElement.className = "progressContainer blue";
		this.fileProgressElement.childNodes[3].className = "progressBarComplete";
		this.fileProgressElement.childNodes[3].style.width = "";
	
	};
	FileProgress.prototype.setError = function () {
		this.fileProgressElement.className = "progressContainer red";
		this.fileProgressElement.childNodes[3].className = "progressBarError";
		this.fileProgressElement.childNodes[3].style.width = "";
	
	};
	FileProgress.prototype.setCancelled = function () {
		this.fileProgressElement.className = "progressContainer";
		this.fileProgressElement.childNodes[3].className = "progressBarError";
		this.fileProgressElement.childNodes[3].style.width = "";
	
	};
	FileProgress.prototype.setStatus = function (status) {
		this.fileProgressElement.childNodes[2].innerHTML = status;
		this.fileProgressElement.childNodes[2].visibility =  status ? "visible" : "hidden";
	};
	
	FileProgress.prototype.toggleCancel = function (show, swfuploadInstance) {
		this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
		if (swfuploadInstance) {
			var fileID = this.fileProgressID;
			this.fileProgressElement.childNodes[0].onclick = function () {
				swfuploadInstance.cancelUpload(fileID);
				return false;
			};
		}
	};
</script> 

<?php
get_footer();
//echo phpinfo();
exit;
?>
