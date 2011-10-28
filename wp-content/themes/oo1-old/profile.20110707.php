<?php

/* Template Name: Vendor Profile */

include_once('guide-functions.php');
require_once('vendor_access/recaptchalib.php');

ao_set_in_guide(true);
ao_set_in_profle(true);
$slug = pods_url_variable(1);
$cmd = pods_url_variable(2);
$goto = pods_url_variable(3);
$redirect_url = pods_url_variable(4);

$err = array();

$profile = new Pod('vendor_profiles');
if ($cmd == 'go') {
	// if we are redirecting traffic then we really don't care if they are a current advertiser
	//	because we don't want to break the blog which has a lot of historical entries that should
	//	still be valid redirects.
	$profile->findRecords( 'id', -1, "t.slug = '$slug'");
}
else {
	// if we ARE NOT redirecting, then we need make sure their are a valid active profile.
	$profile->findRecords( 'id', -1, "t.slug = '$slug' and t.profile_type = 'platinum'");
}
$total = $profile->getTotalRows();
if( $total > 0 ) {
	$profile->fetchRecord();
	$a = get_vendorfields($profile);
}
else {
	header("Location: http://www.occasionsonline.com/atlanta/");
	exit;
}

// ====================================================================
// REDIRECT TRACKING
// ====================================================================
if ($cmd == 'go') {
	if ($goto == 'a') {
		ao_log($a['id'], 'click_ad', $redirect_url);
		if ($redirect_url) {
			header("Location: http://" . $redirect_url);
		}
		elseif ($a['aurl']) {
			header("Location: " . $a['aurl']);
		}
		else {
			header("Location: " . $a['wurl']);
		}
		exit;
	}
	elseif ($goto == 'b') {
		ao_log($a['id'], 'click_blog');
		header("Location: " . $a['burl']);
		exit;
	}
	elseif ($goto == 'f') {
		ao_log($a['id'], 'click_facebook');
		header("Location: " . $a['furl']);
		exit;
	}
	elseif ($goto == 'l') {
		ao_log($a['id'], 'click_linkedin');
		header("Location: " . $a['lurl']);
		exit;
	}
	elseif ($goto == 't') {
		ao_log($a['id'], 'click_twitter');
		header("Location: " . $a['turl']);
		exit;
	}
	else { // i.e. $goto == 'w'
		ao_log($a['id'], 'click_web');
		header("Location: " . $a['wurl']);
		exit;
	}
}


load_vendorimages($profile, $a);

// ====================================================================
// SLIDESHOW XML
// ====================================================================
if ($cmd == 'xml') {
	header("Content-type: text/xml");
	$xml_data = file_get_contents(get_stylesheet_directory() . '/vendorslideshow.xml');
	$xmlnodes = iif($a['video_flv_last'] == '1', $a['image_xmlnodes'] . $a['video_xmlnodes'], $a['video_xmlnodes'] . $a['image_xmlnodes']);
	$xml_data = str_replace('<!-- ... -->', $xmlnodes, $xml_data);
	echo $xml_data;
	exit;
}

