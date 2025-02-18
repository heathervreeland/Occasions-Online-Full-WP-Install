<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();

if (have_posts()) : while (have_posts()) : the_post(); ?>

	<!-- 
	<div class="navigation">
		<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
		<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
	</div>
	-->

	<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
		<h2><?php the_title(); ?></h2>
		<small><?php the_time('F jS, Y') ?> by <?php the_author() ?></small>
		<div class="entry">
			<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>

			<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
			<!-- 
			<p>Categories: <?php the_category(', ') ?><br />
			<?php the_tags( 'Tags: ', ', ', '</p>'); ?>
			-->

			<p class="postmetadata alt">
				<!--
				<small>Posted in <?php the_category(', ') ?>
				<?php the_tags('</small><small>Tags: ', ', ', ''); ?>  </small>
				-->
				<small><?php comments_popup_link('Post the First Comment', '1 	Comment - Post A Comment', '% Comments - Post A Comment'); ?>
				<?php edit_post_link('</small><small>Edit This Post', '', ''); ?>
				<!--
					This entry was posted
					<?php /* This is commented, because it requires a little adjusting sometimes.
						You'll need to download this plugin, and follow the instructions:
						http://binarybonsai.com/wordpress/time-since/ */
						/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
					on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>
					and is filed under <?php the_category(', ') ?>.
					You can follow any responses to this entry through the <?php post_comments_feed_link('RSS 2.0'); ?> feed.

					<?php if ( comments_open() && pings_open() ) {
						// Both Comments and Pings are open ?>
						You can <a href="#respond">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.

					<?php } elseif ( !comments_open() && pings_open() ) {
						// Only Pings are Open ?>
						Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.

					<?php } elseif ( comments_open() && !pings_open() ) {
						// Comments are open, Pings are not ?>
						You can skip to the end and leave a response. Pinging is currently not allowed.

					<?php } elseif ( !comments_open() && !pings_open() ) {
						// Neither Comments, nor Pings are open ?>
						Both comments and pings are currently closed.

					<?php } edit_post_link('Edit this entry','','.'); ?>
				-->
				</small>
			</p>

		</div>
	</div>

<?php comments_template(); ?>

<?php endwhile; else: ?>

	<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>
<?php get_footer(); ?>
