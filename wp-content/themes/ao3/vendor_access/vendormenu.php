<div class="post">
<div id="vendor_menu"> 
<h2>Advertiser Control Panel</h2>
<?php
if (isset($_SESSION['user_id'])) { 
if (!is_advertiser() && !checkAdmin()) {
?>
<div id="account_upgrade">
	<p><b>Upgrade Your Profile!</b></p>
	<p>Your courtesy account has been created. Your profile informaton won't display live on the site until you upgrade to a business profile!</p>
	<p>&nbsp;<br /><a href="<?php echo PAGE_UPGRADE; ?>">Upgrade to a Business Profile NOW!</a><br />&nbsp;</p>
</div>
<?php
}
else {
	//echo "<p>You da man!</p>";
}
?>
	<p>
	<a href="<?php echo PAGE_HOME; ?>">Dashboard</a>
	<br /><a href="<?php echo PAGE_PROFILE; ?>">My Profile</a>
<?php 
	if (is_advertiser()) {
	//if (checkPermission('events')) {
?>
	<br /><a href="<?php echo PAGE_EVENTS; ?>">My Events</a>
<?php 
	//}
	//if (checkPermission('leadlist')) {
?>
	<br /><a href="<?php echo PAGE_LEADS; ?>">Lead List</a>
<?php 
	}
?>
	</p>
	<p>
	<a href="<?php echo PAGE_SETTINGS; ?>">Account Settings</a>
	<br /><a href="http://www.atlantaoccasions.com/submissions">Editorial Submissions</a>
	<br /><a href="http://www.atlantaoccasions.com/networking-events">Networking Events</a>
	<br /><a id="advertiser_tc_link2" href="<?php echo PAGE_TERMS; ?>">Advertiser Terms &amp; Conditions</a>
	<br /><a href="<?php echo PAGE_LOGOUT; ?>">Log Out</a>
	</p>	
<?php
	if (checkAdmin()) {
	/*******************************END**************************/
?>
		<p> <a href="<?php echo PAGE_ADMIN; ?>">User Administration (<?php echo $_SESSION['user_name']; ?>)</a></p>
<?php 
	}
} else { 
?>
<p>
<a href="<?php echo PAGE_LOGIN; ?>">Log In</a>
</p>	
<p>
<a href="<?php echo PAGE_FORGOT; ?>">Password Reset</a><br />
<a href="<?php echo PAGE_REGISTER; ?>">New Advertiser Registration</a>
</p>
<?php } ?>
</div>
</div>
