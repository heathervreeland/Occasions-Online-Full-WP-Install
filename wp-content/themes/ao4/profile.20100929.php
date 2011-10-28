<?php

/* Template Name: Vendor Profile */

include_once('guide-functions.php');
ao_set_in_guide(true);
$slug = pods_url_variable(1);
$cmd = pods_url_variable(2);
$goto = pods_url_variable(3);

$profile = new Pod('vendor_profiles');
$profile->findRecords( 'id', -1, "t.slug = '$slug'");
$total = $profile->getTotalRows();
if( $total > 0 ) {
	$profile->fetchRecord();
	$a = get_vendorfields($profile);
}
else {
	get_header();
?>
<div class="post">
<h1>Vendor Not Found</h1>
<p>We apologize for the mixup but we were not able to find this vendor.</p>
<p>Go to the <a href="javascript:history.back();">Occasions Magazine home page</a> or <a href="javascript:history.back();">back to the page that sent you here</a>.</p>
</div>
<?php
	get_footer();
}

if ($cmd == 'go') {
	if ($goto == 'w') {
		pod_query("UPDATE wp_pod_tbl_vendor_profiles SET clicks_web = clicks_web + 1 WHERE id = " . $a['id']);
		header("Location: " . $a['wurl']);
		exit;
	}
	elseif ($goto == 'b') {
		pod_query("UPDATE wp_pod_tbl_vendor_profiles SET clicks_blog = clicks_blog + 1 WHERE id = " . $a['id']);
		header("Location: " . $a['burl']);
		exit;
	}
	elseif ($goto == 'f') {
		pod_query("UPDATE wp_pod_tbl_vendor_profiles SET clicks_facebook = clicks_facebook + 1 WHERE id = " . $a['id']);
		header("Location: " . $a['furl']);
		exit;
	}
	elseif ($goto == 'l') {
		pod_query("UPDATE wp_pod_tbl_vendor_profiles SET clicks_linkedin = clicks_linkedin + 1 WHERE id = " . $a['id']);
		header("Location: " . $a['lurl']);
		exit;
	}
	elseif ($goto == 't') {
		pod_query("UPDATE wp_pod_tbl_vendor_profiles SET clicks_twitter = clicks_twitter + 1 WHERE id = " . $a['id']);
		header("Location: " . $a['turl']);
		exit;
	}
}


load_vendorimages($profile, $a);

if ($cmd == 'xml') {
	header("Content-type: text/xml");
	$xml_data = file_get_contents(get_stylesheet_directory() . '/vendorslideshow.xml');
	$xml_data = str_replace('<!-- ... -->', $a['image_xmlnodes'], $xml_data);
	echo $xml_data;
	exit;
}

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
	//$to      = '"Ben Vigl [TEST MODE]" <ben@benvigil.com>';
	$subject = 'Found you on AtlantaOccasions.com and would like more information';
	$headers = 'From: "Occasions Magazine Contact Form" <clientcontact@atlantaoccasions.com>' . "\r\n" .
		'Reply-To: "Occasions Magazine" <clientcontact@atlantaoccasions.com>' . "\r\n" .
		'X-Mailer: AO3/PHP/' . phpversion();

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

get_header();

$s = <<<HEREDOC
<div class="post">
<div id="pro_details">
<!-- <div id="pro_image"><img src="{$a['imag']}" /></div> -->
HEREDOC;

$travelpolicy = '';
if (!in_array($a['trav'], array('NA', ''))) {$travelpolicy .= '</p><p class="pro_detail_hangingindent">** Will Travel '. $a['trav']. '';}

