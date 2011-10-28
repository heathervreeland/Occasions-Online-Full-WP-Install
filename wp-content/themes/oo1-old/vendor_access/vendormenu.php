<div class="post">
<div id="vendor_menu">
<div class="ruled left"><span class="head2 ruled-text-left">My Account</span></div>
<?php
if (isset($_SESSION['user_id'])) { 
if (!is_advertiser()) {
?>
<div id="account_upgrade">
	<p><b>Upgrade Your Profile!</b></p>
	<p>Your courtesy account has been created. Your profile information won't display live on the site until you upgrade to a business profile!</p>
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
	if ( is_advertiser() || checkAdmin() ) {
		if ( checkAdmin() ) {
?>
	<br />You can view these as an administrator...
<?php 
		}
?>
	<br /><a href="<?php echo PAGE_EVENTS; ?>">My Events</a>
	<br /><a href="<?php echo PAGE_LEADS; ?>">Lead List</a>
	<br /><a href="<?php echo PAGE_BADGES; ?>">Vendor Badges</a>
	<br /><a href="<?php echo PAGE_SHOWS; ?>">Recommended Bridal Shows</a>
<?php 
	}
?>
	</p>
	<p>
	<a href="<?php echo PAGE_SETTINGS; ?>">Account Settings</a>
	<br /><a href="<?php echo PAGE_PAYMENT; ?>">Make a Payment</a>
	<br /><a href="http://www.occasionsonline.com/submissions">Editorial Submissions</a>
	<br /><a href="http://www.occasionsonline.com/networking-events">Networking Events</a>
	<br /><a href="<?php echo PAGE_CONTACTUS; ?>">Contact Us</a>
	<br /><a href="<?php echo PAGE_FAQ; ?>">Frequently Asked Questions</a>
	<br /><a id="advertiser_tc_link2" href="<?php echo PAGE_TERMS; ?>">Advertiser Terms &amp; Conditions</a>
	<br /><a href="<?php echo PAGE_LOGOUT; ?>">Log Out</a>
	</p>
<?php
	if (checkAdmin()) {
	/*******************************END**************************/
?>
		<p><!-- <a href="<?php echo PAGE_ADMIN; ?>">User Administration</a><br /> -->
		<p><a href="<?php echo PAGE_SUBSCRIBERS; ?>/download">Download Subscribers</a>
		<br />Logged in as: <?php echo $_SESSION['user_name']; ?></p>
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
