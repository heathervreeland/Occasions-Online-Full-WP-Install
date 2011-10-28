<?php 
/*************** PHP LOGIN SCRIPT V 2.0*********************
***************** Auto Approve Version**********************
(c) Balakrishnan 2009. All Rights Reserved

Usage: This script can be used FREE of charge for any commercial or personal projects.

Limitations:
- This script cannot be sold.
- This script may not be provided for download except on its original site.

For further usage, please contact me.

***********************************************************/
$err = array();
					 
if($_POST['doRegister'] == 'Register') {
/******************* Filtering/Sanitizing Input *****************************
This code filters harmful script code and escapes data of all POST data
from the user submitted form.
*****************************************************************/
foreach($_POST as $key => $value) {
	$data[$key] = filter($value);
}

/********************* RECAPTCHA CHECK *******************************
This code checks and validates recaptcha
****************************************************************/
require_once('recaptchalib.php');
     
$resp = recaptcha_check_answer ($privatekey,
								$_SERVER["REMOTE_ADDR"],
								$_POST["recaptcha_challenge_field"],
								$_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
	die ("<h2>Image Verification failed!. Go back and try again.</h2> (reCAPTCHA said: " . $resp->error . ")");			
}
/************************ SERVER SIDE VALIDATION **************************************/
/********** This validation is useful if javascript is disabled in the browswer ***/

if(empty($data['full_name']) || strlen($data['full_name']) < 4) {
	$err[] = "ERROR - Invalid name. Please enter at least 3 or more characters for your name";
}

// Validate User Name
if (!isUserID($data['user_name'])) {
	$err[] = "ERROR - Invalid user name. It can contain alphabet, number and underscore.";
}

// Validate Email
if(!isEmail($data['usr_email'])) {
	$err[] = "ERROR - Invalid email address.";
}
// Check User Passwords
if (!checkPwd($data['pwd'],$data['pwd2'])) {
	$err[] = "ERROR - Invalid Password or mismatch. Enter 5 chars or more";
}
	  
$user_ip = $_SERVER['REMOTE_ADDR'];

// stores sha1 of password
$sha1pass = PwdHash($data['pwd']);

// Automatically collects the hostname or domain like example.com) 
$host  = $_SERVER['HTTP_HOST'];
$host_upper = strtoupper($host);
$path   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

// Generates activation code simple 4 digit number
$activ_code = rand(1000,9999);

$usr_email = $data['usr_email'];
$user_name = $data['user_name'];

/************ USER EMAIL CHECK ************************************
This code does a second check on the server side if the email already exists. It 
queries the database and if it has any existing email it throws user email already exists
*******************************************************************/

$rs_duplicate = mysql_query("select count(*) as total from {$const['TBL_USERS']} where user_email='$usr_email' OR user_name='$user_name'") or die(mysql_error());
list($total) = mysql_fetch_row($rs_duplicate);

if ($total > 0)
{
	$err[] = "ERROR - The username and/or email already exists. Please try again with different username and email.";
}
/***************************************************************************/

if(empty($err)) {

	$sql_insert = "INSERT into {$const['TBL_USERS']}
				(full_name,user_email,pwd,tel,date,users_ip,activation_code,user_name,user_contact,user_title,user_can_events,user_can_leadlist)
				VALUES
				('$data[full_name]','$usr_email','$sha1pass','$data[tel]',now(),'$user_ip','$activ_code','$user_name','$data[user_contact]','$data[user_title]',1,1)";
				
	mysql_query($sql_insert,$link) or die("Insertion Failed:" . mysql_error());
	$user_id = mysql_insert_id($link);
	$md5_id = md5($user_id);
	mysql_query("update {$const['TBL_USERS']} set md5_id='$md5_id' where id='$user_id'");


	// ************************************************************************
	// create the new profile record in the database
	// ************************************************************************
	$api = new PodAPI();
	$profile_data = array();
	
	// safety cleansing
	//pods_sanitize($profile_data);

	// since we are creating a new profile, these fields need initializing
	$profile_data['name'] = $data[full_name];
	$profile_data['slug'] = strtolower(sanitize_file_name($data[full_name]));
	$profile_data['contact_name'] = $data[user_contact];
	$profile_data['contact_title'] = $data[user_title];
	$profile_data['contact_email'] = $usr_email;
	$profile_data['vendor'] = $user_id;
	$profile_data['active'] = '0';
	$profile_data['profile_type'] = 'New';
	$profile_data['expiration_date'] = '0000-00-00 00:00:00';
	$profile_data['payment_plan'] = 'NA';
	$profile_data['payment_amount'] = '0';

	$params = array(
		'datatype' => 'vendor_profiles', 
		'columns' => $profile_data
	);
	// create the item
	$api->save_pod_item($params);

	if($user_registration)  {
	$a_link = "
*****ACTIVATION LINK*****
{$const['PAGE_ACTIVATE']}?user=$md5_id&activ_code=$activ_code";
	}
	else {
		$a_link = 
		"Your account is *PENDING APPROVAL* and will be soon activated the administrator.";
	}

$subject = "Occasions Magazine: Registration Details";
$subject_admin = "Occasions Registration: " . $data[full_name];
$headers = "From: \"Occasions Magazine\" <do-not-reply@atlantaoccasions.com>";
$headers .= "\r\nX-Mailer: AO3/PHP/" . phpversion();

$message = 
"Hello,

Thank you for registering with Occasions Magazine. Before you can log in, you must activate your account.

$a_link

For you records, here are your login details...\n

Username/login: $user_name
Email Address: $usr_email 
Password: $data[pwd]
Activation Code: $activ_code

Thank You,

Occasions Magazine
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";

$message_admin = 
"A new business has registered with Occasions Magazine...

Company: $data[full_name]
Contact: $data[user_contact]
Username/login: $user_name
Email Address: $usr_email 

Occasions backend...
{$const['PAGE_HOME']}
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";


	mail($usr_email, $subject, $message, $headers);
	mail(AO_ADMIN_EMAIL, $subject_admin, $message_admin, $headers);
	mail(AO_TECH_EMAIL, $subject_admin, $message_admin, $headers);

  header("Location: " . PAGE_THANKYOU);  
  exit();
	 
} 
 }					 

