<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
include_once('guide-functions.php');
get_header();

						$s = mysql_real_escape_string(strip_tags($_GET['s']));
						$sqlwhere = "profile_type = 'platinum' ".
							"AND ((t.name LIKE '%$s%') ".
							"OR (category1 LIKE '%$s%') ".
							"OR (category2 LIKE '%$s%') ".
							"OR (category3 LIKE '%$s%') ".
							"OR (category4 LIKE '%$s%') ".
							"OR (category5 LIKE '%$s%') ".
							")";

						$vp = new Pod('vendor_profiles');
						$vp->findRecords( 'id', -1, $sqlwhere);
						$total_vps = $vp->getTotalRows();
						
						$avp = array();
						if( $total_vps > 0 ) {
							?>
							<div class="ruled left"><span class="head2 ruled-text-left">Vendors Matching Your Search</span></div>
							<?php
							while ( $vp->fetchRecord() ) {

								// get our fields from the POD
								$avp[$vp->get_field('id')] = get_vendorfields($vp);
								//pod_query("UPDATE wp_pod_tbl_vendor_profiles SET list_views = list_views + 1 WHERE id = " . $vp->get_field('id'));
							}
						}
						
						//if (in_array($cur_cat, array('venues','dining', 'search')) ) {
						//	show_googlemap($avp);
						//}

						shuffle($avp);
						foreach($avp as $vid => $fields) {
							
							// format our address, if at all
							$addr = '';
							if ( $fields["is_venue"] ) {
								$addr = "{$fields['mapaddr']}{$fields['city']}, {$fields['stat']} {$fields['zipc']}";
							}
							
							// create our rating box
							$rating_data = get_ratingbox($fields['rati']);

							echo <<<HEREDOC
							<div class="guidelist_wrap">
								<div class="guidelist_image"><a href="/profile/{$fields["slug"]}"><img src="{$fields["imag"]}" title="{$fields["summ"]}" alt="{$fields["summ"]}"/></a></div>
								<div class="guidelist_content">
									$rating_data<h2 class="guidelist_name"><a href="/profile/{$fields["slug"]}">{$fields["name"]}</a></h2>
									<p>$addr</p>
									<p class="guidelist_desc">{$fields["summ"]}</p>
								</div>
							</div>
HEREDOC;
						}
?>

	<div class="clear">&nbsp;</div>
	<div class="ruled left"><span class="head2 ruled-text-left">Blog Search Results</span></div>

<?php
if (have_posts()) : ?>

	<?php while (have_posts()) : the_post(); ?>
	<div <?php post_class('oo-search-result-block') ?>>
			<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<small><?php the_time('l, F jS, Y') ?></small>
	
			<div class="entry">
			<p>
				<?php 
					
					$content = apply_filters('the_content', get_the_content('...', 0));
					$content = str_replace(']]>', ']]&gt;', $content);
					$content = strip_tags($content);
					$partial = ao_trim_to_length($content, 300, ' ', '');
					include('block-thumb125.php');
					echo $partial;

				?>
			</p>
			</div>
		</div>
		<div class="clear"></div>
	<?php endwhile; ?>


	<div class="pagination clearfix"><?php get_pagination() ?></div>

<?php else : ?>

	<h2 class="center">No posts found. Try a different search?</h2>

<?php endif; ?>
<?php get_footer(); ?>
