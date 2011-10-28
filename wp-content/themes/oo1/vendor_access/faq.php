<?php 
include_once(get_stylesheet_directory() . '/guide-functions.php');
?>
<?php
	$page_id = 6532;
	$post_content = get_post($page_id);
	$content = $post_content->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]>', $content);
	echo '<div class="post">';
	echo '<h2>', $post_content->post_title, '</h2>';
	echo $content;
	echo '</div>';
	echo '<!-- hide the stupid link within block -->';
	echo '<div style="display:none;"><div class="linkwithin_div"></div></div>';
?>

<div style="height: 500px;">&nbsp;</div>
