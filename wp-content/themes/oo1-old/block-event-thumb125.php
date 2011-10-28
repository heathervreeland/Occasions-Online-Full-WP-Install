<?php
	$title = get_the_title();
	$link = get_permalink();
	$etitle = get_post_meta($post->ID, 'event_title', true);
	$elocation = get_post_meta($post->ID, 'event_location', true);
	$gallery_id = get_post_meta($post->ID, 'gallery_id', true);
	
	if (!$etitle) { $etitle = 'Occasions Event';}
	if (!$elocation) { $elocation = 'USA';}

	echo <<<HEREDOC
	<div class="oo-real-block" onclick="window.location.href='$link';">
HEREDOC;

	include('block-thumb125.php');


	echo <<<HEREDOC
		<p class="centered">$etitle<br />$elocation</p>
	</div>
	<!-- oo-element-block -->
HEREDOC;
?>