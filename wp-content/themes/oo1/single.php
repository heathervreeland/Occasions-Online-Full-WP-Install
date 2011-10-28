<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();

if (have_posts()) : while (have_posts()) : the_post();

	include('block-post-full.php');
	comments_template();

endwhile; else:

	echo "<p>Sorry, no posts matched your criteria.</p>";

endif;

get_footer();

?>
