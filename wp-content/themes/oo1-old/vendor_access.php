<?php

/* Template Name: Vendor Access */


global $img_base_path;
global $img_dst_source;
global $img_dst_large;
global $img_dst_thumb;
global $img_dst_logo;
global $img_dst_logosource;
global $img_dst_video;
global $img_web_source;
global $img_web_large;
global $img_web_thumb;
global $img_web_logo;
global $img_web_logosource;
global $img_web_video;
global $twitter_cache;

//if ($_SERVER['REMOTE_ADDR'] != '76.17.3.14' // ben
//	&& $_SERVER['REMOTE_ADDR'] != '173.14.216.154' // davdi christensen
//	&& $_SERVER['REMOTE_ADDR'] != '74.232.88.126' ) { //heather
//	header("Location: http://www.atlantaoccasions.com/");
//}


$slug = pods_url_variable(1);
$cmd = pods_url_variable(2);
ao_set_in_vendorarea(true);

if ($slug == '') {
	$slug = 'home';
}

include_once('vendor_access/dbc.php');
include_once('guide-functions.php');

if (in_array($slug, array('checkuser','do'))) {
	include "vendor_access/$slug.php";
	exit;
}

if (in_array($slug, array('doimage', 'logout'))) {
	//page_protect();
	include "vendor_access/$slug.php";
	exit;
}

if ($slug == 'leads' && $cmd == 'download') {
	page_protect();
	include "vendor_access/$slug.php";
	exit;
}

if ($slug == 'subscribers' && $cmd == 'download') {
	page_protect();
	include "vendor_access/$slug.php";
	exit;
}

if (in_array($slug, array('login','forgot','register','thankyou','activate'))) {
	logout(false); // logout without the redirect
	get_header();
	ob_start();
	include "vendor_access/vendormenu.php";
	$vendormenu = ob_get_clean();
	ao_set_sidebar_content($vendormenu, "prewidget", true);
	
	include "vendor_access/$slug.php";
	get_footer();
	exit;
}

if (in_array($slug, array('admin','home','profile','settings', 'leads', 'subscribers', 'events', 'event', 'upgrade', 'upgraded', 'payment', 'paymentmade', 'shows', 'badges', 'faq', 'contactus'))) {
	get_header();
	page_protect();
	set_fake_admin();
	set_profile_status();
	
	ob_start();
	include "vendor_access/vendormenu.php";
	$vendormenu = ob_get_clean();
	ao_set_sidebar_content($vendormenu, "prewidget", true);
	
	include "vendor_access/$slug.php";
	get_footer();
	exit;
}

if (in_array($slug, array('terms'))) {
	page_protect();
	include "vendor_access/$slug.php";
	exit;
}

exit;
