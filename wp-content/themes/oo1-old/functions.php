<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

//XX
$img_base_path 		= '/home/oonline/public_html';
//$img_base_path 		= '/home/oonline/2011';

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

include_once('plum-gallery/plum-gallery.php');

define ("AO_ADMIN_EMAIL",		'"Heather Vreeland" <heather@occasionsonline.com>');
define ("AO_OFFICE_EMAIL",		'"Jennifer Taylor" <jennifer@occasionsonline.com>');
define ("AO_SALES_EMAIL",		'"Lisa Alexander" <lisa@occasionsonline.com>');
define ("AO_TECH_EMAIL",		'"Domain Administrator" <domain-admin@benvigil.com>');
define ("AO_EMAIL_FLAGS",		'-f do-not-reply@occasionsonline.com');


automatic_feed_links();
add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size(125, 125, true);

date_default_timezone_set('America/New_York');


// LET CONTRIBUTORS UPLOAD FILES
if ( current_user_can('contributor') && !current_user_can('upload_files') )
	add_action('admin_init', 'allow_contributor_uploads');

function allow_contributor_uploads() {
	$contributor = get_role('contributor');
	$contributor->add_cap('upload_files');
}


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
	if(ao_get_in_vendorarea() || ao_get_require_ssl())
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

$ao_require_ssl = false;
$ao_in_guide = false;
$ao_in_profile = false;
$ao_in_vendorarea = false;
$ao_regprompted = ($_COOKIE['AO3_REGPROMPTED'] == '1');
$sidebar_content_prewidget = NULL;
$sidebar_content_postwidget = NULL;
$sidebar_content_trailing = NULL;
$ao_title = NULL;

function ao_set_require_ssl($content) {
	global $ao_require_ssl;
	$ao_require_ssl = $content;
}

function ao_get_require_ssl() {
	global $ao_require_ssl;
	return $ao_require_ssl;
}

function ao_set_in_guide($content) {
	global $ao_in_guide;
	$ao_in_guide = $content;
}

function ao_get_in_guide() {
	global $ao_in_guide;
	return $ao_in_guide;
}

function ao_set_in_profle($content) {
	global $ao_in_profile;
	$ao_in_profile = $content;
}

