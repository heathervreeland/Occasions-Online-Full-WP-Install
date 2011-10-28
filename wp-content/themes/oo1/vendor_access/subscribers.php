<?php 

if (!checkAdmin()) {
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
	header("Content-Disposition: attachment; filename=occasions_subscriptions_$dtmarker.csv;");
	
	/*
	The Content-transfer-encoding header should be binary, since the file will be read
	directly from the disk and the raw bytes passed to the downloading computer.
	The Content-length header is useful to set for downloads. The browser will be able to
	show a progress meter as a file downloads. The content-lenght can be determines by
	filesize function returns the size of a file.
	*/
	header("Content-Transfer-Encoding: binary");
	//header("Content-Length: ".strlen($csv_data));

	$result = pod_query("SELECT subscription_id, subscription_stamp, subscription_name, subscription_total, subscription_descriptions, subscription_cart, subscription_transaction FROM ao_subscriptions ORDER BY subscription_stamp");
	
	$out = fopen('php://output', 'w');
	
	// get our field information
	//$i = 0;
	//$aFields = array();
	//while ($i < mysql_num_fields($result)) {
	//	$meta = mysql_fetch_field($result, $i);
	//	$aFields[] = $meta->name;
	//	$i++;
	//}
	
	$aFields[] = 'SubscriberID';
	$aFields[] = 'Date';
	$aFields[] = 'Name';
	$aFields[] = 'Address';
	$aFields[] = 'City';
	$aFields[] = 'State';
	$aFields[] = 'Zip';
	
	// write our fields to the CSV file
	write_csv($out, $aFields);

	// write our rows to the CSV file
	while ($row = mysql_fetch_assoc($result)) {
		
		// first unserialize the cart and see if they are a subscriber
		$a_cart = unserialize(base64_decode($row['subscription_cart']));
		
		if ($a_cart['annual'] == '1') {
		
			// now unserialize the transaction to get the address information
			$a_tran = unserialize(base64_decode($row['subscription_transaction']));
		
			$field_data = array();
		
			$field_data[] = $row['subscription_id'];
			$field_data[] = substr($row['subscription_stamp'], 0, 10);
			$field_data[] = $row['subscription_name'];
			$field_data[] = $a_tran['address'];
			$field_data[] = $a_tran['city'];
			$field_data[] = $a_tran['state'];
			$field_data[] = $a_tran['zipcode'];
			write_csv($out, $field_data);
		}
	}
	
	fclose($out);
	exit;
}

?>
