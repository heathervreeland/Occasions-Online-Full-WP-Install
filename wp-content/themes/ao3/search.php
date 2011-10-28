<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();
if (have_posts()) : ?>

	<h2 class="pagetitle">Search Results</h2>

	<?php while (have_posts()) : the_post(); ?>
	<div <?php post_class() ?>>
			<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<small><?php the_time('l, F jS, Y') ?></small>
	
			<div class="entry">
			<p>
				<?php 
					
					if ($excerpt = get_the_excerpt()) {
						//if(has_post_thumbnail()) {
						//	the_post_thumbnail();
						//}
						//else {
						//	echo '<img src="'.get_bloginfo("template_url").'/images/post_thumb.jpg" />';
						//}
						echo $excerpt;
					}
					else{
						the_content();
					}
				?>
			</p>
			</div>
		</div>
	<?php endwhile; ?>


	<div class="pagination clearfix"><?php get_pagination() ?></div>

<?php else : ?>

	<h2 class="center">No posts found. Try a different search?</h2>

<?php endif; ?>
<?php get_footer(); ?>
