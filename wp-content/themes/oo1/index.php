<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();

if (have_posts()) : ?>
			
	<?php while (have_posts()) : the_post(); ?>

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<small><?php the_time('F jS, Y') ?> by <?php the_author() ?></small>

			<div class="entry">
				<?php the_content('Read the rest of this entry &raquo;'); ?>
			</div>

			<p class="postmetadata alt">
			<!--
				<small>Posted in <?php the_category(', ') ?>
				<?php the_tags('</small><small>Tags: ', ', ', ''); ?>  </small>
			-->
				<small><?php comments_popup_link('Post the First Comment', '1 Comment - Post A Comment', '% Comments - Post A Comment'); ?>
				<?php edit_post_link('</small><small>Edit This Post', '', ''); ?>
				</small>
			</p>
		</div>

	<?php endwhile; ?>
	<div class="pagination clearfix"><?php get_pagination() ?></div>

<?php else : ?>

	<h2 class="center">Not Found</h2>
	<p class="center">Sorry, but you are looking for something that isn't here.</p>
	<?php get_search_form(); ?>

<?php endif; ?>
<?php 

get_footer();
?>
