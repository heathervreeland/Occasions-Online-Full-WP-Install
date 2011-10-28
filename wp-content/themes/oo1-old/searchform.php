<?php
	$ao_search_form = '<div id="searchdiv">
	<form role="search" method="get" name="searchform" id="searchform" action="' . iif( ($_GET['t'] != 'v'), '/', '/guide/search/') . '" >
	<span id="searchsubmit" onclick="document.forms[\'searchform\'].submit(); return false;">Search</span>
	<input type="text" value="' . esc_attr(apply_filters('the_search_query', get_search_query())) . '" name="s" id="s" />
	</form>
	</div>';
	echo $ao_search_form;
?>