<?php 

/******************* ACTIVATION BY FORM**************************/
if ($_POST['doReset']=='Reset Password') {
	$err = array();
	$msg = array();
	
	foreach($_POST as $key => $value) {
		$data[$key] = filter($value);
	}

	if($data['user_name'] == '' && $data['user_email'] == '') {
		$err[] = "ERROR - Please enter your username or email address below."; 
	}
	
	if($data['user_email'] != '' && !isEmail($data['user_email'])) {
		$err[] = "ERROR - Please enter a valid email."; 
	}
	
	if(empty($err)) {
		$user_name = $data['user_name'];
		$user_email = $data['user_email'];
		
		if ($user_name != '') {
			$sql = "select id from {$const['TBL_USERS']} where user_name='$user_name'";
		}
		else {
			$sql = "select id from {$const['TBL_USERS']} where user_email='$user_email'";
		}
		
		//check if activ code and user is valid as precaution
		$rs_check = mysql_query($sql) or die (mysql_error()); 
		$num = mysql_num_rows($rs_check);
		
		if ($user_email != '' && $num > 1) {
			// multiple profiles with that email address, must use username
			$err[] = "Multiple profiles exist with that email address. Please enter the username for the profile you wish to reset.";
		}
		elseif ( $num <= 0 ) { 
			$err[] = "Error - Sorry no such account exists or registered.";
		}
		
		if(empty($err)) {
		
			$new_pwd = GenPwd();
			$pwd_reset = PwdHash($new_pwd);
			//$sha1_new = sha1($new);	
			//set update sha1 of new password + salt
			if ($user_name != '') {
				// retreive the email address for this username
				$sql_email = "select user_email from {$const['TBL_USERS']} where user_name='$user_name'";
				$rs_check = mysql_query($sql_email) or die (mysql_error()); 
				list($user_email) = mysql_fetch_row($rs_check);				

				$sql = "update {$const['TBL_USERS']} set pwd='$pwd_reset' WHERE user_name='$user_name'";
				$message = 
"Here are your new password details ...

User Name: $user_name
Temporary Password: $new_pwd

After you log in for the first time, please make sure you change your password to one of your choosing.

Thank You,

Occasons Magazine
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";
			}
			else {
				$sql = "update {$const['TBL_USERS']} set pwd='$pwd_reset' WHERE user_email='$user_email'";
				$message = 
"Here are your new password details ...

User Email: $user_email
Temporary Password: $new_pwd

After you log in for the first time, please make sure you change your password to one of your choosing.

Thank You,

Occasons Magazine
______________________________________________________
THIS IS AN AUTOMATED RESPONSE. 
***DO NOT RESPOND TO THIS EMAIL****
";
			}
			$rs_activ = mysql_query($sql) or die(mysql_error());
									 
			$host  = $_SERVER['HTTP_HOST'];
			$host_upper = strtoupper($host);						 
									 
			//send email
			
	
			mail($user_email, "Occasions Magazine: Password Reset", $message,
				"From: \"Occasions Magazine\" <do-not-reply@atlantaoccasions.com>\r\n" .
				"X-Mailer: PHP/" . phpversion(), AO_EMAIL_FLAGS);
								 
			$msg[] = "Your account password has been reset and a new password has been sent to your email address.";						 
		
			//$msg = urlencode();
			//header("Location: PAGE_FORGOT?msg=$msg");						 
			//exit();
		}
	}
}
?>
<script>
	//$(document).ready(function(){
	//	$("#actForm").validate();
	//});
</script>

<h2 class="titlehdr">Password Reset</h2>

<?php
	  /******************** ERROR MESSAGES*************************************************
	  This code is to show error messages 
	  **************************************************************************/
	if(!empty($err))  {
		echo '<p class="error_msg">';
		foreach ($err as $e) {
			echo "* $e <br>";
		}
		echo "</p>";
	}
	if(!empty($msg)) {
		echo "<p class=\"error_msg\">" . $msg[0] . "</p>";
	}
	  /******************************* END ********************************/	  
?>
<p>If you have forgotten your account password, enter your username OR email address below. <b>Your password will be reset</b> and your new password will be sent to the email address on file.</p>
	 
<p>NOTE: if you have more than one profle with the same email address you MUST enter your username below.</p>
	 
<form action="<?php  echo PAGE_FORGOT; ?>" method="post" name="actForm" id="actForm" >

	<p align="center">&nbsp; </p>
	
	<div class="pro_contactrow">
		<input name="user_name" type="text" class="required name" id="txtboxe" size="25" /><br />
		<label for="txtboxe">Your Username</label> <span class="required"><font color="#CC0000">*</font></span>
	</div>

	<p align="center">OR...</p>
	
	<div class="pro_contactrow">
		<input name="user_email" type="text" class="required email" id="txtboxn" size="25" /><br />
		<label for="txtboxn">Your Email</label> <span class="required"><font color="#CC0000">*</font></span>
	</div>
	
	<div class="pro_contactrow">
		<input name="doReset" type="submit" id="pro_contact_submit" value="Reset Password">
	</div>
	
	<div style="height: 500px;">&nbsp;</div>
</form>
