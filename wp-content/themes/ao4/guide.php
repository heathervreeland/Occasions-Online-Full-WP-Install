<?php

/* Template Name: Vendor Guide */

include_once('guide-functions.php');
ao_set_in_guide(true);

// get our category from the URL
$cur_cat = pods_url_variable(1);
$sub_cat = pods_url_variable(2);
$sub_cat_name = '';

if ($cur_cat == '') {
	$cur_cat = 'home';
}

// if there is no category default to the venue guide, redirect there.
//if (!in_array($cur_cat, array('venues','dining', 'services', 'search')) ) {
//if (($cur_cat == '') || ($cur_cat == 'services')) {
//	header('Location: http://www.atlantaoccasions.com/guide/venues');
//	exit;
//}

if ($cur_cat == 'search') {
	// descriptions and SEO stuff normally comes from the database, but if it was a search, then
	//	set the SEO titles here.
	$cur_title = 'Search Results for Professional Wedding and Event Vendors - AtlantaOccasions.com';
	$cur_head = 'Search Results';
	$cur_desc = '';
}
elseif ($cur_cat == 'home') {
	$cur_title = 'Atlanta Wedding and Event Professionals Guide - AtlantaOccasions.com';
	$cur_head = 'Search Results';
	$cur_desc = '';
}
else {
	// get our category meta infomation from the database
	$cp = new Pod('categories');
	$cp->findRecords( '', -1, '', "SELECT * FROM wp_pod_tbl_categories WHERE slug = '$cur_cat'");
	$total_cps = $cp->getTotalRows();
	if ($cp->fetchRecord()) {
		$cur_title = $cp->get_field('page_title');
		$cur_head = $cp->get_field('page_heading');
		$cur_desc = $cp->get_field('description');
	}
	if ($cur_cat == 'venues' && $sub_cat != '') {
		$vt = new Pod('venue_types');
		$vt->findRecords( '', -1, '', "SELECT * FROM wp_pod_tbl_venue_types WHERE slug = '$sub_cat'");
		$total_vts = $vt->getTotalRows();
		if ($vt->fetchRecord()) {
			$sub_cat_name = $vt->get_field('name');
			$cur_title .= ' - ' . $sub_cat_name;
		}
	}
}

// set our titles before we spit out the header.
ao_set_title($cur_title);
get_header();

?>
				<div class="post">
