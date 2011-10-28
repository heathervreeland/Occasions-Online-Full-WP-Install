		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<div class="post-title-wrap">
				<?php echo get_avatar($post->post_author); ?>
				<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
				<small>
					<?php the_time('F jS, Y') ?> by <?php the_author() ?>
				</small>
			</div>
			<?php
				$tweet_title = get_the_title($post->ID);
				if (strlen($tweet_title) > 100) {
					$tweet_title = ao_trim_to_length($tweet_title, 97, ' ', '...');
				}
			?>
			<div class="post-social-buttons clearme">
				<div class="post-social-twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-text="<?php echo $tweet_title; ?>" data-count="horizontal" data-via="OccasionsMag">Tweet This</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
				<div class="post-social-stumble"><script src="http://www.stumbleupon.com/hostedbadge.php?s=1&r=<?php the_permalink(); ?>"></script></div>
				<div class="post-social-facebook-small"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;layout=button_count&amp;show_faces=false&amp;width=150&amp;action=like&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:150px; height:35px;" allowTransparency="true"></iframe></div>
			</div>
			<div class="entry">
			
				<?php the_content('Read the rest of this entry &raquo;'); ?>
			
			</div>
			
			<small>
				<?php
					$words = explode(' ', get_the_author());
					$first_name = $words[0];
				?>
				<span class="oo-signature"><?php echo $first_name; ?></span> | <?php the_time('F jS, Y') ?> | <a href="/author/<?php the_author_nickname(); ?>">See all posts by <?php echo $first_name; ?></a>
			</small>
			<div class="post-footer">
				<div class="float-right"><span class="head3">Share: </span><span class="head4"><a target="_blank" href="http://twitter.com/home?status=<?php echo "$tweet_title: "; the_permalink(); ?> via @OccasionsMag" title="Share this post on Twitter">Twitter</a> | <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>">Facebook</a></span></div>
				<div class="head3"><?php comments_popup_link('Comment', 'Comment', 'Comment'); ?> <?php edit_post_link('E', '', ''); ?></div>
			</div>
			<div class="oo-editor-guide">
				<div class="oo-editor-guide-vendors1"><div class="oo-editor-guide-vendors2">
					<div class="head4 centered">Search For</div>
					<div class="head1 centered"><a href="/guide">Venues & Vendors</a></div>
				</div></div>
			</div>
			<!-- oo-lead-guide -->
		</div>
