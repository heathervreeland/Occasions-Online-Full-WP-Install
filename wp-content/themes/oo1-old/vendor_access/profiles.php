<h2>Your Profiles</h2>
<?php	
if (isset($_GET['msg'])) {
	echo "<p class=\"error_msg\">$_GET[msg]</p>";
}
echo '<p>Some kind of description should go here.</p>';
echo '<p>&nbsp;</p>';
if (checkAdmin()) {
	get_adminselector();
}
echo '<p><b><a href="', $const['PAGE_PROFILE'], '/new">ADD A NEW PROFILE</a></b></p>';
$profile = new Pod('vendor_profiles');
$active_user_id = get_active_user_id();
//$profile->findRecords( 'id', -1, "t.vendor = {$_SESSION['user_id']}");
$profile->findRecords( 'id', -1, "t.vendor = $active_user_id");

$total = $profile->getTotalRows();
if( $total > 0 ) {
	while ($profile->fetchRecord()) {
		$a = get_vendorfields($profile);
		$live_profile = '';
		if ($a['type'] == 'Platinum') {
			$live_profile = ' | <a target="_blank" href="http://www.atlantaoccasions.com/profile/' . $a['slug'] . '">View on Website</a>';
		}
		echo <<<HEREDOC
		<div id="event_{$ae['id']}" class="eventlist_wrap">
			<div class="eventlist_content">
				<h2 class="eventlist_name">{$a['name']}</h2>
				<p class="eventlist_desc"><a href="{$const['PAGE_PROFILE']}/{$a["id"]}">Edit Profile</a>$live_profile</p>
HEREDOC;
?>
						<table width=600 border=0 cellpadding=0 cellspacing=0>
							<tr>
								<td width="25%"><p><?php echo "Status: ", print_iif($a['acti'] == 1, "Active", "Not Active"); ?></p></td>
								<td width="25%"><p><?php echo "Type: ", $a['type']; ?></p></td>
								<td width="25%"><p><?php echo "Pay Plan: ", $a['plan']; ?></p></td>
								<td width="25%"><p style="text-align: right;"><?php echo iif(substr($a['expi'], 0, 10) == '0000-00-00', 'Does not expire', "Expires: " . substr($a['expi'], 0, 10)); ?></p></td>
							</tr>
						</table>
			</div>
		</div>
<?php
	}
}
?>
<div style="height: 400px;">&nbsp;</div>
