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

ao_set_sidebar_content('<div id="video_teaser"><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="300" height="194" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0"><param name="allowFullScreen" value="true" /><param name="allowscriptaccess" value="always" /><param name="src" value="http://www.youtube.com/v/IYfq8WbZbO4&amp;hl=en_US&amp;fs=1&amp;w=300&amp;h=169&amp;rel=0&amp;hd=1" /><param name="allowfullscreen" value="true" /><embed type="application/x-shockwave-flash" width="300" height="194" src="http://www.youtube.com/v/IYfq8WbZbO4&amp;hl=en_US&amp;fs=1&amp;w=300&amp;h=169&amp;rel=0&amp;hd=1" allowscriptaccess="always" allowfullscreen="true"></embed></object></div>', 'prewidget');
//ao_set_sidebar_content('<div class="from_editor"><div id="sidebarimage"><a href="/blog/from-the-editor" title="From the Editor"><img src="/media/images/editor.png" /></a></div></div>', 'prewidget');
get_footer();
?>
