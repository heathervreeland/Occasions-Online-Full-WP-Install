<?php

/* Template Name: Upgrade Profile */

require('authnet/AuthnetAIM.class.php');
require('authnet/AuthnetARB.class.php');

// let all of WP know that we are in the guide area
//ao_set_in_guide(true);

// this is the profile ID
//$pid = pods_url_variable(2);

// this is the "effective" user id, which is not neccessarily the user that is logged in, in the
//		case of admins, wo are able to mascarade as other users.
$active_user_id = get_active_user_id();

// array to hold our profile fields
$a = array();

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
}
$title = 'Business Profile Upgrade';
$profile_name = $profile->get_field('name');
$pid = $profile->get_field('id');

$result = pod_query("SELECT full_name, user_contact, user_email FROM ao_vendors WHERE id='$active_user_id'");
$row = mysql_fetch_assoc($result);
$user_email = $row['user_email'];
$user_contact = $row['user_contact'];
$full_name = $row['full_name'];

// *******************************************************************
// IS THE USER TRYING TO DO THE UPGRADE???
// *******************************************************************
if ($_POST['submitted'] == "1") {

	$success = false;
	
	// pull everything off of $_POST into our $a array.
	// we're specifically NOT using $_REQUEST here for a tiny bit of security.
	foreach($_POST as $key => $value) {
		$a[$key] = htmlspecialchars(stripslashes($value));
	}

	if ($a['payment_schedule'] == '1') {
		$subscription_total = 69.00;
		$subscription_length = 1;
		$subscription_unit = 'months';
		$start_date = date("Y-m-d", dateadd_months(time(), 1));
	}
	elseif ($a['payment_schedule'] == '2') {
		$subscription_total = 49.00;
		$subscription_length = 1;
		$subscription_unit = 'months';
		$start_date = date("Y-m-d", dateadd_months(time(), 1));
	}
	elseif ($a['payment_schedule'] == '3') {
		$subscription_total = 588.00;
		$subscription_length = 12;
		$subscription_unit = 'months';
		$start_date = date("Y-m-d", dateadd_months(time(), 12));
	}
	else {
		$err[] = 'You must select a payment schedule.';
	}

	if ($a['card_type'] == '') {
		$err[] = 'You must select a credit card type.';
	}
	if ($a['card_number'] != '' && $a['card_type'] != '' && cc_check_type($a['card_number']) != $a['card_type']) {
		$err[] = 'The credit card type does not match the credit card number you entered.';
	}

	if ($a['card_name_first'] == '' || $a['card_name_last'] == '') {
		$err[] = 'You must enter the first and last name on your credit card.';
	}
	if (($a['address'] == '') || ($a['city'] == '') || ($a['state'] == '') || ($a['zipcode'] == '')) {
		$err[] = 'You must enter the complete billing address for your credit card.';
	}
	if ($a['card_number'] == '') {
		$err[] = 'You must enter your credit card number.';
	}
	elseif (cc_check_number($a['card_number']) == 0) {
		$err[] = 'You must enter a valid credit card number.';
	}
	if ($a['card_cvv'] == '') {
		$err[] = 'You must enter your credit card security code.';
	}
	if ($a['agree_terms'] != '1') {
		$err[] = 'You must agree to the Advertiser Terms &amp; Conditions.';
	}
	
	if (empty($err)) {

		// *******************************************************************
		// GOOD TO GO -- RUN THE INITIAL PAYMENT
		// *******************************************************************

		$success = false;
		try
		{
			$user_id = $active_user_id;
		 
			$creditcard = $a['card_number'];
			$expiration1 = $a['card_exp_month'] . '-' . $a['card_exp_year']; // for the first charge
			$expiration2 = $a['card_exp_year'] . '-' . $a['card_exp_month']; // for the subscription
			$auth_total = $subscription_total;
			$cvv        = $a['card_cvv'];
			$invoice    = strval(time());
			$tax        = 0.00;

			$payment = new AuthnetAIM('7jE3f8DhGK6', '9rkC8QgF349Jg48k');
			$payment->setTransaction($creditcard, $expiration1, $auth_total, $cvv, $invoice, $tax);
			$payment->setTransactionType("AUTH_CAPTURE");
			$payment->setParameter("x_duplicate_window", 180);
			$payment->setParameter("x_cust_id", $active_user_id);
			$payment->setParameter("x_customer_ip", $_SERVER['REMOTE_ADDR']);
			$payment->setParameter("x_email_customer", TRUE);
			$payment->setParameter("x_first_name", $a['card_name_first']);
			$payment->setParameter("x_last_name", $a['card_name_last']);
			$payment->setParameter("x_address", $a['address']);
			$payment->setParameter("x_city", $a['city']);
			$payment->setParameter("x_state", $a['state']);
			$payment->setParameter("x_zip", $a['zipcode']);
			$payment->setParameter("x_ship_to_first_name", $a['card_name_first']);
			$payment->setParameter("x_ship_to_last_name", $a['card_name_last']);
			$payment->setParameter("x_ship_to_address", $a['address']);
			$payment->setParameter("x_ship_to_city", $a['city']);
			$payment->setParameter("x_ship_to_state", $a['state']);
			$payment->setParameter("x_ship_to_zip", $a['zipcode']);
			$payment->setParameter("x_description", 'Occasions Initial Authorization');
			//$payment->setParameter("x_test_request", TRUE);
			
			$payment->process();
		 
			if ($payment->isApproved())
			{
				// Get info from Authnet to store in the database
				$authorization_code	= $payment->getAuthCode();
				$avs_result     	= $payment->getAVSResponse(); // not saved at this time
				$cvv_result			= $payment->getCVVResponse(); // not saved at this time
				$transaction_id		= $payment->getTransactionID();
		 
				// Do stuff with this.
				//$err[] = "Amount: $auth_total";
				//$err[] = "Invoice #: $invoice";
				//$err[] = "Auth Code: $approval_code";
				//$err[] = "AVS Result: $avs_result";
				//$err[] = "CVV Result: $cvv_result";
				//$err[] = "Transaction ID: $transaction_id";
				
				// TODO: in case of errors, email the above info to the admin
				
				
				// *******************************************************************
				// NOW WE CAN SET UP THE RECURRING BILLING SUBSCRIPTION
				// *******************************************************************
				$arb = new AuthnetARB();
				
				// Set recurring billing variables
				$arb->setParameter('interval_length', $subscription_length);
				$arb->setParameter('interval_unit', $subscription_unit);
				$arb->setParameter('totalOccurrences', 9999);				
				$arb->setParameter('startDate', $start_date);
		
				// Set recurring billing parameters
				$arb->setParameter('amount', $subscription_total);
				$arb->setParameter('cardNumber', $creditcard);
				$arb->setParameter('expirationDate', $expiration2);
				$arb->setParameter('firstName', $a['card_name_first']);
				$arb->setParameter('lastName', $a['card_name_last']);
				$arb->setParameter('address', $a['address']);
				$arb->setParameter('city', $a['city']);
				$arb->setParameter('state', $a['state']);
				$arb->setParameter('zip', $a['zipcode']);
				$arb->setParameter('subscrName', $profile_name);
				$arb->setParameter('orderInvoiceNumber', $invoice);
				$arb->setParameter('orderDescription', 'Occasions Online Subscription');
				
		
				// Create the recurring billing subscription
				$arb->createAccount();
		
				// If successful let's get the subscription ID
				if ($arb->isSuccessful())
				{
					$sub_id = $arb->getSubscriberID();
					//$err[] = "Subscriber ID: $sub_id";
				}
				else {
					$err[] = "Response Code: " . $arb->getResponseCode();
					$err[] = "Response Text: " . $arb->getResponse();
					
					// TODO: in case of errors, email the above info to the admin
					// in this case we keep going since the initial charge was created
					// successfully but report the error to the admin.
				}
				
				// *******************************************************************
				// STORE THE RESULTS IN THE DATABASE AND UPGRADE THEIR ACCOUNT
				// *******************************************************************
				// all clear to save the subscription data to the database
				$profile_data = array();
				$profile_data['card_num'] = substr($creditcard, -4); // last four digits ONLY
				$profile_data['card_exp_month'] = $a['card_exp_month'];
				$profile_data['card_exp_year'] = $a['card_exp_year'];
				$profile_data['subscriber_id'] = $sub_id;
				$profile_data['active'] = 1;
				$profile_data['profile_type'] = 'Platinum';
				$profile_data['payment_amount'] = $subscription_total;
				$profile_data['payment_plan'] = iif($a['payment_schedule'] == '3', 'Yearly', 'Monthly');
				$profile_data['subscription_plan'] = iif($a['payment_schedule'] == '1', 'Monthly', 'Yearly');
				$profile_data['renewal_month'] = date("m", time());
				$profile_data['renewal_day'] = date("d", time());
				$profile_data['authorization_code'] = $authorization_code;
				$profile_data['transaction_id'] = $transaction_id;
				
				//$subscription_unit = 'months';
				//$start_date = date("Y-m-d", strtotime("+ 1 year"));
				
				// SCREW PODSCMS... just do a plain ole SQL update
				$sql = "UPDATE wp_pod_tbl_vendor_profiles SET ";
				$sql_fields = array();
				foreach ($profile_data as $key => $val) {
					$sql_fields[] .= "$key='$val'";
				}
				$sql .= implode(', ', $sql_fields);
				$sql .= " WHERE vendor = $active_user_id" ;
				
				pod_query($sql);
				$success = true;
		
			}
			else if ($payment->isDeclined())
			{
				// Get reason for the decline from the bank. This always says,
				// "This credit card has been declined". Not very useful.
				$reason = $payment->getResponseText();
				$avs_result     = $payment->getAVSResponse(); // not used at this time
				$cvv_result     = $payment->getCVVResponse(); // not used at this time
		 
				// Politely tell the customer their card was declined
				// and to try a different form of payment.
				$err[] = "There was an error processing this credit card. The response from the bank was: $reason";
				//$err[] = "AVS Result: $avs_result";
				//$err[] = "CVV Result: $cvv_result";

				// TODO: in case of errors, email the above info to the admin
			}
			else if ($payment->isError())
			{
				// Get the error number so we can reference the Authnet
				// documentation and get an error description.
				$error_number  = $payment->getResponseSubcode();
				$error_message = $payment->getResponseText();
		 
				// OR
		 
				// Capture a detailed error message. No need to refer to the manual
				// with this one as it tells you everything the manual does.
				$full_error_message =  $payment->getResponseMessage();
				$avs_result     = $payment->getAVSResponse();
				$cvv_result     = $payment->getCVVResponse();
		 
				$err[] = "Error $error_number: $full_error_message";
				//$err[] = "AVS Result: $avs_result";
				//$err[] = "CVV Result: $cvv_result";
				
				// TODO: in case of errors, email the above info to the admin

				// We can tell what kind of error it is and handle it appropriately.
				if ($payment->isConfigError())
				{
					// We misconfigured something on our end.
					//$err[] = "Config Error";
				}
				else if ($payment->isTempError())
				{
					// Some kind of temporary error on Authorize.Net's end. 
					// It should work properly "soon".
					//$err[] = "Temporary Error";
				}
				else
				{
					// All other errors.
					//$err[] = "Other Error";
				}
		 
				// TODO: in case of errors, email the above info to the admin

				// Report the error to someone who can investigate it
				// and hopefully fix it
		 
				// Notify the user of the error and request they contact
				// us for further assistance
			}
		}
		catch (AuthnetAIMException $e)
		{
			echo 'There was an error processing the transaction. Here is the error message: ';
			echo $e->__toString();
		}

		if ($success) {
		
// =====================================================
// SEND THE ADVERTISER WELCOME EMAIL
// =====================================================
$message = <<<HEREDOC
<html><body>
<p>Dear $user_contact,</p>
<p>Welcome to the Occasions Magazine Vendor Guide. Let&rsquo;s start building your business! Below are few suggestions we&rsquo;ve prepared to help you get the most out of your advertising experience.</p>
<p><b>YOUR PROFILE</b></p>
<p>Your business profile is now live on AtlantaOccasions.com. To attract readers to your profile and enhance your listing, log on using the ID and Password you created to add photos, a detailed description of your services, contact information and link to your website, blog and social networks.</p>
<p><b>USER REVIEWS</b></p>
<p>User reviews are the best third party testimonial your business has. We encourage you to contact your past customers and have them rate and review you on AtlantaOccasions.com. Positive reviews help new customers choose you over the competition! A link to your business profile on your website that reads &ldquo;Rate and Review us on AtlantaOccasions.com&rdquo; helps increase those reviews.</p>
<p><b>LINK TO ATLANTAOCCASIONS.COM</b></p>
<p>Now that your business is featured in on AtlantaOccasions.com you&rsquo;ll want to tell all of your clients. Place a link to your business profile on AtlantaOccasions.com on your website. You can use the advertiser badges available here.</p>
<p><b>NETWORKING</b></p>
<p>Discounted tickets to our monthly networking events are yet another feature Occasions Magazine advertisers enjoy. Keep an eye out of our invitations via email each month. The events we host are the third Wednesday of each month at various event venues. Advertiser&rsquo;s tickets are just $25 to attend.</p>
<p><b>USER LEADS</b></p>
<p>As an advertiser of Occasions Magazine you have access to readers who have registered on the AtlantaOccasions.com site. We don&rsquo;t require visitors to register before viewing ads, so the size of the lead list is in no way relative to the amount of visitors to the site, it&rsquo;s just a list of those people who actually want to be contacted by vendors. Don&rsquo;t miss out... get in touch with these people!<br />
PS - sharing this information with non-advertisers is NOT COOL so don&rsquo;t do it.</p>
<p><b>SUBMISSIONS</b></p>
<p>We are seeking stylish real events to feature and images of inspiring details for our readers. Happenings like weddings, bridal showers, birthday parties, bar/bat mitzvahs, commitment ceremonies, corporate events, grand openings, charity galas, fundraisers and more. If your event included stylish details, coordinating components, and took place within the metro Atlanta area we want to see it! If it did not take place within Metro Atlanta, but is still a stylish Occasion, send it our way to be considered for our Inspiration Department. Please note, we only accept professionally photographed events that have not been published by another website or magazine.</p>
<p><b>PRINT ADVERTISING</b></p>
<p>Occasions Magazine (AtlantaOccasions.com) is the one and only media of if it&rsquo;s kind in Metro Atlanta. And to boot, our print edition reaches thousands of Metro Atlanta brides and families planning events. To receive a copy of our print media kit and schedule a time to discuss enhancing your coverage in the next issue of Occasions Magazine, contact us at <a href="mailto:info@atlantaoccasions.com">info@atlantaoccasions.com</a>.</p>
<p><b>CUSTOMER SUPPORT</b></p>
<p>We&rsquo;re here for you when you need us. For questions regarding your profile or any other comments and concerns, please email <a href="mailto:support@atlantaoccasions.com">support@atlantaoccasions.com</a> and a member of our staff will respond within 24 hours.</p>
<p>We&rsquo;re excited to get to know you better. Feel free to contact us to so we can answer any questions you may have. &nbsp;</p>
<p>Warmest Regards,</p>
<p>Heather Vreeland<br />
Publisher and Editor-in-Chief<br />
Occasions Magazine&nbsp;</p>
<p>______________________________________________________<br />
THIS IS AN AUTOMATED RESPONSE. <br />
***DO NOT RESPOND TO THIS EMAIL****</p>
</body></html>
HEREDOC;

$subject = "Occasions Magazine: Congratulations";
$subject_admin = "New Occasions Upgrade: " . $full_name;

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: "Occasions Magazine" <do-not-reply@atlantaoccasions.com>' . "\r\n";
$headers .= 'X-Mailer: AO3/PHP/' . phpversion() . "\r\n";

$headers_admin = 'From: "Occasions Magazine" <do-not-reply@atlantaoccasions.com>' . "\r\n";
$headers_admin .= 'X-Mailer: AO3/PHP/' . phpversion() . "\r\n";

$message_admin = 
"$full_name has upgraded to a business profile...

Subscription Amount: $subscription_total
Payment Plan: {$profile_data['payment_plan']}
Subscription Plan: {$profile_data['subscription_plan']}
Renewal Month/Day: {$profile_data['renewal_month']}/{$profile_data['renewal_day']}
Authorization Code: {$profile_data['authorization_code']}
Transaction ID: {$profile_data['transaction_id']}

Occasions backend...
{$const['PAGE_HOME']}
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";

mail($user_email, $subject, $message, $headers);
mail(AO_ADMIN_EMAIL, $subject_admin, $message_admin, $headers_admin);
mail('lisa@atlantaoccasions.com', $subject_admin, $message_admin, $headers_admin);
mail(AO_TECH_EMAIL, $subject_admin, $message_admin, $headers_admin);

			// redirect to the profiles page
			header("Location: ". PAGE_UPGRADED);
			exit;
		}
		else {
			// let execution continue so that the errors can be displayed
		}
	}
}


