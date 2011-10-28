<p class="crumb">Back to: <?php echo oo_get_gallery_crumb(); ?></p>
<?php 
					while (have_posts()) : the_post();
						include('block-event-thumb125.php');
					endwhile;
?>
					<p>&nbsp;</p>
					<div class="pagination clearme"><?php get_pagination() ?></div>
