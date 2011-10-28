<?php

/* Template Name: Redirect Utility */

// added by Ben Kaplan - 10/28/11 - prints out dev or www depending upon environment
$subdomain = print_subdomain();

$url .= pods_url_variable(1);
$i = 1;
while ($i < 20) {
	$i++;
	$dir = pods_url_variable($i);
	if ($dir) {
		$url .= '/' . $dir;
	}
}

if (!$url) {
	header("Location: http://{$subdomain}.occasionsonline.com/");
	exit;
}

if (substr($url, 0, 4) != "http") {
	$url = 'http://' . $url;
}

$len = strlen($url);
$action = '';
$target = '';
$sql = "SELECT * FROM wp_pod_tbl_vendor_profiles WHERE
web_url LIKE '$url%' 
OR blog_url LIKE '$url%' 
OR facebook_url LIKE '$url%' 
OR linkedin_url LIKE '$url%' 
OR twitter_url LIKE '$url%' 
OR ad_url LIKE '$url%' 
";

$result = pod_query($sql);
$row = mysql_fetch_assoc($result);
if ($row) {
	$id = $row['id'];
	if ($url == substr($row['web_url'], 0, $len)) 			{$action = 'click_web';}
	elseif ($url == substr($row['blog_url'], 0, $len)) 		{$action = 'click_blog';}
	elseif ($url == substr($row['facebook_url'], 0, $len)) 	{$action = 'click_facebook';}
	elseif ($url == substr($row['linkedin_url'], 0, $len)) 	{$action = 'click_linkedin';}
	elseif ($url == substr($row['twitter_url'], 0, $len)) 	{$action = 'click_twitter';}
	elseif ($url == substr($row['ad_url'], 0, $len)) 		{$action = 'click_ad';}
}

if (!$action) {
	$id = 0;
	$action = 'click_url';
	$target = $url;
}

ao_log($id, $action, $target);
header("Location: " . $url);
exit;
?>
