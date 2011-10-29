<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
					</td>
					<!-- oo-page-center -->
					
					<td id="oo-page-right">
						<?php get_sidebar(); ?>
					</td>
					<!-- oo-page-right -->
				</tr>
			</table>
			<!-- oo-page-content -->

			<div id="ao3footer">
				<div id="ao3footercontent">
					<a href="/"><img id="ao3footerlogo" src="<?php bloginfo('stylesheet_directory'); ?>/images/oo_logo_footer.png" title="The Magazine for Celebrating in Style!" /></a>
					<div id="ao3footersection1">
						<div class="ao3footertitle">Ideas by Color</div>
						<a href="/party-ideas/colors/pink">Pink</a>&nbsp;/&nbsp;
						<a href="/party-ideas/colors/blue">Blue</a>&nbsp;/&nbsp;
						<a href="/party-ideas/colors/yellow">Yellow</a><br />
						<a href="/party-ideas/colors/red">Red</a>&nbsp;/&nbsp;
						<a href="/party-ideas/colors/green">Green</a>&nbsp;/&nbsp;
						<a href="/party-ideas/colors/purple">Purple</a><br />
						<a href="/party-ideas/colors/black">Black</a>&nbsp;/&nbsp;
						<a href="/party-ideas/colors/white">White</a>&nbsp;/&nbsp;
						<a href="/party-ideas/colors/brown">Brown</a><br />
						<a href="/party-ideas/colors/orange">Orange</a>&nbsp;/&nbsp;
						<a href="/party-ideas/colors/teal">Teal</a>
					</div>
					<div id="ao3footersection2">
						<div class="ao3footertitle">Event Inspiration</div>
						<a href="/events/real-weddings">Real Weddings</a><br />
						<a href="/events/real-mitzvahs">Real Mitzvahs</a><br />
						<a href="/party-ideas/party-themes/baby-shower-ideas-party-themes">Real Baby Showers</a><br />
						<a href="/party-ideas/party-themes/birthday-party-ideas">Real Birthday Parties</a><br />
						<a href="/events/real-parties-and-celebrations">Other Celebrations</a><br />
						<a href="/featured-events">Featured in Print</a>
					</div>
					<div id="ao3footersection3">
						<div class="ao3footertitle">Popular Topics</div>
<?php
		$cats = get_categories(array('hide_empty' => 0, 'child_of' => 12, 'orderby' => 'count', 'order' => 'desc'));
		$cats = array_slice($cats, 0, 8, true);
		foreach ($cats as $category) {
			echo '<a href="' . $category->slug . '">' . $category->name . '</a><br />';
		}
?>
					</div>

<!--
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
							<div class="footer_txt"><?php //echo popular_posts(4, 33); ?></div>
						</div>
					</div>			
-->
					<div class="clear"></div>
					<?php wp_footer(); ?>
				</div>
			</div>
	
		</div>
		<!-- oo-page -->

		<div id="copyright">
			<a href="/wp-admin/">Admin</a> |
			<a href="/advertisers">Advertiser Login</a> |
			<a href="/badges">Link To Us</a> |
			<a href="/about">About</a> |
			<a href="/submissions">Editorial Submissions</a> |
			<a onfocus="this.blur();" class="issue_link" href="http://issuu.com/occasionsmagazine/docs/fall2011?mode=embed&layout=http://skin.issuu.com/v/light/layout.xml&showFlipBtn=true">View Magazine</a> |
			<a href="/where-to-buy">Where To Buy</a>
			<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
		</div>
	</div>
	<!-- oo-livearea -->
</div>
<!-- oo-container -->

<!-- Start Quantcast tag -->
<script type="text/javascript">
_qoptions={
qacct:"p-8bJUlYqzEaD76"
};
</script>
<script type="text/javascript" src="http://edge.quantserve.com/quant.js"></script>
<noscript>
<img src="http://pixel.quantserve.com/pixel/p-8bJUlYqzEaD76.gif" style="display: none;" border="0" height="1" width="1" alt="Quantcast"/>
</noscript>
<!-- End Quantcast tag -->

<!-- Start CompeteXL tag -->
<script type="text/javascript">
   __compete_code = 'aa854823f161aba303ca9e7db8657867';
   (function () {
       var s = document.createElement('script'),
           e = document.getElementsByTagName('script')[0],
           t = document.location.protocol.toLowerCase() === 'https:' ?
               'https://c.compete.com/bootstrap/' :
               'http://c.compete.com/bootstrap/';
           s.src = t + __compete_code + '/bootstrap.js';
           s.type = 'text/javascript';
           s.async = true;
           if (e) { e.parentNode.insertBefore(s, e); }
       }());
</script>
<!-- End CompeteXL tag -->

<!-- Start ChartBeat code -->
<script type="text/javascript">
	var _sf_async_config={uid:25181,domain:"occasionsonline.com"};
	(function () {
		function loadChartbeat() {
			window._sf_endpt=(new Date()).getTime();
			var e = document.createElement('script');
			e.setAttribute('language', 'javascript');
			e.setAttribute('type', 'text/javascript');
			e.setAttribute('src', (("https:" == document.location.protocol) ? 
				"https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/" : 
				"http://static.chartbeat.com/") 
				+ "js/chartbeat.js");
			document.body.appendChild(e);
		}
		var oldonload = window.onload;
		window.onload = (typeof window.onload != 'function') ? loadChartbeat : function() { oldonload(); loadChartbeat(); };
	})();
</script>
<!-- End ChartBeat code -->

<!-- Start 1+ google script -->
<?php //added by Ben Kaplan - 10/29/11  adds 1+ button on blog posts and business profiles?>
<script type="text/javascript">
(function() {
var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
po.src = 'https://apis.google.com/js/plusone.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
})();
</script>
<!-- end 1+ google script -->

</body>
</html>
