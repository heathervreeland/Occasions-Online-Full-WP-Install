<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();

?>

<div id="post-editor" class="shadow-up">
	<div id="post-editor-title">From the Editor</div>
	<div id="post-editor-content">
		<div class="head3">Event Elements</div>
		<p>
<?php
		$args=array(
			'child_of' => 12
			);
		$categories = get_categories($args);
	
		foreach($categories as $category) { 
			$cat_name		= $category->name;
			$cat_link		= get_category_link( $category->term_id );
			echo '<a href="' , $cat_link, '" title="View all posts in ' . $cat_name . '">', $cat_name, '</a><br />';
		}
?>
		</p>

		<div class="centered" style="margin: 30px 0px;" >
			<img src="<?php bloginfo('stylesheet_directory'); ?>/images/oo-section-block-divider.png" />
		</div>

		<div class="head3">Inspiration</div>
		<p>
<?php
		$args=array(
			'category_name'    => 'Inspiration',
			'title_li'			=> null,
			'categorize'		=> 0,
			'before'			=> '',
			'after'				=> '<br />',
			'class'				=> 'oo-editor-links',
			'category_before'	=> '',
			'category_after'	=> ''
	    );
		wp_list_bookmarks($args);
?>
		</p>

		<div class="centered" style="margin: 30px 0px;" >
			<img src="<?php bloginfo('stylesheet_directory'); ?>/images/oo-section-block-divider.png" />
		</div>

		<div class="head3">Favorite Blogs</div>
		<p>
<?php
		$args=array(
			'category_name'    => 'Favorite Blogs',
			'title_li'			=> null,
			'categorize'		=> 0,
			'before'			=> '',
			'after'				=> '<br />',
			'class'				=> 'oo-editor-links',
			'category_before'	=> '',
			'category_after'	=> ''
	    );
		wp_list_bookmarks($args);
?>
		</p>

	</div>
	
</div>
<div id="post-editor-post">

<?php
if (have_posts()) : ?>
			
	<?php while (have_posts()) : the_post(); ?>

		<?php include('block-post-editor.php'); ?>

	<?php endwhile; ?>
	<div class="pagination clearfix"><?php get_pagination() ?></div>
</div>

<?php else : ?>

<h2 class="center">Not Found</h2>
	<p class="center">Sorry, but you are looking for something that isn't here.</p>
	<?php get_search_form(); ?>

<?php endif; ?>
<?php 

get_footer();
?>
