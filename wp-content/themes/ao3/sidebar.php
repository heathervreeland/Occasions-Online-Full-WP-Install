<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
	<div id="sidebar" role="complementary">
<?php if ( is_front_page() ) { ?>
<?php }
ao_sidebar_content('prewidget');
?>
		<ul>
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
			if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
				<li>
					<?php get_search_form(); ?>
				</li>
	
				<li>
					<h2>Author</h2>
					<p>A little something about you, the author. Nothing lengthy, just an overview.</p>
				</li>
	
				<?php 
				if ( is_404() || is_category() || is_day() || is_month() ||
							is_year() || is_search() || is_paged() ) {
					?>
					<li>
		
						<?php /* If this is a 404 page */ 
						if (is_404()) { ?>
							<?php /* If this is a category archive */ 
						}
						elseif (is_category()) { ?>
							<p>You are currently browsing the archives for the <?php single_cat_title(''); ?> category.</p>
			
							<?php /* If this is a daily archive */ 
						}
						elseif (is_day()) { ?>
							<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> blog archives
							for the day <?php the_time('l, F jS, Y'); ?>.</p>
				
							<?php /* If this is a monthly archive */ 
						}
						elseif (is_month()) { ?>
							<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> blog archives
							for <?php the_time('F, Y'); ?>.</p>
				
							<?php /* If this is a yearly archive */ 
						} 
						elseif (is_year()) { ?>
							<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> blog archives
							for the year <?php the_time('Y'); ?>.</p>
				
							<?php /* If this is a search result */ 
						}
						elseif (is_search()) { ?>
							<p>You have searched the <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> blog archives
							for <strong>'<?php the_search_query(); ?>'</strong>. If you are unable to find anything in these search results, you can try one of these links.</p>
				
							<?php /* If this set is paginated */ 
						}
						elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
							<p>You are currently browsing the <a href="<?php bloginfo('url'); ?>/"><?php bloginfo('name'); ?></a> blog archives.</p>
			
							<?php 
						} ?>
		
					</li>
					<?php 
				}?>
			<?php endif; ?>
		</ul>
		<img width="300" height="0" src="<?php bloginfo('stylesheet_directory'); ?>/images/1.gif" /><br />
		<?php
		ao_sidebar_content('postwidget');
		if (!ao_get_in_vendorarea()) {
			include('ads-zone-right300.php');
		}
		ao_sidebar_content('trailing');
		?>
	</div>
