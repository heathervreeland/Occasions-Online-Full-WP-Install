<?php
							$content = get_the_content('...', 0);
	
							// we put the gallery code here in case we need to search for the gallery ID inside $content.
							//		to do that we need $content unfiltered, i.e. before any apply_filters() is called.
							$gallery_id = get_post_meta($post->ID, 'gallery_id', true);
							
							// if the gallery ID wasn't in a custom field, look for it in $content
							if (!$gallery_id) {
								$amatches = array();
								if (preg_match('/\[nggallery id=\"*(\d+)\"*\]/i' , $content, $amatches) == 1) {
									$gallery_id = $amatches[1];
								}
							}
		
							// if the gallery ID wasn't in a custom field, look for it in $content
							if (!$gallery_id) {
								$amatches = array();
								if (preg_match('/\[monoslideshow id=\"*(\d+)\"*\]/i' , $content, $amatches) == 1) {
									$gallery_id = $amatches[1];
								}
							}
		
							if ($gallery_id) {
								$plum_gallery = do_shortcode('[plumgallery id="' . $gallery_id . '" hide_controls="true" hide_nav="true" width="240" height="240" fill_stage="true" animation_speed="600" slideshow_speed="4000" num_images="5"]');
							}

							$title = get_the_title();
							$link = get_permalink();
							$content = apply_filters('the_content', $content);
							$content = str_replace(']]>', ']]&gt;', $content);
							$content = strip_tags($content);
							$partial = ao_trim_to_length($content, 400, ' ', '...');
							
							if (!$etitle) { $etitle = 'Occasions Event';}
							if (!$elocation) { $elocation = 'USA';}
		
							echo <<<HEREDOC
								<div id="oo-real-featured-section" class="clearme">
									<div id="oo-real-featured-slideshow" class="shadow-up">
										$plum_gallery
									</div>
									<div id="oo-real-featured-article">
										<h2 id="oo-real-featured-article-title"><a href="$link">$title</a></h2>
										<p id="oo-real-featured-article-story">$partial</p>
										<div id="oo-real-featured-article-more"><a href="$link">Read More &gt;&gt;&gt;</a></div>
									</div>
								</div>
								<!-- oo-real-featured-section -->
HEREDOC;
?>