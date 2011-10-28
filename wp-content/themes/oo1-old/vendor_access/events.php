<?php 
if (!is_advertiser() && !checkAdmin()) {
	header("Location: " . PAGE_HOME);
	exit;
}
echo '<h2>My Events</h2>';
if (checkAdmin()) {
	get_adminselector();
}
echo '<p>This page lists all events for your company. After you create a new event it will automatically be submitted for approval.</p>';
echo '<p>&nbsp;</p>';
echo '<p><b><a href="', $const['PAGE_EVENT'], '/new">ADD A NEW EVENT</a></b></p>';

// get the event list
$e = new Pod('events');
$active_user_id = get_active_user_id();
//$sqlwhere = "( (approved = 1) AND (date_start >= '". date("Y-m-d") . "  00:00:00') ) ";
//$sqlwhere = "t.vendor = {$_SESSION['user_id']}";
$sqlwhere = "t.vendor = $active_user_id";
$e->findRecords( 't.date_start ASC', -1, $sqlwhere);

if( $e->getTotalRows() > 0 ) {
	while ( $e->fetchRecord() ) {
		// get our fields from the POD
		$ae = get_eventfields($e);
		
		$address = "{$ae['address']}, {$ae['city']}, {$ae['state']} {$ae['zipcode']}";
		$nicedate = '<b>' . date("D M j, Y", strtotime( $ae['date_start'] )) . '</b>';
		if ( ($ae['date_end'] != '0000-00-00 00:00:00') && ($ae['date_start'] != $ae['date_end']) ) {
			$nicedate .= ' thru <b>' . date("D M j, Y", strtotime( $ae['date_end'] )) . '</b>';
		}
		$status = iif($ae['approved'] == '1', 'Approved', '<i>Pending Approval</i>');
		echo <<<HEREDOC
		<div id="event_{$ae['id']}" class="eventlist_wrap">
			<div class="eventlist_content">
				<h2 class="eventlist_name">{$ae["name"]}</h2>
				<p class="eventlist_desc">$status - <a href="{$const['PAGE_EVENT']}/{$ae["id"]}">Edit Event</a><br />&nbsp;</p>
				<p class="eventlist_desc">{$ae["description"]}</p>
				<div class="eventlist_detail">
					<div class="eventlist_detailrow">
						<div class="eventlist_detaillabel">Date(s):</div>
						<div class="eventlist_detailtext">$nicedate</div>
					</div>
					<div class="eventlist_detailrow">
						<div class="eventlist_detaillabel">Address:</div>
						<div class="eventlist_detailtext">$address</div>
					</div>
					<div class="eventlist_detailrow">
						<div class="eventlist_detaillabel">Hours:</div>
						<div class="eventlist_detailtext">{$ae["hours"]}</div>
					</div>
					<div class="eventlist_detailrow">
						<div class="eventlist_detaillabel">Location:</div>
						<div class="eventlist_detailtext">{$ae["location"]}</div>
					</div>
					<div class="eventlist_detailrow">
						<div class="eventlist_detaillabel">Cost:</div>
						<div class="eventlist_detailtext">{$ae["cost"]}</div>
					</div>
					<div class="eventlist_detailrow">
						<div class="eventlist_detaillabel">More Info</div>
						<div class="eventlist_detailtext"><a href="{$ae["web_url"]}">{$ae["web_url"]}</a></div>
					</div>
				</div>
			</div>
		</div>
HEREDOC;
	}
}

?>
<div style="height: 500px;">&nbsp;</div>

