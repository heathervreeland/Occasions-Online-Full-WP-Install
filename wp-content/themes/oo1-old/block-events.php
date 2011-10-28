<?php 
					$i = 0;
					while (have_posts()) : the_post();
						$i++;
						
						if ($i == 1) {
							include('block-featured-slideshow.php');
						}
						else {
							include('block-event-thumb125.php');
						}
						
?>
					<?php endwhile; ?>
	
					<p>&nbsp;</p>
					<div class="pagination clearme"><?php get_pagination() ?></div>
