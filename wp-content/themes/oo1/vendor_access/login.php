<?php 
/*************** PHP LOGIN SCRIPT V 2.3*********************
(c) Balakrishnan 2009. All Rights Reserved

Usage: This script can be used FREE of charge for any commercial or personal projects. Enjoy!

Limitations:
- This script cannot be sold.
- This script should have copyright notice intact. Dont remove it please...
- This script may not be provided for download except from its original site.

For further usage, please contact me.

***********************************************************/
$err = array();

foreach($_GET as $key => $value) {
	$get[$key] = filter($value); //get variables are filtered.
}

if ($_POST['doLogin']=='Login') {

	foreach($_POST as $key => $value) {
		$data[$key] = filter($value); // post variables are filtered
	}
	
	
	$user_email = $data['usr_email'];
	$pass = $data['pwd'];
	
	
	if (strpos($user_email,'@') === false) {
		$user_cond = "user_name='$user_email'";
	}
	else {
		$user_cond = "user_email='$user_email'";
	}
	
	$result = mysql_query("SELECT `id`,`pwd`,`full_name`,`user_name`,`approved`,`user_level` FROM {$const['TBL_USERS']} WHERE $user_cond AND `banned` = '0'") or die (mysql_error()); 
	$num = mysql_num_rows($result);
	
	  // Match row found with more than 1 results  - the user is authenticated. 
	if ( $num > 0 ) { 
		
		list($id,$pwd,$full_name,$user_name,$approved,$user_level) = mysql_fetch_row($result);
	
		if(!$approved) {
			$err[] = "Account not activated. Please check your email for activation code";
		}
	 
		//check against salt
		if ($pwd === PwdHash($pass,substr($pwd,0,9))) {
	
			if (empty($err)) {
		
				// this sets session and logs user in  
				session_start();
				session_regenerate_id (true); //prevent against session fixation attacks.
			
				// this sets variables in the session 
				$_SESSION['user_id']= $id;  
				$_SESSION['user_name'] = $user_name;
				$_SESSION['user_level'] = $user_level;
				$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
				
				//update the timestamp and key for cookie
				$stamp = time();
				$ckey = GenKey();
				$sid = sha1('occasions2011' . session_id());
				mysql_query("UPDATE {$const['TBL_USERS']} SET ctime='$stamp', ckey='$ckey', sid='$sid' WHERE id='$id'") or die(mysql_error());
				
				//set a cookie 
				
				if(isset($_POST['remember'])) {
					setcookie("user_id", $_SESSION['user_id'], time()+60*60*24*COOKIE_TIME_OUT, "/");
					setcookie("user_key", sha1($ckey), time()+60*60*24*COOKIE_TIME_OUT, "/");
					setcookie("user_name",$_SESSION['user_name'], time()+60*60*24*COOKIE_TIME_OUT, "/");
				}
				header("Location: " . PAGE_HOME);
			}
		}
		else {
			$err[] = "Invalid Login. Please try again with the correct user email and password.";
		}
	}
	else {
			$err[] = "Error - Invalid login. No such user exists";
	}		
}
?>
<script>
	$(document).ready(function(){
		$("#logForm").validate();
	});
</script>

	<h2>Advertiser Login</h2>
	<p>&nbsp;</p>
	<?php
		/******************** ERROR MESSAGES*************************************************
		This code is to show error messages 
		**************************************************************************/
		if(!empty($err))  {
			echo '<p class="error_msg">';
			foreach ($err as $e) {
				echo "$e <br>";
			}
			echo "</p>";	
		}
		/******************************* END ********************************/	  
	?>
      <form action="<?php echo PAGE_LOGIN; ?>" method="post" name="logForm" id="logForm" >

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="txtemail">Username or Email</label></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="usr_email" type="text" class="required" id="txtemail" size="25" /></div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="txtpass">Password</label></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="pwd" type="password" class="required password" id="txtpass" size="25" /> <a href="<?php echo PAGE_FORGOT; ?>">Forgot Your Password?</a></div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label>&nbsp;</label></div>
			<div class="pro_contacttxt" id="ven_txt"><input class="pro_checkbox" name="remember" type="checkbox" id="remember" value="1" /> <label for="remember">Remember me</label></div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label>&nbsp;</label></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="doLogin" type="submit" id="pro_contact_submit" value="Login" /></div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label>&nbsp;</label></div>
			<div class="pro_contacttxt" id="ven_txt">&nbsp;</div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label>&nbsp;</label></div>
			<div class="pro_contacttxt" id="ven_txt"><a href="<?php echo PAGE_REGISTER; ?>">New Advertiser Registration</a></div>
		</div>

        <p>&nbsp;</p>
      </form>
      <div style="height: 400px;">&nbsp;</div>
	<script language="javascript">
	<!--
	   document.getElementById("txtemail").focus();
	 -->
	</script>
