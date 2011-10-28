<?php

/* Template Name: Event Profile */

if (!is_advertiser() && !checkAdmin()) {
	header("Location: " . PAGE_HOME);
	exit;
}

// let all of WP know that we are in the guide area
ao_set_in_guide(true);

// this is the profile ID
$eid = pods_url_variable(2);

// this is the "effective" user id, which is not neccessarily the user that is logged in, in the
//		case of admins, wo are able to mascarade as other users.
$active_user_id = get_active_user_id();

// array to hold our profile fields
$a = array();


// *******************************************************************
// INITIALIZATION, so to speak
// *******************************************************************

// Basically, if we have a $eid, go ahead an get the data from the database, We might end up
//		replacing that data with data the user is trying to save .. a good baseline, so to speak
if ($eid != '' && $eid != 'new') {
	$event = new Pod('events');
	$event->findRecords( 'id', -1, "t.id = '$eid' and t.vendor = $active_user_id");
	$total = $event->getTotalRows();
	if( $total > 0 ) {
		$event->fetchRecord();
		$a = get_eventfields($event);
	}
	$title = 'Edit Event';
}
else {
	$eid = 'new';
	$title = 'New Event';
}


// *******************************************************************
// IS THE USER TRYING TO SAVE THE EVENT???
// *******************************************************************
if ($_POST['submitted'] == "1") {

	$success = false;
	
	// pull everything off of $_POST into our $a array.
	// we're specifically NOT using $_REQUEST here for a tiny bit of security.
	foreach($_POST as $key => $value) {
		$a[$key] = htmlspecialchars(stripslashes($value));
	}

	// since the unchecked input checkboxes don't submit ANY $_POST data when unchecked,
	//		we have to explicitly set those values from $_POST since the "stale" data from the
	//		database may contain "checked" values that the user intends to be "un-checked".
	$a['approved'] = iif($_POST['approved'] == '1', '1', '0');

	/// special case: we want the memo field data to have the quotes intact
	$a['description'] = htmlspecialchars(stripslashes($_POST['description']), ENT_NOQUOTES);
	
	// now we validate the data the user entered... super easy
	if ($a['name'] == '') {
		$err[] = 'You must enter a valid event name.';
	}

	if ($a['date_start'] == '') {
		$err[] = 'You must select a start date for your event.';
	}

	// make the dates suitable for insertion
	$a['date_start'] = date('Y-m-d',strtotime($a['date_start'])) . ' 00:00:00';
	$a['date_end'] = date('Y-m-d',strtotime($a['date_end'])) . ' 00:00:00';
	
	if (empty($err)) {

		/* these fields are not used or not updated by the user
		slug
		vendor
		keywords
		event_type (always '1' = Weddings)
		ao2_companyguid
		latitude
		longitude
		*/

		// these are all the fields we are going to save
		$a_fields = array(
			"name",
			"date_start",
			"date_end",
			"hours",
			"location",
			"address",
			"city",
			"state",
			"zipcode",
			"web_url",
			"cost",
			"description",
			"phone"
		);
		
		if (checkAdmin()) {
			// additional fields that admins are able to save
			$a_adminfields = array(
				"approved"
			);
			$a_fields = array_merge($a_fields, $a_adminfields);
		}
		
		
		$event_data = array();
		
		// now move all the field data into our new array
		foreach ($a_fields as $field) {
			$event_data[$field] = addslashes($a[$field]);
		}
		
		// if we don't have them already, get the geo lat/log pair from google
		if (($event_data['latitude'] == '') || ($event_data['longitude'] == '')) {
		
			$adresse = $event_data['address']; // grab the content of the "adresse" field in your pod
			$ville = $event_data['city']; // grab the content of the "ville" field in your pod, ville is french for town
			$state = $event_data['state']; // grab the content of the "ville" field in your pod, ville is french for town
			$zip = $event_data['zipcode']; // grab the content of the "ville" field in your pod, ville is french for town
			
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
				$event_data['latitude'] = $decoded->results[0]->geometry->location->lat; // copy lat into the field called lat in your pod
				$event_data['longitude'] = $decoded->results[0]->geometry->location->lng; // copy long into the field called long in your pod
			}
		}
	
		// all clear to save the data to the database
		$api = new PodAPI();
		
		// safety cleansing
		pods_sanitize($event_data);
	
		if ($eid == 'new') {
		
			// since we are saving a new event, these fields need initializing this one time only
			$event_data['vendor'] = get_active_user_id();
			$event_data['approved'] = '1';
			$event_data['event_type'] = '1';
			$a['approved'] = '1';
	
			$params = array(
				'datatype' => 'events', 
				'columns' => $event_data
			);
			// create the item
			$api->save_pod_item($params);
			$success = true;
		}
		else {
		
			// SCREW PODSCMS... just do a plain ole SQL update
			$sql = "UPDATE wp_pod_tbl_events SET ";
			$sql_fields = array();
			foreach ($event_data as $key => $val) {
				$sql_fields[] .= "$key='$val'";
			}
			$sql .= implode(', ', $sql_fields);
			$sql .= " WHERE id=$eid" ;
			
			pod_query($sql);
	
			//// saving an existing event
			//$params = array(
			//	'datatype' => 'events', 
			//	'pod_id' => $eid,
			//	'columns' => $event_data
			//);
			// create the item
			//$api->save_pod_item($params);
			$success = true;
		}
		
		if ($success) {

$subject_admin = "New Occasions Event from " . $event_data['name'];

$headers_admin = 'From: "Occasions Magazine" <do-not-reply@atlantaoccasions.com>' . "\r\n";
$headers_admin .= 'X-Mailer: AO3/PHP/' . phpversion() . "\r\n";

$message_admin = 
"{$event_data['name']} has created a new event...

{$event_data['name']}
Starts on {$event_data['date_start']}
Ends on {$event_data['date_end']}
Hours: {$event_data['hours']}
Location: {$event_data['location']}
Address: {$event_data['address']}
City: {$event_data['city']}
State: {$event_data['state']}
Zip: {$event_data['zipcode']}
Website: {$event_data['web_url']}
Cost: {$event_data['cost']}
Phone: {$event_data['phone']}

Occasions backend...
{$const['PAGE_HOME']}
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";

//mail($user_email, $subject, $message, $headers, AO_EMAIL_FLAGS);
mail(AO_ADMIN_EMAIL, $subject_admin, $message_admin, $headers_admin, AO_EMAIL_FLAGS);
mail(AO_TECH_EMAIL, $subject_admin, $message_admin, $headers_admin, AO_EMAIL_FLAGS);

			// redirect to the events page
			header("Location: ". PAGE_EVENTS);
			exit;
		}
		else {
			// let execution continue so that the errors can be displayed
		}

	}
}

