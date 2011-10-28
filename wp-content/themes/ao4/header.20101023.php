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
<?php if (ao_get_title()) { 
echo '<title>', ao_get_title(), '</title>';
}
else { ?>
<title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
<?php } ?>
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<!--[if IE 6]> 
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style-winie6.css" />
<![endif]-->

<!--[if IE 7]> 
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/style-winie7.css" />
<![endif]-->

<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/colorbox.css" type="text/css" media="screen" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<script type="text/javascript" src="/media/js/jquery.min.js"></script>
<script type="text/javascript" src="/media/js/jquery.qtip-1.0.0-rc3-min.js"></script>
<script type="text/javascript" src="/media/js/jquery.validate.js"></script>
<script type="text/javascript" src="/media/js/jquery.oembed.min.js"></script>
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
	else {
?>
<script type='text/javascript' src='http://ads.occasionsalamode.com/www/delivery/spcjs.php?id=1&amp;block=1&amp;blockcampaign=1&amp;target=_blank'></script>
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
		$("#issue_link").colorbox({width:"90%", height:"90%", iframe:true});
		$("#contact_link").colorbox({width:"700px", height:"600px", iframe:true, scrolling:false});
		$("#register_link").colorbox({width:"825px", height:"950px", iframe:true, scrolling:true, close:"Close"});
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
			echo '		$.fn.colorbox({width:"825px", height:"950px", iframe:true, scrolling:true, href:"/register/", close:"Close", open:true});';
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
						y: 2,
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
<div id="ao3container">
	<div id="ao3header">
		<div id="ao3headermast">
			<div id="ao4logo"><a href="/"><img title="The Magazine for Celebrating in Style" src="<?php bloginfo('stylesheet_directory'); ?>/images/ao4-logo.png" /></a></div>
			<div id="taglinks"><a href="/about">about</a> | <a href="/advertise">advertise</a> | <a href="/submissions">submissions</a> | <a onfocus="this.blur();" id="issue_link" href="http://issuu.com/atlantaoccasions/docs/summer2010?mode=embed&layout=http://skin.issuu.com/v/light/layout.xml&showFlipBtn=true">view magazine</a></div>
			<div id="connectblock"><span class="connecttext">Connect</span> <a target="_blank" href="http://twitter.com/OccasionsMag"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo-twitter.jpg" /></a> <a target="_blank" href="http://www.facebook.com/AtlantaOccasions"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo-facebook.jpg" /></a>&nbsp;&nbsp;&nbsp;<span class="connecttext">Subscribe</span> <a id="register_link" href="/register/"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo-email.jpg" /></a> <a href="/feed"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo-rss.jpg" /></a></div>
 		</div><!-- ao3headercontent -->
		<div id="ao3headercontent">
			<img id="ao4header" usemap="#ao4header" src="<?php bloginfo('stylesheet_directory'); ?>/images/header-1.jpg" />
 		</div><!-- ao3headercontent -->
	</div><!-- ao3header -->
	<div id="ao3pagewrap">
<map name="ao4header" id="ao4header">
<area shape="rect" coords="0,0,135,195" href="#" alt="Credit: Alea Moore Photography" onclick="javascript:this.blur(); return false;">
<area shape="rect" coords="136,0,273,195" href="#" alt="Credit: Alea Moore Photography" onclick="javascript:this.blur(); return false;">
<area shape="rect" coords="274,0,412,195" href="#" alt="Credit: Christine Gallagher Photography" onclick="javascript:this.blur(); return false;">
<area shape="rect" coords="413,0,551,195" href="#" alt="Credit: Andrea Taylor Studio" onclick="javascript:this.blur(); return false;">
<area shape="rect" coords="552,0,701,195" href="#" alt ="Credit: Andrea Taylor Studio" onclick="javascript:this.blur(); return false;">
<area shape="rect" coords="702,0,839,195" href="#" alt="Credit: Christine Gallagher Photography" onclick="javascript:this.blur(); return false;">
<area shape="rect" coords="840,0,975,195" href="#" alt="Credit: Christine Gallagher Photography" onclick="javascript:this.blur(); return false;">
</map>
<table id="ao3page">
			<tr>
				<td id="pagetop" colspan="2">
					<div id="menubar"><?php get_search_form(); ?><ul id="jsddm">
							<li><a href="/">Home</a>
							</li>
<li><a href="/guide/">Vendor Guide</a></li>
<li><a href="/gallery/">Real Events</a>
<ul><li><a href="/gallery/weddings-gallery/">Weddings</a></li><li><a href="/gallery/social-events-gallery/">Social Events</a></li><li><a href="/gallery/corporate-events-gallery/">Corporate Events</a></li><li><a href="/gallery/mitzvahs-gallery/">Mitzvahs</a></li><li><a href="/gallery/parties-gallery/">Parties</a></li></ul>
</li>
							<li><a href="/events/">Calendar</a>
								<ul>
									<li><a href="/events/">View All Events . . .</a></li><?php 
$e = new Pod('events');
$sqlwhere = "( (approved = 1) AND (date_start >= '". date("Y-m-d") . "  00:00:00') ) ";
$e->findRecords( 't.date_start ASC', 15, $sqlwhere);

if( $e->getTotalRows() > 0 ) {
	while ( $e->fetchRecord() ) {
		echo '<li><a href="/events/#event_', $e->get_field('id') , '">', ao_trim_to_length(date("D M j, Y", strtotime( $e->get_field('date_start') )) . ' - ' . $e->get_field('name'), 50), '</a></li>';
	}
}
?></ul>
							</li>
							<li><a href="/blog/">Departments</a>
								<ul><?php echo preg_replace("/[\t\n\r\f\v]/", "", wp_list_categories('hide_empty=false&child_of=12&title_li=&echo=0')); ?></ul>
							</li>
						</ul></div>
				</td>
			</tr>
			<tr>
				<td id="pagecenter">
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
