<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
<script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>
<meta name="alexaVerifyID" content="KemOZK3goi3teGuO-Phi0tUDJZA" />
<?php 
if (ao_get_title()) { 
echo '<title>', ao_get_title(), '</title>';
}
else { ?>
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<?php } ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/style-fonts.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<!--[if IE 6]> 
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style-winie6.css" />
<![endif]-->

<!--[if IE 7]> 
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style-winie7.css" />
<![endif]-->

<!--[if IE 8]> 
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style-winie8.css" />
<![endif]-->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/colorbox.css" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<script type="text/javascript" src="/media/js/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js" ></script>
<script type='text/javascript' src='<?php bloginfo('stylesheet_directory'); ?>/plum-gallery/jquery.plum-gallery.js'></script>
<script type="text/javascript" src="/media/js/jquery.qtip-1.0.0-rc3-min.js"></script>
<script type="text/javascript" src="/media/js/jquery.validate.js"></script>
<script type="text/javascript" src="/media/js/jquery.oembed.js"></script>
<?php
	if (ao_get_in_vendorarea()) {
?>
<script type="text/javascript" src="/media/js/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="/media/js/spry/SpryTabbedPanels.js"></script>
<script type="text/javascript" src="/media/js/swfupload.js"></script>
<script type='text/javascript' src='<?php bloginfo('stylesheet_directory'); ?>/jquery.jdpicker.js'></script>
<script type='text/javascript' src='<?php bloginfo('stylesheet_directory'); ?>/jquery.timepicker.min.js'></script>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/jquery.jdpicker.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/jquery.timepicker.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/SpryTabbedPanels.css" type="text/css" media="screen" />
<link rel="stylesheet" href="/media/css/swfupload.css" type="text/css" media="screen" />
<?php
	}
	elseif (ao_get_in_dropbox()) {
?>
<script type="text/javascript" src="/media/js/swfupload.js"></script>
<link rel="stylesheet" href="/media/css/swfupload.css" type="text/css" media="screen" />
<?php
	}
	else {
?>
<!-- AD SERVING START -->
<script type='text/javascript' src='http://partner.googleadservices.com/gampad/google_service.js'>
</script>
<script type='text/javascript'>
GS_googleAddAdSenseService("ca-pub-4127320558779752");
GS_googleEnableAllServices();
</script>
<script type='text/javascript'>
GA_googleAddSlot("ca-pub-4127320558779752", "300x250_spot_1");
GA_googleAddSlot("ca-pub-4127320558779752", "300x125_spot_1");
GA_googleAddSlot("ca-pub-4127320558779752", "300x125_spot_2");
GA_googleAddSlot("ca-pub-4127320558779752", "300x125_spot_3");
GA_googleAddSlot("ca-pub-4127320558779752", "300x125_spot_4");
GA_googleAddSlot("ca-pub-4127320558779752", "300x125_spot_5");
GA_googleAddSlot("ca-pub-4127320558779752", "300x125_spot_6");
GA_googleAddSlot("ca-pub-4127320558779752", "300x125_spot_7");
GA_googleAddSlot("ca-pub-4127320558779752", "300x125_spot_8");
GA_googleAddSlot("ca-pub-4127320558779752", "300x125_spot_9");
GA_googleAddSlot("ca-pub-4127320558779752", "300x125_spot_10");
</script>
<script type='text/javascript'>
GA_googleFetchAds();
</script>
<!-- AD SERVING END -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?v=3.0&sensor=false"></script>
<?php
	}
?>
<script type='text/javascript' src='/wp-includes/js/swfobject.js?ver=2.1'></script>
<script type="text/javascript" src="/media/js/spry/SpryCollapsiblePanel.js"></script>
<script type="text/javascript" src="/media/js/colorbox/jquery.colorbox-min.js"></script>

<script type="text/javascript" src="/media/js/general.js"></script>

