		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

			<div class="post-title-wrap clearme">
				<img id="default-thumb" src="/media/images/mag-thumb.png" alt="" title="">
				<?php echo get_avatar($post->post_author); ?>
				<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
				<small>
					Posted <?php the_time('F jS, Y') ?> by <?php the_author() ?> |
					<?php comments_popup_link('Post the First Comment', '1 	Comment - Post A Comment', '% Comments - Post A Comment'); ?>
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
				<div class="post-social-facebook"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php the_permalink(); ?>&amp;layout=standard&amp;show_faces=false&amp;width=400&amp;action=like&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:400px; height:35px;" allowTransparency="true"></iframe></div>
        <?php //added by Ben Kaplan - 10/29/11 ?>
        <div class="post-social-1plus"><div class="g-plusone" data-size="medium" data-annotation="bubble" data-width="50" data-href="<?php the_permalink(); ?>"></div></div>
			</div>
			<div class="entry">

			
				<?php
				
					$content = get_the_content();
					// ===============================================================
					// if we are on page 2 (or more) of a post that has a gallery
					// 		shortcode in the content, extract the shortcode and 
					//		display ONLY the gallery by executing the shortcode.
					// ===============================================================
					if ((stripos($_SERVER['QUERY_STRING'], 'nggpage=') !== false || stripos($_SERVER['QUERY_STRING'], 'pid=') !== false) && preg_match('/\[nggallery.+id=\d+\]/i', $content, $a_matches)) {
						echo do_shortcode( $a_matches[0] );
						echo "<div>&nbsp;</div>";
					}
					else {
						// these are the stylesource "credit" fields
						$afields = array(
							credit_photography => 'Photography',
							credit_videography => 'Videography',
							credit_venue => 'Venue',
							credit_catering => 'Caterer',
							credit_decor => 'Decor',
							credit_linen => 'Linen',
							credit_floral => 'Florals',
							credit_eventplanner => 'Event Planner',
							credit_wedding_dress => 'Wedding Dress',
							credit_bridesmaids_dresses => 'Bridesmaid\'s Dresses',
							credit_grooms_attire => 'Groom\'s Attire',
							credit_hair => 'Hair',
							credit_makeup => 'Makeup',
							credit_weddingcake => 'Cake',
							credit_dessert => 'Dessert',
							credit_entertainment => 'Entertainment',
							credit_stationery => 'Stationery',
							credit_calligraphy => 'Calligraphy',
							credit_favors => 'Favors',
							credit_transportation => 'Tramsportation',
							credit_equipmentrentals => 'Equipment Rentals',
							credit_lighting => 'Lighting'
							);
						// get all our custom fields
						$acustom_data = get_post_custom();
						$gallery_id = $acustom_data['gallery_id'][0];
						$photo_test = $acustom_data['credit_photography'][0];
						//$gallery_id = get_post_meta($post->ID, 'gallery_id', true);
		
						//if ($gallery_id && $photo_test) {
						if ($photo_test) {
							if ($gallery_id) {
								echo do_shortcode( '[plumgallery id="' . $gallery_id . '"]' );
								echo "<div id=\"post-stylesource1\">\n";
								echo "<div id=\"post-stylesource2\">\n";
								echo "<div id=\"post-stylesource-title\">STYLEsource</div>\n";
							
								foreach ( $afields as $field => $label ) {
									$data = $acustom_data[$field][0];
									$link = $acustom_data[$field . '_link'][0];
									if ($data) {
										echo "<p><span class=\"uppercase\">$label</span><br />";
										if ($link) {							
											echo "<a target=\"blank\" href=\"$link\">$data</a></p>\n";
										}
										else {
											echo "$data</p>\n";
										}
									}
								}
								
								echo "</div>\n";
								echo "</div>\n";
								echo "<div id=\"post-stylesource-post\">\n";
								the_content('<p class="serif">Read the rest of this entry &raquo;</p>');
								echo "</div>\n";
							}
							else {
								$style_source = "<div id=\"post-stylesource1\">\n";
								$style_source .= "<div id=\"post-stylesource2\">\n";
								$style_source .= "<div id=\"post-stylesource-title\">STYLEsource</div>\n";
							
								foreach ( $afields as $field => $label ) {
									$data = $acustom_data[$field][0];
									$link = $acustom_data[$field . '_link'][0];
									if ($data) {
										$style_source .= "<p><span class=\"uppercase\">$label</span><br />";
										if ($link) {							
											$style_source .= "<a target=\"blank\" href=\"$link\">$data</a></p>\n";
										}
										else {
											$style_source .= "$data</p>\n";
										}
									}
								}
								
								$style_source .= "</div>\n";
								$style_source .= "</div>\n";
								$style_source .= "<div id=\"post-stylesource-post\">\n";
								$content = preg_replace('/\[stylesource\]/i', $style_source, $content, 1, $stylesource_replaced);
								$content = apply_filters('the_content', $content);
								$content = str_replace(']]>', ']]&gt;', $content);
								//$content = strip_tags($content);
								echo $content;
								if ($stylesource_replaced > 0) {
									echo "</div><!-- post-stylesource-post -->\n";
								}
							}
							
						}
						else {
							the_content('<p class="serif">Read the rest of this entry &raquo;</p>');
						}
					}				
				?>

			
				<!-- <div class="head2 right">&gt;&gt; See more <?php single_cat_title(); ?></div> -->

				<div class="clear"></div>
				<small>
					<?php
						$words = explode(' ', get_the_author());
						$first_name = $words[0];
					?>
					<span class="oo-signature"><?php echo $first_name; ?></span> | <?php the_time('F jS, Y') ?> | <a href="/author/<?php the_author_nickname(); ?>">See all posts by <?php echo $first_name; ?></a>
				</small>
				<div id="social-share" class="post-footer">
					<div class="share-comment head3"><?php comments_popup_link('Comment', 'Comment', 'Comment'); ?> <?php edit_post_link('E', '', ''); ?></div>
					<div class="share-social-media head3">
            Share: 
            <a target="_blank" href="http://twitter.com/home?status=<?php echo '$tweet_title: '; the_permalink(); ?> via @OccasionsMag" title="Share this post on Twitter">
              <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/oo_social_twitter.png" alt="Twitter" width="25" height="25" />
              </a> 
              <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>">
                <img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/oo_social_facebook.png" alt="Facebook" width="25" height="25" />
              </a>
            </div>
					<div class="share-subscribe">
            <a href="/subscribe"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/share-subscribe.png" /></a>
          </div>
				</div>
				<div class="clear">&nbsp;</div>
				<div class="ruled left"><span class="head2 ruled-text-left">Related Articles</span></div>
				<div class="linkwithin_div"></div>
				<div class="clear">&nbsp;</div>
			</div>
		</div>