// *******************************************************************
// START SPITTIN' OUT THE PAGE
// *******************************************************************
//get_header();

	echo "<h2>$title</h2>";
?>
	<div class="post">
	<form action="<?php echo PAGE_UPGRADE; ?>" method="post" name="profileForm" id="profileForm" >

	<p>Thank you for considering Occasions Magazine (AtlantaOccasions.com) to promote your business. If you’re looking for opportunities to advertise your business where you can reach brides, bat mitzvahs and birthday parties too, you’ve come to the right place.</p>
	
	<p>You can continue adding information and photographs to your listing, but the information in your courtesy account won’t display live on the site until you upgrade to a business profile.  Upgrading is easy and the only way to fully experience the benefits of membership and inclusion in the Occasions Magazine Vendor Guide. And it’s so worth it.  <a target="_blank" href="/testimonials">Read our current advertiser testimonials here</a>.</p>
	
	<p>Occasions Magazine advertisers enjoy online business profiles that include...</p>
	<ul>
		<li>Unlimited Photos and Video</li>
		<li>1000 word description of your services</li>
		<li>Links to website, blog and social networks</li>
		<li>User Reviews and rating system</li>
		<li>Leads List</li>
		<li>The ability to post events to our online calendar</li>
		<li>24/7 control and accessibility of their online ads</li>
		<li>Reports on views and click through rates of your business profile</li>
		<li>Email Inquiries</li>
		<li>Discounted tickets to networking events</li>
		<li>Optional map of your location</li>
	</ul>
	<p>The Online Business Profile Advertising rates are:</p>
	<div style="border-bottom: 1px dotted #CCC;">&nbsp;</div>