if (substr($a['date_start'],0,10)=='0000-00-00'){
	$a['date_start'] = '';
}
else {
	$a['date_start'] = date('Y/m/d',strtotime($a['date_start']));
}

if (substr($a['date_end'],0,10)=='0000-00-00'){
	$a['date_end'] = '';
}
else {
	$a['date_end'] = date('Y/m/d',strtotime($a['date_end']));
}


// *******************************************************************
// START SPITTIN' OUT THE PAGE
// *******************************************************************
//get_header();

echo "<h2>$title</h2>";
if (checkAdmin()) {
	get_adminselector();
}
?>

	<form action="<?php echo PAGE_EVENT, "/$eid"; ?>" method="post" name="eventForm" id="eventForm" >

		<p class="vendor_txt_r"><input name="doSave" type="submit" id="vendor_submit" value="Save Event Changes" /></p>
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
				<p class="TabbedPanelsTab"> Event Details </p>
				<p class="TabbedPanelsTab"> Description </p>
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

					<p class="vendor_label"><label for="e_name">Event Name</label> <span class="required"><font color="#CC0000">*</font></span></p>
					<p class="vendor_txt"><input name="name" type="text" id="e_name" size="40" class="required" value="<?php echo $a['name']; ?>" /></p>
			
					<p class="vendor_label"><label for="date_start">Start Date</label></p>
					<p class="vendor_txt"><input name="date_start" type="text" id="date_start" size="100" value="<?php echo $a['date_start']; ?>" /></p>
					
					<p class="vendor_label"><label for="date_end">End Date</label></p>
					<p class="vendor_txt"><input name="date_end" type="text" id="date_end" size="40" value="<?php echo $a['date_end']; ?>" /></p>
					
					<p class="vendor_label"><label for="hours">Hours</label></p>
					<p class="vendor_txt"><input name="hours" type="text" id="hours" size="40" value="<?php echo $a['hours']; ?>" /></p>
					
					<p class="vendor_label"><label for="location">Location</label></p>
					<p class="vendor_txt"><input name="location" type="text" id="location" size="40" value="<?php echo $a['location']; ?>" /></p>
					
					<p class="vendor_label"><label for="address">Address</label></p>
					<p class="vendor_txt"><input name="address" type="text" id="address" size="40" value="<?php echo $a['address']; ?>" /></p>
					
					<p class="vendor_label"><label for="city">City / State / Zipcode</label></p>
					<p class="vendor_txt"><input name="city" type="text" id="city" size="40" value="<?php echo $a['city']; ?>" /> <input name="state" type="text" id="state" size="40" value="<?php echo $a['state']; ?>" /> <input name="zipcode" type="text" id="zipcode" size="40" value="<?php echo $a['zipcode']; ?>" /></p>
					
					<p class="vendor_label"><label for="cost">Cost</label></p>
					<p class="vendor_txt"><input name="cost" type="text" id="cost" size="40" value="<?php echo $a['cost']; ?>" /></p>
					
					<p class="vendor_label"><label for="phone">Phone Number</label></p>
					<p class="vendor_txt"><input name="phone" type="text" id="phone" size="40" value="<?php echo $a['phone']; ?>" /></p>
					
					<p class="vendor_label"><label for="web_url">More Information Link (website address)</label></p>
					<p class="vendor_txt"><input name="web_url" type="text" id="web_url" size="40" value="<?php echo $a['web_url']; ?>" /> ex. "http://www.domain.com/"</p>
					
					<p>&nbsp;</p>

					<p>&nbsp;</p>

				</div> 
				<div class="TabbedPanelsContent">

					<p class="vendor_label"><label for="description">Event Description</label></p>
					<p class="vendor_desc">Use this area to describe the event.</p>
					<p class="vendor_txt"><textarea name="description"><?php echo $a['description']; ?></textarea></p>
					
					<p>&nbsp;</p>

				</div> 

