<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

// pull the subdirectories
$profile = pods_url_variable(0); 
$cur_cat = pods_url_variable(1);
//echo 'the cat is ' . $profile;

// inserted by Ben Kaplan - 11/18/11
// pull global variable set in profile.php
global $is_venue;

?>
	<div id="YYsidebar" role="complementary">
<?php if ( is_front_page() ) { ?>
<?php }
ao_sidebar_content('prewidget');
?>
		<img width="320" height="0" src="<?php bloginfo('stylesheet_directory'); ?>/images/1.gif" /><br />
		
<?php
		if (!ao_get_in_profile()) {
?>
			<div id="oo-subscribe1"><div id="oo-subscribe2">
			<div class="head1 centered">Subscribe</div>
			<div class="head5 knockout centered"><a href="/subscribe">In Print</a> • <a class="register_link" href="/register/">Email</a> • <a href="/feed">RSS</a></div>
			</div></div>
			<?php get_search_form(); ?>

<?php
		}

    // inserted by Ben Kaplan - 11/18/11
    // checks to see if we are in a /venues directory
    // if so, then display venues sub-nav
    if ( $cur_cat == 'venues' ) { ?>

      <div class="sidebar-nav">
        <div class="oo-sidebar-section-title">Types of Venues <img src="http://dev.occasionsonline.com/wp-content/themes/oo1/images/oo-sidebar-divider.png"></div>
        <dl class="oo-sidebar-nav-list">
          <dd><a href="/atlanta/venues/hotel">Hotel Ballrooms</a></dd>
          <dd><a href="/atlanta/venues/country-club">Country Clubs</a></dd>
          <dd><a href="/atlanta/venues/antebellum-home">Antebellum Homes</a></dd>
          <dd><a href="/atlanta/venues/outdoor">Outdoor</a></dd>
          <dd><a href="/atlanta/venues/rooftop">Rooftop</a></dd>
          <dd><a href="/atlanta">More . . .</a></dd>
        </dl>
      </div>

<?php 
    }

    // inserted by Ben Kaplan - 11/18/11
    // checks to see if we are in a /events directory
    // if so, then display links to search pages for other party ideas 
    if ( $profile == 'events' ) { ?>

      <div class="sidebar-nav">
        <div class="oo-sidebar-section-title">Party Ideas <img src="http://dev.occasionsonline.com/wp-content/themes/oo1/images/oo-sidebar-divider.png"></div>
        <dl class="oo-sidebar-nav-list">
          <dd><a href="/party-ideas/colors">Search by Color</a></dd>
          <dd><a href="/party-ideas/party-themes">Search by Themes</a></dd>
        </dl>
      </div>

<?php 
    }
		ao_sidebar_content('postwidget');
		if (!ao_get_in_vendorarea()) {
			include('ads-zone-right300.php');
		}
		ao_sidebar_content('trailing');
		?>

		<div class="oo-sidebar-zone">
			<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FOccasions-Magazine%2F145861875458364&amp;width=300&amp;colorscheme=light&amp;show_faces=true&amp;stream=false&amp;header=false&amp;height=330" scrolling="no" frameborder="0" style="background-color: #FFF; border:none; overflow:hidden; width:300px; height:330px;" allowTransparency="true"></iframe>
		</div>
	</div>

<?php
return;
// ==========================================================================================
?>

	<div class="oo-sidebar-section">
	<div class="oo-sidebar-section-title">Services<img src="<?php bloginfo('stylesheet_directory'); ?>/images/oo-sidebar-divider.png" /></div>
	</div>
<?php
	$categories = new Pod('categories');
	$categories->findRecords( '', 0, '', 'SELECT name, slug, short_title, description FROM wp_pod_tbl_categories WHERE hide <> 1 ORDER BY name');
	$total_cats = $categories->getTotalRows();
	$half_cats = (int) (($total_cats + 1) / 2);

	$i = 0;
	echo '<div class="oo-sidebar-halfcol-left">';
	if( $total_cats > 0 ) {
		while ( $categories->fetchRecord() ) {
			$cat_name		= $categories->get_field('name');
			$cat_slug		= $categories->get_field('slug');
			echo '<div class="oo-sidebar-guide-list"><a href="/guide/', $cat_slug, '" title="View all vendors in the ' . $cat_name . ' category">', $cat_name, '</a></div>';

			$i++;
			if ($i == $half_cats) {
				echo '</div><div class="oo-sidebar-halfcol-right">';
			}
		}
	}
	echo '</div>';
?>



	<div class="oo-sidebar-section">
	<div class="oo-sidebar-section-title">Departments<img src="<?php bloginfo('stylesheet_directory'); ?>/images/oo-sidebar-divider.png" /></div>
	</div>
	<div class="clear"></div>

<?php
	$args=array(
		'child_of' => 12
		);
	$categories = get_categories($args);

	$half_cats = (int) ((count($categories) + 1) / 2);

	$i = 0;
	echo '<div class="oo-sidebar-halfcol-left">';
	foreach($categories as $category) { 
		$cat_name		= $category->name;
		$cat_link		= get_category_link( $category->term_id );
		echo '<div class="oo-sidebar-guide-list"><a href="' , $cat_link, '" title="View all posts in ' . $cat_name . '">', $cat_name, '</a></div>';

		$i++;
		if ($i == $half_cats) {
			echo '</div><div class="oo-sidebar-halfcol-right">';
		}
	}
	echo '</div>';
?>

	</div>
?>