// ====================================================================
// CONTACT FORM
// ====================================================================
if ($cmd == 'contact') {
?>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US"> 
<head profile="http://gmpg.org/xfn/11"> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
<script type='text/javascript' src='http://ads.occasionsalamode.com/www/delivery/spcjs.php?id=1&amp;block=1&amp;blockcampaign=0&amp;target=_blank'></script> 
<title>Contact <?php echo $a['name']; ?></title> 
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style-popup.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/colorbox.css" type="text/css" media="screen" />

<?php wp_head(); ?>
</head> 
<body> 
<div class="post">

<?php

echo <<<HEREDOC
<h2>{$a['name']}</h2>
<p>
{$a['mapaddr']}{$a['city']}, {$a['stat']} {$a['zipc']}<br />
{$a['pho1']}
</p>
HEREDOC;

$txt_name = '';
$txt_email = '';
$txt_phone = '';
$txt_best = '';
$txt_comments = '';

if ($_POST['submitted'] == "1") {

	$txt_name = htmlspecialchars($_POST['txt_name']);
	$txt_email = htmlspecialchars($_POST['txt_email']);
	$txt_phone = htmlspecialchars($_POST['txt_phone']);
	$txt_best = htmlspecialchars($_POST['txt_best']);
	$txt_comments = stripcslashes(htmlspecialchars($_POST['txt_comments'], ENT_NOQUOTES));

	$to      = $a['emai'];
	$subject = 'Found you on OccasionsOnline.com and would like more information';
	$headers = 'From: "Occasions Magazine Contact Form" <clientcontact@OccasionsOnline.com>' . "\r\n" .
		'Reply-To: "Occasions Magazine" <clientcontact@OccasionsOnline.com>' . "\r\n" .
		'X-Mailer: AO5/PHP/' . phpversion();

	$msg = 'The following was sent from the Occasions Magazine Contact Form:'. "\r\n\r\n" .
			'Name: '. $txt_name. "\r\n" .
			'Email: '. $txt_email. "\r\n" .
			'Phone: '. $txt_phone. "\r\n" .
			'Best Time to Contact: '. $txt_best. "\r\n" .
			'Comment/Details: '. "\r\n\r\n" . $txt_comments. "\r\n\r\n\r\n" .
			
			'------------------------------------------------------------'. "\r\n" .
			'SENT TO : ' . $a['emai'] . "\r\n" .
			'SENT AT : ' . date("D F j, Y, g:i a") . "\r\n" .
			'FROM IP : ' . get_real_ip() . "\r\n" .
			'------------------------------------------------------------'. "\r\n";
	
	mail($to, $subject, $msg, $headers);
	mail(AO_ADMIN_EMAIL, $subject, $msg, $headers);
	mail(AO_TECH_EMAIL, $subject, $msg, $headers);

	echo <<<HEREDOC
	<h3>Thank you!</h3>
	<p>Your email has been sent to {$a['name']}.</p>
	<p>If you do not receive a response within 24 hours, we suggest giving them a quick call to make sure your email was delivered successfully.</p>
HEREDOC;
}
else {

	echo <<<HEREDOC
	<p>To contact this business, please fill out the email form below and click "Send Email" and it will be delivered to the appropriate contact person at <b>{$a['name']}</b> immediately. Please provide as much information as possible to ensure a timely response.</p>
	<form action="./" method="post">
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_name">Name:</label></div>
			<div class="pro_contacttxt"><input name="txt_name" type="text" size="50" id="txt_name" value="$txt_name" /></div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_email">Email:</label></div>
			<div class="pro_contacttxt"><input name="txt_email" type="text" size="50" id="txt_email" value="$txt_email" /></div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_phone">Phone:</label></div>
			<div class="pro_contacttxt"><input name="txt_phone" type="text" size="50" id="txt_phone" value="$txt_phone" /></div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_best">Best time to contact:</label></div>
			<div class="pro_contacttxt"><input name="txt_best" type="text" size="50" id="txt_best" value="$txt_best" /></div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label for="txt_comments">Comments/Details:</label></div>
			<div class="pro_contacttxt"><textarea name="txt_comments" rows="2" cols="20" id="txt_comments">$txt_comments</textarea></div>
		</div>
		<div class="pro_contactrow">
			<div class="pro_contactlabel"><label>&nbsp;</label></div>
			<div class="pro_contacttxt"><input type="submit" id="pro_contact_submit" name="pro_contact_submit" value="Send Email"  /> </div>
		</div>
		<input type="hidden" name="submitted" value="1" />
	</form>
	<script language="javascript">
	<!--
	   document.getElementById("txt_name").focus();
	 -->
	</script>
HEREDOC;
}
?>
</div>
</body>
</html>

<?php 
	exit;
}

// ====================================================================
// PROFILE PAGE
// ====================================================================
$txt_name = '';
$txt_email = '';
$txt_rating = '';
$txt_comments = '';

