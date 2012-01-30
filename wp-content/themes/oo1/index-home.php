<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
/* Template Name: HOME PAGE */

get_header();
?>
<div id="oo-lead-section" class="clearme">

	<div id="oo-lead-graphics">
		<div id="oo-lead-rotator" class="shadow-up">
			<?php vSlider(); ?>
		</div>
		<!-- oo-lead-rotator -->
		
		<div id="oo-lead-guide">
			<div id="oo-lead-guide-vendors1"><div id="oo-lead-guide-vendors2">
				<div class="head4 centered">Search For</div>
				<div class="head1 centered"><a href="/guide">Venues & Vendors</a></div>
			</div></div>
		</div>
		<!-- oo-lead-guide -->
		
	</div>
	<!-- oo-lead-section -->

<script type="text/javascript">
	// check for title truncation
	$(document).ready(function(){
		if ( ($("#oo-lead-article-title").height() - 20) > $("#oo-lead-article-title-wrap").height()) {
			$("#oo-lead-article-title-elipses").show(1500);
		}
		$('.oo-element-title').each(function(index) {
			if ( ($(this).height() - 20) > $(this).parent().height() ) {
				$(this).prev().show(1500);
			}
		});
	});
</script>

	<div id="oo-lead-articles">
<?php
// get all posts EXCEPT the "from-the-editor" and "industry" categories
$args = array( 'showposts' => 4, 'category' => "-52,-2908" );
$lastposts = get_posts( $args );
$i = 0;
foreach($lastposts as $post) : setup_postdata($post);
	$i++;
	$title = get_the_title();
	$link = get_permalink();
	$content = get_the_content('...', 0);
	$content = preg_replace('/\[nggallery.+id=\d+\]/i', ' ', $content, 1);
	//echo "<!-- $content -->";
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = trim(strip_tags($content));
	if ($i > 1) {
		$content = '<b>' . get_the_title() . '</b> ' . $content;
	}
	$partial = ao_trim_to_length($content, 130, ' ', '');

	// the first post has a different layout than the rest.
	if ($i == 1) {
		echo <<<HEREDOC
		<div class="oo-lead-article-lead">
			<div id="oo-lead-article-title-wrap">
				<div id="oo-lead-article-title-elipses" class="head2">. . .</div>
				<div id="oo-lead-article-title" class="head2"><a href="$link">$title</a></div>
			</div>
			<!-- oo-lead-article-title-wrap -->
			<p>$partial <a href="$link">more&nbsp;&gt;</a></p>
		</div>
		<!-- oo-lead-article-lead -->
		
		<p id="oo-lead-title-tease">Recent Articles...</p>
HEREDOC;
	}
	else {
		echo <<<HEREDOC
		<div class="oo-lead-article-tease">
			<p>$partial <a href="$link">more&nbsp;&gt;</a></p>
		</div>
		<!-- oo-lead-article-tease -->
HEREDOC;
	}
?>
<?php endforeach; ?>

	</div>
	<!-- oo-lead-articles -->
</div>
<!-- oo-lead-section -->

<div class="oo-feature-tease oo-bgcolor-green clearme">
	<div class="head6 knockout float-right right lowercase"><a class="lowercase" href="/gallery">&gt;&gt;&gt; click here</a></div>
	<div class="head4 knockout">Ideas abound in our new <b>images gallery</b></div>
</div>
<!-- oo-feature-tease -->

<div id="oo-featured-section" class="clearme">
	<div id="oo-featured-block1" class="shadow-up">
		<div class="head3 centered">Event Elements</div>
		<div class="oo-featured-block-wrap">
<?php
		$args=array(
			'child_of' => 12
			);
		$categories = get_categories($args);
	
		foreach($categories as $category) { 
			$cat_name		= $category->name;
			$cat_link		= get_category_link( $category->term_id );
			echo '<div class="featured-block-list"><a href="' , $cat_link, '" title="View all posts in ' . $cat_name . '">', $cat_name, '</a></div>';
		}
?>
		</div>
		<!-- oo-featured-block-wrap -->
	</div>
	<!-- oo-featured-block1 -->

	<div id="oo-featured-block2" class="shadow-up">
		<div class="head3 centered">Real Events</div>
		<div class="oo-featured-block-wrap">

<?php
$args = array( 'showposts' => 3, 'category' => 11 );
$lastposts = get_posts( $args );
foreach($lastposts as $post) : setup_postdata($post);

	$content = apply_filters('the_content', get_the_content('...', 0));
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = '<b>' . get_the_title() . '</b> ' . strip_tags($content);
	$partial = ao_trim_to_length($content, 120, ' ', '');
?>
   	<p><?php echo $partial; ?> <a href="<?php the_permalink() ?>">more&nbsp;&gt;</a></p>
<?php endforeach; ?>

		</div>
		<!-- oo-featured-block-wrap -->
	</div>
	<!-- oo-featured-block2 -->

	<div id="oo-featured-block3" class="shadow-up">
		<div class="head3 centered">From The Editor</div>
		<div class="oo-featured-block-wrap">
<?php
$args = array( 'showposts' => 3, 'category' => 52 );
$lastposts = get_posts( $args );
foreach($lastposts as $post) : setup_postdata($post);

	$content = apply_filters('the_content', get_the_content('', 0));
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = '<b>' . get_the_title() . '</b> ' . strip_tags($content);
	$partial = ao_trim_to_length($content, 120, ' ', '');
?>
   	<p><?php echo $partial; ?> <a href="<?php the_permalink() ?>">more&nbsp;&gt;</a></p>
<?php endforeach; ?>

		</div>
		<!-- oo-featured-block-wrap -->
	</div>
	<!-- oo-featured-block3 -->
</div>
<!-- oo-featured-section -->


<div class="oo-adsense-links">
	<script type="text/javascript"><!--
	google_ad_client = "ca-pub-4127320558779752";
	/* Text Links */
	google_ad_slot = "9981324710";
	google_ad_width = 600;
	google_ad_height = 15;
	//-->
	</script>
	<script type="text/javascript"
	src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
	</script>
</div>
<!-- oo-adsense-links -->

<?php
/* added by Ben Kaplan 1/29/12 - created function to pull code out of html */
echo recent_real_events();
?>
<!-- oo-real-section -->

<div id="oo-elements-section" class="clearme">
	<?php
	$args = array( 'showposts' => 4, 'category' => 12 );
	$lastposts = get_posts( $args );
	foreach($lastposts as $post) : setup_postdata($post);
	
		include('block-post-half.php');
	
	endforeach;
	?>
	
</div>
<!-- oo-elements-section -->

<?php
get_footer();
?>
