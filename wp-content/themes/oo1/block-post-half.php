<?php
	$title = get_the_title();
	$link = get_permalink();
	$content = apply_filters('the_content', get_the_content('...', 0));
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = strip_tags($content);
	$partial = ao_trim_to_length($content, 130, ' ', '');

	echo <<<HEREDOC
	<div class="oo-element-block" onclick="window.location.href='$link'">
		<div class="oo-element-title-wrap">
			<div class="oo-element-title-elipses head2">. . .</div>
			<div class="oo-element-title head2"><a href="$link">$title</a></div>
		</div>
		<!-- oo-element-title-wrap -->
HEREDOC;

	include('block-thumb125.php');

	echo <<<HEREDOC
		<p>$partial <a href="$link">more&nbsp;&gt;</a></p>
	</div>
	<!-- oo-element-block -->
HEREDOC;
?>