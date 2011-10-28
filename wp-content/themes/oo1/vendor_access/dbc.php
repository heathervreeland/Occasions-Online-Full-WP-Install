<?php
/*************** PHP LOGIN SCRIPT V 2.3*********************
(c) Balakrishnan 2010. All Rights Reserved

Usage: This script can be used FREE of charge for any commercial or personal projects. Enjoy!

Limitations:
- This script cannot be sold.
- This script should have copyright notice intact. Dont remove it please...
- This script may not be provided for download except from its original site.

For further usage, please contact me.

/******************** MAIN SETTINGS - PHP LOGIN SCRIPT V2.1 **********************
Please complete wherever marked xxxxxxxxx

/************* MYSQL DATABASE SETTINGS *****************
1. Specify Database name in $dbname
2. MySQL host (localhost or remotehost)
3. MySQL user name with ALL previleges assigned.
4. MySQL password

Note: If you use cpanel, the name will be like account_database
*************************************************************/
//XX

//added by Ben Kaplan - 10/28/11 - prints out dev or {$subdomain} depending upon environment
$subdomain = print_subdomain();

define ("PAGE_ADMIN",		"https://{$subdomain}.occasionsonline.com/advertisers/admin"); // admin.php
define ("PAGE_LOGIN",		"https://{$subdomain}.occasionsonline.com/advertisers/login"); // login.php
define ("PAGE_LOGOUT",		"https://{$subdomain}.occasionsonline.com/advertisers/logout"); // logout.php
define ("PAGE_HOME",		"https://{$subdomain}.occasionsonline.com/advertisers/home"); // home.php
define ("PAGE_PROFILES",	"https://{$subdomain}.occasionsonline.com/advertisers/profiles"); // profiles.php
define ("PAGE_PROFILE",		"https://{$subdomain}.occasionsonline.com/advertisers/profile"); // profile.php
define ("PAGE_SETTINGS",	"https://{$subdomain}.occasionsonline.com/advertisers/settings"); // settings.php
define ("PAGE_REGISTER",	"https://{$subdomain}.occasionsonline.com/advertisers/register"); // register.php
define ("PAGE_FORGOT",		"https://{$subdomain}.occasionsonline.com/advertisers/forgot"); // forgot.php
define ("PAGE_ACTIVATE",	"https://{$subdomain}.occasionsonline.com/advertisers/activate"); // activate.php
define ("PAGE_THANKYOU",	"https://{$subdomain}.occasionsonline.com/advertisers/thankyou"); // thankyou.php
define ("PAGE_CHECKUSER",	"https://{$subdomain}.occasionsonline.com/advertisers/checkuser"); // checkuser.php
define ("PAGE_LEADS",		"https://{$subdomain}.occasionsonline.com/advertisers/leads"); // leads.php
define ("PAGE_EVENTS",		"https://{$subdomain}.occasionsonline.com/advertisers/events"); // events.php
define ("PAGE_EVENT",		"https://{$subdomain}.occasionsonline.com/advertisers/event"); // event.php
define ("PAGE_DO",			"https://{$subdomain}.occasionsonline.com/advertisers/do"); // do.php
define ("PAGE_DOIMAGE",		"https://{$subdomain}.occasionsonline.com/advertisers/doimage"); // do_image.php
define ("PAGE_UPGRADE",		"https://{$subdomain}.occasionsonline.com/advertisers/upgrade"); // upgrade.php
define ("PAGE_UPGRADED",	"https://{$subdomain}.occasionsonline.com/advertisers/upgraded"); // upgraded.php
define ("PAGE_PAYMENT",		"https://{$subdomain}.occasionsonline.com/advertisers/payment"); // payment.php
define ("PAGE_PAYMENTMADE",	"https://{$subdomain}.occasionsonline.com/advertisers/paymentmade"); // paymentmade.php
define ("PAGE_TERMS",		"https://{$subdomain}.occasionsonline.com/advertisers/terms"); // terms.php
define ("PAGE_SHOWS",		"https://{$subdomain}.occasionsonline.com/advertisers/shows"); // shows.php
define ("PAGE_BADGES",		"https://{$subdomain}.occasionsonline.com/advertisers/badges"); // badges.php
define ("PAGE_SUBSCRIBERS",	"https://{$subdomain}.occasionsonline.com/advertisers/subscribers"); // subscribers.php
define ("PAGE_FAQ",			"https://{$subdomain}.occasionsonline.com/advertisers/faq"); // faq.php
define ("PAGE_CONTACTUS",	"https://{$subdomain}.occasionsonline.com/advertisers/contactus"); // contactus.php