<?php
if(!empty($err))  {
	echo '<p class="error_msg">';
	foreach ($err as $e) {
		echo "$e<br />";
	}
	echo "</p>";
}
?>
	<p>&nbsp;</p>
	<p class="vendor_label"><label for="payment_schedule">Select a Payment Schedule</label></p>
	<p class="vendor_txt">
		<input class="vendor_checkbox" name="payment_schedule" type="radio" id="payment_schedule1" value="1" <?php echo iif($a['payment_schedule'] == '1', 'checked', ''); ?> /> <label for="payment_schedule1">Month-to-Month - <b>$69 Per Month</b></label><br />
		<input class="vendor_checkbox" name="payment_schedule" type="radio" id="payment_schedule2" value="2" <?php echo iif($a['payment_schedule'] == '2', 'checked', ''); ?> /> <label for="payment_schedule2">Annual Commitment - <b>$49 Per Month, Paid Monthly</b></label><br />
		<input class="vendor_checkbox" name="payment_schedule" type="radio" id="payment_schedule3" value="3" <?php echo iif($a['payment_schedule'] == '3', 'checked', ''); ?> /> <label for="payment_schedule3">Annual Commitment - <b>$588, Paid Yearly</b></label><br />
	</p>
	
	<p>&nbsp;</p>
	<p class="vendor_label"><label for="payment_schedule">Credit Card Information</label></p>
	<p class="vendor_desc">Select the credit card type:</p>
	<p class="vendor_txt">
		<input class="vendor_checkbox" name="card_type" type="radio" id="card_typev" value="v" <?php echo iif($a['card_type'] == 'v', 'checked', ''); ?> /> <label for="card_typev">VISA</label><br />
		<input class="vendor_checkbox" name="card_type" type="radio" id="card_typem" value="m" <?php echo iif($a['card_type'] == 'm', 'checked', ''); ?> /> <label for="card_typem">Mastercard</label><br />
		<input class="vendor_checkbox" name="card_type" type="radio" id="card_typea" value="a" <?php echo iif($a['card_type'] == 'a', 'checked', ''); ?> /> <label for="card_typea">American Express</label><br />
		<input class="vendor_checkbox" name="card_type" type="radio" id="card_typed" value="d" <?php echo iif($a['card_type'] == 'd', 'checked', ''); ?> /> <label for="card_typed">Discover</label><br />
	</p>
	
	<p class="vendor_desc">First name (as it appears on your credit card)</p>
	<p class="vendor_txt"><input name="card_name_first" type="text" id="card_name_first" size="40" class="required" value="<?php echo $a['card_name_first']; ?>" /></p>

	<p class="vendor_desc">Last name (as it appears on your credit card)</p>
	<p class="vendor_txt"><input name="card_name_last" type="text" id="card_name_last" size="40" class="required" value="<?php echo $a['card_name_last']; ?>" /></p>

	<p class="vendor_desc">Billing Address</p>
	<p class="vendor_txt"><input name="address" type="text" id="address" size="40" value="<?php echo $a['address']; ?>" /></p>
	
	<p class="vendor_desc">Billing City / State / Zipcode:</p>
	<p class="vendor_txt"><input name="city" type="text" id="city" size="40" value="<?php echo $a['city']; ?>" /> <input name="state" type="text" id="state" size="40" value="<?php echo $a['state']; ?>" /> <input name="zipcode" type="text" id="zipcode" size="40" value="<?php echo $a['zipcode']; ?>" /></p>
	
	<p class="vendor_desc">Card number:</p>
	<p class="vendor_txt"><input name="card_number" type="text" id="card_number" size="40" class="required" value="<?php echo $a['card_number']; ?>" /></p>

	<p class="vendor_desc">Card Expiration Date:</p>
	<p class="vendor_txt">
		<select class="vendor_select" name="card_exp_month" id="card_exp_month">
		<?php
		$a_months = array(
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

	<p class="vendor_desc">Security  Code:</p>
	<p class="vendor_txt"><input name="card_cvv" type="text" id="card_cvv" size="40" class="required" value="<?php echo $a['card_cvv']; ?>" /><br /><img src="/media/images/cvv.jpg" /></p>

	<div class="upgrade_terms">
	<p><b>NOTE:</b> This account does not expire. It will auto-recur and stay in effect until canceled. There is no need to renew unless you receive an email from us stating that your credit card has expired or was declined. All subscriptions are automatically billed on a monthly or annual basis, depending on whether you have a monthly or yearly account.</p>
	
	<p>You are agreeing to be charged for a subscription service. You will be billed now, and will be automatically billed again periodically from today's signup date until you cancel the subscription. Month-to-Month accounts may cancel the subscription from the advertiser control panel accessible when you log into the website. If you cancel within a billing cycle, you will not be billed for the following period.  Annual contracts may cancel the advertising contract at the end of their 12-month agreement.</p>
	</div>

	<p class="vendor_txt"><input class="vendor_checkbox" name="agree_terms" type="checkbox" id="agree_terms" value="1" /> <label for="agree_terms">I agree with the above NOTE and the complete <a id="advertiser_tc_link" href="<?php echo PAGE_TERMS; ?>">Advertiser Terms &amp; Conditions</a></label><br />
	</p>

	<input type="hidden" name="submitted" value="1" />
	<p class="vendor_txt">&nbsp;<br /><input name="doSave" type="submit" id="vendor_submit" value="Upgrade My Profile" /></p>

	<p>&nbsp;</p>
	</form>
</div>

<?php
//echo phpinfo();
?>