?>
  <script>
  $(document).ready(function(){
    $.validator.addMethod("username", function(value, element) {
        return this.optional(element) || /^[a-z0-9\_]+$/i.test(value);
    }, "Username must contain only letters, numbers, or underscore.");

    $("#regForm").validate();
  });
  </script>

<p>
	<?php 
	 if (isset($_GET['done'])) { ?>
	  <h2>Thank you</h2> Your registration is now complete and you can <a href="<?php echo PAGE_LOGIN; ?>">login here</a>";
	 <?php exit();
	  }
	?></p>
      <h2>New Advertiser Registration</h2>
      <p>Please register for a free courtesy advertiser account. Please note that fields marked <span class="required">*</span> 
        are required.</p>
	 <?php
	 if(!empty($err))  {
	   echo "<div class=\"msg\">";
	  foreach ($err as $e) {
	    echo "* $e <br>";
	    }
	  echo "</div>";	
	   }
	 ?> 
	 
	  <br>
	<form action="<?php echo PAGE_REGISTER; ?>" method="post" name="regForm" id="regForm" >

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="full_name">Company Name</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="full_name" type="text" id="full_name" size="40" class="required" /></div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="user_contact">Contact Name</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="user_contact" type="text" id="user_contact" class="required" /></div>
		</div>
		
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="user_title">Contact Title</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="user_title" type="text" id="user_title" class="required" /></div>
		</div>
		
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="tel">Contact Phone</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="tel" type="text" id="tel" class="required" /></div>
		</div>
		
		<p>&nbsp;</p>
		<h2>Login Details</h2>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="tel">Username</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt">
				<input name="user_name" type="text" id="user_name" class="required username" minlength="5" /> 
				<input name="btnAvailable" type="button" id="pro_contact_submit" 
				onclick='$("#checkid").html("Please wait..."); $.get("<?php  echo PAGE_CHECKUSER; ?>",{ cmd: "check", user: $("#user_name").val() } ,function(data){  $("#checkid").html(data); });'
				value="Check Availability"> 
				<span style="color:red; font: bold 12px verdana; " id="checkid" ></span>
			</div>
		</div>
		
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="usr_email">Your Email</label> <span class="required email"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="usr_email" type="text" id="usr_email" class="required" /> <i>ex: name@domain.com</i></div>
		</div>
		
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="pwd">Password</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="pwd" type="password" class="required password" minlength="5" id="pwd" /> ** 5 chars minimum</div>
		</div>
		
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="pwd2">Retype Password</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="pwd2"  id="pwd2" class="required password" type="password" minlength="5" equalto="#pwd" /></div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label>Image Verification</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><table><tr><td><?php require_once('recaptchalib.php'); echo recaptcha_get_html($publickey, null, true); ?></td></tr></table></div>
		</div>
		
        <p align="center">&nbsp; </p>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label>&nbsp;</label></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="doRegister" type="submit" id="pro_contact_submit" value="Register" /></div>
		</div>
      <div style="height: 400px;">&nbsp;</div>

      </form>
	<script language="javascript">
	<!--
	   document.getElementById("full_name").focus();
	 -->
	</script>