define ("TBL_USERS",		"ao_vendors");

// THESE ARE ALREADY DEFINED IN WORDPRESS
//define ("DB_HOST", "xxxxxx"); // set database host
//define ("DB_USER", "xxxxxx"); // set database user
//define ("DB_PASSWORD", "xxxxxxx"); // set database password
//define ("DB_NAME", "xxxxxx"); // set database name

$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die("Couldn't make connection.");
$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database");

/* Registration Type (Automatic or Manual) 
 1 -> Automatic Registration (Users will receive activation code and they will be automatically approved after clicking activation link)
 0 -> Manual Approval (Users will not receive activation code and you will need to approve every user manually)
*/
$user_registration = 1;  // set 0 or 1

define("COOKIE_TIME_OUT", 30); //specify cookie timeout in days (default is 10 days)
define('SALT_LENGTH', 9); // salt for password

//define ("ADMIN_NAME", "admin"); // sp

/* Specify user levels */
define ("ADMIN_LEVEL", 5);
define ("USER_LEVEL", 1);
define ("GUEST_LEVEL", 0);

$const = get_defined_constants();
$test = 'yyy';


/**** PAGE PROTECT CODE  ********************************
This code protects pages to only logged in users. If users have not logged in then it will redirect to login page.
If you want to add a new page and want to login protect, COPY this from this to END marker.
Remember this code must be placed on very top of any html or php page.
********************************************************/

function page_protect() {
	session_start();
	
	global $db; 
	$const = get_defined_constants();
	
	/* Secure against Session Hijacking by checking user agent */
	if (isset($_SESSION['HTTP_USER_AGENT'])) {
		if ($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT'])) {
			logout();
			exit;
		}
	}

	
	// if there is a session we first check to see if they are set to be forced to log out
	// then make sure the php session_id() matches the value stored in the database
	if ($_SESSION[user_id]) {
		$result = mysql_query("select force_logout, sid from {$const['TBL_USERS']} where id='$_SESSION[user_id]'");
		list($force_logout, $sid) = mysql_fetch_row($result);
		
		if ($force_logout) {
			logout();
			exit;
		}
		
		if ($sid != sha1('occasions2011' . session_id())) {
			logout();
			exit;
		}
		
	}
	
	// before we allow sessions, we need to check authentication key - ckey and ctime stored in database
	
	/* If session not set, check for cookies set by Remember me */
	if (!isset($_SESSION['user_id']) && !isset($_SESSION['user_name']) ) {

		if(isset($_COOKIE['user_id']) && isset($_COOKIE['user_key'])) {

			/* we double check cookie expiry time against stored in database */
			$cookie_user_id  = filter($_COOKIE['user_id']);
			$rs_ctime = mysql_query("select `ckey`,`ctime` from {$const['TBL_USERS']} where `id` ='$cookie_user_id'") or die(mysql_error());
			list($ckey,$ctime) = mysql_fetch_row($rs_ctime);
			// cookie expiry
			if( (time() - $ctime) > 60*60*24*COOKIE_TIME_OUT) {
				logout();
				exit;
			}

			/* Security check with untrusted cookies - dont trust value stored in cookie. 		
			/* We also do authentication check of the `ckey` stored in cookie matches that stored in database during login*/
			if( !empty($ckey) && is_numeric($_COOKIE['user_id']) && isUserID($_COOKIE['user_name']) && $_COOKIE['user_key'] == sha1($ckey)  ) {

				session_regenerate_id(); //against session fixation attacks.
				
				$_SESSION['user_id'] = $_COOKIE['user_id'];
				$_SESSION['fake_user_id'] = $_COOKIE['user_id'];
				$_SESSION['user_name'] = $_COOKIE['user_name'];

				//* query user level from database instead of storing in cookies */
				//$result = mysql_query("select user_level, user_can_leadlist from {$const['TBL_USERS']} where id='$_SESSION[user_id]'");
				//list($user_level, $user_can_leadlist) = mysql_fetch_row($result);
				//
				//$_SESSION['user_level'] = $user_level;
				//$_SESSION['user_can_leadlist'] = $user_can_leadlist;
				$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);
				
			}
			else {
				logout();
				exit;
			}
		
		} else {
			header("Location: " . PAGE_LOGIN);
			exit();
		}
	}
	/* query user level from database instead of storing in cookies */
	$result = mysql_query("select full_name, user_level, force_logout, user_can_leadlist, user_can_events from {$const['TBL_USERS']} where id='$_SESSION[user_id]'");
	list($full_name, $user_level, $force_logout, $user_can_leadlist, $user_can_events) = mysql_fetch_row($result);
	
	if ($force_logout) {
		logout();
		exit;
	}
	
	$_SESSION['user_level'] = $user_level;
	$_SESSION['full_name'] = $full_name;
	$_SESSION['user_can_leadlist'] = $user_can_leadlist;
	$_SESSION['user_can_events'] = $user_can_events;
}



