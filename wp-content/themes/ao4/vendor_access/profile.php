<?php

/* Template Name: Vendor Profile */

include_once($img_base_path . '/media/js/class.upload.php');

// let all of WP know that we are in the guide area
//ao_set_in_guide(true);

// this is the profile ID
//$pid = pods_url_variable(2);

// this is the "effective" user id, which is not neccessarily the user that is logged in, in the
//		case of admins, wo are able to mascarade as other users.
$active_user_id = get_active_user_id();

// array to hold our profile fields
$a = array();

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
	"video_first",
	"video_flv_last",
	"type1",
	"type2",
	"type3"
);

if (checkAdmin()) {
	// additional fields that admins are able to save
	$a_adminfields = array(
		"rating",
		"card_num",
		"card_exp_month",
		"card_exp_year",
		"subscriber_id",
		"active",
		"profile_type",
		"payment_amount",
		"payment_plan",
		"subscription_plan",
		"renewal_month",
		"renewal_day",
		"authorization_code",
		"transaction_id"
	);
	$a_fields = array_merge($a_fields, $a_adminfields);
}

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
	
	if (pods_url_variable(3) == 'logolist') {

		get_header();
		$profile = new Pod('vendor_profiles');
		$profile->findRecords( 'name', -1, "t.profile_type = 'Platinum'");
		$total = $profile->getTotalRows();
		echo "<p>$total</p>";
		$i = 0;
		while ($profile->fetchRecord()) {
			// figure out which photo to use...
			$aimg = $profile->get_field('profile_image');
			$xname = $profile->get_field('name');
			$xpid = $profile->get_field('id');
			$xdest = "/media/images/profiles/logos/$xpid/logo.jpg";
			
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
				$img = strtolower($profile->get_field('v2_profile_image_sm'));
				$img = str_replace('.jpeg', '.jpg', $img);
				$img = str_replace('.gif', '.jpg', $img);
				$img = str_replace('.png', '.jpg', $img);
				$img = str_replace('.tif', '.jpg', $img);
				$img = str_replace('.tiff', '.jpg', $img);
			}
			
			if ($img != "" || file_exists(ABSPATH . $img)) {
				if (!is_dir("$img_dst_logo/$xpid")) {mkdir("$img_dst_logo/$xpid", 0755);}
				echo "<p>$xname<br />$img_base_path$img<br />$img_dst_logo/$xpid/logo.jpg</p>";
				copy($img_base_path . $img, "$img_dst_logo/$xpid/logo.jpg");
			}
		
		}
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

$profile = new Pod('vendor_profiles');
//$profile->findRecords( 'id', -1, "t.id = '$pid' and t.vendor = $active_user_id");
$profile->findRecords( 'id', -1, "t.vendor = $active_user_id");
$total = $profile->getTotalRows();
if( $total > 0 ) {
	$profile->fetchRecord();
	$a = get_vendorfields($profile);
	load_vendorimages($profile, $a);
	$pid = $a['id'];
}
$title = 'My Profile';



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
			header("Location: ". PAGE_HOME);
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
$types = new Pod('venue_types');
$types->findRecords( '', 0, '', 'SELECT name, slug FROM wp_pod_tbl_venue_types ORDER BY name');
$s1 = '<select name="category1" id="category1" class="vendor_select">';
$s2 = '<select name="category2" id="category2" class="vendor_select">';
$s3 = '<select name="category3" id="category3" class="vendor_select">';
$s4 = '<select name="category4" id="category4" class="vendor_select">';
$s5 = '<select name="category5" id="category5" class="vendor_select">';
$t1 = '<select name="type1" id="type1" class="vendor_select">';
$t2 = '<select name="type2" id="type2" class="vendor_select">';
$t3 = '<select name="type3" id="type3" class="vendor_select">';

$s1 .= '<option value="0"';
$s2 .= '<option value="0"';
$s3 .= '<option value="0"';
$s4 .= '<option value="0"';
$s5 .= '<option value="0"';
$t1 .= '<option value="0"';
$t2 .= '<option value="0"';
$t3 .= '<option value="0"';

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
if( $a['type1'] == "0" ) {
    $t1 .= "selected ";
}
if( $a['type2'] == "0" ) {
    $t2 .= "selected ";
}
if( $a['type3'] == "0" ) {
    $t3 .= "selected ";
}

