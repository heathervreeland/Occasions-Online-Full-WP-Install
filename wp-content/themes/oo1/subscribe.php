<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
/*
Template Name: Subscribe
*/

/*

Here's the process
1) Landing page ( $landing == true; - this is the default )
	This page contains the entire product line. The user enters the 
	quantities they want and proceed to checkout...

2) Checkout page ( $_POST['checkout'] == "1" )
	This is where the user enters their payment information. From 
	this page, one of three things can happen:

	a) Cart update ( (isset($_POST['subscription_update'])) )
		The user is updating quantities and we are returning to 
		the checkout page.

	b) Cart add ( (isset($_POST['subscription_add'])) )
		The user wants to go back to the product page.

	c) The user is completing the order...

3) Process the payment ( $_POST['process'] == "1" )
	Assuming everything is processed successfully, the following 
	occurs:
	
	a) The user is sent an email with summary information about 
		the order.
	b) Various Occasions users are sent an email too.
	c) The thank you page with the order summary is displayed.

While the order is in process, the cart contents array is stored in 
a serialized form in a cookie. When the order is processed and 
payment is made, the cookie is cleared and everything is stored in 
the database in the following fields:

	ID
	TIME STAMP
	CUSTOMER NAME
	ORDER TOTAL
	SERIALIZED ARRAY $a_desc	cart product descriptions
	SERIALIZED ARRAY $a_cart	cart contents
	SERIALIZED ARRAY $a			transaction information

*/


require('vendor_access/authnet/AuthnetAIM.class.php');
require('vendor_access/authnet/AuthnetARB.class.php');

ao_set_require_ssl(true);
$landing = true;

$a = array();

// create our "cart" with default values
$a_cart = array(
	'annual'	=> 1,
	'sf2009'	=> 0,
	'sf2009b'	=> 0, // 'b' stands for BOX
	'ws2010'	=> 0,
	'ws2010b'	=> 0,
	'sf2010'	=> 0,
	'sf2010b'	=> 0,
	'w2010'		=> 0, // this is actually supposed to be w2011, but oh well
	'w2010b'	=> 0, // this is actually supposed to be w2011b
	's2011'		=> 0,
	's2011b'	=> 0,
	'f2011'		=> 0,
	'f2011b'	=> 0
);

// descriptions
$a_desc = array(
	'annual'	=> array('count' => 0,	'desc' => 'Occasions Magazine Annual Print Subscription', 'cost' => 16.95),
	'sf2009'	=> array('count' => 1,	'desc' => 'Summer/Fall 2009 Issue', 'cost' => 5.99),
	'sf2009b'	=> array('count' => 10,	'desc' => 'Summer/Fall 2009 Issue (box of 10)', 'cost' => 50.00),
	'ws2010'	=> array('count' => 1,	'desc' => 'Winter/Spring 2010 Issue', 'cost' => 5.99),
	'ws2010b'	=> array('count' => 10,	'desc' => 'Winter/Spring 2010 Issue (box of 10)', 'cost' => 50.00),
	'sf2010'	=> array('count' => 1,	'desc' => 'Summer/Fall 2010 Issue', 'cost' => 5.99),
	'sf2010b'	=> array('count' => 10,	'desc' => 'Summer/Fall 2010 Issue (box of 10)', 'cost' => 50.00),
	'w2010'		=> array('count' => 1,	'desc' => 'Winter 2011 Issue', 'cost' => 5.99),
	'w2010b'	=> array('count' => 10,	'desc' => 'Winter 2011 Issue (box of 10)', 'cost' => 50.00),
	's2011'		=> array('count' => 1,	'desc' => 'Summer 2011 Issue', 'cost' => 5.99),
	's2011b'	=> array('count' => 10,	'desc' => 'Summer 2011 Issue (box of 10)', 'cost' => 50.00),
	'f2011'		=> array('count' => 1,	'desc' => 'Fall 2011 Issue', 'cost' => 5.99),
	'f2011b'	=> array('count' => 10,	'desc' => 'Fall 2011 Issue (box of 10)', 'cost' => 50.00)
);

// then check to see if we have a shopping cart to load
if ($_COOKIE['subscription']) {
	$a_cart = unserialize(base64_decode($_COOKIE['subscription']));
}

// pull everything off of $_POST into our $a array.
// we're specifically NOT using $_REQUEST here for a tiny bit of security.
foreach($_POST as $key => $value) {
	$a[$key] = htmlspecialchars(stripslashes($value));
}

