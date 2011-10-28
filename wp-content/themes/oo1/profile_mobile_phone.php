<?php
// *******************************************************************
// PAGE - CONTACT SUBMIT
// *******************************************************************
require( '/home/oonline/public_html/wp-load.php' );
include_once('guide-functions.php');
$success = false;

$pid = htmlspecialchars($_POST['pid']);
$txt_name = htmlspecialchars($_POST['txt_name']);
$txt_email = htmlspecialchars($_POST['txt_email']);
$txt_phone = htmlspecialchars($_POST['txt_phone']);
$txt_message = stripcslashes(htmlspecialchars($_POST['txt_message'], ENT_NOQUOTES));

if ($txt_name && $txt_email && $txt_phone && $txt_message) {

	$profile = new Pod('vendor_profiles');
	$profile->findRecords( 'id', -1, "t.id = '$pid'");
	//$profile->findRecords( 'id', $pid);
	$total = $profile->getTotalRows();
	if( $total > 0 ) {
		$profile->fetchRecord();
		$a = get_vendorfields($profile);
	
		$to      = $a['emai'];
		$subject = 'Found you on OccasionsOnline.com Mobile ('.$txt_name.')';
		$headers = 'From: "Occasions Magazine Contact Form" <clientcontact@occasionsonline.com>' . "\r\n" .
			'Reply-To: "Occasions Magazine" <clientcontact@occasionsonline.com>' . "\r\n" .
			'X-Mailer: AO5/PHP/' . phpversion();
		
		$msg = 'The following was sent from the Occasions Magazine Mobile Contact Form:'. "\r\n\r\n" .
				'Name: '. $txt_name. "\r\n" .
				'Email: '. $txt_email. "\r\n" .
				'Phone: '. $txt_phone. "\r\n" .
				'Message: '. "\r\n\r\n" . $txt_message. "\r\n\r\n\r\n" .
				
				'------------------------------------------------------------'. "\r\n" .
				'SENT TO : ' . $a['name'] . "\r\n" .
				'CONTACT : ' . $a['emai'] . "\r\n" .
				'SENT AT : ' . date("D F j, Y, g:i a") . "\r\n" .
				'FROM IP : ' . get_real_ip() . "\r\n" .
				'------------------------------------------------------------'. "\r\n";
		
		mail($to, $subject, $msg, $headers, AO_EMAIL_FLAGS);
		mail(AO_ADMIN_EMAIL, $subject, $msg, $headers, AO_EMAIL_FLAGS);
		mail(AO_TECH_EMAIL, $subject, $msg, $headers, AO_EMAIL_FLAGS);
		$success = true;
	}
	else {
		$err_msg = "There was an error locating contact information for this vendor. We are sorry for the inconvenience.";
		$err_title = "We Encountered a Problem";
	}
}
else {
	$err_msg = "Please be sure to fill in all fields.";
	$err_title = "Almost there...";
}


if ($success) {
?>
<div data-role="page" id="contact_results" data-theme="o">

	<div data-role="header" data-position="fixed">
		<h1>Thank You</h1>
	</div>

	<div data-role="content" data-theme="o">
		<h3>Thank you!</h3>
		<p>Your email has been sent to <?php echo $a['name']; ?>.</p>
		<p>If you do not receive a response within 24 hours, we suggest giving them a quick call to make sure your email was delivered successfully.</p>
		<a href="#home" data-role="button" data-icon="arrow-l" data-theme="c" data-direction="reverse">Close</a>
	</div>
</div>


<?php } else { ?>


<div data-role="page" id="contact_results" data-theme="o">

	<div data-role="header" data-position="fixed">
		<h1>Error</h1>
	</div>

	<div data-role="content" data-theme="o">
		<h2><?php echo $err_title; ?></h2>
		<p><?php echo $err_msg; ?></p>
		<a href="#contact" data-role="button" data-icon="arrow-l" data-theme="c" data-direction="reverse">Back</a>
	</div>
</div>
<?php } ?>
