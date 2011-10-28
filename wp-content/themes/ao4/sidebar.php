<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
	<div id="sidebar" role="complementary">
<?php if ( is_front_page() ) { ?>
<?php }
ao_sidebar_content('prewidget');
?>
		<img width="300" height="0" src="<?php bloginfo('stylesheet_directory'); ?>/images/1.gif" /><br />
		<?php
		ao_sidebar_content('postwidget');
		if (!ao_get_in_vendorarea()) {
			include('ads-zone-right300.php');
		}
		ao_sidebar_content('trailing');
		?>
	</div>
	<div id="sidebar2">
		<h2><a href="/guide/">Atlanta Wedding & Event Services</a></h2>
<?php
	$categories = new Pod('categories');
	$categories->findRecords( '', 0, '', 'SELECT name, slug, short_title, description FROM wp_pod_tbl_categories WHERE hide <> 1 ORDER BY name');
	$total_cats = $categories->getTotalRows();
	
	if( $total_cats > 0 ) {
		while ( $categories->fetchRecord() ) {
			$cat_name		= $categories->get_field('name');
			$cat_slug		= $categories->get_field('slug');
			echo '<p><a href="/guide/', $cat_slug, '">', $cat_name, '</a></p>';
		}
	}
?>
	</div>
