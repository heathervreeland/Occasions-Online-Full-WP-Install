<?php 
/********************** MYSETTINGS.PHP**************************
This updates user settings and password
************************************************************/

$err = array();
$msg = array();

$active_user_id = get_active_user_id();

if($_POST['doUpdate'] == 'Update') {

	$rs_pwd = mysql_query("select pwd from {$const['TBL_USERS']} where id='$active_user_id'");
	list($old) = mysql_fetch_row($rs_pwd);
	$old_salt = substr($old,0,9);

	//check for old password in md5 format
	if($old === PwdHash($_POST['pwd_old'],$old_salt)) {
		$newsha1 = PwdHash($_POST['pwd_new']);
		mysql_query("update {$const['TBL_USERS']} set pwd='$newsha1' where id='$active_user_id'");
		$msg[] = "Your password has been updated.";
	}
	else {
		$err[] = "Your old password is invalid.";
	}
}

if($_POST['doSave'] == 'Save') {
	// Filter POST data for harmful code (sanitize)
	foreach($_POST as $key => $value) {
		$data[$key] = filter($value);
	}
	
	// since the unchecked input checkboxes don't submit ANY $_POST data when unchecked,
	//		we have to explicitly set those values from $_POST since the "stale" data from the
	//		database may contain "checked" values that the user intends to be "un-checked".
	$data['approved'] = iif($_POST['approved'] == '1', '1', '0');

	$sql = "UPDATE {$const['TBL_USERS']} SET
			full_name = '$data[full_name]',
			user_contact = '$data[user_contact]',
			user_title = '$data[user_title]',
			user_email = '$data[user_email]',
			tel = '$data[tel]'";
	if (checkAdmin()) {
		// additional field that admins are able to save
		$sql .= ", approved = '$data[approved]'";
	}
	$sql .= " WHERE id='$active_user_id'";

	mysql_query($sql) or die(mysql_error());
	
	$msg[] = "Account settings were saved.";
}
 
$rs_settings = mysql_query("select * from {$const['TBL_USERS']} where id='$active_user_id'"); 


// *********************************************************************************************
?>

<script>
	$(document).ready(function(){
		$("#myform").validate();
		$("#pform").validate();
	});
</script>
<h2 class="titlehdr">Account Settings</h2>
<?php
	if (checkAdmin()) {
		get_adminselector();
	}
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
if(!empty($msg))  {
	echo '<p class="error_msg">' . $msg[0] . "</p>";
}
/******************************* END ********************************/	  
?>
<p>Here you can make changes to your account settings. You will not be able to change your username which has been already registered.</p>
<?php
while ($row_settings = mysql_fetch_array($rs_settings)) {
?>

		<form action="<?php echo PAGE_SETTINGS; ?>" method="post" name="myform" id="myform">

<?php 	
if (checkAdmin()) {
?>
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="approved">&nbsp;</label></div>
			<div class="pro_contacttxt" id="ven_txt"><input class="vendor_checkbox" name="approved" type="checkbox" id="approved" value="1" <?php echo iif($row_settings['approved'] == '1', 'checked', ''); ?> /> <label for="approved">Check here to activate this company. (admin only)</label></div>
		</div>
<?php 	
}
?>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="full_name">Company Name</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="full_name" type="text" id="full_name" size="40" class="required" value="<? echo $row_settings['full_name']; ?>" /></div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="user_contact">Contact Name</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="user_contact" type="text" id="user_contact" class="required" value="<? echo $row_settings['user_contact']; ?>" /></div>
		</div>
		
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="user_title">Contact Title</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="user_title" type="text" id="user_title" class="required" value="<? echo $row_settings['user_title']; ?>" /></div>
		</div>
		
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="tel">Contact Phone</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="tel" type="text" id="tel" class="required" value="<? echo $row_settings['tel']; ?>" /></div>
		</div>

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="usr_email">Your Email</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="user_email" type="text" id="usr_email" class="required" value="<? echo $row_settings['user_email']; ?>" /></div>
		</div>
		
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="tel">Username</label></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="user_name" type="text" id="user_name" class="required username" minlength="5" value="<? echo $row_settings['user_name']; ?>" disabled /> </div>
		</div>
		
        <p>&nbsp;</p>
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label>&nbsp;</label></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="doSave" type="submit" id="pro_contact_submit" value="Save" /></div>
		</div>
        <p>&nbsp;</p>

	</form>
<?php
}
?>
      <h2>Change Password</h2>
      <p>To change your password, please enter your current password and new password.</p>
      <form name="pform" id="pform" method="post" action="">

		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="pwd">Current Password</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="pwd_old" type="password" class="required password" minlength="5" id="pwd_old" /></div>
		</div>
		
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label for="pwd">New Password</label> <span class="required"><font color="#CC0000">*</font></span></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="pwd_new" type="password" class="required password" minlength="5" id="pwd_new" /> ** 5 chars minimum</div>
		</div>
		
        <p>&nbsp;</p>
		<div class="pro_contactrow">
			<div class="pro_contactlabel" id="ven_label"><label>&nbsp;</label></div>
			<div class="pro_contacttxt" id="ven_txt"><input name="doUpdate" type="submit" id="pro_contact_submit" value="Update" /></div>
		</div>
      </form>
      <div style="height: 200px;">&nbsp;</div>
