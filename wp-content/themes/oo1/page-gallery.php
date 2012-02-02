<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
/*
Template Name: Stand Alone Gallery 
*/
get_header();

$gallery_id = pods_url_variable(1);

$shortcode = '[nggallery id=' . $gallery_id . ']';

if (have_posts()) : while (have_posts()) : the_post(); 
?>
	<div class="page" id="post-<?php the_ID(); ?>">
	<div class="ruled left"><span class="head2 ruled-text-left"><?php the_title(); ?></span></div>
		<div class="entry">

      <?php 

      echo do_shortcode( $shortcode ); 

      ?>

		</div>
	</div>
<?php endwhile; endif;

get_footer(); 
?>
