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
		<p>Content/design copyright (c) <?php echo date('Y'); ?> <a title="Occasions Magazine: Atlanta" href="/">Atlanta Occasions, Inc.</a> (<a href="/wp-admin/">Admin</a>)
			<br /><a href="<?php bloginfo('rss2_url'); ?>">Entries (RSS)</a>
			| <a href="<?php bloginfo('comments_rss2_url'); ?>">Comments (RSS)</a>
			| <a href="/advertisers">Advertiser Login</a>
			<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
		</p>
		<?php wp_footer(); ?>
	</div>
</div><!-- ao3container -->
</body>
</html>
