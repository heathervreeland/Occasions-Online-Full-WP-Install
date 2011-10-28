<?php

/* Template Name: New User Registration */

include_once('guide-functions.php');
require_once('vendor_access/recaptchalib.php');
require_once('MCAPI.class.php');
ao_set_regprompted('1');

//$slug = pods_url_variable(1);
//$cmd = pods_url_variable(2);

//$profile = new Pod('user_profiles');
//$profile->findRecords( 'id', -1, "t.slug = '$slug'");
//$total = $profile->getTotalRows();
//if( $total > 0 ) {
//	$profile->fetchRecord();
//	$a = get_vendorfields($profile);
//}

// ************************************************************************
// spit out the header, super simple
// ************************************************************************
?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"> 
<head profile="http://gmpg.org/xfn/11"> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type='text/javascript' src='<?php bloginfo('stylesheet_directory'); ?>/jquery.jdpicker.js'></script> 
<title>Occasions Magazine: User Registration</title> 
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style-popup.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/jquery.jdpicker.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/colorbox.css" type="text/css" media="screen" />

<script type="text/javascript">
	$(document).ready(function(){
		$('#txt_date').jdPicker({ 
			//date_format:"mm/dd/YYYY", 
			start_of_week:0, 
			date_min:"Jan 01 1970"
		});
	});
</script>

<?php wp_head(); ?>

</head> 
<body> 
<div class="post">

<?php

echo <<<HEREDOC
<h2>Occasions Magazine: User Registration</h2>
HEREDOC;

// ************************************************************************
// pull all the fields off $_POST, even if they are not there since it
//		will allow us to set our defaults
// ************************************************************************
$missing_fields = false;
$missing_error = '';
$txt_name = htmlspecialchars($_POST['txt_name']);
$txt_email = htmlspecialchars($_POST['txt_email']);
$txt_address = htmlspecialchars($_POST['txt_address']);
$txt_city = htmlspecialchars($_POST['txt_city']);
$txt_state = htmlspecialchars($_POST['txt_state']);
$txt_zip = htmlspecialchars($_POST['txt_zip']);
$txt_phone = htmlspecialchars($_POST['txt_phone']);
$txt_date = htmlspecialchars($_POST['txt_date']);
if ($_POST['txt_service_interest']) {
	$txt_service_interest = stripcslashes(implode(", ", $_POST['txt_service_interest']));
}
$txt_interest_weddings = iif($_POST['txt_interest_weddings'] == 'on', '1', '0');
$txt_interest_social = iif($_POST['txt_interest_social'] == 'on', '1', '0');
$txt_interest_corporate = iif($_POST['txt_interest_corporate'] == 'on', '1', '0');
$txt_interest_mitzvahs = iif($_POST['txt_interest_mitzvahs'] == 'on', '1', '0');
$txt_interest_parties = iif($_POST['txt_interest_parties'] == 'on', '1', '0');
$txt_referral = htmlspecialchars($_POST['txt_referral']);
$txt_comments = stripcslashes(htmlspecialchars($_POST['txt_comments'], ENT_NOQUOTES));

$txt_interest_weddings_c = iif($_POST['txt_interest_weddings'] == 'on', ' checked ', '');
$txt_interest_social_c = iif($_POST['txt_interest_social'] == 'on', ' checked ', '');
$txt_interest_corporate_c = iif($_POST['txt_interest_corporate'] == 'on', ' checked ', '');
$txt_interest_mitzvahs_c = iif($_POST['txt_interest_mitzvahs'] == 'on', ' checked ', '');
$txt_interest_parties_c = iif($_POST['txt_interest_parties'] == 'on', ' checked ', '');

if ($_POST['submitted'] == "1") {
	// first check the required fields
	if (!$txt_name || !$txt_email || !$txt_address || !$txt_city || !$txt_state || !$txt_zip) {
		$missing_fields = true;
		$missing_error = '<p style="font-weight: bold; color: red;">One or more required fields (marked with an *) are missing.</p>';
	}
	// then do more detailed checks
	else {
		// check for a valid email
		if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i", $txt_email)) {
			$missing_fields = true;
			$missing_error .= '<p style="font-weight: bold; color: red;">Please enter a valid email address.</p>';
		}
		// check and validate recaptcha
		$resp = recaptcha_check_answer ($privatekey,
										$_SERVER["REMOTE_ADDR"],
										$_POST["recaptcha_challenge_field"],
										$_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) {
			$missing_fields = true;
			$missing_error .= '<p style="font-weight: bold; color: red;">Image Verification failed! (reCAPTCHA said: ' . $resp->error . ')</p>';
		}
	}
}

