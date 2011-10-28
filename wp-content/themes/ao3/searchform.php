<?php
	$ao_search_form = '<form role="search" method="get" id="searchform" action="' . iif( ($_GET['t'] != 'v'), '/', '/guide/search/') . '" >
	<div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
	<input type="text" value="' . esc_attr(apply_filters('the_search_query', get_search_query())) . '" name="s" id="s" />
	<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
	</div>
	<div id="searchoptions">
	<input id="t1" onclick="javascript: getElementById('. "'" . 'searchform'. "'" . ').action='. "'" . '/'. "'" . '; getElementById('. "'" . 's'. "'" . ').focus();" type="radio" name="t" value="w" '. iif( ($_GET['t'] != 'v'), 'checked ', '') .'/><label for="t1">Website</label> 
	<input id="t2" onclick="javascript: getElementById('. "'" . 'searchform'. "'" . ').action='. "'" . '/guide/search/'. "'" . '; getElementById('. "'" . 's'. "'" . ').focus();" type="radio" name="t" value="v" '. iif( ($_GET['t'] == 'v'), 'checked ', '') .'/><label for="t2">Find a Vendor</label></div>
	</form>';
	echo $ao_search_form;
?>