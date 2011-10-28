<?php 
include_once(get_stylesheet_directory() . '/guide-functions.php');
?>
<?php
	$page_id = 2537;
	$vendor_badges = get_post($page_id);
	$content = $vendor_badges->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]>', $content);
	echo '<div class="post">';
	echo '<h2>', $vendor_badges->post_title, '</h2>';
	echo $content;
	echo '</div>';
	echo '<!-- hide the stupid link within block -->';
	echo '<div style="display:none;"><div class="linkwithin_div"></div></div>';

include_once(get_stylesheet_directory() . '/badgebuilder.php');

?>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