if ($a['showadd'] == '1' || $travelpolicy <> '') {
	$s .= '<h2>Address / Location</h2><p>';

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

$s .= '<h2>Contact Information</h2>';
$s .= '<p>';
if ($a['cont']) {$s .= $a['cont'];}
//if ($a['titl']) {$s .= '<br /><i>('. $a['titl'], ')</i>';}
if ($a['emai']) {$s .= '<br /><a onfocus="this.blur();" id="contact_link" href="/profile/'. $a['slug']. '/contact/">Send Email</a>';}
$s .= '</p><p>';
if ($a['pho1']) {$s .= '</p><p>'. $a['pho1']. '<br /><i>('. $a['pho1t']. ')</i>';}
if ($a['pho2']) {$s .= '</p><p>'. $a['pho2']. '<br /><i>('. $a['pho2t']. ')</i>';}
if ($a['pho3']) {$s .= '</p><p>'. $a['pho3']. '<br /><i>('. $a['pho3t']. ')</i>';}
$s .= '</p><p>';
if ($a['wurl']) {$s .= '<a href="/profile/' . $slug . '/go/w" target="_blank">Visit Website</a><br />';}
if ($a['burl']) {$s .= '<a href="/profile/' . $slug . '/go/b" target="_blank">Visit Blog</a><br />';}
if ($a['furl']) {$s .= '<a href="/profile/' . $slug . '/go/f" target="_blank">Facebook Page</a><br />';}
if ($a['lurl']) {$s .= '<a href="/profile/' . $slug . '/go/l" target="_blank">LinkedIn Profile</a><br />';}
if ($a['turl']) {$s .= '<a href="/profile/' . $slug . '/go/t" target="_blank">Twitter Feed</a><br />';}
$s .= '</p>';
if ($a['id'] == 'x582') {
	$s .= get_twitterfeed($a);
}
if ($a['accv'] == 1) {$s2 = '<img src="/media/images/logo-visa.jpg" /> ';}
if ($a['accm'] == 1) {$s2 .= '<img src="/media/images/logo-mc.jpg" /> ';}
if ($a['acca'] == 1) {$s2 .= '<img src="/media/images/logo-amex.jpg" /> ';}
if ($a['accd'] == 1) {$s2 .= '<img src="/media/images/logo-discover.jpg" /> ';}
if ($a['accb'] == 1) {$s2 .= '<img src="/media/images/logo-echeck.jpg" /> ';}
if ($a['accp'] == 1) {$s2 .= '<img src="/media/images/logo-paypal.jpg" /> ';}
if ($s2 != '') {
	$s .= "<p>We Accept... <br />$s2</p>";
}
$s .= '</div> <!-- class="post" -->';
ao_set_sidebar_content($s, "prewidget");
?>


<div class="post">

<?php 
	$xml_url = urlencode(get_bloginfo('url') . "/profile/$slug/xml");
	echo '<div class="pro_backlink"><p><a href="javascript:history.back();">&lt; Back to Search Results</a></div>';
	$rating_data = get_ratingbox($a);
	echo $rating_data, '<h2>', $a['name'], '</h2>';
	echo '<small>', $a['tagl'], '</small>';
	echo '<div class="entry">';
	
	$have_video = false;
	if ($a['video_first'] == '1') {
		if ($a['video_url'] != '' && substr($a['video_url'], 0, 4) == 'http') {
			echo '<div class="pro_video"><a href="', $a['video_url'], '" class="oembed">video</a></div>';
			echo '<p>&nbsp;</p>';
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
									swfobject.embedSWF("http://www.atlantaoccasions.com/wp-content/uploads/monoslideshow.swf", "mo1_1", "600", "470", "7.0.0", false, this.flashvars, this.params , this.attr );
								}
							}
							mo1_1.start();
						</script> 
HEREDOC;
	}
	
	if ($a['video_first'] != '1') {
		if ($a['video_url'] != '' && substr($a['video_url'], 0, 4) == 'http') {
			echo '<div class="pro_video"><a href="', $a['video_url'], '" class="oembed">video</a></div>';
			echo '<p>&nbsp;</p>';
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
					maxHeight: 362,
					vimeo: {autoplay: true, maxWidth: 600, maxHeight: 362}
			});
		});
	</script>

<?php	
	}
	
	if ( $a["is_venue"] || $a['showadd'] == '1') {
		show_googlemap(array($a), 0, 11);
	}

	echo '<p>', $a['desc'], '</p>';

	if ($a['offe']) {
		echo '<div id="special_offer">';
		echo '<h2>Special Offer</h2>';
		echo '<p>', $a['offe'], '</p>';
		echo '</div>';
	}

	if ( $a["is_venue"] ) {
		$s = '<div id="venue_details">';
		$s .= '<h2>Venue Details</h2>';
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
		$s .= '<p class="pro_detail_field">Alcohol Policy:</p>';
		if ($a['alco'] == '1') {$s .= '<p class="pro_detail_indent">Permitted</p>';}
			elseif ($a['alco'] == '0') {$s .= '<p class="pro_detail_indent">Not permitted</p>';}
			else {$s .= '<p class="pro_detail_indent">(not specified)</p>';}
		$s .= '<p class="pro_detail_field">Onsite Accomodatons:</p>';
		if ($a['acco']) {$s .= '<p class="pro_detail_indent">Yes</p>';}
			else {$s .= '<p class="pro_detail_indent">No</p>';}
		$s .= '<p class="pro_detail_field">Handicap Accessible:</p>';
		if ($a['acce'] == '1') {$s .= '<p class="pro_detail_indent">Yes</p>';}
			elseif ($a['acce'] == '0') {$s .= '<p class="pro_detail_indent">No</p>';}
			else {$s .= '<p class="pro_detail_indent">(not specified)</p>';}
		$s .= '</div>';
		echo $s;
	}
	
?>
					</div>
				</div>
			</td>
<?php
get_footer();
pod_query("UPDATE wp_pod_tbl_vendor_profiles SET profile_views = profile_views + 1 WHERE id = " . $a['id']);
?>