function filter($data) {
	$data = trim(htmlentities(strip_tags($data)));
	
	if (get_magic_quotes_gpc()) {
		$data = stripslashes($data);
	}
	
	return mysql_real_escape_string($data);
}



function EncodeURL($url) {
	$new = strtolower(ereg_replace(' ','_',$url));
	return($new);
}

function DecodeURL($url) {
	$new = ucwords(ereg_replace('_',' ',$url));
	return($new);
}

function ChopStr($str, $len) {

	if (strlen($str) < $len) {
		return $str;
	}
	
	$str = substr($str,0,$len);
	if ($spc_pos = strrpos($str," ")) {
		$str = substr($str,0,$spc_pos);
	}

	return $str . "...";
}	

function isEmail($email){
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

function isUserID($username)
{
	if (preg_match('/^[a-z\d_]{5,20}$/i', $username)) {
		return true;
	}
	else {
		return false;
	}
 }	
 
function isURL($url) 
{
	if (preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $url)) {
		return true;
	}
	else {
		return false;
	}
} 

function checkPwd($x,$y) 
{
	if(empty($x) || empty($y) ) {
		return false;
	}

	if (strlen($x) < 4 || strlen($y) < 4) { 
		return false;
	}

	if (strcmp($x,$y) != 0) {
		return false;
	} 
	return true;
}

