<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();

if (have_posts()) : 

	$post = $posts[0]; // Hack. Set $post so that the_date() works.
	
	if (is_category()) { 
		$title = single_cat_title("", false);
	}
	elseif( is_tag() ) {
		$title = single_tag_title("Posts Tagged ", false);
	}
	elseif (is_day()) {
		$title = "Archive for " . get_the_time('F jS, Y');
	}
	elseif (is_month()) {
		$title = "Archive for " . get_the_time('F, Y');
	}
	elseif (is_year()) {
		$title = "Archive for " . get_the_time('Y');
	}
	elseif (is_year()) {
		$title = "Archive for " . get_the_time('Y');
	}
	elseif (is_author()) {
		global $post;
		$author_id=$post->post_author;
		$title = "Posts by " . get_the_author_meta( 'display_name', $author_id );
	}
	elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {
		$title = "Blog Archives";
	}
?>
	
	<div class="ruled left"><span class="head2 ruled-text-left"><?php echo $title; ?></span></div>
					
<?php 
					$i = 0;
					while (have_posts()) : the_post();
						$i++;
						
						if ($i == 1) {
							include('block-post-full.php');
							echo '<div class="clear"></div>';
						}
						else {
							include('block-post-half.php');
						}
						
?>
					<?php endwhile; ?>
	
					<p>&nbsp;</p>
					<div class="pagination clear"><?php get_pagination() ?></div>

				<?php else :
				
					if ( is_category() ) { // If this is a category archive
						printf("<h2>Sorry, but there aren't any posts in the %s category yet.</h2>", single_cat_title('',false));
					} else if ( is_date() ) { // If this is a date archive
						echo("<h2>Sorry, but there aren't any posts with this date.</h2>");
					} else if ( is_author() ) { // If this is a category archive
						$userdata = get_userdatabylogin(get_query_var('author_name'));
						printf("<h2>Sorry, but there aren't any posts by %s yet.</h2>", $userdata->display_name);
					} else {
						echo("<h2 class='center'>No posts found.</h2>");
					}
				
				endif;
				?>
<?php get_footer(); ?>