$s1 .= '>&lt;none&gt;</option>';
$s2 .= '>&lt;none&gt;</option>';
$s3 .= '>&lt;none&gt;</option>';
$s4 .= '>&lt;none&gt;</option>';
$s5 .= '>&lt;none&gt;</option>';
$t1 .= '>&lt;none&gt;</option>';
$t2 .= '>&lt;none&gt;</option>';
$t3 .= '>&lt;none&gt;</option>';

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
while ( $types->fetchRecord() ) {
	$t1 .= '<option value="' . $types->get_field('slug') . '" ';
	$t2 .= '<option value="' . $types->get_field('slug') . '" ';
	$t3 .= '<option value="' . $types->get_field('slug') . '" ';
	
	if( $a['type1'] == $types->get_field('slug') ) { $t1 .= "selected "; }
	if( $a['type2'] == $types->get_field('slug') ) { $t2 .= "selected "; }
	if( $a['type3'] == $types->get_field('slug') ) { $t3 .= "selected "; }

	$t1 .= '>' . $types->get_field('name') . '</option>';
	$t2 .= '>' . $types->get_field('name') . '</option>';
	$t3 .= '>' . $types->get_field('name') . '</option>';
}
$s1 .= '</select>';
$s2 .= '</select>';
$s3 .= '</select>';
$s4 .= '</select>';
$s5 .= '</select>';
$t1 .= '</select>';
$t2 .= '</select>';
$t3 .= '</select>';

// *******************************************************************
// START SPITTIN' OUT THE PAGE
// *******************************************************************
//get_header();

	echo "<h2>$title</h2>";
	if (checkAdmin()) {
		get_adminselector();
	}