<script type="text/javascript">

	// ColorBox flyouts START
	$(document).ready(function(){
		$('#menu-content-guide').load("<?php bloginfo('stylesheet_directory'); ?>/guide-menu.php");
		$(".issue_link").colorbox({width:"90%", height:"90%", iframe:true});
		$(".contact_link").colorbox({width:"700px", height:"600px", iframe:true, scrolling:false});
		$(".register_link").colorbox({width:"825px", height:"90%", iframe:true, scrolling:true, close:"Close"});
<?php
	if (ao_get_in_vendorarea()) {
?>
		$("#advertiser_tc_link").colorbox({width:"700px", height:"600px", iframe:true});
		$("#advertiser_tc_link2").colorbox({width:"700px", height:"600px", iframe:true});
<?php
	}
?>
<?php
	//if ($_SERVER['REMOTE_ADDR'] == '76.17.3.14') {
		if (ao_get_in_guide() && !ao_get_regprompted()) {
			//echo '		$.fn.colorbox({width:"700px", height:"600px", iframe:true, scrolling:false, href:"/register/", open:true});';
			echo '		$.fn.colorbox({width:"825px", height:"90%", iframe:true, scrolling:true, href:"/register/", close:"Close", open:true});';
		}
	//}
?>
		// ColorBox flyouts END
		$('area').each(function() {
			$(this).qtip({
				content: $(this).attr('alt'), // Use the ALT attribute of the area map
				position: {
					corner: {
						target: 'bottomMiddle',
						tooltip: 'bottomMiddle'
					},
					adjust: {
						x: 0,
						y: 2
					}
				},
				style: {
					width: 136,
					padding: 3,
					background: '#333333',
					'font-family': 'Arial',
					'font-size': '0.75em',
					color: '#FFFFFF',
					opacity: 0.7,
					textAlign: 'left',
					tip: false // Apply a tip at the default tooltip corner
				}
			});
		});
	});



	// MENU code START
	<?php 
		// SEE: http://javascript-array.com/scripts/jquery_simple_drop_down_menu/  
		// a more advanced menu is here: http://users.tpg.com.au/j_birch/plugins/superfish/
	?>
	var timeout    = 500;
	var closetimer = 0;
	var ddmenuitem = 0;
	
	function jsddm_open()
	{  jsddm_canceltimer();
	   jsddm_close();
	   ddmenuitem = $(this).find('ul').css('display', 'block');
	   ddmenuitem = $(this).find('ul').css('visibility', 'visible');
	}
	
	function jsddm_close()
	{  if(ddmenuitem) ddmenuitem.css('visibility', 'hidden');}
	
	function jsddm_timer()
	{  closetimer = window.setTimeout(jsddm_close, timeout);}
	
	function jsddm_canceltimer()
	{  if(closetimer)
	   {  window.clearTimeout(closetimer);
		  closetimer = null;}}
	
	$(document).ready(function()
	{  $('#jsddm > li').bind('mouseover', jsddm_open)
	   $('#jsddm > li').bind('mouseout',  jsddm_timer)});
	
	document.onclick = jsddm_close;
	// MENU code END
	
</script>

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>

<!-- WP_HEAD -->
<?php wp_head(); ?>
<!-- WP_HEAD END -->

</head>
<body <?php body_class(); ?>>
<div id="oo-container">
<?php
//global $wp_query;
//$cat_array = $wp_query->query;
//$cat = $cat_array['category_name'];
	if (ao_get_in_guide()) {
  //$vendor_info = get_vendorfields();
  //echo 'the category is ' . $cat . '<br />';
?>
  <!--in the vendor area-->
<?php } 
//var_dump($wp_query); ?>
	<div id="oo-outernav">
		<div id="oo-topnav">
			<div id="oo-connect">
				<a href="/feed"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/oo_social_rss.png" /></a>
				<a class="register_link" href="/register/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/oo_social_email.png" /></a>
				<a target="_blank" href="http://www.facebook.com/pages/Occasions-Magazine/145861875458364"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/oo_social_facebook.png" /></a>
				<a target="_blank" href="http://twitter.com/OccasionsMag"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/oo_social_twitter.png" /></a>
			</div>
			<div id="oo-links">
				<a href="/about">about</a>
				<a href="/submissions">submit a recent event</a>
				<a href="http://mediakit.occasionsonline.com/">advertise</a>
				<a onfocus="this.blur();" class="issue_link" href="http://issuu.com/occasionsmagazine/docs/winter2012?mode=embed&layout=http://skin.issuu.com/v/light/layout.xml&showFlipBtn=true">view magazine</a>
				<a href="/badges">link to us</a>
			</div>
			<div id="oo-tagline">The Magazine For <span id="oo-tagline-pop">Celebrating</span> In Style</div>
			
		</div>
		<!-- oo-topnav -->
	</div>
	<!-- oo-outernav -->
    
	<div id="oo-livearea">
		<div id="oo-page">
			<div id="oo-mast">
				<div id="oo-logo"><a href="/"><img title="The Magazine for Celebrating in Style" src="<?php bloginfo('stylesheet_directory'); ?>/images/oo_logo.png" /></a>
				</div>
				<div id="oo-mast-ad">
					<script type="text/javascript"><!--
					google_ad_client = "ca-pub-4127320558779752";
					/* Lead OO Banner */
					google_ad_slot = "2339421180";
					google_ad_width = 728;
					google_ad_height = 90;
					//-->
					</script>
					<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
				</div>
			</div>
			<!-- oo-mast -->
			
			<div id="menubar"><ul id="jsddm">
				<li><a href="/">Home</a>
				</li>
