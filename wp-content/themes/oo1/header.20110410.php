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
<meta name="alexaVerifyID" content="KemOZK3goi3teGuO-Phi0tUDJZA" />
<?php if (ao_get_title()) { 
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
<script type='text/javascript' src='http://ads.occasionsalamode.com/www/delivery/spcjs.php?id=1&amp;block=1&amp;blockcampaign=1&amp;target=_blank'></script>
<!-- AD SERVING END -->
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<?php
	}
?>
<script type='text/javascript' src='/wp-includes/js/swfobject.js?ver=2.1'></script>
<script type="text/javascript" src="/media/js/spry/SpryCollapsiblePanel.js"></script>
<script type="text/javascript" src="/media/js/colorbox/jquery.colorbox-min.js"></script>

<script type="text/javascript">

	// ColorBox flyouts START
	$(document).ready(function(){
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
	<div id="oo-outernav">
		<div id="oo-topnav">
			<div id="oo-links">
				<a href="/about">about</a>
				<a href="/submissions">submit a recent event</a>
				<a href="/advertise">advertise</a>
				<a onfocus="this.blur();" class="issue_link" href="http://issuu.com/atlantaoccasions/docs/winter2011?mode=embed&layout=http://skin.issuu.com/v/light/layout.xml&showFlipBtn=true">view magazine</a>
				<a href="/badges">link to us</a>
			</div>
			<!-- oo-links -->
			<?php get_search_form(); ?>
			
		</div>
		<!-- oo-topnav -->
	</div>
	<!-- oo-outernav -->
    
	<div id="oo-livearea">
		<div id="oo-page">
			<div id="oo-mast">
				<div id="oo-logo"><a href="/"><img title="The Magazine for Celebrating in Style" src="<?php bloginfo('stylesheet_directory'); ?>/images/ao4-logo-240.png" /></a>
					<div id="oo-tagline">The Magazine For <span id="oo-tagline-pop">Celebrating</span> In Style</div>
				</div>
				<div id="oo-mast-ad">
				</div>
			</div>
			<!-- oo-mast -->
			
			<div id="menubar"><ul id="jsddm">
				<li><a href="/">Home</a>
				</li>
<li><a href="/atlanta">Vendor Guide</a>
<ul class="shadow"><li><div id="menu-content-guide">
	<div id="menu-content-featured">
		<div class="head3">Highest Rated Vendors</div>
<?php
	include_once('guide-functions.php');

	$vp = new Pod('vendor_profiles');
	$vp->findRecords( '', 0, '', 'SELECT * FROM wp_pod_tbl_vendor_profiles WHERE profile_type = \'platinum\' AND rating > 4 ORDER BY NAME');
	$total_vps = $vp->getTotalRows();
	
	$avp = array();
	if( $total_vps > 0 ) {
		while ( $vp->fetchRecord() ) {
			// get our fields from the POD
			$avp[$vp->get_field('id')] = get_vendorfields($vp);
		}
	}
	
	// shuffle our vendors and then only take the first two
	shuffle($avp);
	$avp = array_slice($avp, 0, 3, true);

	foreach($avp as $vid => $fields) {
		
		// create our rating box
		$rating_data = get_ratingbox($fields["rati"]);

		echo <<<HEREDOC
		<div class="menu-featured-vendor" onclick="window.location.href='/profile/{$fields["slug"]}'">
			<a href="/profile/{$fields["slug"]}"><img src="{$fields["imag"]}" title="{$fields["summ"]}" /></a>
			<a href="/profile/{$fields["slug"]}">{$fields["name"]}</a>
			<!-- <h2 class="guidelist_name"><a href="/profile/{$fields["slug"]}">{$fields["name"]}</a></h2> -->
			$rating_data
		</div>
		
HEREDOC;
	}
?>
	</div>
	<div id="menu-content-guidelist">
<?php
	$categories = new Pod('categories');
	$categories->findRecords( '', 0, '', 'SELECT name, slug, short_title, description FROM wp_pod_tbl_categories WHERE hide <> 1 AND priority = 1 ORDER BY sort_order, name');
	$total_cats = $categories->getTotalRows();
	
	if( $total_cats > 0 ) {
		while ( $categories->fetchRecord() ) {
			$cat_name		= $categories->get_field('name');
			$cat_slug		= $categories->get_field('slug');
			echo '<div class="menu-guide-list"><a href="/atlanta/', $cat_slug, '">', $cat_name, '</a></div>';
		}
	}
?>
<p class="menu-guide-list">&nbsp;</p>
<p class="menu-guide-list"><a href="/atlanta/">more...</a></p>
</div></div></li></ul>
</li>
				<li><a href="/event-planning">Event Elements</a>
					<ul><?php echo preg_replace("/[\t\n\r\f\v]/", "", wp_list_categories('hide_empty=false&child_of=12&title_li=&echo=0')); ?></ul>
				</li>
<li><a href="/party-ideas">Party Ideas</a></li>
<li><a href="/events/">Real Events</a>
<ul><li><a href="/events/real-weddings/">Weddings</a></li><li><a href="/events/real-mitzvahs/">Mitzvahs</a></li><li><a href="/events/real-parties-and-celebrations/">Parties & Celebratons</a></li><li><a href="/featured-events">Featured In Print</a></li></ul>
</li>
<li><a href="/calendar">Calendar</a>
<ul><li><a href="/weekend-guide">Weekend Guide</a></li><li><a href="/calendar">View All Events . . .</a></li></ul>
</li>
				<li><a href="/from-the-editor">From the Editor</a>
				</li></ul></div>			
			<!-- menubar -->
			<div id="oo-connect">
				<a href="/feed"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo-rss.jpg" /></a>
				<a class="register_link" href="/register/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo-email.jpg" /></a>
				<a target="_blank" href="http://www.facebook.com/pages/Occasions-Magazine/145861875458364"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo-facebook.jpg" /></a>
				<a target="_blank" href="http://twitter.com/OccasionsMag"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo-twitter.jpg" /></a>
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