if ($_POST['review_submitted'] == "1") {

	$txt_name = htmlspecialchars($_POST['txt_name']);
	$txt_email = htmlspecialchars($_POST['txt_email']);
	$txt_comments = htmlspecialchars($_POST['txt_comments'], ENT_NOQUOTES);
	$rdo_rating = htmlspecialchars($_POST['rdo_rating']);

	if ($txt_name == '' || $txt_email == '' || $txt_comments == '') {
		$err[] = "All fields are required to submit a review.";
	}

	/********************* RECAPTCHA CHECK *******************************
	This code checks and validates recaptcha
	****************************************************************/
	$resp = recaptcha_check_answer ($privatekey,
									$_SERVER["REMOTE_ADDR"],
									$_POST["recaptcha_challenge_field"],
									$_POST["recaptcha_response_field"]);
	
	if (!$resp->is_valid) {
		$err[] = "Image Verification failed! (reCAPTCHA said: " . $resp->error . ")";
	}

	if (empty($err)) {

		$to      = $a['emai'];
		//$to      = '"Ben Vigl [TEST MODE]" <ben@benvigil.com>';
		$subject = $a['name'] . ' has been reviewed on OccasionsOnline.com';
		$headers = 'From: "Occasions Magazine" <do-not-reply@OccasionsOnline.com>' . "\r\n" .
			'Reply-To: "Occasions Magazine" <do-not-reply@OccasionsOnline.com>' . "\r\n" .
			'X-Mailer: AO3/PHP/' . phpversion();
	
		$msg = 'The following review of ' . $a['name'] . ' was submitted:'. "\r\n\r\n" .
				'Name: '. $txt_name. "\r\n" .
				'Email: '. $txt_email. "\r\n" .
				'Rating: '. $rdo_rating. "\r\n" .
				'Review: '. "\r\n\r\n" . $txt_comments. "\r\n\r\n\r\n" .
				
				'------------------------------------------------------------'. "\r\n" .
				'SENT TO : ' . $a['emai'] . "\r\n" .
				'SENT AT : ' . date("D F j, Y, g:i a") . "\r\n" .
				'FROM IP : ' . get_real_ip() . "\r\n" .
				'------------------------------------------------------------'. "\r\n";
		
		mail($to, $subject, $msg, $headers);
		mail(AO_ADMIN_EMAIL, $subject, $msg, $headers);
		mail(AO_OFFICE_EMAIL, $subject, $msg, $headers);
		mail(AO_TECH_EMAIL, $subject, $msg, $headers);
	
		// all clear to save the data to the database
		$api = new PodAPI();
		
		// since we are saving a new review, these fields need initializing this one time only
		$comment_data['vendor'] = $a['id'];
		$comment_data['name'] = $txt_name;
		$comment_data['email'] = $txt_email;
		$comment_data['rating'] = $rdo_rating;
		$comment_data['comment'] = $txt_comments;
		$comment_data['comment_date'] = date("Y-m-d H:i:s");
		$comment_data['hide'] = 0;
	
		// safety cleansing
		pods_sanitize($comment_data);
	
		$params = array(
			'datatype' => 'comments', 
			'columns' => $comment_data
		);
		// create the item
		$api->save_pod_item($params);
		
		// set these to NULL so the fields will not be pre-filled below.
		$txt_name = NULL;
		$txt_email = NULL;
		$txt_comments = NULL;
		$rdo_rating = NULL;

		// now recalc the average rating...
		pod_query("UPDATE wp_pod_tbl_vendor_profiles SET rating = (SELECT AVG(rating) FROM wp_pod_tbl_comments WHERE vendor='" . $a['id'] . "' AND hide=0) WHERE id = " . $a['id']);
		
		$err[] = "Your review has been submitted.";
	}
}


get_header();

$s = <<<HEREDOC
<div class="post">
<div class="oo-sidebar-zone" id="pro_details">
<!-- <div id="pro_image"><img src="{$a['imag']}" /></div> -->
HEREDOC;

$travelpolicy = '';
if (!in_array($a['trav'], array('NA', ''))) {$travelpolicy .= '</p><p>** Will Travel '. $a['trav']. '';}

if ($a['showadd'] == '1' || $travelpolicy <> '') {
	$s .= '<div class="head3 oo-color-brown centered">Address</div><p>';

	if ($a['showadd'] == '1') {
		$s .= $a['address'];
		if ($a['suite'] <> '') {
			$s .= '<br />' . $a['suite'];
		}
		$s .= '<br />';
		$s .= "{$a['city']}, {$a['stat']} {$a['zipc']}";
	}
	$s .= $travelpolicy;
	$s .= '</p>';
}

$s .= '<p>&nbsp;</p>';
$s .= '<div class="head3 oo-color-brown centered">Phone</div>';
$s .= '<p>';
if ($a['cont']) {$s .= $a['cont'];}
$s .= '<br />';
if ($a['pho1']) {$s .= $a['pho1t'] . ': ' . $a['pho1'] . '<br />';}
if ($a['pho2']) {$s .= $a['pho2t'] . ': ' . $a['pho2'] . '<br />';}
if ($a['pho3']) {$s .= $a['pho3t'] . ': ' . $a['pho3'] . '<br />';}
$s .= '</p>';

