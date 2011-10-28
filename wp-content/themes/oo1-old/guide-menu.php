<?php
// bring in the WP bling...
require("/home/oonline/public_html/wp-blog-header.php");
// for some reason WP thinks this is a "not found" page and returns a 404 error header, so we force a 200
status_header(200);
?>
	<div id="menu-content-featured">
		<div class="head3">Highest Rated Vendors</div>
<?php
	include_once('guide-functions.php');

	$vp = new Pod('vendor_profiles');
	$vp->findRecords( '', 0, '', 'SELECT * FROM wp_pod_tbl_vendor_profiles WHERE profile_type = \'platinum\' AND rating > 4 ORDER BY NAME');
	$total_vps = $vp->getTotalRows();
	
	$avp = array();
	if( $total_vps > 0 ) {
		while ( $vp->fetchRecord() ) {
			// get our fields from the POD
			$avp[$vp->get_field('id')] = get_vendorfields($vp);
		}
	}
	
	// shuffle our vendors and then only take the first two
	shuffle($avp);
	$avp = array_slice($avp, 0, 3, true);

	foreach($avp as $vid => $fields) {
		
		// create our rating box
		$rating_data = get_ratingbox($fields["rati"]);

		echo <<<HEREDOC
		<div class="menu-featured-vendor" onclick="window.location.href='/profile/{$fields["slug"]}'">
			<a href="/profile/{$fields["slug"]}"><img src="{$fields["imag"]}" title="{$fields["summ"]}" /></a>
			<a href="/profile/{$fields["slug"]}">{$fields["name"]}</a>
			<!-- <h2 class="guidelist_name"><a href="/profile/{$fields["slug"]}">{$fields["name"]}</a></h2> -->
			$rating_data
		</div>
		
HEREDOC;
	}
?>
	</div>
	<div id="menu-content-guidelist">
<?php
	$categories = new Pod('categories');
	$categories->findRecords( '', 0, '', 'SELECT name, slug, short_title, description FROM wp_pod_tbl_categories WHERE hide <> 1 AND priority = 1 ORDER BY sort_order, name');
	$total_cats = $categories->getTotalRows();
	
	if( $total_cats > 0 ) {
		while ( $categories->fetchRecord() ) {
			$cat_name		= $categories->get_field('name');
			$cat_slug		= $categories->get_field('slug');
			echo '<div class="menu-guide-list"><a href="/atlanta/', $cat_slug, '">', $cat_name, '</a></div>';
		}
	}
?>
<p class="menu-guide-list">&nbsp;</p>
<p class="menu-guide-list"><a href="/atlanta/">more...</a></p>
</div>