<li><a href="/atlanta">Local Services</a>
<ul class="shadow"><li><div id="menu-content-guide">Guide is loading...</div></li></ul>
</li>
<li><a href="/atlanta/venues">Local Venues</a>
<ul><li><a href="/atlanta/venues/hotel">Hotel Ballrooms</a></li><li><a href="/atlanta/venues/country-club">Country Clubs</a></li><li><a href="/atlanta/venues/antebellum-home">Antebellum Homes</a></li><li><a href="/atlanta/venues/outdoor">Outdoor</a></li><li><a href="/atlanta/venues/rooftop">Rooftop</a></li><li><a href="/atlanta">More . . .</a></li></ul>
</li>
<li><a href="/party-ideas">Ideas</a>
<ul><li><a href="/party-ideas/colors">Search by Color</a></li><li><a href="/party-ideas/party-themes">Search by Theme</a></li><li><a href="/party-ideas/event-elements">Search by Event</a></li></ul>
</li>
<li><a href="/events/">Real Events</a>
<ul><li><a href="/events/real-weddings/">Weddings</a></li><li><a href="/events/real-mitzvahs/">Mitzvahs</a></li><li><a href="/party-ideas/party-themes/baby-shower-ideas-party-themes">Real Baby Showers</a></li><li><a href="/party-ideas/party-themes/birthday-party-ideas">Real Birthday Parties</a></li><!--li><a href="/featured-events">Featured In Print</a></li--></ul>
</li>
<li><a href="/event-planning">Event Elements</a>
	<ul><?php echo preg_replace("/[\t\n\r\f\v]/", "", wp_list_categories('hide_empty=false&child_of=12&title_li=&echo=0')); ?></ul>
</li>
<li><a href="/from-the-editor">Editor's Diary</a>
<ul><li><a href="/from-the-editor">Editor's Blog</a></li><li><a href="http://pinterest.com/occasionsmag/" target="_blank">Follow us on Pinterest</a></li><li><a href="/weekend-guide">Weekend Guide</a></li><li><a href="/calendar">Recommended Events</a></li></ul>
</li></ul></div>
			<!-- menubar -->
