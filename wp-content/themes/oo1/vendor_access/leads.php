<?php 

if (!is_advertiser() && !checkAdmin()) {
	header("Location: " . PAGE_HOME);
	exit;
}

$cmd = pods_url_variable(2);

if ($cmd == 'download') {

	// fix for IE catching or PHP bug issue
	header("Pragma: public");
	header("Expires: 0"); // set expiration time
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	// browser must download file from server instead of cache
	
	// force download dialog
	header("Content-Type: application/force-download");
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	
	// use the Content-Disposition header to supply a recommended filename and
	// force the browser to display the save dialog.
	$dtmarker = date('Ymd');
	header("Content-Disposition: attachment; filename=occasions_leads_$dtmarker.csv;");
	
	/*
	The Content-transfer-encoding header should be binary, since the file will be read
	directly from the disk and the raw bytes passed to the downloading computer.
	The Content-length header is useful to set for downloads. The browser will be able to
	show a progress meter as a file downloads. The content-lenght can be determines by
	filesize function returns the size of a file.
	*/
	header("Content-Transfer-Encoding: binary");
	//header("Content-Length: ".strlen($csv_data));

	$result = pod_query("SELECT name AS Name, email AS Email, address AS Address, city AS City, state AS State, zipcode AS Zipcode, phone AS Phone, DATE_FORMAT(event_date,'%Y/%m/%d') AS EventDate, interests AS Interests, interest_weddings AS InterestedInWeddings, interest_social AS InterestedInSocial, interest_corporate AS InterestedInCorporate, interest_mitzvahs AS InterestedInMitzvahs, interest_parties AS InterestedInParties, notes AS Notes, inquire_date AS DateOfInquiry FROM wp_pod_tbl_user_profiles WHERE event_date = '0000-00-00 00:00:00' OR event_date >= NOW() ORDER BY id");
	
	$out = fopen('php://output', 'w');
	
	// get our field information
	$i = 0;
	$aFields = array();
	while ($i < mysql_num_fields($result)) {
		$meta = mysql_fetch_field($result, $i);
		$aFields[] = $meta->name;
		$i++;
	}
	// write our fields to the CSV file
	write_csv($out, $aFields);

	// write our rows to the CSV file
	while ($row = mysql_fetch_assoc($result)) {
		$field_data = array_values($row);
		write_csv($out, $field_data);
	}
	
	fclose($out);
	exit;
}

$cmd = iif($cmd == '', 'bydate', $cmd);
$nav = '<p>Sort by: ';

if ($cmd == 'bydate') {
	$sortby = 't.event_date';
	$nav .= 'Event Date | ';
}
else {
	$nav .= '<a href="' . $const['PAGE_LEADS'] . '/bydate">Event Date</a> | ';
}

if ($cmd == 'byinquiry') {
	$sortby = 't.inquire_date DESC';
	$nav .= 'Inquiry Date | ';
}
else {
	$nav .= '<a href="' . $const['PAGE_LEADS'] . '/byinquiry">Inquiry Date</a> | ';
}

if ($cmd == 'byname') {
	$sortby = 't.name';
	$nav .= 'Contact Name';
}
else {
	$nav .= '<a href="' . $const['PAGE_LEADS'] . '/byname">Contact Name</a>';
}

$nav .= '</p>';

echo '<h2>Lead List Access</h2>';
echo '<p>This page lists all website visitor registrations for which no event date was listed or where the event date falls in the future. ';
echo 'You can also <a href="', $const['PAGE_LEADS'] , '/download">download all leads</a> as an Excel-compatible CSV file.</p>';
echo '<p>&nbsp;</p>';
echo $nav;

$leads = new Pod('user_profiles');
$leads->findRecords($sortby, -1, "t.event_date = '0000-00-00 00:00:00' OR t.event_date >= NOW()");
$total = $leads->getTotalRows();
if( $total > 0 ) {
	while ($leads->fetchRecord()) {
	
		$lead_name = $leads->get_field('name');
		$lead_edate = iif($leads->get_field('event_date') == '0000-00-00 00:00:00', '<i>date not specified</i>', substr($leads->get_field('event_date'), 0, 10));
		$lead_idate = substr($leads->get_field('inquire_date'), 0, 10);
		$lead_contact = iif($leads->get_field('email') <> '', '<a href="mailto:' . $leads->get_field('email') . '">' . $leads->get_field('email') . '</a>', '<i>no email given</i>') . ' | ' ;
		$lead_contact .= iif($leads->get_field('phone') <> '', $leads->get_field('phone'), '<i>no phone given</i>');
		$lead_address = $leads->get_field('address') . ', ' . $leads->get_field('city') . ' ' . $leads->get_field('state') . ' ' . $leads->get_field('zipcode');
		
		$lead_interested = '';
		$ainterests = array();
		if ($leads->get_field('interest_weddings') == '1') 	{$ainterests[] = 'Weddings';}
		if ($leads->get_field('interest_social') == '1') 	{$ainterests[] = 'Social Events';}
		if ($leads->get_field('interest_corporate') == '1') 	{$ainterests[] = 'Corporate Events';}
		if ($leads->get_field('interest_mitzvahs') == '1') 	{$ainterests[] = 'Mitzvahs';}
		if ($leads->get_field('interest_parties') == '1') 	{$ainterests[] = 'Parties';}
		if (count($ainterests) > 0) {
			$lead_interested = implode(', ', $ainterests);
		}
		
		$lead_services = $leads->get_field('interests');
		$lead_referred = $leads->get_field('referral');
		$lead_notes = $leads->get_field('notes');

		echo <<<HEREDOC
			<div id="event_{$ae['id']}" class="eventlist_wrap">
				<div class="eventlist_content">
					<p class="eventlist_name">$lead_name</p>
					<p class="eventlist_desc"></p>
					<div class="eventlist_detail">
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Event Date::</div>
							<div class="eventlist_detailtext">$lead_edate</div>
						</div>
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Inquired on:</div>
							<div class="eventlist_detailtext">$lead_idate</div>
						</div>
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Contact:</div>
							<div class="eventlist_detailtext">$lead_contact</div>
						</div>
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Address:</div>
							<div class="eventlist_detailtext">$lead_address</div>
						</div>
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Interested in:</div>
							<div class="eventlist_detailtext">$lead_interested</div>
						</div>
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Services wanted:</div>
							<div class="eventlist_detailtext">$lead_services</div>
						</div>
						<div class="eventlist_detailrow">
							<div class="eventlist_detaillabel">Notes:</div>
							<div class="eventlist_detailtext">$lead_notes</div>
						</div>
					</div>
				</div>
			</div>
HEREDOC;
	}
}

function comma_trans(&$item)
{
	$item = strtr($item, ',', '&#44'); //Translates any commas into their HTML entity.
}


?>
