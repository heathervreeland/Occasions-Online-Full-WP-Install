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

<script type='text/javascript' src='http://ads.occasionsalamode.com/www/delivery/spcjs.php?id=1&amp;block=1&amp;blockcampaign=0&amp;target=_blank'></script>
<script type='text/javascript' src='/wp-includes/js/swfobject.js?ver=2.1'></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/media/js/spry/SpryCollapsiblePanel.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="/media/js/colorbox/jquery.colorbox-min.js"></script>

<script type="text/javascript">

	// ColorBox flyouts START
	$(document).ready(function(){
		$("#issue_link").colorbox({width:"90%", height:"90%", iframe:true});
		$("#contact_link").colorbox({width:"700px", height:"600px", iframe:true, scrolling:false});
<?php
	//if ($_SERVER['REMOTE_ADDR'] == '76.17.3.14') {
		if (ao_get_in_guide() && !ao_get_regprompted()) {
			//echo '		$.fn.colorbox({width:"700px", height:"600px", iframe:true, scrolling:false, href:"/register/", open:true});';
			echo '		$.fn.colorbox({width:"825px", height:"950px", iframe:true, scrolling:true, href:"/register/", close:"Close", open:true});';
		}
	//}
?>

	});
	// ColorBox flyouts END

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
		<div id="ao3headercontent">
			<div id="tagline">THE MAGAZINE FOR CELEBRATING IN STYLE&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;ATLANTA</div>
		</div><!-- ao3headercontent -->
	</div><!-- ao3header -->
	<div id="ao3pagewrap">
		<table id="ao3page">
			<tr>
				<td id="pagetop" colspan="2">
					<div id="menubar">
						<ul id="jsddm">
							<li><a href="/">Home</a>
							</li>
<li><a href="" onclick="javascript: jsddm_open(); return false;">Vendor Guide</a>

<?php
		$categories = new Pod('categories');
		//$categories->findRecords( '', 0, '', 'SELECT name, slug, description FROM wp_pod_tbl_categories WHERE parentid IN (3) AND hide <> 1 ORDER BY sort_order, name');
		$categories->findRecords( '', 0, '', 'SELECT name, slug, description FROM wp_pod_tbl_categories WHERE hide <> 1 ORDER BY sort_order, name');
		$total_cats = $categories->getTotalRows();
		//echo '<ul><li><a href="/guide/venues">Venues</a></li><li><a href="/guide/dining">Rehearsal Dinner & Private Dining</a></li>';
		echo '<ul>';
		
		if( $total_cats > 0 ) {
			while ( $categories->fetchRecord() ) {
				$cat_name		= $categories->get_field('name');
				$cat_slug		= $categories->get_field('slug');
				echo '<li><a href="/guide/', $cat_slug, '">', $cat_name, '</a></li>';
			}
		}
?>
</ul></li>
<li><a href="/gallery/">Real Event Galleries</a>
<ul><li><a href="/gallery/weddings-gallery/">Weddings</a></li><li><a href="/gallery/social-events-gallery/">Social Events</a></li><li><a href="/gallery/corporate-events-gallery/">Corporate Events</a></li><li><a href="/gallery/mitzvahs-gallery/">Mitzvahs</a></li><li><a href="/gallery/parties-gallery/">Parties</a></li></ul>
</li>
							<li><a href="/blog/">Departments</a>
								<ul><?php echo preg_replace("/[\t\n\r\f\v]/", "", wp_list_categories('hide_empty=false&child_of=12&title_li=&echo=0')); ?></ul>
							</li>
							<li><a href="/events/">Upcoming Events</a>
								<ul>
									<li><a href="/events/">View All Events . . .</a></li><?php 
$e = new Pod('events');
$sqlwhere = "( (approved = 1) AND (date_start >= '". date("Y-m-d") . "  00:00:00') ) ";
$e->findRecords( 't.date_start ASC', 15, $sqlwhere);

if( $e->getTotalRows() > 0 ) {
	while ( $e->fetchRecord() ) {
		echo '<li><a href="/events/#event_', $e->get_field('id') , '" />', ao_trim_to_length(date("D M j, Y", strtotime( $e->get_field('date_start') )) . ' - ' . $e->get_field('name'), 50), '</a></li>';
	}
}
?></ul>
							</li>
							<li><a style="border-right: 1px solid #fff;" href="/about">About</a>
<ul><?php echo preg_replace("/[\t\n\r\f\v]/", "", wp_list_pages('title_li=&echo=0')); ?><li><a href="http://www.facebook.com/AtlantaOccasions" target="_blank">Facebook</a></li><li><a href="http://twitter.com/OccasionsMag" target="_blank">Twitter</a></li><li><a onfocus="this.blur();" id="issue_link" href="http://issuu.com/atlantaoccasions/docs/summer2010?mode=embed&layout=http://skin.issuu.com/v/light/layout.xml&showFlipBtn=true">View Magazine</a></li></ul>
							</li>
						</ul>
					</div>
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