<div id="submenubar">
<?php
	$section = pods_url_variable(0);
	$subsection = pods_url_variable(1);
	$subsubsection = pods_url_variable(2);

	if (!$section || array_search($section, array('x','atlanta','profile','party-ideas','events','featured-events','event-planning','from-the-editor','weekend-guide','calendar'))) {
		setcookie('AO3_SECTION', $section, time()+60*60*24*30, '/');
		setcookie('AO3_SUBSECTION', $subsection, time()+60*60*24*30, '/');
	}
	else {
		$section = $_COOKIE['AO3_SECTION'];
		$subsection = $_COOKIE['AO3_SUBSECTION'];
	}

	if ($section == 'atlanta' && $subsection == 'venues') {
		$submenu = array(
			array('title' => 'Types of Venues'),
			array('title' => 'Hotel Ballrooms', 	'url' => '/atlanta/venues/hotel'),
			array('title' => 'Country Clubs', 		'url' => '/atlanta/venues/country-club'),
			array('title' => 'Antebellum Homes', 	'url' => '/atlanta/venues/antebellum-home'),
			array('title' => 'Outdoor', 			'url' => '/atlanta/venues/outdoor'),
			array('title' => 'Rooftop', 			'url' => '/atlanta/venues/rooftop'),
			array('title' => 'More . . .',				'url' => '/atlanta')
		);
	}
	elseif ($section == 'atlanta' || $section == 'profile') {
		$submenu = array(
			array('title' => 'Atlanta Vendors'),
			array('title' => 'Venues', 			'url' => '/atlanta/venues'),
			array('title' => 'Photographers', 	'url' => '/atlanta/photographers'),
			array('title' => 'Caterers', 		'url' => '/atlanta/caterers'),
			array('title' => 'Planners', 		'url' => '/atlanta/wedding-planners'),
			array('title' => 'Invitations', 	'url' => '/atlanta/invitations'),
			array('title' => 'DJs', 			'url' => '/atlanta/djs'),
			array('title' => 'Bands',			'url' => '/atlanta/bands'),
			array('title' => 'More . . .',			'url' => '/atlanta')
		);
	}
	elseif ($section == 'events' || $section == 'featured-events' || ($section == 'party-ideas' && $subsubsection == 'baby-shower-ideas-party-themes') || ($section == 'party-ideas' && $subsubsection == 'birthday-party-ideas')) {
		$submenu = array(
			array('title' => 'Real Events'),
			array('title' => 'Real Weddings', 				'url' => '/events/real-weddings'),
			array('title' => 'Real Mitzvahs', 				'url' => '/events/real-mitzvahs'),
			array('title' => 'Real Baby Showers',			'url' => '/party-ideas/party-themes/baby-shower-ideas-party-themes'),
			array('title' => 'Real Birthday Parties', 		'url' => '/party-ideas/party-themes/birthday-party-ideas')
			//array('title' => 'Featured in Print',			'url' => '/featured-events')
		);
	}
	elseif ($section == 'party-ideas') {
		$submenu = array(
			array('title' => 'Get Inspired'),
			array('title' => 'Search by Color', 			'url' => '/party-ideas/colors'),
			array('title' => 'Search by Theme', 			'url' => '/party-ideas/party-themes'),
			array('title' => 'Search by Event', 			'url' => '/party-ideas/event-elements')
		);
	}
	elseif ($section == 'event-planning') {
		$submenu = array(
			array('title' => 'Event Elements')
		);
		$cats = get_categories(array('hide_empty' => 0, 'child_of' => 12));
		$cats = array_slice($cats, 0, 4, true);
		foreach ($cats as $category) {
			array_push($submenu, array('title' => $category->name, 'url' => $category->slug));
		}
		array_push($submenu, array('title' => 'All . . .', 'url' => '/event-planning'));
	}
	elseif ($section == 'from-the-editor' || $section == 'weekend-guide' || $section == 'calendar') {
		$submenu = array(
			array('title' => "Editor's Diary"),
			array('title' => 'Follow us on Pinterest',		'url' => 'http://pinterest.com/occasionsmag/'),
			array('title' => 'Weekend Guide',				'url' => '/weekend-guide'),
			array('title' => 'Recommended Events', 			'url' => '/calendar')
		);
	}
	
//Blogs We Love (new page I want to create), Follow us on Pinterest (link to our pinterest), Recommended Events (links to calendar of events),

	else {
		$submenu = array(
			array('title' => 'Inspiration for Every Occasion'),
			array('title' => 'Weddings', 				'url' => '/events/real-weddings'),
			array('title' => 'Mitzvahs', 				'url' => '/events/real-mitzvahs'),
			array('title' => 'Birthday Parties', 		'url' => '/party-ideas/party-themes/birthday-party-ideas'),
			array('title' => 'Baby Showers',			'url' => '/party-ideas/party-themes/baby-shower-ideas-party-themes'),
			array('title' => 'Other Celebrations',		'url' => '/events/real-parties-and-celebrations')
		);
	}

	$menuitem = array_shift($submenu);
	echo '<span id="submenubar-title">' . $menuitem['title'] . '</span>';
	foreach ($submenu as $menuitem) {
		echo '<a href="' . $menuitem['url'] . '">' . $menuitem['title'] . '</a>';
	}

?>
</div>

			<div class="clear">&nbsp;</div>
			<table id="oo-page-content" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td id="oo-page-center">
<?php return;?>












							<li><a href="#">Recent Posts</a>
								<ul>
<?php
	$recent_posts = wp_get_recent_posts();
	foreach($recent_posts as $post){
		echo '<li><a href="' . get_permalink($post["ID"]) . '" title="'. htmlspecialchars($post["post_title"], ENT_QUOTES) . '" >' . ao_trim_to_length($post["post_title"], 50, " ", " . . .") . '</a></li> ';
	}
?>      
								</ul>
							</li>
