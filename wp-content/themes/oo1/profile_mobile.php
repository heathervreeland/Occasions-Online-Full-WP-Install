<!DOCTYPE html> 
<html> 
	<head> 
	<title>Occasions Online: <?php echo $a['name']; ?></title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="stylesheet" href="/media/js/jquery.mobile-1.0b2/jquery.mobile-1.0b2.min.css" />
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style-mobile.css" type="text/css" media="screen" />
	<link href="/media/js/photoswipe.v1.0.19/photoswipe.css" type="text/css" rel="stylesheet" />
	<style type="text/css">
		div.gallery-row:after { clear: both; content: "."; display: block; height: 0; visibility: hidden; }
		div.gallery-item { float: left; width: 33.333333%; }
		div.gallery-item a { display: block; margin: 5px; border: 1px solid #3c3c3c; }
		div.gallery-item img { display: block; width: 100%; height: auto; }
		#gallerypage .ui-content { overflow: hidden; }
	</style>
	<script src="/media/js/jquery-1.6.2.min.js"></script>
	<script src="/media/js/jquery.mobile-1.0b2/jquery.mobile-1.0b2.min.js"></script>
	<script src="/media/js/photoswipe.v1.0.19/simple-inheritance.min.js"></script>
	<script src="/media/js/photoswipe.v1.0.19/jquery.animate-enhanced.min.js"></script>
	
	<!-- NOTE: including the jQuery engine version -->
	<script src="/media/js/photoswipe.v1.0.19/code-photoswipe-jQuery-1.0.19.min.js"></script>
	<script type="text/javascript">
		/*
			This example uses the jQuery engine for PhotoSwipe. By default, this 
			does not use hardware acceleration. However, in this example, we use
			the jQuery animate enhanced plugin to achieve this
		*/
		$(document).ready(function(){
			
			$('div.gallery-page').live('pageshow', function(e){
				// Re-initialize with the photos for the current page
				$("div.gallery a", e.target).photoSwipe();
				return true;
			});
		});
		
	</script>
</head> 

<body>

<?php
// *******************************************************************
// PAGE - HOME
// *******************************************************************
?>
<div data-role="page" id="home" data-theme="o">

	<div data-role="content" data-theme="o">
		<p style="text-align:center;"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/omlogo_mobile.png"></p>

		<h2><?php echo $a['name']; ?></h2>
		<p><i><?php echo $a['tagline']; ?></i></p>
		<p><?php echo $a['summary']; ?><?php if ($a['description'] != $a['summary']) { ?> <a href="#more">more &gt;</a><?php } ?></p>
		<?php if ($a['offer']) { ?>
		<ul data-role="listview" data-inset="true" data-theme="o">
			<li><a href="#offer">Special Offer!</a></li>
		</ul>
		<?php } ?>
		<ul data-role="listview" data-inset="true" data-theme="o">
			<!-- <li data-role="list-divider">Section Title</li> -->
			<li><a href="#gallerypage">Image Gallery</a></li>
			<?php if ($a['video_url']) { ?><li><a href="<?php echo $a['video_url']; ?>">Video</a></li><?php } ?>
			<li><a href="#reviews">Customer Reviews</a></li>
			<li><a href="#contact">Contact Us</a></li>
		</ul>
		<h3>Connect with us...</h3>
		<ul data-role="listview" data-inset="true" data-theme="o">
			<?php if ($a['wurl']) {echo '<li><a href="/profile/' . $a['slug'] . '/go/w" target="_blank">Website</a></li>';} ?>
			<?php if ($a['burl']) {echo '<li><a href="/profile/' . $a['slug'] . '/go/b" target="_blank">Blog</a></li>';} ?>
			<?php if ($a['furl']) {echo '<li><a href="/profile/' . $a['slug'] . '/go/f" target="_blank">Facebook</a></li>';} ?>
			<?php if ($a['turl']) {echo '<li><a href="/profile/' . $a['slug'] . '/go/t" target="_blank">Twitter</a></li>';} ?>
		</ul>
	<div data-role="controlgroup" data-type="horizontal" style="text-align: center;">
	<a href="<?php echo "/profile/$slug/p"; ?>" data-ajax="false" data-role="button" data-theme="c" data-icon="forward">View Full Site</a>
	</div>
	
	</div>

</div>

<?php
// *******************************************************************
// PAGE - OFFER
// *******************************************************************
?>
<div data-role="page" id="offer" data-add-back-btn="true" data-theme="o">

	<div data-role="header" data-position="fixed">
		<h1>Special Offer</h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="o">
		<h2>Special Offer from <?php echo $a['name']; ?></h2>
		<p><?php echo $a['offer']; ?></p>
	</div>
</div>

<?php
// *******************************************************************
// PAGE - MORE
// *******************************************************************
?>
<div data-role="page" id="more" data-add-back-btn="true" data-theme="o">

	<div data-role="header" data-position="fixed">
		<h1><?php echo $a['name']; ?></h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="o">
		<h2><?php echo $a['name']; ?></h2>
		<p><i><?php echo $a['tagline']; ?></i></p>
		<p><?php echo $a['description'] ?></p>
	</div>
</div>

<?php
// *******************************************************************
// PAGE - GALLERY
// *******************************************************************
?>
<div data-role="page" id="gallerypage" class="gallery-page" data-add-back-btn="true" data-theme="o">

	<div data-role="header" data-position="fixed">
		<h3>Image Gallery</h3>
	</div>
	<div data-role="content" data-theme="o">
		<h3><?php echo $a['name']; ?></h3>
		<div class="gallery">
			<div class="gallery-row">
<?php
		$img_id = 0;
		$img_rowid = 0;
		foreach ($a['imagelist_thumb'] as $img_thumb) {
			if ($img_rowid == 3) {
				echo '</div><div class="gallery-row">';
				$img_rowid = 0;
			}
			$img_large = $a['imagelist_large'][$img_id];
?>
			<div class="gallery-item"><a href="<?php echo $img_large; ?>" rel="external"><img src="<?php echo $img_thumb; ?>" alt="" /></a></div>
<?php
			$img_id++;
			$img_rowid++;
		}
?>
			</div>
		</div>
	</div>

</div>

<?php
// *******************************************************************
// PAGE - REVIEWS
// *******************************************************************
?>
<div data-role="page" id="reviews" data-add-back-btn="true" data-theme="o">

	<div data-role="header" data-position="fixed">
		<h1>Customer Reviews</h1>
	</div><!-- /header -->

	<div data-role="content" data-theme="o">
		<h2>Customer Reviews for <?php echo $a['name']; ?></h2>
		<?php
			$comments = new Pod('comments');
			$comments->findRecords( 'id', -1, "t.vendor = '{$a['id']}' AND t.hide = 0");
			$total_comments = $comments->getTotalRows();
			
			if( $total_comments > 0 ) {
				while ( $comments->fetchRecord() ) {
					$comment	= $comments->get_field('comment');
					$name		= $comments->get_field('name');
					$rating		= $comments->get_field('rating');
					$rating_box	= get_ratingbox($rating);
					echo "$rating_box<p>Review by: <b>$name</b></p>";
					echo "<p>$comment</p>";
				}
			}
			else {
				echo "<p>No reviews yet.</p>";
			}
		?>
	</div>
	
</div>


<?php
// *******************************************************************
// PAGE - CONTACT
// *******************************************************************
?>
<div data-role="page" id="contact" data-add-back-btn="true" data-theme="o">

	<div data-role="header" data-position="fixed">
		<h1>Contact Us</h1>
	</div>

	<div data-role="content">
		<h2>Contact <?php echo $a['name']; ?></h2>
		<form id="form_contact" data-transition="slidedown" action="<?php bloginfo('stylesheet_directory'); ?>/profile_mobile_phone.php" method="post">
			<input type="hidden" name="pid" value="<?php echo $a['id']; ?>" />
			<div data-role="fieldcontain">
				<label for="txt_name">Your Name:</label>
				<input type="text" name="txt_name" id="txt_name" value=""  />
			</div>
			<div data-role="fieldcontain">
				<label for="txt_email">Your Email:</label>
				<input type="email" name="txt_email" id="txt_email" value=""  />
			</div>
			<div data-role="fieldcontain">
				<label for="txt_phone">Your Phone:</label>
				<input type="tel" name="txt_phone" id="txt_phone" value=""  />
			</div>
			<div data-role="fieldcontain">
				<label for="txt_message">Message:</label>
				<textarea cols="40" rows="8" name="txt_message" id="txt_message"></textarea>
			</div>
			<fieldset class="ui-grid-a">
				<div class="ui-block-a"><a href="#" data-role="button" data-icon="delete" data-theme="c" data-rel="back">Cancel</a></div>
				<div class="ui-block-b"><input id="btn_send" type="submit" data-icon="check"  data-theme="o" value="Send" /></div>	   
			</fieldset>
		</form>
	</div>
	
</div>


<?php
// *******************************************************************
// PAGE - VIDEO
// *******************************************************************
?>
<div data-role="page" id="video" data-add-back-btn="true" data-theme="o">

	<div data-role="content">
		<iframe class="youtube-player" type="text/html" width="100%" height="100%" src="http://www.youtube.com/embed/IrZaFUzLoQQ" frameborder="0"></iframe>
	</div>
	
</div>

</body>
</html>