if ($_POST['submitted'] == "1" && !$missing_fields) {

	// ************************************************************************
	// SAVING DATA
	// ************************************************************************
	$api = new PodAPI();
	// all of the values to save and options to use
	
	$reg_data = array(
			'name' => addslashes($txt_name),
			'email' => addslashes($txt_email),
			'address' => addslashes($txt_address),
			'city' => addslashes($txt_city),
			'state' => strtoupper(addslashes($txt_state)),
			'zipcode' => addslashes($txt_zip),
			'phone' => addslashes($txt_phone),
			'event_date' => addslashes($txt_date),
			'interests' => addslashes($txt_service_interest),
			'interest_weddings' => $txt_interest_weddings,
			'interest_social' => $txt_interest_social,
			'interest_corporate' => $txt_interest_corporate,
			'interest_mitzvahs' => $txt_interest_mitzvahs,
			'interest_parties' => $txt_interest_parties,
			'referral' => addslashes($txt_referral),
			'notes' => addslashes($txt_comments),
			'inquire_date' => date("Y-m-d H:i:s")
		);
	pods_sanitize($reg_data);
	$params = array(
		'datatype' => 'user_profiles', 
		'columns' => $reg_data
	);
	 
	// create the item
	$api->save_pod_item($params);

	// ************************************************************************
	// MAILCHIMP INSERTION
	// ************************************************************************
	// grab an API Key from http://admin.mailchimp.com/account/api/
	$api = new MCAPI('e7c8035acf9e6541b2a1e0c27ccd2dfd-us1');

	// grab your List's Unique Id by going to http://admin.mailchimp.com/lists/
	// Click the "settings" link for the list - the Unique Id is at the bottom of that page. 
	$list_id = 'da7db994ec';

	if($api->listSubscribe($list_id, $txt_email, '', '', false) === true) {
		// It worked!	
		$mailchimp = 'Their email address was successfully added to MailChimp.';
	}else{
		// An error ocurred, return error message	
		$mailchimp = 'There was an error adding their email to MailChimp: ' . $api->errorMessage;
	}

	// ************************************************************************
	// SEND NOTIFICATION EMAILS
	// ************************************************************************
	$subject = "Occasions Magazine: New Lead";
	$headers = "From: \"Occasions Magazine\" <do-not-reply@atlantaoccasions.com>";
	$headers .= "\r\nX-Mailer: AO3/PHP/" . phpversion();

$message = 
"The following user registered online...

Name: $txt_name
Email: $txt_email
Address: $txt_address, $txt_city, $txt_state $txt_zip
Phone: $txt_phone
Event Date: $txt_date
Service Interests: $txt_service_interest
Interested in Weddings: $txt_interest_weddings
Interested in Social: $txt_interest_social
Interested in Corporate: $txt_interest_corporate
Interested in Mitzvahs: $txt_interest_mitzvahs
Interested in Parties: $txt_interest_parties
Referred by: $txt_referral
Comments: $txt_comments

$mailchimp

Thank You,

Occasions Magazine
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";

	mail(AO_ADMIN_EMAIL, $subject, $message, $headers, AO_EMAIL_FLAGS);
	mail(AO_TECH_EMAIL, $subject, $message, $headers, AO_EMAIL_FLAGS);

	echo <<<HEREDOC
	<h3>Thank you!</h3>
	<p>&nbsp;</p>
	<p>Thank you for registering with Occasions Magazine. </p>
	<p>&nbsp;</p>
	<!-- <p>
	$txt_name<br />
	$txt_email<br />
	$txt_address<br />
	$txt_city<br />
	$txt_state<br />
	$txt_zip<br />
	$txt_phone<br />
	$txt_date<br />
	$txt_service_interest<br />
	$txt_interest_weddings<br />
	$txt_interest_social<br />
	$txt_interest_corporate<br />
	$txt_interest_mitzvahs<br />
	$txt_interest_parties<br />
	$txt_referral<br />
	$txt_comments<br />
	</p>
	-->
<div class="pro_contacttxt"><input type="submit" id="pro_contact_cancel" name="pro_contact_cencel" value="FINSHED!" onclick="parent.$.fn.colorbox.close(); return false;" /></div>
HEREDOC;
}
else {
	// ************************************************************************
	// DISPLAYING DATA
	//		Either there was a data error or this is the first time here.
	// ************************************************************************
	$spam_html = recaptcha_get_html($publickey, null, true);
	echo <<<HEREDOC
	<p>Party planning has its moments and we're here to help. Please take a moment to fill out the form below. We're crazy about the details so tell us who you are and all about your upcoming occasion on the calendar. And if you're up for it, click the additional box below and gain access to exclusive event planning articles and quality vendor relationships (maybe even discounts too). Happy planning!</p>
	$missing_error
	<form action="./" method="post">
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_name">* Full Name:</label></div>
			<div class="pro_contacttxt"><input name="txt_name" type="text" size="50" id="txt_name" value="$txt_name" /></div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_email">* Email:</label></div>
			<div class="pro_contacttxt"><input name="txt_email" type="text" size="50" id="txt_email" value="$txt_email" /></div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_address">* Address:</label></div>
			<div class="pro_contacttxt"><input name="txt_address" type="text" size="50" id="txt_address" value="$txt_address" /></div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_city">* City/State/Zip:</label></div>
			<div class="pro_contacttxt">
				<input name="txt_city" type="text" size="50" id="txt_city" value="$txt_city" />&nbsp;
				<input name="txt_state" type="text" size="2" id="txt_state" value="$txt_state" />&nbsp;
				<input name="txt_zip" type="text" size="5" id="txt_zip" value="$txt_zip" />
			</div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_phone">Phone:</label></div>
			<div class="pro_contacttxt"><input name="txt_phone" type="text" size="50" id="txt_phone" value="$txt_phone" /></div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_date">Event Date:</label></div>
			<div class="pro_contacttxt"><input name="txt_date" type="text" size="50" id="txt_date" value="$txt_date"  /></div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel">Services I am looking for:<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;</div>
HEREDOC;
		
		$categories = new Pod('categories');
		$categories->findRecords( '', 0, '', 'SELECT name, slug, description FROM wp_pod_tbl_categories WHERE parentid IN (3) ORDER BY name');
		$total_cats = $categories->getTotalRows();
		
		echo '<table border=0 width=480><tr><td width="50%" class="pro_contacttd">';
		$i = 0;
		$j = 0;
		if( $total_cats > 0 ) {
			while ( $categories->fetchRecord() ) {
				$i++;
				$j++;
				if ($i > ($total_cats/2)) {
					echo '</td><td width="50%" class="pro_contacttd">';
					$i = 0;
				}
				// set our variables
				$cat_name		= $categories->get_field('name');
				$cat_slug		= $categories->get_field('slug');
				$cat_desc		= $categories->get_field('description');
				
				$cat_checked 	= '';
				if (strpos($txt_service_interest, $cat_name) !== false) {
					$cat_checked = 'checked ';
				}
				
				echo <<<HEREDOC
				<input name="txt_service_interest[]" id="txt_service_interest_$j" type="checkbox" value="$cat_name" class="pro_checkbox" $cat_checked/> <label for="txt_service_interest_$j">$cat_name</label><br />
HEREDOC;
			}
		}
		echo '</td></tr></table>';
	
	echo <<<HEREDOC
		<div class="pro_contactrow">
			<div class="pro_contactlabel">I am interested in:<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;<br />&nbsp;</div>
			<div class="pro_contacttxt" style="margin-top: 10px;">
				<input name="txt_interest_weddings" type="checkbox" id="txt_interest_weddings" class="pro_checkbox" $txt_interest_weddings_c/> <label for="txt_interest_weddings">Weddings</label><br />
				<input name="txt_interest_social" type="checkbox" id="txt_interest_social" class="pro_checkbox" $txt_interest_social_c/> <label for="txt_interest_social">Social Events</label><br />
				<input name="txt_interest_corporate" type="checkbox" id="txt_interest_corporate" class="pro_checkbox" $txt_interest_corporate_c/> <label for="txt_interest_corporate">Corporate Events</label><br />
				<input name="txt_interest_mitzvahs" type="checkbox" id="txt_interest_mitzvahs" class="pro_checkbox" $txt_interest_mitzvahs_c/> <label for="txt_interest_mitzvahs">Mitzvahs</label><br />
				<input name="txt_interest_parties" type="checkbox" id="txt_interest_parties" class="pro_checkbox" $txt_interest_parties_c/> <label for="txt_interest_parties">Parties</label><br />&nbsp;
			</div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_referral">How did you hear about us:</label></div>
			<div class="pro_contacttxt"><input name="txt_referral" type="text" size="50" id="txt_referral" value="$txt_referral" /></div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_comments">Notes/Comments:</label></div>
			<div class="pro_contacttxt"><textarea name="txt_comments" rows="2" cols="20" id="txt_comments">$txt_comments</textarea></div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label>* Spam Protection</label></div>
			<div class="pro_contacttxt"><table><tr><td>$spam_html</td></tr></table></div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label>&nbsp;</label></div>
			<div class="pro_contacttxt"><input type="submit" id="pro_contact_submit" name="pro_contact_submit" value="REGISTER NOW"  /> <input type="submit" id="pro_contact_cancel" name="pro_contact_cencel" value="No Thanks" onclick="parent.$.fn.colorbox.close(); return false;" /></div>
		</div>
		<input type="hidden" name="submitted" value="1" />
	</form>
	<script language="javascript">
	<!--
		document.getElementById("txt_name").focus();
	 -->
	</script>
HEREDOC;
}
?>
</div>
</body>
</html>