//if ($a['id'] == 'x582') {
//	$s .= get_twitterfeed($a);
//}
if ($a['offe']) {
	$s .= '<p>&nbsp;</p>';
	$s .= '<div class="head3 oo-color-brown centered">Specials</div>';
	$s .= '<p>' . $a['offe'] . '</p>';
}

if ( $a["is_venue"] ) {
	$s .= '<p>&nbsp;</p>';
	$s .= '<div class="head3 oo-color-brown centered">Venue Details</div>';
	$s .= '<p class="pro_detail_field">Spaces Available:</p>';
	if ((int)$a['spac'] > 0) {$s .= '<p class="pro_detail_indent">'. (int)$a['spac']. '</p>';}
		else {$s .= '<p class="pro_detail_indent">(not specified)</p>';}
	$s .= '<p class="pro_detail_field">Capacity:</p>';
	if ($a['capa']) {$s .= '<p class="pro_detail_indent">'. $a['capa']. '</p>';}
		else {$s .= '<p class="pro_detail_indent">(not specified)</p>';}
	$s .= '<p class="pro_detail_field">Square Footage:</p>';
	if ($a['squa']) {$s .= '<p class="pro_detail_indent">'. $a['squa']. '</p>';}
		else {$s .= '<p class="pro_detail_indent">(not specified)</p>';}
	$s .= '<p class="pro_detail_field">Catering Policy:</p>';
	if ($a['ctng']) {$s .= '<p class="pro_detail_indent">'. $a['ctng']. '</p>';}
		else {$s .= '<p class="pro_detail_indent">(not specified)</p>';}
	//$s .= '<p class="pro_detail_field">Alcohol Policy:</p>';
	//if ($a['alco'] == '1') {$s .= '<p class="pro_detail_indent">Permitted</p>';}
	//	elseif ($a['alco'] == '0') {$s .= '<p class="pro_detail_indent">Not permitted</p>';}
	//	else {$s .= '<p class="pro_detail_indent">(not specified)</p>';}
	$s .= '<p class="pro_detail_field">Onsite Accommodations:</p>';
	if ($a['acco']) {$s .= '<p class="pro_detail_indent">Yes</p>';}
		else {$s .= '<p class="pro_detail_indent">No</p>';}
	$s .= '<p class="pro_detail_field">Handicap Accessible:</p>';
	if ($a['acce'] == '1') {$s .= '<p class="pro_detail_indent">Yes</p>';}
		elseif ($a['acce'] == '0') {$s .= '<p class="pro_detail_indent">No</p>';}
		else {$s .= '<p class="pro_detail_indent">(not specified)</p>';}
}

if ($a['accv'] == 1) {$s2 = '<img src="/media/images/logo-visa.jpg" /> ';}
if ($a['accm'] == 1) {$s2 .= '<img src="/media/images/logo-mc.jpg" /> ';}
if ($a['acca'] == 1) {$s2 .= '<img src="/media/images/logo-amex.jpg" /> ';}
if ($a['accd'] == 1) {$s2 .= '<img src="/media/images/logo-discover.jpg" /> ';}
if ($a['accb'] == 1) {$s2 .= '<img src="/media/images/logo-echeck.jpg" /> ';}
if ($a['accp'] == 1) {$s2 .= '<img src="/media/images/logo-paypal.jpg" /> ';}
if ($s2 != '') {
	$s .= '<p>&nbsp;</p>';
	$s .= "<div>We Accept... <br />$s2</div>";
}

