<?php 
include_once(get_stylesheet_directory() . '/guide-functions.php');
?>
<!-- <h2 class="titlehdr">Advertiser Dashboard</h2> -->
<?php
	if (checkAdmin()) {
		get_adminselector();
	}
	if (isset($_GET['msg'])) {
		echo "<p class=\"error_msg\">$_GET[msg]</p>";
	}
	
	if (get_profile_status() == 'New') {
		$page_id = 2282;
	}
	else {
		$page_id = 2269;
	}
	
	$vendor_home = get_post($page_id);
	$content = $vendor_home->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]>', $content);
	echo $content;
	
?>

<div style="height: 500px;">&nbsp;</div>
