<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

$img_base_path 		= '/home/heatherv/public_html';

$img_dst_source 	= "$img_base_path/media/images/profiles/source";
$img_dst_large		= "$img_base_path/media/images/profiles/large";
$img_dst_thumb		= "$img_base_path/media/images/profiles/thumbs";
$img_dst_logo		= "$img_base_path/media/images/profiles/logos";
$img_dst_logosource	= "$img_base_path/media/images/profiles/logo-source";
$img_dst_video		= "$img_base_path/media/images/profiles/video";
$img_web_source		= "/media/images/profiles/source";
$img_web_large		= "/media/images/profiles/large";
$img_web_thumb		= "/media/images/profiles/thumbs";
$img_web_logo		= "/media/images/profiles/logos";
$img_web_logosource	= "/media/images/profiles/logo-source";
$img_web_video		= "/media/images/profiles/video";

$twitter_cache		= "$img_base_path/media/images/profiles/twitter";


define ("AO_ADMIN_EMAIL",		'"Heather Vreeland" <heather@atlantaoccasions.com>');
define ("AO_TECH_EMAIL",		'"Domain Administrator" <domain-admin@benvigil.com>');


automatic_feed_links();
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size(125, 125, true);

date_default_timezone_set('America/New_York');

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
}

function ao_mce_options( $init ) {
	$init['theme_advanced_blockformats'] = 'p,address,pre,code,h3,h4,h5,h6';
	//$init['theme_advanced_disable'] = 'forecolor';
	$init['relative_urls'] = false;
	$init['remove_script_host'] = true;
	$init['document_base_url'] = get_bloginfo('url') . '/';
	return $init;
}
add_filter('tiny_mce_before_init', 'ao_mce_options');

// this function forces a HTTPS secure connection, redirecting if needed, if we are
//		in designated areas of the website.
function force_https()
{
	if(ao_get_in_vendorarea())
	{
		if($_SERVER['SERVER_PORT'] != '443')
		{
			$strLocation='https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			header("Location: $strLocation");
			exit();
		}
	} else {
		if($_SERVER['SERVER_PORT'] == '443')
		{
			$strLocation='http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
			header("Location: $strLocation");
			exit();
		}
	}
}
// HOOK the HTTPS-related functon above
add_filter('get_header', 'force_https');



// EDIT BEN
function ao_category_template( $template ) {

	if( is_category( array( 'services' ) ) ) {
		$template = locate_template( array( 'ao-services.php', 'archive.php' ) );
	}

	/// anything that starts with "guide-"
	elseif( substr(get_category(get_query_var('cat'))->slug, 0, 6) == 'guide-') {
		$template = locate_template( array( 'ao-guide.php', 'archive.php' ) );
	}

	// these are more examples...
	//elseif( is_category( array( 21, 32 ) ) ) // We can search for multiple categories by ID by passing an array
	//	$template = locate_template( array( 'template_id_B.php', 'category.php' ) );

	//elseif( is_category( 'food' ) ) // We can search for categories by their slug
	//	$template = locate_template( array( 'template_slug_A.php', 'category.php' ) );

	//elseif( is_category( array( 'music', 'movies' ) ) ) // We can search for multiple categories by slug as well
	//	$template = locate_template( array( 'template_slug_A.php', 'category.php' ) );
	
	
	return $template;
}

function ao_single_template( $template ) {

	if( ao_post_is_in_descendant_category( get_term_by( 'slug', 'services', 'category' ) ) )
		$template = locate_template( array( 'ao-profile.php', 'archive.php' ) );

	// these are more examples...
	//elseif( is_category( array( 21, 32 ) ) ) // We can search for multiple categories by ID by passing an array
	//	$template = locate_template( array( 'template_id_B.php', 'category.php' ) );

	//elseif( is_category( 'food' ) ) // We can search for categories by their slug
	//	$template = locate_template( array( 'template_slug_A.php', 'category.php' ) );

	//elseif( is_category( array( 'music', 'movies' ) ) ) // We can search for multiple categories by slug as well
	//	$template = locate_template( array( 'template_slug_A.php', 'category.php' ) );
	
	return $template;
}
// HOOK the category templates
add_filter( 'category_template', 'ao_category_template' );
add_filter( 'single_template', 'ao_single_template' );


function ao_post_is_in_descendant_category( $cats, $_post = null )
{
	foreach ( (array) $cats as $cat ) {
		// get_term_children() accepts integer ID only
		$descendants = get_term_children( (int) $cat, 'category');
		if ( $descendants && in_category( $descendants, $_post ) )
			return true;
	}
	return false;
}

function ao_trim_to_length($string, $limit, $break=" ", $pad=" . . .") {
	// return with no change if string is shorter than $limit
	if(strlen($string) <= $limit) return $string;
	
	$string = substr($string, 0, $limit);
	if(false !== ($breakpoint = strrpos($string, $break))) {
		$string = substr($string, 0, $breakpoint);
	}
	return $string . $pad;
}

$ao_in_guide = false;
$ao_in_vendorarea = false;
$ao_regprompted = ($_COOKIE['AO3_REGPROMPTED'] == '1');
$sidebar_content_prewidget = NULL;
$sidebar_content_postwidget = NULL;
$sidebar_content_trailing = NULL;
$ao_title = NULL;

function ao_set_in_guide($content) {
	global $ao_in_guide;
	$ao_in_guide = $content;
}

function ao_get_in_guide() {
	global $ao_in_guide;
	return $ao_in_guide;
}