$s .= '<p>&nbsp;</p>';
$s .= '</div>';

	$s .= '<div class="oo-sidebar-section">';
	$s .= '<div class="oo-sidebar-section-title">Services<img src="' . get_bloginfo('stylesheet_directory') . '/images/oo-sidebar-divider.png" /></div>';
	$s .= '</div>';
	$categories = new Pod('categories');
	$categories->findRecords( '', 0, '', 'SELECT name, slug, short_title, description FROM wp_pod_tbl_categories WHERE hide <> 1 ORDER BY name');
	$total_cats = $categories->getTotalRows();
	$half_cats = (int) (($total_cats + 1) / 2);

	$i = 0;
	$s .= '<div class="oo-sidebar-halfcol-left">';
	if( $total_cats > 0 ) {
		while ( $categories->fetchRecord() ) {
			$cat_name		= $categories->get_field('name');
			$cat_slug		= $categories->get_field('slug');
			$s .= '<div class="oo-sidebar-guide-list"><a href="/guide/' . $cat_slug . '" title="View all vendors in the ' . $cat_name . ' category">' . $cat_name . '</a></div>';

			$i++;
			if ($i == $half_cats) {
				$s .= '</div><div class="oo-sidebar-halfcol-right">';
			}
		}
	}
	$s .= '<p>&nbsp;</p>';
	$s .= '</div>';

ao_set_sidebar_content($s, "prewidget");
?>



<div class="post">

