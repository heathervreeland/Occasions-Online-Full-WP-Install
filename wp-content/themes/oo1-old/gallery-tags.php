<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
/*
Template Name: Gallery Tags
*/
get_header();

$slug = strtolower(pods_url_variable(2));
$slug .= ",$slug\-2";

if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="page" id="post-<?php the_ID(); ?>">
	<div class="ruled left"><span class="head2 ruled-text-left"><?php the_title(); ?></span></div>
		<div class="entry">
			<?php echo do_shortcode('[nggtags gallery=' . $slug . ']'); ?>

		</div>
	</div>
<?php endwhile; endif;

edit_post_link('Edit this entry.', '<p>', '</p>');
get_footer(); 
?>
