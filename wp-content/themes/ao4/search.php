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
							echo '<h1>Vendors Matching Your Search</h1>';
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

if (have_posts()) : ?>

	<div class="clear"></div>
	<h1>Blog Search Results</h1>

	<?php while (have_posts()) : the_post(); ?>
	<div <?php post_class() ?>>
			<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
			<small><?php the_time('l, F jS, Y') ?></small>
	
			<div class="entry">
			<p>
				<?php 
					
					if(!empty($post->post_excerpt)) {
						// if there is manual excerpt, show it
						// this is the case for galleries which have a single large image
						
						echo get_the_excerpt();
						
					}
					elseif ($excerpt = get_the_excerpt()) {
						// this case should cover automatic excerpts
						
						if(has_post_thumbnail()) {
							the_post_thumbnail(array(125,125));
						}
						//else {
						//	echo '<img src="'.get_bloginfo("template_url").'/images/post_thumb.jpg" />';
						//}
						echo $excerpt;
					}
					else{
						//if(has_post_thumbnail()) {
						//	the_post_thumbnail(array(125,125));
						//}
						the_content();
					}
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