// check to see if we are updating the cart contents...
if (isset($_POST['subscription_update'])) {
	$_POST['process'] = 0;
	$_POST['checkout'] = 1;
	$landing = false;
}
elseif (isset($_POST['subscription_add'])) {
	$_POST['process'] = 0;
	$_POST['checkout'] = 0;
	$landing = true;
}

if ($_POST['process'] == "1") {
	// *******************************************************************
	// PROCESS PAYMENT
	// *******************************************************************
	$success = false;
	
	// at this point, the cart should have been retieved from the cookie, not $_POST[]
	if ($a_cart['annual'] == '1') {
		$subscription_total = 16.95;
		$subscription_length = 12;
		$subscription_unit = 'months';
		$start_date = date("Y-m-d", dateadd_months(time(), 12));
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
	if (($a['name_first_ship'] == '') || ($a['name_last_ship'] == '') || ($a['address_ship'] == '') || ($a['city_ship'] == '') || ($a['state_ship'] == '') || ($a['zipcode_ship'] == '')) {
		$err[] = 'You must complete the shipping information for this order.';
	}
	
	if (empty($err)) {

		// *******************************************************************
		// GOOD TO GO -- RUN THE FULL CHARGE
		// *******************************************************************

		$success = false;
		try
		{
			$user_id = $active_user_id;
		 
			$creditcard = $a['card_number'];
			$expiration1 = $a['card_exp_month'] . '-' . $a['card_exp_year']; // for the first charge
			$expiration2 = $a['card_exp_year'] . '-' . $a['card_exp_month']; // for the subscription
			$cvv        = $a['card_cvv'];
			$invoice    = strval(time());
			if ((strtolower($a['state_ship']) == 'ga') || (strtolower($a['state_ship']) == 'georgia')) {
				$a['ordertotal'] = $a['ordertotal'] - $a['taxtotal'];
				$a['taxtotal'] = 0;
			}
			$tax        = $a['taxtotal'];
			$auth_total = $a['ordertotal'];

			$payment = new AuthnetAIM('7jE3f8DhGK6', '9rkC8QgF349Jg48k');
			$payment->setTransaction($creditcard, $expiration1, $auth_total, $cvv, $invoice, $tax);
			$payment->setTransactionType("AUTH_CAPTURE");
			$payment->setParameter("x_duplicate_window", 180);
			$payment->setParameter("x_cust_id", 'webuser');
			$payment->setParameter("x_customer_ip", $_SERVER['REMOTE_ADDR']);
			$payment->setParameter("x_email_customer", TRUE);
			$payment->setParameter("x_first_name", $a['card_name_first']);
			$payment->setParameter("x_last_name", $a['card_name_last']);
			$payment->setParameter("x_address", $a['address']);
			$payment->setParameter("x_city", $a['city']);
			$payment->setParameter("x_state", $a['state']);
			$payment->setParameter("x_zip", $a['zipcode']);
			$payment->setParameter("x_ship_to_first_name", $a['name_first_ship']);
			$payment->setParameter("x_ship_to_last_name", $a['name_last_ship']);
			$payment->setParameter("x_ship_to_address", $a['address_ship']);
			$payment->setParameter("x_ship_to_city", $a['city_ship']);
			$payment->setParameter("x_ship_to_state", $a['state_ship']);
			$payment->setParameter("x_ship_to_zip", $a['zipcode_ship']);
			$payment->setParameter("x_description", 'Occasions Magazine Print Order');
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
				
				if ($a_cart['annual'] == '1') {
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
					$arb->setParameter('subscrName', $a['card_name_first'] . ' '. $a['card_name_last']);
					$arb->setParameter('orderInvoiceNumber', $invoice);
					$arb->setParameter('orderDescription', 'Occasions Magazine Annual Subscription');
					
			
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
				}
				// *******************************************************************
				// STORE THE RESULTS IN THE DATABASE
				// *******************************************************************
				// all clear to save the subscription data to the database
				//$profile_data = array();
				//$profile_data['card_num'] = substr($creditcard, -4); // last four digits ONLY
				//$profile_data['card_exp_month'] = $a['card_exp_month'];
				//$profile_data['card_exp_year'] = $a['card_exp_year'];
				//$profile_data['subscriber_id'] = $sub_id;
				//$profile_data['active'] = 1;
				//$profile_data['profile_type'] = 'Platinum';
				//$profile_data['payment_amount'] = $subscription_total;
				//$profile_data['payment_plan'] = iif($a['payment_schedule'] == '3', 'Yearly', 'Monthly');
				//$profile_data['subscription_plan'] = iif($a['payment_schedule'] == '1', 'Monthly', 'Yearly');
				//$profile_data['renewal_month'] = date("m", time());
				//$profile_data['renewal_day'] = date("d", time());
				//$profile_data['authorization_code'] = $authorization_code;
				//$profile_data['transaction_id'] = $transaction_id;
				
				$success = true;
				
				// obliterate the card number, but keep the last 4 digits...
				$a['card_number'] = substr($a['card_number'], -4);
				
				// store the cart, descriptions and transaction information in the database
				$safe_desc = base64_encode(serialize($a_desc));
				$safe_cart = base64_encode(serialize($a_cart));
				$safe_tran = base64_encode(serialize($a));
				
				// SCREW PODSCMS... just do a plain ole SQL insert
				$sql = "INSERT INTO ao_subscriptions VALUES(NULL, NULL, '{$a['card_name_first']} {$a['card_name_last']}', {$a['ordertotal']}, '$safe_desc', '$safe_cart', '$safe_tran');";
				pod_query($sql);
		
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
			// SEND THE NOTIFICATION EMAIL
			// =====================================================
			
			$card4 = strtoupper($a['card_type']) . ' xxxx-' . substr($a['card_number'], -4);
			$cardexp = $a['card_exp_month'] . '/' . $a['card_exp_year'];
			
			$message = <<<HEREDOC
<html><body>
<p>The following order was placed online:</p>
<p><b>Billing Information:</b><br />
{$a['card_name_first']} {$a['card_name_last']}<br />
{$a['address']}<br />
{$a['city']}, {$a['state']} {$a['zipcode']}
</p>
<p><b>Shipping Information:</b><br />
{$a['name_first_ship']} {$a['name_last_ship']}<br />
{$a['address_ship']}<br />
{$a['city_ship']}, {$a['state_ship']} {$a['zipcode_ship']}
</p>
<p>Card Number: $card4 exp: $cardexp<br />
Auth Code: $authorization_code<br />
Tran ID: $transaction_id
</p>
<p>Please allow 4-6 weeks for delivery of your first issue.</p>
HEREDOC;

			ob_start();
?>

<table class="sub-table" cellspacing="0" cellpadding="4" border="1">
<?php
			$ordertotal = 0;
			$mags = 0;
			foreach($a_cart as $key => $value) {
				if ($value > 0 && $value != 'x') {
					$linetotal = $a_desc[$key]['cost'] * $value;
					$ordertotal += $linetotal;
					$mags += $a_desc[$key]['count'] * $value;
?>
	<tr>
		<td class="sub-col1 cell">Qty: <?php echo $value; ?></td>
		<td class="sub-col2 cell"><?php echo $a_desc[$key]['desc']; ?></td>
		<td class="sub-col3 cell">$<?php echo number_format($a_desc[$key]['cost'], 2, '.', ','); ?> / ea</td>
		<td class="sub-col4 cell">$<?php echo number_format($linetotal, 2, '.', ','); ?></td>
	</tr>
<?php
				}
			}
			if ((strtolower($a['state_ship']) == 'ga') || (strtolower($a['state_ship']) == 'georgia')) {
				$taxtotal = ((int)(((($ordertotal * 0.07) * 1000) + 5) / 10) / 100);
			}
			else {
				$taxtotal = 0;
			}
			// figure out shipping
			// boxes have 10 in each box
			$boxes = (int) ($mags / 10);
			// boxes are $14 each
			$shippingtotal = $boxes * 14;
			// this is how many we have left over from our boxes
			$mags -= ($boxes * 10);
			if ($mags > 1) {
				$shippingtotal += 10;
			}
			elseif ($mags == 1) {
				$shippingtotal += 5;
			}
			$ordertotal += ($shippingtotal + $taxtotal);
?>
	<tr>
		<td class="sub-col12" colspan="2"></td>
		<td class="sub-col3">7% Tax (GA only)</td>
		<td class="sub-col4 cell">$<?php echo number_format($taxtotal, 2, '.', ','); ?></td>
	</tr>
	<tr>
		<td class="sub-col12" colspan="2"></td>
		<td class="sub-col3">Shipping</td>
		<td class="sub-col4 cell">$<?php echo number_format($shippingtotal, 2, '.', ','); ?></td>
	</tr>
	<tr>
		<td class="sub-col12" colspan="2"></td>
		<td class="sub-col3 bold">TOTAL</td>
		<td class="sub-col4 bold">$<?php echo number_format($ordertotal, 2, '.', ','); ?></td>
	</tr>
</table>

<?php 
			$message .= ob_get_clean();
			$message .= <<<HEREDOC
<p>______________________________________________________<br />
THIS IS AN AUTOMATED RESPONSE. <br />
***DO NOT RESPOND TO THIS EMAIL****</p>
</body></html>
HEREDOC;

			$subject = "Occasions Magazine Print Subscription/Order from " . $a['card_name_first'] . " ". $a['card_name_last'];
			
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: "Occasions Magazine" <do-not-reply@occasionsonline.com>' . "\r\n";
			$headers .= 'X-Mailer: OO1/PHP/' . phpversion() . "\r\n";
			
			mail(AO_ADMIN_EMAIL, $subject, $message, $headers, AO_EMAIL_FLAGS);
			mail(AO_OFFICE_EMAIL, $subject, $message, $headers, AO_EMAIL_FLAGS);
			mail(AO_TECH_EMAIL, $subject, $message, $headers, AO_EMAIL_FLAGS);
			

			// =====================================================
			// NOW SPIT OUT THE RESULTS PAGE TO THE CUSTOMER
			// =====================================================
			
			get_header();
?>
<div class="ruled left"><span class="head2 ruled-text-left">Subscription/Order Complete</span></div>
<p>Thank you for your order!</p>
<p>Please allow 4-6 weeks for delivery of your first issue.</p>
<p>If you have any questions or need to get in touch with us, please email <a href="mailto:info@occasionsonline.com">info@occasionsonline.com</a></p>
<p>PLEASE PRINT THIS PAGE FOR YOUR RECORDS.</p>
<?php

			$customer_info = <<<HEREDOC
<p><b>Billing Information:</b><br />
{$a['card_name_first']} {$a['card_name_last']}<br />
{$a['address']}<br />
{$a['city']}, {$a['state']} {$a['zipcode']}
</p>
<p>CC #: $card4 exp: $cardexp</p>
<p><b>Shipping Information:</b><br />
{$a['name_first_ship']} {$a['name_last_ship']}<br />
{$a['address_ship']}<br />
{$a['city_ship']}, {$a['state_ship']} {$a['zipcode_ship']}
</p>
HEREDOC;

			echo $customer_info;

?>
<table class="sub-table" cellspacing="1" cellpadding="0" border="0">
<?php
			$ordertotal = 0;
			$mags = 0;
			foreach($a_cart as $key => $value) {
				if ($value > 0 && $value != 'x') {
					$linetotal = $a_desc[$key]['cost'] * $value;
					$ordertotal += $linetotal;
					$mags += $a_desc[$key]['count'] * $value;
?>
	<tr>
		<td class="sub-col1 cell">Qty: <?php echo $value; ?></td>
		<td class="sub-col2 cell"><?php echo $a_desc[$key]['desc']; ?></td>
		<td class="sub-col3 cell">$<?php echo number_format($a_desc[$key]['cost'], 2, '.', ','); ?> / ea</td>
		<td class="sub-col4 cell">$<?php echo number_format($linetotal, 2, '.', ','); ?></td>
	</tr>
<?php
				}
			}
			if ((strtolower($a['state_ship']) == 'ga') || (strtolower($a['state_ship']) == 'georgia')) {
				$taxtotal = ((int)(((($ordertotal * 0.07) * 1000) + 5) / 10) / 100);
			}
			else {
				$taxtotal = 0;
			}
			
			// figure out shipping
			// boxes have 10 in each box
			$boxes = (int) ($mags / 10);
			// boxes are $14 each
			$shippingtotal = $boxes * 14;
			// this is how many we have left over from our boxes
			$mags -= ($boxes * 10);
			if ($mags > 1) {
				$shippingtotal += 10;
			}
			elseif ($mags == 1) {
				$shippingtotal += 5;
			}
			$ordertotal += ($shippingtotal + $taxtotal);
?>
	<tr>
		<td class="sub-col12" colspan="2"></td>
		<td class="sub-col3">7% Tax (GA only)</td>
		<td class="sub-col4 cell">$<?php echo number_format($taxtotal, 2, '.', ','); ?></td>
	</tr>
	<tr>
		<td class="sub-col12" colspan="2"></td>
		<td class="sub-col3">Shipping</td>
		<td class="sub-col4 cell">$<?php echo number_format($shippingtotal, 2, '.', ','); ?></td>
	</tr>
	<tr>
		<td class="sub-col1"></td>
		<td class="sub-col2"></td>
		<td class="sub-col3 bold">TOTAL</td>
		<td class="sub-col4 bold">$<?php echo number_format($ordertotal, 2, '.', ','); ?></td>
	</tr>
</table>

<?php
			// finally, clear their "shopping cart"
			setcookie('subscription', '', 0, '/', '', true);

			$_POST['process'] = 0;
			$_POST['checkout'] = 0;
			$landing = false;
		}
		else {
			// the credit card failed
			// let execution continue so that the errors can be displayed
			$_POST['process'] = 0;
			$_POST['checkout'] = 1;
			$landing = false;
		}
	}
	else {
		// there was an error with the data, so go back to the checkout page
		$_POST['process'] = 0;
		$_POST['checkout'] = 1;
		$landing = false;
	}
}

if ($_POST['checkout'] == "1") {
	// *******************************************************************
	// CHECKOUT
	// *******************************************************************
	
	// first, get the cart from the submitted form, if any.
	// pull everything off of $_POST into our $a array.
	// we're specifically NOT using $_REQUEST here for a tiny bit of security.
	foreach($a_cart as $key => $value) {
		if (isset($_POST[$key])) {
			$a_cart[$key] = htmlspecialchars(stripslashes($_POST[$key]));
		}
	}

	// store the cart contents in a cookie
	$safe_subscription = base64_encode(serialize($a_cart));
	setcookie('subscription', $safe_subscription, 0, '/', '', true);
	
	get_header();
	// now we can spit out their order in a pretty format...
?>

<div class="ruled left"><span class="head2 ruled-text-left">Subscription Checkout</span></div>
<p>Here is a summary of your order:</p>
<form action="/subscribe" method="post" name="subscribeForm" id="subscribeForm" >
<table class="sub-table" cellspacing="1" cellpadding="0" border="0">
<?php
	$ordertotal = 0;
	$mags = 0;
	foreach($a_cart as $key => $value) {
		if ($value > 0 && $value != 'x') {
			$linetotal = $a_desc[$key]['cost'] * $value;
			$ordertotal += $linetotal;
			$mags += $a_desc[$key]['count'] * $value;
?>
	<tr>
		<td class="sub-col1 cell"><input name="<?php echo $key; ?>" type="text" id="<?php echo $value; ?>" size="2" class="subscription-text" value="<?php echo $value; ?>" /></td>
		<td class="sub-col2 cell"><?php echo $a_desc[$key]['desc']; ?></td>
		<td class="sub-col3 cell">$<?php echo number_format($a_desc[$key]['cost'], 2, '.', ','); ?> / ea</td>
		<td class="sub-col4 cell">$<?php echo number_format($linetotal, 2, '.', ','); ?></td>
	</tr>
<?php
		}
	}
	if ((strtolower($a['state_ship']) == 'ga') || (strtolower($a['state_ship']) == 'georgia') || ($a['state_ship'] == '')) {
		$taxtotal = ((int)(((($ordertotal * 0.07) * 1000) + 5) / 10) / 100);
	}
	else {
		$taxtotal = 0;
	}
	
	// figure out shipping
	// boxes have 10 in each box
	$boxes = (int) ($mags / 10);
	// boxes are $14 each
	$shippingtotal = $boxes * 14;
	// this is how many we have left over from our boxes
	$mags -= ($boxes * 10);
	if ($mags > 1) {
		$shippingtotal += 10;
	}
	elseif ($mags == 1) {
		$shippingtotal += 5;
	}
	$ordertotal += ($shippingtotal + $taxtotal);
?>
	<tr>
		<td class="sub-col12" colspan="2"><input class="subscription_update" name="subscription_update" type="submit" id="subscription_update" value="Update Quantities" /></td>
		<td class="sub-col3">7% Tax (GA only)</td>
		<td class="sub-col4 cell">$<?php echo number_format($taxtotal, 2, '.', ','); ?></td>
	</tr>
	<tr>
		<td class="sub-col12" colspan="2"><input class="subscription_update" name="subscription_add" type="submit" id="subscription_add" value="Back to Ordering Page" /></td>
		<td class="sub-col3">Shipping</td>
		<td class="sub-col4 cell">$<?php echo number_format($shippingtotal, 2, '.', ','); ?></td>
	</tr>
	<tr>
		<td class="sub-col1"></td>
		<td class="sub-col2"></td>
		<td class="sub-col3 bold">TOTAL</td>
		<td class="sub-col4 bold">$<?php echo number_format($ordertotal, 2, '.', ','); ?></td>
	</tr>
</table>

<?php
if(!empty($err))  {
	echo '<p class="error_msg">';
	foreach ($err as $e) {
		echo "$e<br />";
	}
	echo "</p>";
}
?>
	<script type="text/javascript">
		function use_bill() {
			$("#name_first_ship").val($("#card_name_first").val());
			$("#name_last_ship").val($("#card_name_last").val());
			$("#address_ship").val($("#address").val());
			$("#city_ship").val($("#city").val());
			$("#state_ship").val($("#state").val());
			$("#zipcode_ship").val($("#zipcode").val());
		}
	</script>

	<p>&nbsp;</p>
	<div class="head3 oo-color-brown">Payment Information</div>
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
			'2011'=>'2011',
			'2012'=>'2012',
			'2013'=>'2013',
			'2014'=>'2014',
			'2015'=>'2015',
			'2016'=>'2016',
			'2017'=>'2017',
			'2018'=>'2018',
			'2019'=>'2019'
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
	<p class="vendor_txt"><input name="card_cvv" type="text" id="card_cvv" size="40" class="required" value="<?php echo $a['card_cvv']; ?>" /></p>
	<div style="text-align: left;"><img src="/media/images/cvv.jpg" /></div>

	<p>&nbsp;</p>
	<div class="head3 oo-color-brown">Shipping Information</div>

	<p><a href="#" onclick="use_bill(); return false;">Use Billing Information</a></p>
	<p class="vendor_desc">First Name</p>
	<p class="vendor_txt"><input name="name_first_ship" type="text" id="name_first_ship" size="40" class="required" value="<?php echo $a['name_first_ship']; ?>" /></p>

	<p class="vendor_desc">Last Name</p>
	<p class="vendor_txt"><input name="name_last_ship" type="text" id="name_last_ship" size="40" class="required" value="<?php echo $a['name_last_ship']; ?>" /></p>

	<p class="vendor_desc">Shipping Address</p>
	<p class="vendor_txt"><input name="address_ship" type="text" id="address_ship" size="40" value="<?php echo $a['address_ship']; ?>" /></p>
	
	<p class="vendor_desc">Shipping City / State / Zipcode:</p>
	<p class="vendor_txt"><input name="city_ship" type="text" id="city_ship" size="40" value="<?php echo $a['city_ship']; ?>" /> <input name="state_ship" type="text" id="state_ship" size="40" value="<?php echo $a['state_ship']; ?>" /> <input name="zipcode_ship" type="text" id="zipcode_ship" size="40" value="<?php echo $a['zipcode_ship']; ?>" /></p>

  <p>Please allow 4-6 weeks for delivery of your first issue.</p>

<input type="hidden" name="process" value="1" />
<input type="hidden" name="ordertotal" value="<?php echo $ordertotal; ?>" />
<input type="hidden" name="taxtotal" value="<?php echo $taxtotal; ?>" />
<input class="subscription_submit" name="subscription_submit" type="submit" id="subscription_submit" value="Complete my Order" />
</form>
<p>&nbsp;</p>

<?php	
}
elseif ($landing)
{
	// *******************************************************************
	// LANDING PAGE
	// *******************************************************************
$s1 = '<select name="annual" id="annual" class="annual_select">';
$s1 .= '<option value="1"';
if( $a_cart['annual'] == "1" ) {
    $s1 .= " selected";
}
$s1 .= '>YES, I want to subscribe to Occasions Magazine</option>';
$s1 .= '<option value="x"';
if( $a_cart['annual'] == "x" ) {
    $s1 .= " selected";
}
$s1 .= '>No thanks, I only want to order back issues</option>';
$s1 .= '</select>';


	get_header();
?>
<form action="/subscribe" method="post" name="subscribeForm" id="subscribeForm" >
<div class="ruled left"><span class="head2 ruled-text-left">Subscribe</span></div>
<div class="float-right" style="vertical-align: middle; margin-top: 6px;">We accept 
<img style="vertical-align: middle;" src="/media/images/logo-visa.jpg" />
<img style="vertical-align: middle;" src="/media/images/logo-mc.jpg" />
<img style="vertical-align: middle;" src="/media/images/logo-amex.jpg" />
<img style="vertical-align: middle;" src="/media/images/logo-discover.jpg" />
</div>
<p>Subscriptions are NOW available for Occasions Magazine!</p>

<div class="subscription-block">
	<img src="/media/images/occasions_winter2012_badge_145w.jpg" />
	<div class="head3 oo-color-brown">Annual Print Subscription</div>
	<div class="head5 normalcase">REGION: Atlanta</div>
	<p>Occasions Magazine IS the magazine for celebrating in style. The tri-annual 
	publication showcases the best of the best in Atlanta weddings, mitzvahs, 
	parties and celebrations. In-the-know readers turn to the glossy resource 
	guide for all things party planning. So, if you've got an upcoming occasion 
	on the calendar, subscribe to Occasions Magazine to get all this goodness, 
	delivered right to your doorstep.</p>
	<div class="subscription-price oo-color-green">$16.95 Per Year</div>
	<p><?php echo $s1; ?></p>
	<input class="subscription_submit" name="subscription_submit" type="submit" id="subscription_submit" value="Place my Order" />
</div>

<div class="head5">DON'T FORGET TO SUBSCRIBE TO OUR <a class="register_link" href="/register/"><span class="oo-color-green">EMAIL NEWSLETTERS</span></a> & <a href="/feed"><span class="oo-color-green">RSS FEED</span></a></div>
<p>&nbsp;</p>
<div class="subscription-archives head3 oo-color-green">Order Archives</div>


<div class="backissue-block1 clearme"><div class="backissue-block2">
	<img src="/media/images/occasions_fall2011_badge_145w.jpg" />
	<div><span class="head3 oo-color-brown">Fall 2011 (current issue)</span></div>
	<div class="head5 normalcase">REGION: Atlanta</div>
	<p>Cozy up to our Fall 2011 issue and you'll find a vibrant plum and bronze 
	color story set among one of Atlanta's newest venues, Foxhall Resort and 
	Sporting Club. We'll take you inside their lush landscape and give you party 
	planning inspiration for any fall fete. Our venue guide continues to 
	grow with more and more Atlanta reception sites and our real event features 
	begin with a baby shower for Kim Zolciak from the Real Housewives of Atlanta. 
	You don't want to miss getting your hands on a hard copy of this one!</p>
	<div class="priceline">
		<div class="subscription-price"><input name="f2011" type="text" id="f2011" size="2" class="subscription-text" value="<?php echo $a_cart['f2011']; ?>" /> $5.99 EACH</div>
		<div class="subscription-price"><input name="f2011b" type="text" id="f2011b" size="2" class="subscription-text" value="<?php echo $a_cart['f2011b']; ?>" /> $50.00 / BOX OF 10</div>
	</div>
</div></div>

<div class="backissue-block1 clearme"><div class="backissue-block2">
	<img src="/media/images/occasions_summer2011_badge_145w.jpg" />
	<div><span class="head3 oo-color-brown">Summer 2011</span></div>
	<div class="head5 normalcase">REGION: Atlanta</div>
	<p>It's summer and lime green and preppy party inspiration is coming to 
	you straight out of Palm Beach. Our Summer 2011 issue is the perfect 
	compliment to warm summer days and party planners looking for ideas. 
	As always you can find our comprehensive venue guide with over 30 
	reception sites around Atlanta and 15 real event features we're just 
	dying to share with you.</p>
	<div class="priceline">
		<div class="subscription-price">&nbsp;</div>
		<div class="subscription-price"><span class="subscription-soldout">SOLD OUT</span></div>
	</div>
</div></div>

<div class="backissue-block1 clearme"><div class="backissue-block2">
	<img src="/media/images/occasions_winter2011_badge_145w.jpg" />
	<div><span class="head3 oo-color-brown">Winter 2011</span></div>
	<div class="head5 normalcase">REGION: Atlanta</div>
	<p>Winter 2011 is full of warm ideas to keep readers cozy during the cold 
	winter months of the south with a bright spring palette of honeysuckle, 
	perfect for any table setting. This issue features 15 real event features 
	and a jaw dropping bridal fashion shoot at Atlanta's most romantic venue, 
	The Swan House. Plus a destination feature of The Breakers in Palm Beach, 
	Florida.</p>
	<div class="priceline">
		<div class="subscription-price"><input name="w2010" type="text" id="w2010" size="2" class="subscription-text" value="<?php echo $a_cart['w2010']; ?>" /> $5.99 EACH</div>
		<div class="subscription-price"><input name="w2010b" type="text" id="w2010b" size="2" class="subscription-text" value="<?php echo $a_cart['w2010b']; ?>" /> $50.00 / BOX OF 10</div>
	</div>
</div></div>

<div class="backissue-block1 clearme"><div class="backissue-block2">
	<img src="/media/images/issue-summer-fall-2010.jpg" />
	<div><span class="head3 oo-color-brown">Summer/Fall 2010</span></div>
	<div class="head5 normalcase">REGION: Atlanta</div>
	<p>The Summer 2010 issue introduced the city to it's most unique venue, 
	200 Peachtree. An old Macy's department store, turned luxurious event 
	venue in the heart of downtown Atlanta. This issue features 14 real 
	event features and Atlanta's Top 10 most influential event planners.</p>
	<div class="priceline">
		<div class="subscription-price"><input name="sf2010" type="text" id="sf2010" size="2" class="subscription-text" value="<?php echo $a_cart['sf2010']; ?>" /> $5.99 EACH</div>
		<div class="subscription-price"><input name="sf2010b" type="text" id="sf2010b" size="2" class="subscription-text" value="<?php echo $a_cart['sf2010b']; ?>" /> $50.00 / BOX OF 10</div>
	</div>
</div></div>

<div class="backissue-block1 clearme"><div class="backissue-block2">
	<img src="/media/images/issue-winter-spring-2010.jpg" />
	<div><span class="head3 oo-color-brown">Winter/Spring 2010</span></div>
	<div class="head5 normalcase">REGION: Atlanta</div>
	<p>Winter 2010 was the issue of rooftop venues across Atlanta and the 
	introduction of the city's chicest new venue, Ventanas. This issue 
	features 7 real events and inspiration for a sorbet chic dinner setting.</p>
	<div class="priceline">
		<div class="subscription-price">&nbsp;</div>
		<div class="subscription-price"><span class="subscription-soldout">SOLD OUT</span></div>
	</div>
</div></div>

<div class="backissue-block1 clearme"><div class="backissue-block2">
	<img src="/media/images/issue-summer-fall-2009.jpg" />
	<div><span class="head3 oo-color-brown">Summer/Fall 2009</span> <span class="premier-issue oo-fancy">Premier Issue</span></div>
	<div class="head5 normalcase">REGION: Atlanta</div>
	<p>The Premiere Issue of Occasions Magazine hit newsstands July 1, 2009 
	with a bang! Order our very first issue to see where it all started.</p>
	<div class="priceline">
		<div class="subscription-price">&nbsp;</div>
		<div class="subscription-price"><span class="subscription-soldout">SOLD OUT</span></div>
	</div>
</div></div>

<p>SHIPPING:<br />
&nbsp;&nbsp;&nbsp;&nbsp;$5 for 1 magazine<br />
&nbsp;&nbsp;&nbsp;&nbsp;$10 for 2-9 magazines<br />
&nbsp;&nbsp;&nbsp;&nbsp;$14 per box of 10<br />
Please allow 4-6 weeks for delivery of your first issue.
</p>

<input type="hidden" name="checkout" value="1" />
<input class="subscription_submit" name="subscription_submit" type="submit" id="subscription_submit" value="Place my Order" />
</form>
<p>&nbsp;</p>
<div class="head5">DON'T FORGET TO SUBSCRIBE TO OUR <a class="register_link" href="/register/"><span class="oo-color-green">EMAIL NEWSLETTERS</span></a> & <a href="/feed"><span class="oo-color-green">RSS FEED</span></a></div>
<p>&nbsp;</p>

<?php
}
// *******************************************************************
// ALL DONE
// *******************************************************************

edit_post_link('Edit this entry.', '<p>', '</p>');
get_footer(); 
?>