<?php
if (checkAdmin()) {
?>
				<div class="TabbedPanelsContent">
					<p class="vendor_label"><label for="alcohol_permitted">Event Approval</label></p>
					<p class="vendor_txt"><input class="vendor_checkbox" name="approved" type="checkbox" id="approved" value="1" <?php echo iif($a['approved'] == '1', 'checked', ''); ?> /> <label for="approved">This event is approved.</label><br />
					</p>
				</div> 
<?php
}
?>
 			</div> 
		</div> 

		
		<input type="hidden" name="eid" value="<?php echo $eid; ?>" />
		<input type="hidden" name="submitted" value="1" />
		<p class="vendor_txt_r">&nbsp;<br /><input name="doSave" type="submit" id="vendor_submit" value="Save Event Changes" /></p>

	</form>
	<p>&nbsp;</p>

<?php
// *******************************************************************
// IT'S JAVASCRIPT FROM HERE ON DOWN
// *******************************************************************
?>

<script language="JavaScript" type="text/javascript"> 
	$(document).ready(function(){
		$('#date_start').jdPicker({ 
			date_format:"YYYY/mm/dd", 
			start_of_week:0, 
			date_min:"1970/01/01"
		});
		$('#date_end').jdPicker({ 
			date_format:"YYYY/mm/dd", 
			start_of_week:0, 
			date_min:"1970/01/01"
		});
	});

	var TabbedPanels3 = new Spry.Widget.TabbedPanels("TabbedPanels3");
	CKEDITOR.replace( 'description' );
</script> 

<?php
//get_footer();
//echo phpinfo();
//exit;
?>