?>
	<form action="<?php echo PAGE_PROFILE; ?>" method="post" name="profileForm" id="profileForm" >

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
<?php
if (checkAdmin()) {
?>
				<p class="TabbedPanelsTab"> Admin Only </p>
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
					
					<p class="vendor_label"><label for="category1">Category</label></p>
					<p class="vendor_desc">Select the category for your profile. The category must reflect the nature of your business.</p>
					<p class="vendor_txt"><?php echo $s1; ?> <span class="required"><font color="#CC0000">*</font></span></p>
<?php
if (checkAdmin()) {
?>
					<p class="vendor_desc">Additional categories (viewable by administrators only).</p>
					<p class="vendor_txt"><?php echo $s2; ?></p>
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

					<p class="vendor_label"><label for="type1">Venue Type</label></p>
					<p class="vendor_desc">Select up to 3 venue types that apply to this venue.</p>
					<p class="vendor_txt"><?php echo $t1; ?></p>
					<p class="vendor_txt"><?php echo $t2; ?></p>
					<p class="vendor_txt"><?php echo $t3; ?></p>

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
					<p class="vendor_label">Profile Logo &amp; Profile Images</p>
					<p class="vendor_desc">All images must meet the following specifications:
					<p class="vendor_desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; <b>JPG/JPEG, GIF, PNG or BMP</b> format<br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; <b>No larger than 4000 pixels</b> on the longest side<br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; <b>Less than 4MB</b> in file size<br />&nbsp;
					</p>
					<p class="vendor_desc">NOTE: Changes to your logo/images will appear in your profile <b>IMMEDIATELY</b> after upload.<br />&nbsp;</p>
					<p class="vendor_uploadflash"><span id="spanButtonPlaceholder2"></span></p>
					<div id="divFileProgressContainer2"></div>
					<div id="logo_block">
<?php
get_logo_block($a);
?>
					</div>

					<p style="border-bottom: 1px solid #CCCCCC;">&nbsp;</p>

					<p class="vendor_label">Profile Images</p>
					<p class="vendor_uploadflash"><span id="spanButtonPlaceholder"></span></p>
					<div id="divFileProgressContainer"></div>
					<div id="thumbnail_block">
<?php
get_thumbnail_block($a);
?>
					</div>
				</div> 
				<div class="TabbedPanelsContent">

					<p class="vendor_label">VIDEO IN YOUR PROFILE</p>
					<p class="vendor_desc">There are two methods for displaying video in your profile:</p>
					<p class="vendor_desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Embeddeding a single video from a 3rd party video service (e.g. Youtube, Vimeo, etc.)<br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Uploading 1 or more videos to display inside your photo slideshow
					</p>
					<br />&nbsp;

					<p style="border-bottom: 1px solid #CCCCCC;">&nbsp;</p>

					<p class="vendor_label"><label for="video_url">METHOD #1: Embedding Video from a 3rd Party Service</label></p>
					<p class="vendor_desc">Choose this method if you already have video uploaded to a supported 3rd party video service such as Youtube, Vimeo or Viddler. Enter the URL to the video below and we will automatically embed the video in your profile. By default, it will display below the photo slideshow.</p>
					<p class="vendor_desc"><i>Supported video services include: YouTube, Vimeo, Viddler, Google Video, Qik, and Animoto.</i><br />&nbsp;</p>
					<p class="vendor_desc">Simply enter the website address for your video, for example:
					<p class="vendor_desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; YouTube: <b>http://www.youtube.com/watch?v=FRscebgg5C0</b><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Vimeo: <b>http://vimeo.com/7809605</b><br />&nbsp;
					</p>
					<p class="vendor_txt"><input name="video_url" type="text" id="video_url" size="40" value="<?php echo $a['video_url']; ?>" /></p>
					
					<p class="vendor_txt"><input class="vendor_checkbox" name="video_first" type="checkbox" id="video_first" value="1" <?php echo iif($a['video_first'] == '1', 'checked', ''); ?> /> <label for="video_first">Show my video ABOVE the slideshow</label><br />&nbsp;</p>

					<p style="border-bottom: 1px solid #CCCCCC;">&nbsp;</p>

					<p class="vendor_label"><label for="video_url">METHOD #2: Video Inside Your Image Slideshow Viewer</label></p>
					<p class="vendor_desc">Choose this method to display 1 or more videos inside the slideshow viewer. By default, videos will display before your images and will automatically start playing in alphabetical order, one after the other.<br />&nbsp;</p>
					<p class="vendor_desc">All uploaded videos must meet following requirements:</p>
					<p class="vendor_desc">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; Your videos must be in <b>.FLV format</b><br />
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&bull; The maximum size allowed is <b>30MB per video.</b><br />&nbsp;
					</p>
					<p class="vendor_txt"><input class="vendor_checkbox" name="video_flv_last" type="checkbox" id="video_flv_last" value="1" <?php echo iif($a['video_flv_last'] == '1', 'checked', ''); ?> /> <label for="video_flv_last">Show my video(s) AFTER the still images in the slideshow</label><br />&nbsp;</p>

					<p class="vendor_uploadflash"><span id="spanButtonPlaceholder3"></span></p>
					<div id="divFileProgressContainer3"></div>
					<div id="video_block">
<?php
get_video_block($a);
?>
					</div>
					<p class="vendor_desc">&nbsp;</p>
				</div> 
<?php
if (checkAdmin()) {
?>
				<div class="TabbedPanelsContent">
					<p class="vendor_label">Subscription Status</p>
					<p class="vendor_desc">Profile Type:</p>
					<p class="vendor_txt">
						<select class="vendor_select" name="profile_type" id="profile_type">
						<?php
						$a_protypes = array(
							'New'		=> 'New (unlisted)',
							'Platinum'	=> 'Platinum'
						);
						foreach ($a_protypes as $key => $val) {
							if ($a['profile_type'] == $key) {
								echo '<option selected="true" value="', $key, '">', $val, '</option>';
							} else {
								echo '<option value="', $key, '">', $val, '</option>';
							}
						}
						?>
						</select>
					</p>

					<p class="vendor_desc">Payment amount:</p>
					<p class="vendor_txt"><input name="payment_amount" type="text" id="payment_amount" size="40" value="<?php echo $a['payment_amount']; ?>" /></p>
				
					<p class="vendor_desc">Payment plan:</p>
					<p class="vendor_txt">
						<select class="vendor_select" name="payment_plan" id="payment_plan">
						<?php
						$a_payplans = array(
							'NA'		=> 'Not Applicable',
							'Monthly'	=> 'Monthly',
							'Yearly'	=> 'Yearly'
						);
						foreach ($a_payplans as $key => $val) {
							if ($a['payment_plan'] == $key) {
								echo '<option selected="true" value="', $key, '">', $val, '</option>';
							} else {
								echo '<option value="', $key, '">', $val, '</option>';
							}
						}
						?>
						</select>
					</p>

					<p class="vendor_desc">Subscription plan:</p>
					<p class="vendor_txt">
						<select class="vendor_select" name="subscription_plan" id="subscription_plan">
						<?php
						$a_subplans = array(
							'NA'		=> 'Not Applicable',
							'Monthly'	=> 'Monthly',
							'Yearly'	=> 'Yearly'
						);
						foreach ($a_subplans as $key => $val) {
							if ($a['subscription_plan'] == $key) {
								echo '<option selected="true" value="', $key, '">', $val, '</option>';
							} else {
								echo '<option value="', $key, '">', $val, '</option>';
							}
						}
						?>
						</select>
					</p>
				
					<p class="vendor_desc">Renewal month/day:</p>
					<p class="vendor_txt">
						<select class="vendor_select" name="renewal_month" id="renewal_month">
						<?php
						$a_months = array(
							'0'=>'&lt;none&gt;',
							'01'=>'1 - January',
							'02'=>'2 - February',
							'03'=>'3 - March',
							'04'=>'4 - April',
							'05'=>'5 - May',
							'06'=>'6 - June',
							'07'=>'7 - July',
							'08'=>'8 - August',
							'09'=>'9 - September',
							'10'=>'10 - October',
							'11'=>'11 - November',
							'12'=>'12 - December'
						);
						foreach ($a_months as $key => $val) {
							if ($a['renewal_month'] == $key) {
								echo '<option selected="true" value="', $key, '">', $val, '</option>';
							} else {
								echo '<option value="', $key, '">', $val, '</option>';
							}
						}
						?>
						</select>
						<select class="vendor_select" name="renewal_day" id="renewal_day">
						<?php
						$a_days = array(
							'0'=>'&lt;none&gt;',
							'1'=>'1',
							'2'=>'2',
							'3'=>'3',
							'4'=>'4',
							'5'=>'5',
							'6'=>'6',
							'7'=>'7',
							'8'=>'8',
							'9'=>'9',
							'10'=>'10',
							'11'=>'11',
							'12'=>'12',
							'13'=>'13',
							'14'=>'14',
							'15'=>'15',
							'16'=>'16',
							'17'=>'17',
							'18'=>'18',
							'19'=>'19',
							'20'=>'20',
							'21'=>'21',
							'22'=>'22',
							'23'=>'23',
							'24'=>'24',
							'25'=>'25',
							'26'=>'26',
							'27'=>'27',
							'28'=>'28',
							'29'=>'29',
							'30'=>'30',
							'31'=>'31'
						);
						foreach ($a_days as $key => $val) {
							if ($a['renewal_day'] == $key) {
								echo '<option selected="true" value="', $key, '">', $val, '</option>';
							} else {
								echo '<option value="', $key, '">', $val, '</option>';
							}
						}
						?>
						</select>
					</p>
				
					<p class="vendor_label">Transaction Information</p>

					<p class="vendor_desc">Card number (last 4 digits):</p>
					<p class="vendor_txt"><input name="card_num" type="text" id="card_num" size="40" class="required" value="<?php echo $a['card_num']; ?>" /></p>
				
					<p class="vendor_desc">Card Expiration Date:</p>
					<p class="vendor_txt">
						<select class="vendor_select" name="card_exp_month" id="card_exp_month">
						<?php
						$a_months = array(
							'0'=>'&lt;none&gt;',
							'01'=>'1 - January',
							'02'=>'2 - February',
							'03'=>'3 - March',
							'04'=>'4 - April',
							'05'=>'5 - May',
							'06'=>'6 - June',
							'07'=>'7 - July',
							'08'=>'8 - August',
							'09'=>'9 - September',
							'10'=>'10 - October',
							'11'=>'11 - November',
							'12'=>'12 - December'
						);
						foreach ($a_months as $key => $val) {
							if ($a['card_exp_month'] == $key) {
								echo '<option selected="true" value="', $key, '">', $val, '</option>';
							} else {
								echo '<option value="', $key, '">', $val, '</option>';
							}
						}
						?>
						</select>
						<select class="vendor_select" name="card_exp_year" id="card_exp_year">
						<?php
						$a_years = array(
							'0'=>'&lt;none&gt;',
							'2010'=>'2010',
							'2011'=>'2011',
							'2012'=>'2012',
							'2013'=>'2013',
							'2014'=>'2014',
							'2015'=>'2015',
							'2016'=>'2016',
							'2017'=>'2017'
						);
						foreach ($a_years as $key => $val) {
							if ($a['card_exp_year'] == $key) {
								echo '<option selected="true" value="', $key, '">', $val, '</option>';
							} else {
								echo '<option value="', $key, '">', $val, '</option>';
							}
						}
						?>
						</select>
					</p>
				
					<p class="vendor_desc">Authorization Code:</p>
					<p class="vendor_txt"><input name="authorization_code" type="text" id="authorization_code" size="40" class="required" value="<?php echo $a['authorization_code']; ?>" /></p>
				
					<p class="vendor_desc">Transaction ID:</p>
					<p class="vendor_txt"><input name="transaction_id" type="text" id="transaction_id" size="40" class="required" value="<?php echo $a['transaction_id']; ?>" /></p>
				
					<p class="vendor_desc">AuthorizeNET Subscriber ID:</p>
					<p class="vendor_txt"><input name="subscriber_id" type="text" id="subscriber_id" size="40" class="required" value="<?php echo $a['subscriber_id']; ?>" /></p>
				
				
					<p class="vendor_label">Statistics (changes to the following fields will not be saved)</p>
					<p class="vendor_desc">Customer Rating</p>
					<p class="vendor_txt"><input name="rating" type="text" id="rating" size="5" value="<?php echo (int) $a['rating']; disabled ?>" /></p>
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
	
	var swfu1, swfu2, swfu3;
	window.onload = function () {
		swfu1 = new SWFUpload({
			// Backend Settings
			upload_url: '<?php echo PAGE_DOIMAGE, '/', $a['id']; ?>',
			post_params: {"PHPSESSID": "<?php echo session_id(); ?>", "cmd": "upload_images"},

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
			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES,
			
			// Flash Settings
			flash_url : "/media/flash/swfupload.swf",

			custom_settings : {
				upload_target : "divFileProgressContainer"
			},
			
			// Debug Settings
			debug: false
		});

		swfu2 = new SWFUpload({
			// Backend Settings
			upload_url: '<?php echo PAGE_DOIMAGE, '/', $a['id']; ?>',
			post_params: {"PHPSESSID": "<?php echo session_id(); ?>", "cmd": "upload_logo"},

			// File Upload Settings
			file_size_limit : "8 MB",	// 2MB
			file_types : "*.jpg;*.jpeg;*.gif;*.png",
			file_types_description : "Readable Images Formats",
			file_upload_limit : "0",

			// Event Handler Settings - these functions as defined in swfhandlers.js
			//  The handlers are not part of SWFUpload but are part of my website and control how
			//  my website reacts to the SWFUpload events.
			file_queue_error_handler : fileQueueError2,
			file_dialog_complete_handler : fileDialogComplete2,
			upload_progress_handler : uploadProgress2,
			upload_error_handler : uploadError2,
			upload_success_handler : uploadSuccess2,
			upload_complete_handler : uploadComplete2,

			// Button Settings
			button_placeholder_id : "spanButtonPlaceholder2",
			button_width: 120,
			button_height: 18,
			button_text : '<span class="swf_button">UPLOAD LOGO</span>',
			button_text_style : '.swf_button { font-family: Verdana; color: #666666; font-weight: bold; font-size: 10pt; }',
			button_text_top_padding: 0,
			button_text_left_padding: 8,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,
			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILE,
			
			// Flash Settings
			flash_url : "/media/flash/swfupload.swf",

			custom_settings : {
				upload_target : "divFileProgressContainer2"
			},
			
			// Debug Settings
			debug: false
		});

		swfu3 = new SWFUpload({
			// Backend Settings
			upload_url: '<?php echo PAGE_DOIMAGE, '/', $a['id']; ?>',
			post_params: {"PHPSESSID": "<?php echo session_id(); ?>", "cmd": "upload_videos"},

			// File Upload Settings
			file_size_limit : "35 MB",	// 2MB
			file_types : "*.flv",
			file_types_description : "Flash Video",
			file_upload_limit : "0",

			// Event Handler Settings - these functions as defined in swfhandlers.js
			//  The handlers are not part of SWFUpload but are part of my website and control how
			//  my website reacts to the SWFUpload events.
			file_queue_error_handler : fileQueueError3,
			file_dialog_complete_handler : fileDialogComplete3,
			upload_progress_handler : uploadProgress3,
			upload_error_handler : uploadError3,
			upload_success_handler : uploadSuccess3,
			upload_complete_handler : uploadComplete3,

			// Button Settings
			button_placeholder_id : "spanButtonPlaceholder3",
			button_width: 120,
			button_height: 18,
			button_text : '<span class="swf_button">UPLOAD VIDEOS</span>',
			button_text_style : '.swf_button { font-family: Verdana; color: #666666; font-weight: bold; font-size: 10pt; }',
			button_text_top_padding: 0,
			button_text_left_padding: 8,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,
			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES,
			
			// Flash Settings
			flash_url : "/media/flash/swfupload.swf",

			custom_settings : {
				upload_target : "divFileProgressContainer3"
			},
			
			// Debug Settings
			debug: false
		});

	};

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
	
	function fileQueueError2(file, errorCode, message) {
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
	
	function fileQueueError3(file, errorCode, message) {
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
	
	function fileDialogComplete2(numFilesSelected, numFilesQueued) {
		try {
			if (numFilesQueued > 0) {
				this.startUpload();
			}
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function fileDialogComplete3(numFilesSelected, numFilesQueued) {
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
	
	function uploadProgress2(file, bytesLoaded) {
	
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
	
	function uploadProgress3(file, bytesLoaded) {
	
		try {
			var percent = Math.ceil((bytesLoaded / file.size) * 100);
	
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setProgress(percent);
			if (percent === 100) {
				progress.setStatus("Saving Video...");
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
	
	function uploadSuccess2(file, serverData) {
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
	
	function uploadSuccess3(file, serverData) {
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
					"/advertisers/doimage/<?php echo $pid; ?>",
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
	
	function uploadComplete2(file) {
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
				$("#logo_block").html('<p class="vendor_desc">Reloading logo . . .</p>');
				$.get(
					"/advertisers/doimage/<?php echo $pid; ?>",
					{cmd:"get_logo_block"},
					function(data){
						$("#logo_block").html(data);
					}
				);
			}
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadComplete3(file) {
		try {
			/*  I want the next upload to continue automatically so I'll call startUpload here */
			if (this.getStats().files_queued > 0) {
				this.startUpload();
			} else {
				var progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setComplete();
				progress.setStatus("");
				progress.toggleCancel(false);
				$(".progressName").html('Video upload finished.');
				$("#video_block").html('<p class="vendor_desc">Reloading video list . . .</p>');
				$.get(
					"/advertisers/doimage/<?php echo $pid; ?>",
					{cmd:"get_video_block"},
					function(data){
						$("#video_block").html(data);
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
	
	
	function uploadError2(file, errorCode, message) {
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
	
	function uploadError3(file, errorCode, message) {
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
//get_footer();
//echo phpinfo();
//exit;
?>
