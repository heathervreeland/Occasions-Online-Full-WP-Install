<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

automatic_feed_links();
add_theme_support( 'post-thumbnails' );

if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'before_widget' => '<li id="%1$s" class="widget %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widgettitle">',
		'after_title' => '</h2>',
	));
}

// HOOK the category templates
add_filter( 'category_template', 'ao_category_template' );
add_filter( 'single_template', 'ao_single_template' );

function ao_mce_options( $init ) {
	$init['theme_advanced_blockformats'] = 'p,address,pre,code,h3,h4,h5,h6';
	//$init['theme_advanced_disable'] = 'forecolor';
	$init['relative_urls'] = false;
	$init['remove_script_host'] = true;
	$init['document_base_url'] = get_bloginfo('url') . '/';
	return $init;
}
add_filter('tiny_mce_before_init', 'ao_mce_options');


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

// EDIT BEN
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

$sidebar_content_prewidget = NULL;
$sidebar_content_postwidget = NULL;
$sidebar_content_trailing = NULL;
$ao_title = NULL;

function ao_set_title($content) {
	global $ao_title;
	$ao_title = $content;
}

function ao_get_title() {
	global $ao_title;
	return $ao_title;
}

function ao_set_sidebar_content($content, $location) {

	global $sidebar_content_prewidget;
	global $sidebar_content_postwidget;
	global $sidebar_content_trailing;

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

function iif($condition, $true, $false) {

	return ($condition ? $true : $false);
}

function print_iif($condition, $true, $false) {

	echo iif($condition, $true, $false );
}

?>
