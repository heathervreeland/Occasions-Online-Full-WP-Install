<?php 
include_once(get_stylesheet_directory() . '/guide-functions.php');
?>
<?php
	if (checkAdmin()) {
		get_adminselector();
	}
	
	$page_id = 2385;
	$vendor_home = get_post($page_id);
	$content = $vendor_home->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]>', $content);
	echo $content;
	echo '<!-- hide the stupid link within block -->';
	echo '<div style="display:none;"><div class="linkwithin_div"></div></div>';
	
?>

<div style="height: 500px;">&nbsp;</div>
