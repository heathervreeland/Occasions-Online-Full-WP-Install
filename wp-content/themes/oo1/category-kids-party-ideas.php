<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
/* 
Category Template: Party Themes Template
*/

get_header();

// override the posts per page
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

if ($paged == 1) {
	query_posts( "posts_per_page=12&cat=$cat");
}
else {
	query_posts( "posts_per_page=12&cat=$cat&paged=$paged");
}

?>
<div class="ruled left"><span class="head2 ruled-text-left"><?php single_cat_title(); ?></span></div>

				<?php if (have_posts()) : ?>
					
					<!--
	
					<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
					<?php /* If this is a category archive */ if (is_category()) { ?>
					<h2 class="pagetitle">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h2>
					<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
					<h2 class="pagetitle">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>
					<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
					<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
					<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
					<h2 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h2>
					<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
					<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
					<?php /* If this is an author archive */ } elseif (is_author()) { ?>
					<h2 class="pagetitle">Author Archive</h2>
					<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
					<h2 class="pagetitle">Blog Archives</h2>
					<?php } ?>
					
					-->

<?php include('block-party-themes.php'); ?>

				<?php else :
				
					if ( is_category() ) { // If this is a category archive
						printf("<h2 class='center'>Sorry, but there aren't any posts in the %s category yet.</h2>", single_cat_title('',false));
					} else if ( is_date() ) { // If this is a date archive
						echo("<h2>Sorry, but there aren't any posts with this date.</h2>");
					} else if ( is_author() ) { // If this is a category archive
						$userdata = get_userdatabylogin(get_query_var('author_name'));
						printf("<h2 class='center'>Sorry, but there aren't any posts by %s yet.</h2>", $userdata->display_name);
					} else {
						echo("<h2 class='center'>No posts found.</h2>");
					}
					//get_search_form();
					
				endif;
				?>

<?php get_footer(); ?>