function GenPwd($length = 7)
{
  $password = "";
  $possible = "0123456789bcdfghjkmnpqrstvwxyz"; //no vowels
  
  $i = 0; 
    
  while ($i < $length) { 

    
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       
    
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  return $password;

}

function GenKey($length = 7)
{
  $password = "";
  $possible = "0123456789abcdefghijkmnopqrstuvwxyz"; 
  
  $i = 0; 
    
  while ($i < $length) { 

    
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
       
    
    if (!strstr($password, $char)) { 
      $password .= $char;
      $i++;
    }

  }

  return $password;

}


function logout($do_redirect = TRUE)
{
	global $db;
	$const = get_defined_constants();
	session_start();
	
	$cookieUserID = mysql_escape_string($_COOKIE['user_id']);
	
	if(isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {
		mysql_query("UPDATE {$const['TBL_USERS']} SET ckey='', ctime= '', sid='', force_logout=0 WHERE `id`='$_SESSION[user_id]' OR  `id` = '$cookieUserID'") or die(mysql_error());
	}
	
	/************ Delete the sessions****************/
	unset($_SESSION['user_id']);
	unset($_SESSION['fake_user_id']);
	unset($_SESSION['user_name']);
	unset($_SESSION['user_level']);
	unset($_SESSION['user_can_leadlist']);
	unset($_SESSION['user_can_events']);
	unset($_SESSION['HTTP_USER_AGENT']);
	session_unset();
	session_destroy(); 
	
	/* Delete the cookies*******************/
	setcookie("user_id", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
	setcookie("user_name", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
	setcookie("user_key", '', time()-60*60*24*COOKIE_TIME_OUT, "/");
	
	if ($do_redirect) {
		header("Location: ". PAGE_LOGIN);
	}
}

// Password and salt generation
function PwdHash($pwd, $salt = null) {

	if ($salt === null) {
		$salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
	}
	else {
		$salt = substr($salt, 0, SALT_LENGTH);
	}
	return $salt . sha1($pwd . $salt);
}

function checkAdmin() {

	if($_SESSION['user_level'] == ADMIN_LEVEL) {
		return 1;
	} 
	else { 
		return 0 ;
	}
}

function checkPermission($perm) {

	if($_SESSION["user_can_$perm"] == '1') {
		return 1;
	} 
	else { 
		return 0 ;
	}
}

function get_adminselector() {
	// this functon generates the HTML code to display the admin user select list which is used
	//		to allow admins to masquarade as other users.
?>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" name="profileForm" id="profileForm" >
	<p class="vendor_desc">You are logged in as...</p>
	<?php
	
	$ven = get_active_user_id();
	$result = pod_query("SELECT id, full_name FROM ao_vendors ORDER BY full_name");
	
	echo '<p class="vendor_txt"><select name="fake_user_id" id="fake_user_id" class="vendor_select">';
	echo '<option value="0"';
	if ($ven == "0") {
		echo ' selected';
	}
	echo '>&lt;none&gt;</option>';
	
	while ($row = mysql_fetch_assoc($result))
	{
		echo '<option value="', $row['id'], '"';
		if ($ven == $row['id']) {
			echo ' selected';
		}
		echo '>', $row['full_name'], '</option>';
	}
	echo '</select>&nbsp;&nbsp;&nbsp;<input name="doSave" type="submit" id="admin_selector_submit" value="Change" /></p>';
	echo '</form>';
}

function get_active_user_id() {
	// this functon returns the "effective" user id, which is not neccessarily the user that is
	//		logged in, as might be the case with admins, wo are able to masquarade as other users.
	if (checkAdmin() && ($_SESSION['fake_user_id'] != '') && ($_SESSION['user_id'] != $_SESSION['fake_user_id'])) {
		return $_SESSION['fake_user_id'];
	}
	else {
		return $_SESSION['user_id'];
	}
}

function set_fake_admin() {
	// this functon checks to see if a value is set for $_POST['fake_user_id'], which means that
	//		the user (i.e. an admin) is setting the ID of the user they want to masquarade as.
	if (checkAdmin() && ($_POST['fake_user_id'] != '')) { 
		$_SESSION['fake_user_id'] = $_POST['fake_user_id'];
	}
}

function set_profile_status() {

	// this is the "effective" user id, which is not neccessarily the user that is logged in, in the
	//		case of admins, who are able to mascarade as other users.
	$active_user_id = get_active_user_id();
	
	$profile = new Pod('vendor_profiles');
	$profile->findRecords( 'id', -1, "t.vendor = $active_user_id");
	$total = $profile->getTotalRows();
	if( $total > 0 ) {
		$profile->fetchRecord();
		$_SESSION['profile_type'] = $profile->get_field('profile_type');
	}
	else {
		$_SESSION['profile_type'] = 'New';
	}
}

function get_profile_status() {
	if (!isset($_SESSION['profile_type'])) {
		set_profile_status();
	}
	return $_SESSION['profile_type'];
}

function is_advertiser() {
	return (get_profile_status() != 'New');
}

?>