function ao_set_in_vendorarea($content) {
	global $ao_in_vendorarea;
	$ao_in_vendorarea = $content;
}

function ao_get_in_vendorarea() {
	global $ao_in_vendorarea;
	return $ao_in_vendorarea;
}

function ao_set_regprompted($content) {
	global $ao_regprompted;
	$ao_regprompted = $content;
	setcookie('AO3_REGPROMPTED', $content, time()+60*60*24*30, '/');
}

function ao_get_regprompted() {
	global $ao_regprompted;
	return $ao_regprompted;
}

function ao_set_title($content) {
	global $ao_title;
	$ao_title = $content;
}

function ao_get_title() {
	global $ao_title;
	return $ao_title;
}

function ao_set_sidebar_content($content, $location, $replace = false) {

	global $sidebar_content_prewidget;
	global $sidebar_content_postwidget;
	global $sidebar_content_trailing;

	if ($replace) {
		if ($location == 'prewidget') {
			$sidebar_content_prewidget = $content;
		}
		elseif ($location == 'postwidget') {
			$sidebar_content_postwidget = $content;
		}
		else {
			$sidebar_content_trailing = $content;
		}
	}
	else {
		if ($location == 'prewidget') {
			$sidebar_content_prewidget .= $content;
		}
		elseif ($location == 'postwidget') {
			$sidebar_content_postwidget .= $content;
		}
		else {
			$sidebar_content_trailing .= $content;
		}
	}	
}

function ao_get_sidebar_content($location) {

	global $sidebar_content_prewidget;
	global $sidebar_content_postwidget;
	global $sidebar_content_trailing;

	if ($location == 'prewidget') {
		return $sidebar_content_prewidget;
	}
	elseif ($location == 'postwidget') {
		return $sidebar_content_postwidget;
	}
	else {
		return $sidebar_content_trailing;
	}
}

function ao_sidebar_content($location) {
	echo ao_get_sidebar_content($location);
}

function get_real_ip() {
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function cc_check_type( $cardnumber ) { 

   $cardtype = "UNKNOWN";

   $len = strlen($cardnumber);
   if     ( $len == 15 && substr($cardnumber, 0, 1) == '3' )                 { $cardtype = "a"; } // amex
   elseif ( $len == 16 && substr($cardnumber, 0, 4) == '6011' )              { $cardtype = "d"; } // discover
   elseif ( $len == 16 && substr($cardnumber, 0, 1) == '5'  )                { $cardtype = "m"; } // mastercard
   elseif ( ($len == 16 || $len == 13) && substr($cardnumber, 0, 1) == '4' ) { $cardtype = "v"; } // visa

   return ( $cardtype );

}


function cc_check_number( $cardnumber ) {    

    $dig = toCharArray($cardnumber); 
    $numdig = sizeof ($dig); 
    $j = 0; 
    for ($i=($numdig-2); $i>=0; $i-=2){ 
        $dbl[$j] = $dig[$i] * 2; 
        $j++; 
    }     
    $dblsz = sizeof($dbl); 
    $validate =0; 
    for ($i=0;$i<$dblsz;$i++){ 
        $add = toCharArray($dbl[$i]); 
        for ($j=0;$j<sizeof($add);$j++){ 
            $validate += $add[$j]; 
        } 
    $add = ''; 
    } 
    for ($i=($numdig-1); $i>=0; $i-=2){ 
        $validate += $dig[$i]; 
    } 
    if (substr($validate, -1, 1) == '0') { return 1;  }
    else { return 0; }
}

// takes a string and returns an array of characters 
function toCharArray($input){ 
    $len = strlen($input); 
    for ($j=0;$j<$len;$j++){ 
        $char[$j] = substr($input, $j, 1);     
    } 
    return ($char); 
} 

function iif($condition, $true, $false) {

	return ($condition ? $true : $false);
}

function print_iif($condition, $true, $false) {

	echo iif($condition, $true, $false );
}


function dateadd_months($base_time = null, $months = 1) {
	if (is_null($base_time)) {
		$base_time = time();
	}
	
	$x_months_to_the_future    = strtotime( "+" . $months . " months", $base_time );
	$month_before              = (int) date( "m", $base_time ) + 12 * (int) date( "Y", $base_time );
	$month_after               = (int) date( "m", $x_months_to_the_future ) + 12 * (int) date( "Y", $x_months_to_the_future );
	
	if ($month_after > $months + $month_before) {
		$x_months_to_the_future = strtotime( date("Ym01His", $x_months_to_the_future) . " -1 day" );
	}

	return $x_months_to_the_future;
}

function popular_posts($num, $trim_to_length = 0) {

    global $wpdb;
    $posts = $wpdb->get_results("SELECT comment_count, ID, post_title FROM $wpdb->posts ORDER BY comment_count DESC LIMIT 0 , $num");
 
	$apopular = array();
    foreach ($posts as $post) {
        setup_postdata($post);
        $id = $post->ID;
        $title = $post->post_title;
        $count = $post->comment_count;
 
        if ($count != 0) {
        	$s = '<a href="' . get_permalink($id) . '" title="' . $title . '">' . iif($trim_to_length > 0, ao_trim_to_length($title, $trim_to_length, ' ', '...'), $title) . '</a> ';
            $apopular[] = $s;
        }
    }
    $popular = implode(', ',$apopular);
    return $popular;
}

function ao_log($item_id, $action, $data = '') {
	$ip = get_real_ip();
	pod_query("INSERT INTO ao_logs VALUES(NULL, NULL, '$ip', $item_id, '$action', '$data')");
}

?>