function ao_get_in_profile() {
	global $ao_in_profile;
	return $ao_in_profile;
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

function ao_get_in_dropbox() {
	return (pods_url_variable(0) == 'dropbox');
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

function gethostbyaddr_timeout($ip, $dns = '208.75.148.101', $timeout = 1000) {
	// taken from http://www.php.net/manual/en/function.gethostbyaddr.php#46869
	// random transaction number (for routers etc to get the reply back)
	$data = rand(0, 99);
	// trim it to 2 bytes
	$data = substr($data, 0, 2);
	// request header
	$data .= "\1\0\0\1\0\0\0\0\0\0";
	// split IP up
	$bits = explode(".", $ip);
	// error checking
	if (count($bits) != 4) return "ERROR";
	// there is probably a better way to do this bit...
	// loop through each segment
	for ($x=3; $x>=0; $x--)
	{
		// needs a byte to indicate the length of each segment of the request
		switch (strlen($bits[$x]))
		{
			case 1: // 1 byte long segment
				$data .= "\1"; break;
			case 2: // 2 byte long segment
				$data .= "\2"; break;
			case 3: // 3 byte long segment
				$data .= "\3"; break;
			default: // segment is too big, invalid IP
				return "INVALID";
		}
		// and the segment itself
		$data .= $bits[$x];
	}
	// and the final bit of the request
	$data .= "\7in-addr\4arpa\0\0\x0C\0\1";
	// create UDP socket
	$handle = @fsockopen("udp://$dns", 53);
	// send our request (and store request size so we can cheat later)
	$requestsize=@fwrite($handle, $data);

	// convert to microsoconds
	$timeout *= 1000;
	@socket_set_timeout($handle, floor($timeout/1000000), $timeout%1000000);
	//@socket_set_timeout($handle, $timeout - $timeout%1000, $timeout%1000);
	// hope we get a reply
	$response = @fread($handle, 1000);
	@fclose($handle);
	if ($response == "")
		return $ip;
	// find the response type
	$type = @unpack("s", substr($response, $requestsize+2));
	if ($type[1] == 0x0C00)	 // answer
	{
		// set up our variables
		$host="";
		$len = 0;
		// set our pointer at the beginning of the hostname
		// uses the request size from earlier rather than work it out
		$position=$requestsize+12;
		// reconstruct hostname
		do
		{
			// get segment size
			$len = unpack("c", substr($response, $position));
			// null terminated string, so length 0 = finished
			if ($len[1] == 0)
				// return the hostname, without the trailing .
				return substr($host, 0, strlen($host) -1);
			// add segment to our host
			$host .= substr($response, $position+1, $len[1]) . ".";
			// move pointer on to the next segment
			$position += $len[1] + 1;
		}
		while ($len != 0);
		// error - return the hostname we constructed (without the . on the end)
		return $ip;
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
	$ua = getenv("HTTP_USER_AGENT");
	$ua = htmlspecialchars($ua);
	
	// UserAgent strings compiled from http://user-agent-string.info/rpc/get_data.php?uaslist=csv
	// 76.17.3.14 -- Ben
	// 98.88.13.141 -- Heather office
	// 74.176.242.42 - Heather home
	$grep_str = "/98\.88\.13\.141|74\.176\.242\.42|76\.17\.3\.14|\bbot|bot\b|crawler|spider|worm|crawl|slurp|downloader|search|verifier|sbider|scout|harvest|sniffer|validator|checker|parser|archiver|tracker|generator|loadcontrol|CSSCheck|Cynthia|checklink|link\w+|Valet|xenu|jeeves|blogpulse|blaiz|browsershots|baypup|robozilla|ccubee|Mediapartners|changedetection|copyrightsheriff|DataFountains|del\.icio\.us\-thumbnails|DepSpid|egothor|dnsdigger|ejupiter|ExactSEEK|FairShare|gonzo|ichiro|iCjobs|InternetSeer|L\.webis|Megatext|Metaspinner|ranking|Netintelligence|Nuhk|Nutch|OpenCalaisSemanticProxy|pingdone|pompos|posterus|scooter|shelob|snappy|Vagabondo|vortex|vermut|watchmouse|wectar|wwwster|heritrix|holmes|livedoor|minirank|metauri|OnetSzukaj|WebImages|wget/i";
	// first we match against known spider/crawler/indexer user agent string keywords
	$robot = preg_match($grep_str, $ua);
	if (!$robot) {
		// match against ips in the list
		$robot = preg_match($grep_str, $ip);
	}
	if (!$robot) {
		// now match the hostname against keywords
		// since this in potentially time intensive, we only do this if needed
		//$host = gethostbyaddr_timeout($ip);
		//$robot = preg_match($grep_str, $host);
	}

	if ($robot) {
		$data = "BOT: $ua";
	}
	pod_query("INSERT INTO ao_logs VALUES(NULL, NULL, '$ip', $item_id, '$action', '$data')");
}

/**
* A pagination function
* @param integer $range: The range of the slider, works best with even numbers
* Used WP functions:
* get_pagenum_link($i) - creates the link, e.g. http://site.com/page/4
* previous_posts_link(' A<< '); - returns the Previous page link
* next_posts_link(' A>> '); - returns the Next page link
*/
function get_pagination($range = 5){
  // $paged - number of the current page
  global $paged, $wp_query;
  // How much pages do we have?
  if ( !$max_page ) {
    $max_page = $wp_query->max_num_pages;
  }
  // We need the pagination only if there are more than 1 page
  if($max_page > 1){
    if(!$paged){
      $paged = 1;
    }
    echo '<span class="pag-ends">';
    // On the first page, don't put the First page link
    if($paged != 1){
      echo "<a href=" . get_pagenum_link(1) . "><</a>";
      //echo "<a href=" . get_pagenum_link(1) . "> First </a>";
    }
    // To the previous page
    //previous_posts_link(' A<< ');
    previous_posts_link(' Prev ');
    echo '</span>';
    // We need the sliding effect only if there are more pages than is the sliding range
    if($max_page > $range){
      // When closer to the beginning
      if($paged < $range){
        for($i = 1; $i <= ($range + 1); $i++){
          echo "<a href='" . get_pagenum_link($i) ."'";
          if($i==$paged) echo "class='current'";
          echo ">$i</a>";
        }
      }
      // When closer to the end
      elseif($paged >= ($max_page - ceil(($range/2)))){
        for($i = $max_page - $range; $i <= $max_page; $i++){
          echo "<a href='" . get_pagenum_link($i) ."'";
          if($i==$paged) echo "class='current'";
          echo ">$i</a>";
        }
      }
      // Somewhere in the middle
      elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
        for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){
          echo "<a href='" . get_pagenum_link($i) ."'";
          if($i==$paged) echo "class='current'";
          echo ">$i</a>";
        }
      }
    }
    // Less pages than the range, no sliding effect needed
    else{
      for($i = 1; $i <= $max_page; $i++){
        echo "<a href='" . get_pagenum_link($i) ."'";
        if($i==$paged) echo "class='current'";
        echo ">$i</a>";
      }
    }
    echo '<span class="pag-ends">';
    // Next page
    //next_posts_link(' A>> ');
    next_posts_link(' Next ');
    // On the last page, don't put the Last page link
    if($paged != $max_page){
      echo " <a href=" . get_pagenum_link($max_page) . ">></a>";
      //echo " <a href=" . get_pagenum_link($max_page) . "> Last </a>";
    }
    echo '</span>';
  }
}

function oo_get_first_gallery_image($galleryid, $class = '') {
	global $wpdb;
	global $ngg_options;
	if (!$galleryid) return;
	if (!$wpdb->nggallery) return;
	
	if (! $ngg_options) {
		$ngg_options = get_option('ngg_options');
	}
	
	$picturelist = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$galleryid' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] LIMIT 1");
	if ($class) $myclass = ' class="'.$class.'" ';
	if ($picturelist) {
		$pid = $picturelist[0]->pid;
		if (is_callable(array('nggGallery','get_image_url'))) {
			// new NextGen 1.0+
			$out = '<img alt="' . __('property photo') . '" src="' . nggGallery::get_image_url($pid) . '" ' . $myclass . '/>';
		}
		else {
			// backwards compatibility - NextGen below 1.0
			$out = '<img alt="' . __('property photo') . '" src="' . nggallery::get_image_url($pid) . '" ' . $myclass . '/>';
		}
		return $out;
	}
}

function oo_get_first_gallery_thumbnail($galleryid, $class = '') {
	global $wpdb;
	global $ngg_options;
	if (!$galleryid) return;
	if (!$wpdb->nggallery) return;
	
	if (! $ngg_options) {
		$ngg_options = get_option('ngg_options');
	}
	
	$picturelist = $wpdb->get_results("SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$galleryid' AND tt.exclude != 1 ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] LIMIT 1");
	if ($class) $myclass = ' class="'.$class.'" ';
	if ($picturelist) {
		$pid = $picturelist[0]->pid;
		if (is_callable(array('nggGallery','get_image_url'))) {
			// new NextGen 1.0+
			$out = '<img alt="' . __('property photo') . '" src="' . nggGallery::get_thumbnail_url($pid) . '" ' . $myclass . '/>';
		}
		else {
			// backwards compatibility - NextGen below 1.0
			$out = '<img alt="' . __('property photo') . '" src="' . nggallery::get_thumbnail_url($pid) . '" ' . $myclass . '/>';
		}
		return $out;
	}
}

add_shortcode('gallery_crumb', 'oo_get_gallery_crumb');
$gallery_crumb_done = false;

function oo_get_gallery_crumb() {
	global $gallery_crumb_done;
	
	if ($gallery_crumb_done) {
		return '';
	}
	$slug = array();
	$title = array();
	$html = array();
	$i = 0;
	$s = pods_url_variable($i);
	$url = '';
	if ($s == "party-ideas") {
	
		while ($s != '') {
			$slug[$i] = $s;
			$page = get_page_by_path($page_slug);
			if ($page) {
				$title[$i] = get_the_title( $page->ID );
			}
			else {
				$title[$i] = ucwords(strtolower(str_replace('-', ' ', $slug[$i])));
			}
			$url .= "/" . $slug[$i];
			$html[$i] = "<a href=\"$url\">{$title[$i]}</a>";
			
			$i++;
			$s = pods_url_variable($i);
		}
		$crumb = implode(' / ', $html);	
		$safe_crumb = base64_encode(serialize($crumb));
		setcookie('crumb', $safe_crumb, 0, '/');
	}
//	elseif (is_post()) {
//		$crumb = "<a href=\"" . get_permalink() . "\">" . get_the_title() . "</a>";
//		$safe_crumb = base64_encode(serialize($crumb));
//		setcookie('crumb', $safe_crumb, 0, '/');
//	}
	else {
		$crumb = unserialize(base64_decode($_COOKIE['crumb']));
	}
	$gallery_crumb_done = true;
	return '<p class="crumb">Back to: ' . $crumb . '</p>';
}

add_shortcode('distribution_map', 'oo_get_distribution_map');

function oo_get_distribution_map() {

	$result = pod_query("SELECT * FROM ao_dist_locations");
	while ($row = mysql_fetch_assoc($result))
	{
		$row['dist_location'] = htmlentities($row['dist_location'], ENT_QUOTES);
		$row['dist_address1'] = htmlentities($row['dist_address1'], ENT_QUOTES);
		$row['dist_address2'] = htmlentities($row['dist_address2'], ENT_QUOTES);
		$map_items[$row['dist_id']] = $row;
	}
	ob_start();
	show_distributionmap($map_items, -1, 6, true, false);
	return ob_get_clean();
}

function show_distributionmap($amap_items, $center_element = -1, $zoom_level = 9, $show_map = false, $show_button = true) {

	if ($show_button) {
?>
	<div id="map_hide" onclick="maps_swap(); return false;">Show On Map</div>
<?php
	}
?>
	<div id="collapsing_map" class="CollapsiblePanel">
		<div style="visibility: hidden;" class="CollapsiblePanelTab"></div>
		<div class="CollapsiblePanelContent">
			<div id="map_canvas" style="width:600px; height:600px"></div>
		</div>
	</div>
		
	<script type="text/javascript">
	
<?php
	if ($show_map) {
?>
		var collapsing_map = new Spry.Widget.CollapsiblePanel("collapsing_map", { contentIsOpen: true });
		var map_initialized = true;
		maps_initialize();
<?php
	}
	else {
?>
		var collapsing_map = new Spry.Widget.CollapsiblePanel("collapsing_map", { contentIsOpen: false });
		var map_initialized = false;
<?php
	}
?>

		
		function maps_swap() {
		
			e=document.getElementById('map_hide');
			if (e.innerHTML == 'Hide Map') {
				e.innerHTML = 'Show On Map';
				collapsing_map.close();
			}
			else {
				e.innerHTML = 'Hide Map';
				collapsing_map.open();
				if (!map_initialized) {
					maps_initialize();
					map_initialized = true;
				}
			}
		}
		
		function maps_initialize() {
			<?php
				if (($center_element == -1) || ($amap_items[$center_element]['dist_latitude'] == '') || ($amap_items[$center_element]['dist_longitude'] == '')) {
					// ATLANTA
					echo 'var city_latlng = new google.maps.LatLng(32.4800,-84.3880);';
				}
				else {
					echo 'var city_latlng = new google.maps.LatLng(', $amap_items[$center_element]['dist_latitude'], ',', $amap_items[$center_element]['dist_longitude'], ');';
				}
			?>

			var city_options = {
				zoom: <?php echo $zoom_level; ?>,
				center: city_latlng,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
		
			var ao_map = new google.maps.Map(document.getElementById("map_canvas"), city_options);
		
			var vendor_infowindow = new google.maps.InfoWindow({
				content: ""
			});
			
			google.maps.event.addListener(ao_map, "click", function(){ 
				vendor_infowindow.close(); 
			}); 
		
<?php
			$i = 0;
			foreach($amap_items as $vid => $fields) {
				$i++;
				if ($fields["dist_latitude"] <> "" && $fields["dist_longitude"] <> "") {
				echo <<<HEREDOC

				var vendor_latlng$i = new google.maps.LatLng({$fields["dist_latitude"]},{$fields["dist_longitude"]});
				var vendor_marker$i = new google.maps.Marker({
					position: vendor_latlng$i,
					map: ao_map
				});
				
				var vendor_details$i = '<p><b>{$fields["dist_location"]}</b><br />{$fields["dist_address1"]}<br />{$fields["dist_city"]}, {$fields["dist_state"]} {$fields["dist_zip"]}</p>';
				google.maps.event.addListener(vendor_marker$i, 'click', function() {
					vendor_infowindow.setContent(vendor_details$i);
					vendor_infowindow.open(ao_map,vendor_marker$i);
				});
HEREDOC;
				}
			}
?>

		}
	</script>
	
<?php
}


?>
