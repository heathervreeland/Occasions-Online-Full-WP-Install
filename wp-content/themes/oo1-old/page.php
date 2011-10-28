<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); 
if (have_posts()) : while (have_posts()) : the_post(); ?>
	<div class="page" id="post-<?php the_ID(); ?>">
	<div class="ruled left"><span class="head2 ruled-text-left"><?php the_title(); ?></span></div>
<?php
	$s1 = pods_url_variable(0);
	$s2 = pods_url_variable(1);
	$s3 = pods_url_variable(2);
	if ($s1 == 'party-ideas' && $s2 != '' && $s3 == '') {
		echo oo_get_gallery_crumb();
	}
?>
		<div class="entry">
			<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>

			<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>

		</div>
	</div>
<?php endwhile; endif; ?>
<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
<?php comments_template(); ?>
<?php get_footer(); ?>
