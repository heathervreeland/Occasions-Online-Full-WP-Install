<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

	<div id="page-outer">
	<div id="page-inner">
	<table id="thepage">
		<tr>
			<td id="pageleft">
				<div id="greektext">
				<img width="140" height="0" src="<?php bloginfo('stylesheet_directory'); ?>/images/1.gif" /><br />
				<div class="from_editor">
				<div id="editor_title"><img src="/media/images/editor.jpg" /></div>
				<div id="editor_image"><img src="/media/images/editor.png" title="Publisher & Editor-in-Chief of Atlanta Occasions Magazine. Entrepreneurial Extraordinaire, Business Blogger, Marketing Maven, Queen." atl="Publisher & Editor-in-Chief of Atlanta Occasions Magazine. Entrepreneurial Extraordinaire, Business Blogger, Marketing Maven, Queen." /></div>
				<p>Welcome to the new AtlantaOccasions.com, or what’s now cleverly known as Occasions a la Mode:  The blog for celebrating in style. </p>
				<p>We love a great party and don’t discriminate on type so, bring your wedding, mitzvah, birthday party, anniversary soiree, charity gala or business gathering ideas to the table because we’ve blazed a new trail to discovering painless party planning, talented quality-centric event vendors and fashion-forward fêtes just for you. Now that is sweet.</p>
				<p align="right">- Heather Vreeland</p>
				</div>
				<?php include('ads-zone-left125.php'); ?>
				</div>
			</td>
			<td id="pagecenter">
				<?php if (have_posts()) : ?>
			
					<?php while (have_posts()) : the_post(); ?>
			
						<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
							<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
							<small><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></small>
			
							<div class="entry">
								<?php the_content('Read the rest of this entry &raquo;'); ?>
							</div>
			
							<p class="postmetadata alt">
								<small>Posted in <?php the_category(', ') ?>
								<?php the_tags('</small><small>Tags: ', ', ', ''); ?>  </small><small><?php comments_popup_link('Post the First Comment', '1 Comment - Post A Comment', '% Comments - Post A Comment'); ?>
								<?php edit_post_link('</small><small>Edit This Post', '', ''); ?>
								</small>
							</p>
						</div>
			
					<?php endwhile; ?>
			        <div class="pagination clearfix"><?php get_pagination() ?></div>
			        <!-- 
					<div class="navigation">
						<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
						<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
					</div>
					-->
			
				<?php else : ?>
			
					<h2 class="center">Not Found</h2>
					<p class="center">Sorry, but you are looking for something that isn't here.</p>
					<?php get_search_form(); ?>
			
				<?php endif; ?>
			</td>
<?php get_footer(); ?>
