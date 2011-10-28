<?php 
foreach($_GET as $key => $value) {
	$get[$key] = filter($value);
}

/******** EMAIL ACTIVATION LINK**********************/
if(isset($get['user']) && !empty($get['activ_code']) && !empty($get['user']) && is_numeric($get['activ_code']) ) {

$err = array();
$msg = array();

$user = mysql_real_escape_string($get['user']);
$activ = mysql_real_escape_string($get['activ_code']);

//check if activ code and user is valid
$rs_check = mysql_query("select id, user_name, user_contact, user_email from {$const['TBL_USERS']} where md5_id='$user' and activation_code='$activ'") or die (mysql_error()); 
$num = mysql_num_rows($rs_check);
list($user_id, $user_name, $user_contact, $user_email) = mysql_fetch_row($rs_check);

  // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num <= 0 ) { 
	$err[] = "Sorry no such account exists or activation code invalid.";
	//header("Location: activate.php?msg=$msg");
	//exit();
}

if(empty($err)) {

// set the approved field to 1 to activate the account
$rs_activ = mysql_query("update {$const['TBL_USERS']} set approved='1' WHERE 
						 md5_id='$user' AND activation_code = '$activ' ") or die(mysql_error());
$msg[] = 'Thank you! Your account has been activated. <a href="' . $const['PAGE_LOGIN'] . '">CLICK HERE TO LOG IN</a>';

// =====================================================
// SEND THE COURTESY WELCOME EMAIL
// =====================================================
$email_message = <<<HEREDOC
<html><body>
<p>Dear $user_contact,</p>
<p>Thank you for considering Occasions Magazine (AtlantaOccasions.com) to promote your business. If you're looking for opportunities to advertise your business where you can reach brides, bat mitzvahs and birthday parties too, you've come to the right place.</p>

<p>You can continue adding information and photographs to your listing, but the information in your courtesy account won't display live on the site until you upgrade to a business profile. Upgrading is easy and the only way to fully experience the benefits of membership and inclusion in the Occasions Magazine Vendor Guide. And it's so worth it.  <a target="_blank" href="http://www.atlantaoccasions.com/testimonials">Read our current advertiser testimonials here</a>.</p>

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
<p>Month-to-Month $69 Per Month<br />
Annual Commitments $49 paid each month or $588 in advance&nbsp;</p>
<p>To get started, <a href="{$const['PAGE_LOGIN']}">login HERE</a> using the username and password you created and click on the &quot;My Profile&quot; link.</p>
<p>We&rsquo;re excited to get to know you better. Feel free to contact us to so we can answer any questions you may have.</p>
<p>Warmest Regards,</p>
<p>Heather Vreeland<br />
Publisher and Editor-in-Chief<br />
Occasions Magazine&nbsp;</p>
<p>______________________________________________________<br />
THIS IS AN AUTOMATED RESPONSE. <br />
***DO NOT RESPOND TO THIS EMAIL****</p>
</body></html>
HEREDOC;


$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: "Occasions Magazine" <do-not-reply@atlantaoccasions.com>' . "\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";

mail($user_email, "Occasions Magazine: Welcome", $email_message, $headers);

//header("Location: activate.php?done=1&msg=$msg");						 
//exit();
 }
}

/******************* ACTIVATION BY FORM**************************/
if ($_POST['doActivate']=='Activate')
{
$err = array();
$msg = array();

$user_email = mysql_real_escape_string($_POST['user_email']);
$activ = mysql_real_escape_string($_POST['activ_code']);
//check if activ code and user is valid as precaution
$rs_check = mysql_query("select id from {$const['TBL_USERS']} where user_email='$user_email' and activation_code='$activ'") or die (mysql_error()); 
$num = mysql_num_rows($rs_check);
  // Match row found with more than 1 results  - the user is authenticated. 
    if ( $num <= 0 ) { 
	$err[] = "Sorry no such account exists or activation code invalid.";
	//header("Location: activate.php?msg=$msg");
	//exit();
	}
//set approved field to 1 to activate the user
if(empty($err)) {
	$rs_activ = mysql_query("update {$const['TBL_USERS']} set approved='1' WHERE 
						 user_email='$user_email' AND activation_code = '$activ' ") or die(mysql_error());
	$msg[] = 'Thank you! Your account has been activated. <a href="' . $const['PAGE_LOGIN'] . '">CLICK HERE TO LOG IN</a>';
 }
//header("Location: activate.php?msg=$msg");						 
//exit();
}

	

?>
<script>
	$(document).ready(function() {
		$("#actForm").validate();
	});
</script>
<h2 class="titlehdr">Vendor Activation</h2>

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
	if(!empty($msg))  {
		echo '<p class="error_msg">' . $msg[0] . "</p>";
	}
	  /******************************* END ********************************/	  
	  ?>
	<p>&nbsp;</p>
	<!--
      <p>Please enter your email and activation code sent to you to your email 
        address to activate your account. Once your account is activated you can 
        <a href="login.php">login here</a>.</p>
	 
      <form action="activate.php" method="post" name="actForm" id="actForm" >
        <table width="65%" border="0" cellpadding="4" cellspacing="4" class="loginform">
          <tr> 
            <td colspan="2">&nbsp;</td>
          </tr>
          <tr> 
            <td width="36%">Your Email</td>
            <td width="64%"><input name="user_email" type="text" class="required email" id="txtboxn" size="25"></td>
          </tr>
          <tr> 
            <td>Activation code</td>
            <td><input name="activ_code" type="password" class="required" id="txtboxn" size="25"></td>
          </tr>
          <tr> 
            <td colspan="2"> <div align="center"> 
                <p> 
                  <input name="doActivate" type="submit" id="doLogin3" value="Activate">
                </p>
              </div></td>
          </tr>
        </table>
        <div align="center"></div>
        <p align="center">&nbsp; </p>
      </form>
-->
<div style="height: 500px;">&nbsp;</div>