<?php 
	if(!empty($err))  {
		echo "<div class=\"error_msg\">";
		foreach ($err as $e) {
			echo "* $e <br>";
		}
		echo "</div>";	
	}

	$plain_url = get_bloginfo('url') . "/profile/$slug";
	$xml_url = urlencode(get_bloginfo('url') . "/profile/$slug/xml");
	$swf_url = get_bloginfo('url') . "/wp-content/uploads/monoslideshow.swf";
	
	//echo '<div class="right"><a href="javascript:history.back();">Back to Search Results</a></div>';
	
	echo '<div id="pro-top1">';
		echo '<h1>', $a['name'], '</h1>';
		echo '<small>';
		if ($a['city'] && $a['stat']) {
			echo $a['city'], ', ', $a['stat'];
			if ($a['tagl']) {
				echo '&nbsp;&nbsp;|&nbsp;&nbsp;';
			}
		}
		echo $a['tagl'], '</small>';
		
		$s = '';
		if ($a['furl']) {$s .= '&nbsp;<a href="/profile/' . $slug . '/go/f" target="_blank" title="Visit our Facebook Page"><img src="'. get_bloginfo('stylesheet_directory')  .'/images/logo-facebook.jpg"></a>';}
		if ($a['turl']) {$s .= '&nbsp;<a href="/profile/' . $slug . '/go/t" target="_blank" title="View our Twitter Feed."><img src="'. get_bloginfo('stylesheet_directory')  .'/images/logo-twitter.jpg"></a>';}
		if ($a['lurl']) {$s .= '&nbsp;<a href="/profile/' . $slug . '/go/l" target="_blank" title="Visit our LinkedIn Profile"><img src="'. get_bloginfo('stylesheet_directory')  .'/images/logo-linkedin.jpg"></a>';}
		
		if ($s) {
			echo '<div id="pro-social-block">';
			echo $s;
			echo '</div>';
		}
	echo '</div>';
	
	
	echo '<div id="pro-top2">';

		$rating_data = get_ratingbox($a["rati"]);
		echo $rating_data;
		echo '<div class="uppercase float-right"><a href="#reviews">Post A Review</a>&nbsp;&nbsp;</div>';
	
		$s = '';
		echo '<div class="head3">';
			if ($a['emai']) {$s .= '<a onfocus="this.blur();" class="contact_link" href="/profile/'. $a['slug']. '/contact/">Email</a>';}
			if ($a['wurl']) {
				if ($s) {$s .= '&nbsp;&nbsp;|&nbsp;&nbsp;';}
				$s .= '<a href="/profile/' . $slug . '/go/w" target="_blank">Website</a>';
			}
			if ($a['burl']) {
				if ($s) {$s .= '&nbsp;&nbsp;|&nbsp;&nbsp;';}
				$s .= '<a href="/profile/' . $slug . '/go/b" target="_blank">Blog</a>';
			}
			echo $s;
	
		echo '</div>';
	echo '</div>';

		$tweet_title = 'Check out ' . $a['name'] . ' on OccasionsOnline.com';
		if (strlen($tweet_title) > 100) {
			$tweet_title = ao_trim_to_length($tweet_title, 97, ' ', '...');
		}
	?>
	<div class="post-social-buttons clearme">
		<div class="post-social-twitter"><a href="http://twitter.com/share" class="twitter-share-button" data-text="<?php echo $tweet_title; ?>" data-count="horizontal" data-via="OccasionsMag">Tweet This</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div>
		<div class="post-social-stumble"><script src="http://www.stumbleupon.com/hostedbadge.php?s=1&r=<?php echo $plain_url; ?>"></script></div>
		<div class="post-social-facebook"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo $plain_url; ?>&amp;layout=standard&amp;show_faces=false&amp;width=400&amp;action=like&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:400px; height:35px;" allowTransparency="true"></iframe></div>
	</div>

	<?php
	echo '<div class="entry">';
	
	$have_video = false;
	if ($a['video_first'] == '1') {
		if ($a['video_url'] != '' && substr($a['video_url'], 0, 4) == 'http') {
			echo '<div class="pro_video"><a href="', $a['video_url'], '" class="oembed">video</a></div>';
			echo '<div>&nbsp;</div>';
			$have_video = true;
		}
	}

	if ($a['image_count'] > 0) {
		echo <<<HEREDOC
						<div class="monoslideshow"> 
							<div class="swfobject" id="mo1_1" style="width:600px; height:470px;"> 
								<p>The <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> and <a href="http://www.mozilla.com/firefox/">a browser with Javascript support</a> are needed..</p> 
							</div>
						</div> 
						<script type="text/javascript" defer="defer"> 
							var mo1_1 = {
								params : {
									quality : "best",
									wmode : "opaque",
									allowfullscreen : "true",
									bgcolor : "#FFFFFF"},
								flashvars : {
									dataFile : "$xml_url",
									showLogo : "false",
									showRegistration : "false"},
								attr : {
									styleclass : "slideshow",
									name : "so1"},
								start : function() {
									swfobject.embedSWF("$swf_url", "mo1_1", "600", "470", "7.0.0", false, this.flashvars, this.params , this.attr );
								}
							}
							mo1_1.start();
						</script> 
HEREDOC;
	}
	
	if ($a['video_first'] != '1') {
		if ($a['video_url'] != '' && substr($a['video_url'], 0, 4) == 'http') {
			echo '<div class="pro_video"><a href="', $a['video_url'], '" class="oembed">video</a></div>';
			echo '<div>&nbsp;</div>';
			$have_video = true;
		}
	}

	if ($have_video) {
?>
	<script type="text/javascript">
		$(document).ready(function() {
				$(".oembed").oembed(null, {
					embedMethod: "replace", 
					maxWidth: 600,
					//maxHeight: 362,
					vimeo: {autoplay: true, maxWidth: 600, maxHeight: 362}
			});
		});
	</script>

<?php	
	}
	
	if ( $a["is_venue"] || $a['showadd'] == '1') {
		show_googlemap(array($a), 0, 11);
	}

	echo '<div class="pro_content">';
	echo '<div class="head3 oo-color-brown x">About</div>';
	echo '<p>', $a['desc'], '</p>';
	echo '</div>';

	echo '<div class="pro_content">';
	echo '<div id="reviews" class="head3 oo-color-brown">Customer Reviews</div>';

	$comments = new Pod('comments');
	$comments->findRecords( 'id', -1, "t.vendor = '{$a['id']}' AND t.hide = 0");
	$total_comments = $comments->getTotalRows();
	
	if( $total_comments > 0 ) {
		while ( $comments->fetchRecord() ) {
			$comment	= $comments->get_field('comment');
			$name		= $comments->get_field('name');
			$rating		= $comments->get_field('rating');
			$rating_box	= get_ratingbox($rating);
			echo "$rating_box<div class=\"pro_review\"><p>Review by: <b>$name</b></p>";
			echo "<p>$comment</p></div>";
		}
	}

	$rand1 = rand(1,9);
	$rand2 = rand(1,9);
	$rand3 = $rand1 * 2;
	$rand4 = $rand2 * 2;
	$spam_html = recaptcha_get_html($publickey, null, true);

	$review_form = <<<HEREDOC
	<p>Please fill out ALL the fields below. Your email is required for verification purposes only and will not be published. All fields are REQUIRED.</p>
	<form action="/profile/$slug" method="post">
		<div class="pro_reviewrow">
			<div class="pro_reviewlabel"><label for="txt_name">Name:</label></div>
			<div class="pro_reviewtxt"><input name="txt_name" type="text" size="50" id="txt_name" value="$txt_name" /></div>
		</div>
		<div class="pro_reviewrow">
			<div class="pro_reviewlabel"><label for="txt_email">Email:</label></div>
			<div class="pro_reviewtxt"><input name="txt_email" type="text" size="50" id="txt_email" value="$txt_email" /></div>
		</div>
		<div class="pro_reviewrow">
			<div class="pro_reviewlabel"><label for="txt_comments">Your Review:</label></div>
			<div class="pro_reviewtxt"><textarea name="txt_comments" rows="2" cols="20" id="txt_comments">$txt_comments</textarea></div>
		</div>
		<div class="pro_reviewrow">
			<div class="pro_reviewlabel"><label for="rating">Rating:</label></div>
			<div class="pro_reviewtxt"><input class="pro_checkbox" name="rdo_rating" type="radio" id="rating5" value="5" checked /> <label for="rating5"><img src="/media/images/star.png" /><img src="/media/images/star.png" /><img src="/media/images/star.png" /><img src="/media/images/star.png" /><img src="/media/images/star.png" /></label></div>
		</div>
		<div class="pro_reviewrow">
			<div class="pro_reviewlabel"><label for="rating">&nbsp;</label></div>
			<div class="pro_reviewtxt"><input class="pro_checkbox" name="rdo_rating" type="radio" id="rating4" value="4" /> <label for="rating4"><img src="/media/images/star.png" /><img src="/media/images/star.png" /><img src="/media/images/star.png" /><img src="/media/images/star.png" /></label></div>
		</div>
		<div class="pro_reviewrow">
			<div class="pro_reviewlabel"><label for="rating">&nbsp;</label></div>
			<div class="pro_reviewtxt"><input class="pro_checkbox" name="rdo_rating" type="radio" id="rating3" value="3" /> <label for="rating3"><img src="/media/images/star.png" /><img src="/media/images/star.png" /><img src="/media/images/star.png" /></label></div>
		</div>
		<div class="pro_reviewrow">
			<div class="pro_reviewlabel"><label for="rating">&nbsp;</label></div>
			<div class="pro_reviewtxt"><input class="pro_checkbox" name="rdo_rating" type="radio" id="rating2" value="2" /> <label for="rating2"><img src="/media/images/star.png" /><img src="/media/images/star.png" /></label></div>
		</div>
		<div class="pro_reviewrow">
			<div class="pro_reviewlabel"><label for="rating">&nbsp;</label></div>
			<div class="pro_reviewtxt"><input class="pro_checkbox" name="rdo_rating" type="radio" id="rating1" value="1" /> <label for="rating1"><img src="/media/images/star.png" /></label></div>
		</div>
		<div class="pro_reviewrow">
			<div class="pro_reviewlabel"><label>Spam Protection</label></div>
			<div class="pro_reviewtxt"><table><tr><td>$spam_html</td></tr></table></div>
		</div>
		<div class="pro_reviewrow">
			<div class="pro_reviewlabel"><label>&nbsp;</label></div>
			<div class="pro_reviewtxt"><input type="submit" id="pro_review_submit" name="pro_review_submit" value="Submit My Review"  /> </div>
		</div>
		<input type="hidden" name="review_submitted" value="1" />
		<input type="hidden" name="rand3" value="$rand3" />
		<input type="hidden" name="rand4" value="$rand4" />
	</form>
HEREDOC;

?>


	<div id="review_hide" onclick="review_show(); return false;">Leave a Review</div>

	<div id="collapsing_review" class="CollapsiblePanel">
		<div style="visibility: hidden;" class="CollapsiblePanelTab"></div>
		<div class="CollapsiblePanelContent">
			<div id="review_canvas" style="width:600px; height:400px"><?php echo $review_form; ?></div>
		</div>
	</div>
		
	<script type="text/javascript">
		var collapsing_review = new Spry.Widget.CollapsiblePanel("collapsing_review", { contentIsOpen: false });
		
		function review_show() {
		
			e=document.getElementById('review_hide');
			if (e.innerHTML == 'Close') {
				collapsing_review.close();
				e.innerHTML = 'Leave a Review';
			}
			else {
				collapsing_review.open();
				e.innerHTML = 'Close';
			}
		}
	</script>

<?php
		echo '</div>';
	//}
?>
					</div>
				</div>
			</td>
<?php
get_footer();
ao_log($a['id'], 'profile_view');
//pod_query("UPDATE wp_pod_tbl_vendor_profiles SET profile_views = profile_views + 1 WHERE id = " . $a['id']);
?>

