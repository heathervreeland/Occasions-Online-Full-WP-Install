<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
/*
Template Name: Archives
*/

get_header();
?>

	<div id="page-outer">
	<div id="page-inner">
	<table id="thepage">
		<tr>
			<td id="pageleft">
				<?php //include('ads-zone-left140.php'); ?>
			</td>
			<td id="pagecenter">

				<h2>Blogazine:</h2>
					<ul>
						<?php wp_get_archives('type=monthly'); ?>
					</ul>
				
				<h2>Atlanta Occasions Features:</h2>
					<ul>
						 <?php wp_list_categories(); ?>
					</ul>

			</td>
			<td id="pageright">
				<?php get_sidebar(); ?>
			</td>
		</tr>
	</table>
	</div>
	</div>
<?php get_footer(); ?>
