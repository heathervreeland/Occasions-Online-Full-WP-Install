<?php
	$ao_search_form = '<form role="search" method="get" name="searchform" id="searchform" action="' . iif( ($_GET['t'] != 'v'), '/', '/guide/search/') . '" >
	<div id="searchdiv">
	<input type="text" value="' . esc_attr(apply_filters('the_search_query', get_search_query())) . '" name="s" id="s" />
	<!--<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />-->
	<span id="searchsubmit" onclick="document.forms[\'searchform\'].submit(); return false;">Search</span>
	</div>
	</form>';
	echo $ao_search_form;
?>