<?php
					if ($cur_cat == 'test') {
						
						echo '<p>Nothing to see here... move along.</p>';
						

					}
					elseif ($cur_cat == 'home') {
?>
						<div class="scriptyline"><span class="scriptytitle">Atlanta Wedding & Event Services</span></div>
						<!-- <div><img title="Atlanta Wedding & Event Services" src="<?php bloginfo('stylesheet_directory'); ?>/images/services.jpg" /></div> -->
<?php
						$categories = new Pod('categories');
						$categories->findRecords( '', 0, '', 'SELECT name, slug, short_title, description FROM wp_pod_tbl_categories WHERE hide <> 1 ORDER BY sort_order, name');
						$total_cats = $categories->getTotalRows();
						
						echo '<div class="guide-catcolumn">';
						$cols = 0;
						if( $total_cats > 0 ) {
							while ( $categories->fetchRecord() ) {
								$cat_name		= $categories->get_field('short_title');
								$cat_slug		= $categories->get_field('slug');
								echo '<div class="guide-cat"><a href="/guide/', $cat_slug, '">', $cat_name, '</a></div>';
								$cols++;
								if ($cols == 5) {
									echo '</div><div class="guide-catcolumn">';
									$cols = 0;
								}
							}
						}
						echo '</div>';
						echo '<div class="clear"></a>';
						echo '<div class="guide-catcolumn"><a href="/guide/venues"><div class="guide-catimage"><img src="', bloginfo('stylesheet_directory'), '/images/cat-venues.jpg" /></div><div class="guide-catname">Venues</div></a></div>';
						echo '<div class="guide-catcolumn"><a href="/guide/photographers"><div class="guide-catimage"><img src="', bloginfo('stylesheet_directory'), '/images/cat-photographers.jpg" /></div><div class="guide-catname">Photographers</div></a></div>';
						echo '<div class="guide-catcolumn"><a href="/guide/caterers"><div class="guide-catimage"><img src="', bloginfo('stylesheet_directory'), '/images/cat-caterers.jpg" /></div><div class="guide-catname">Caterers</div></a></div>';
						echo '<div class="guide-catcolumn"><a href="/guide/rentals"><div class="guide-catimage"><img src="', bloginfo('stylesheet_directory'), '/images/cat-rentals.jpg" /></div><div class="guide-catname">Rentals</div></a></div>';
						echo '<div class="guide-catcolumn"><a href="/guide/florals"><div class="guide-catimage"><img src="', bloginfo('stylesheet_directory'), '/images/cat-decor.jpg" /></div><div class="guide-catname">Decor</div></a></div>';
						echo '<div class="clear"></a>';

?>
						<p>&nbsp;</p>
						<div class="scriptyline"><span class="scriptytitle">Atlanta Event Venues</span></div>
						<!-- <div><img title="Atlanta Event Venues" src="<?php bloginfo('stylesheet_directory'); ?>/images/venues.jpg" /></div> -->
<?php
						$types = new Pod('venue_types');
						$types->findRecords( '', 0, '', 'SELECT name, slug FROM wp_pod_tbl_venue_types ORDER BY name');
						$total_types = $types->getTotalRows();
						
						echo '<div class="guide-typecolumn">';
						$cols = 0;
						echo '<div class="guide-type"><a href="/guide/venues">all venues</a></div>';
						$cols++;
						if( $total_types > 0 ) {
							while ( $types->fetchRecord() ) {
								$type_name		= $types->get_field('name');
								$type_slug		= $types->get_field('slug');
								echo '<div class="guide-type"><a href="/guide/venues/', $type_slug, '">', $type_name, '</a></div>';
								$cols++;
								if ($cols == 5) {
									echo '</div><div class="guide-typecolumn">';
									$cols = 0;
								}
							}
						}
						echo '</div>';
						
						$sqlwhere = "profile_type = 'platinum' ".
							"AND ((category1 = 'venues') ".
							"OR (category2 = 'venues') ".
							"OR (category3 = 'venues') ".
							"OR (category4 = 'venues') ".
							"OR (category5 = 'venues') ".
							")";
	
						$vp = new Pod('vendor_profiles');
						$vp->findRecords( 'id', -1, $sqlwhere);
						$total_vps = $vp->getTotalRows();
						
						$avp = array();
						if( $total_vps > 0 ) {
							while ( $vp->fetchRecord() ) {
								// get our fields from the POD
								$avp[$vp->get_field('id')] = get_vendorfields($vp);
							}
						}
						
						echo '<div class="clear" style="height: 0;"></div>';
						show_googlemap($avp, -1, 9, true, false);

						echo '<div class="clear"></a>';
						echo '<div class="guide-featuredcolumn">';
						echo '<div class="guide-featuredcolumninner">';
						echo '<div class="guide-featuredtitle">Recent Real Events</div>';

						$recent = new WP_Query();
						$recent->query('cat=11&showposts=10');
						while($recent->have_posts()) {
							$recent->the_post();
							echo '<div class="guide-featuredname">&bull;&nbsp;&nbsp;<a title="', the_title('','',false), '" href="', the_permalink(), '">', ao_trim_to_length(the_title('','',false), 30, ' ', '...'), '</a></div>';
						}

						echo '</div>';
						echo '</div>';
						echo '<div id="guide-featuredcolumn-right" class="guide-featuredcolumn">';
						echo '<div class="guide-featuredcolumninner">';
						echo '<div class="guide-featuredtitle">Popular Vendors</div>';

						$pops = new Pod('vendor_profiles');
						$pops->findRecords( '', 0, '', 'SELECT name, slug FROM wp_pod_tbl_vendor_profiles WHERE profile_type = \'platinum\' ORDER BY profile_views DESC LIMIT 0, 10');
						$total_pops = $pops->getTotalRows();
						
						if( $total_pops > 0 ) {
							while ( $pops->fetchRecord() ) {
								$pops_name		= $pops->get_field('name');
								$pops_slug		= $pops->get_field('slug');
								echo '<div class="guide-featuredname">&bull;&nbsp;&nbsp;<a href="/profile/', $pops_slug, '">', $pops_name, '</a></div>';
							}
						}

						echo '</div>';
						echo '</div>';
						

					}
					elseif ($cur_cat == 'services') {
						
						// "services" is different in that it can contain subcategories, so get
						//		those subcategories and display them
						
						$services = new Pod('categories');
						$services->findRecords( '', 0, '', 'SELECT name, slug, description FROM wp_pod_tbl_categories WHERE parentid IN (3) AND hide <> 1 ORDER BY name');
						$total_cats = $services->getTotalRows();
						
						echo "<h2>$cur_head</h2>";
						echo "<p>$cur_desc</p>";
						
						echo '<table border=0 width=600><tr><td width="50%">';
						$i = 0;
						if( $total_cats > 0 ) {
							while ( $services->fetchRecord() ) {
								$i++;
								if ($i > ($total_cats/2)) {
									echo '</td><td width="50%">';
									$i = 0;
								}
								// set our variables
								$cat_name		= $services->get_field('name');
								$cat_slug		= $services->get_field('slug');
								$cat_desc		= $services->get_field('description');
								
								echo <<<HEREDOC
<div class="guidesvcs_category" id="category_$cat_slug">
	<h2 class="guidesvcs_name" id="category_title_$cat_slug">
		<a href="/guide/$cat_slug/">$cat_name</a>
	</h2>
</div>

HEREDOC;
							}
						}
						echo '</td></tr></table>';
						featuredvendors('<h2>You Might Like . . .</h2>', 'services', 1);
					}
					else {
						featuredvendors('<h2>You Might Like . . .</h2>', $cur_cat, 1);
						
						$s = mysql_real_escape_string(strip_tags($_GET['s']));

						// get the category
						if (($cur_cat == 'search') && ($s != "")) {
							echo "<h2>$cur_head</h2>";
							$s = mysql_real_escape_string(strip_tags($_GET['s']));
							$sqlwhere = "profile_type = 'platinum' ".
								"AND ((t.name LIKE '%$s%') ".
								"OR (category1 LIKE '%$s%') ".
								"OR (category2 LIKE '%$s%') ".
								"OR (category3 LIKE '%$s%') ".
								"OR (category4 LIKE '%$s%') ".
								"OR (category5 LIKE '%$s%') ".
								")";
						}
						else {	

							echo "<h2>$cur_head", iif($sub_cat_name != '', " ($sub_cat_name)", ''), "</h2>";
							echo "<p>$cur_desc</p>";

							// now get the vendor list
							
							// leave this here for REFERENCE ONLY, it is not used
							$sql = "SELECT wp_pod_tbl_vendor_profiles.id AS vid, ".
								"wp_pod_tbl_vendor_profiles.name AS name, ".
								"wp_pod_tbl_vendor_profiles.slug AS slug, ".
								"wp_pod_tbl_vendor_profiles.description AS description, ".
								"v2_profile_image_sm ".
								"FROM wp_pod_tbl_vendor_profiles ".
								"JOIN wp_pod ON (wp_pod_tbl_vendor_profiles.id = wp_pod.tbl_row_id) ".
								"JOIN wp_pod_rel ON (wp_pod.id = wp_pod_rel.pod_id) ".
								"JOIN wp_pod_tbl_categories ON (wp_pod_rel.tbl_row_id = wp_pod_tbl_categories.id) ".
								"WHERE ".
								"wp_pod_rel.field_id = 110 ".
								"AND profile_type = 'Platinum' ".
								"AND wp_pod_tbl_categories.slug = '$cur_cat'";
	
							$sqlwhere = "profile_type = 'platinum' ".
								"AND ((category1 = '$cur_cat') ".
								"OR (category2 = '$cur_cat') ".
								"OR (category3 = '$cur_cat') ".
								"OR (category4 = '$cur_cat') ".
								"OR (category5 = '$cur_cat') ".
								")";
							
							if ($cur_cat == 'venues' && $sub_cat != '') {
							
								$sqlwhere .= " AND ((type1 = '$sub_cat') ".
									"OR (type2 = '$sub_cat') ".
									"OR (type3 = '$sub_cat') ".
									")";
							}
						}
	
						$vp = new Pod('vendor_profiles');
						$vp->findRecords( 'id', -1, $sqlwhere);
						$total_vps = $vp->getTotalRows();
						
						$avp = array();
						if( $total_vps > 0 ) {
							while ( $vp->fetchRecord() ) {

								// get our fields from the POD
								$avp[$vp->get_field('id')] = get_vendorfields($vp);
								//pod_query("UPDATE wp_pod_tbl_vendor_profiles SET list_views = list_views + 1 WHERE id = " . $vp->get_field('id'));
							}
						}
						
						if (in_array($cur_cat, array('venues','dining', 'search')) ) {
							show_googlemap($avp);
						}

						shuffle($avp);
						foreach($avp as $vid => $fields) {
							
							// format our address, if at all
							$addr = '';
							if ( $fields["is_venue"] ) {
								$addr = "{$fields['mapaddr']}{$fields['city']}, {$fields['stat']} {$fields['zipc']}";
							}
							
							// create our rating box
							$rating_data = get_ratingbox($fields["rati"]);

							echo <<<HEREDOC
							<div class="guidelist_wrap">
								<div class="guidelist_image"><a href="/profile/{$fields["slug"]}"><img src="{$fields["imag"]}" title="{$fields["summ"]}" alt="{$fields["summ"]}"/></a></div>
								<div class="guidelist_content">
									$rating_data<h2 class="guidelist_name"><a href="/profile/{$fields["slug"]}">{$fields["name"]}</a></h2>
									<p>$addr</p>
									<p class="guidelist_desc">{$fields["summ"]}</p>
								</div>
							</div>
HEREDOC;
						}
					}
?>
				</div>
<?php get_footer(); ?>
