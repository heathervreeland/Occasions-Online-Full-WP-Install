<?php

/* Template Name: Make a Payment */

require('authnet/AuthnetAIM.class.php');
//require('authnet/AuthnetARB.class.php');

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
$title = 'Make a Payment';
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

	if (floatval($a['pay_amount']) < 2) {
		$err[] = 'You must enter a valid payment amount.';
	}
	else {
		$a['pay_amount'] = number_format(floatval($a['pay_amount']), 2, '.', '');
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
			$auth_total = $a['pay_amount'];
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
			$payment->setParameter("x_description", 'Occasions Advertiser Payment');
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
				
				/// skip database storage
				
				// SCREW PODSCMS... just do a plain ole SQL update
				//$sql = "UPDATE wp_pod_tbl_vendor_profiles SET ";
				//$sql_fields = array();
				//foreach ($profile_data as $key => $val) {
				//	$sql_fields[] .= "$key='$val'";
				//}
				//$sql .= implode(', ', $sql_fields);
				//$sql .= " WHERE vendor = $active_user_id" ;
				
				//pod_query($sql);
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
			// SEND THE ADMIN EMAIL
			// =====================================================

			$subject_admin = "Payment from: " . $full_name;
			
			$headers_admin = 'From: "Occasions Magazine" <do-not-reply@occasionsonline.com>' . "\r\n";
			$headers_admin .= 'X-Mailer: AO3/PHP/' . phpversion() . "\r\n";
		
			$message_admin = 
"$full_name has made a payment...

Payment Amount: \${$a['pay_amount']}
Authorization Code: {$profile_data['authorization_code']}
Transaction ID: {$profile_data['transaction_id']}

Occasions backend...
{$const['PAGE_HOME']}
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";

			mail(AO_ADMIN_EMAIL, $subject_admin, $message_admin, $headers_admin, AO_EMAIL_FLAGS);
			mail(AO_TECH_EMAIL, $subject_admin, $message_admin, $headers_admin, AO_EMAIL_FLAGS);
		
			// redirect to the profiles page
			header("Location: ". PAGE_PAYMENTMADE);
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
	<form action="<?php echo PAGE_PAYMENT; ?>" method="post" name="profileForm" id="profileForm" >

	<p>Thank you for advertising with Occasions Magazine and OccasionsOnline.com to promote your business. If you’re looking for opportunities to advertise your business where you can reach brides, bat mitzvahs and birthday parties too, you’ve come to the right place.</p>
	
	<p>Fill in the form below to make your payment.</p>
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
	<p class="vendor_label"><label for="payment_schedule">Enter Your Payment Amount</label></p>
	<p class="vendor_txt"><input name="pay_amount" type="text" id="pay_amount" size="40" class="required" value="<?php echo $a['pay_amount']; ?>" /> <i>(Format: 123.00)</i></p>
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
	<p><b>NOTE:</b> By clicking the "Make a Payment" button below you are agreeing to allow Occasions Magazine, Inc. to charge your credit card for the amount you have entered above.</p>
	</div>

	<p class="vendor_txt"><input class="vendor_checkbox" name="agree_terms" type="checkbox" id="agree_terms" value="1" /> <label for="agree_terms">I agree with the complete <a id="advertiser_tc_link" href="<?php echo PAGE_TERMS; ?>">Advertiser Terms &amp; Conditions</a></label><br />
	</p>

	<input type="hidden" name="submitted" value="1" />
	<p class="vendor_txt">&nbsp;<br /><input name="doSave" type="submit" id="vendor_submit" value="Make a Payment" /></p>

	<p>&nbsp;</p>
	</form>
</div>

<?php
//echo phpinfo();
?>
