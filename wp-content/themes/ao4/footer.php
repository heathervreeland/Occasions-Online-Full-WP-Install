<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>

			</td>
				<td id="pageright">
					<?php get_sidebar(); ?>
				</td>
			</tr>
		</table>
	</div><!-- ao3pagewrap -->
	<div id="ao3footer">
		<div id="ao3footercontent">
			<img id="ao3footerlogo" src="<?php bloginfo('stylesheet_directory'); ?>/images/ao4-logo2.png" />
			<div id="ao3footerlinks">
				<div class="footer_row">
					<div class="footer_label">Popular Services</div>
					<div class="footer_txt"><a href="/guide/venues">Atlanta Wedding Venues</a>, <a href="/guide/photographers">Atlanta Wedding Photographers</a>, <a href="/guide/caterers">Atlanta Caterers</a>, <a href="/guide/rentals">Atlanta Equipment Rentals</a>, <a href="/guide/rentals">Atlanta Tent Rentals</a></div>
				</div>
				<div class="footer_row">
					<div class="footer_label">Popular Real Events</div>
					<div class="footer_txt"><a href="/gallery/weddings-gallery">Atlanta Weddings</a>, <a href="/gallery/mitzvahs-gallery">Atlanta Mitzvahs</a>, <a href="/gallery/parties-gallery">Atlanta Parties</a>, <a href="/gallery/corporate-events-gallery">Atlanta Corporate Events</a></div>
				</div>
				<div class="footer_row">
					<div class="footer_label">Popular Posts</div>
					<div class="footer_txt"><?php echo popular_posts(4, 33); ?></div>
				</div>
			</div>			
			<div class="clear"></div>
			<?php wp_footer(); ?>
		</div>
	</div>
</div><!-- ao3container -->
<div id="ao3footerbottom"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/1.gif" height=35 width=0 /></div>
<div id="copyright">
		<p>Content/design copyright (c) <?php echo date('Y'); ?> <a title="Occasions Magazine: Atlanta" href="/">Atlanta Occasions, Inc.</a> (<a href="/wp-admin/">Admin</a>) | <a href="/advertisers">Advertiser Login</a>
				<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
			</p>
<div>
</body>
</html>
