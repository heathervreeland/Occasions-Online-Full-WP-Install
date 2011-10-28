<?php

/* Template Name: Vendor Sitemap */


header ("Content-Type:text/xml");

include_once('guide-functions.php');

// added by Ben Kaplan - 10/28/11 - prints out dev or www depending upon environment
$subdomain = print_subdomain();

$stamp = date("Y-m-d");

echo <<<XMLOUT
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
XMLOUT;

// =====================================================
// FIRST, OUTPUT THE VENDORS
// =====================================================

$sqlwhere = "profile_type = 'platinum' ";
$vp = new Pod('vendor_profiles');
$vp->findRecords( 'id', -1, $sqlwhere);

if( $vp->getTotalRows() > 0 ) {
	while ( $vp->fetchRecord() ) {
		$slug = $vp->get_field('slug');

echo <<<XMLOUT
	<url>
		<loc>http://$subdomain.occasionsonline.com/profile/$slug</loc>
		<lastmod>$stamp</lastmod>
		<changefreq>weekly</changefreq>
		<priority>0.9</priority>
	</url>
XMLOUT;

	}
}

// =====================================================
// OUTPUT THE GUIDE ITSELF & CATEGORIES
// =====================================================

echo <<<XMLOUT
	<url>
		<loc>http://$subdomain.occasionsonline.com/atlanta</loc>
		<lastmod>$stamp</lastmod>
		<changefreq>weekly</changefreq>
		<priority>0.8</priority>
	</url>
XMLOUT;

$categories = new Pod('categories');
$categories->findRecords( '', 0, '', 'SELECT slug FROM wp_pod_tbl_categories WHERE hide <> 1 ORDER BY sort_order, name');
if( $categories->getTotalRows() > 0 ) {
	while ( $categories->fetchRecord() ) {
		$slug = $categories->get_field('slug');

echo <<<XMLOUT
	<url>
		<loc>http://$subdomain.occasionsonline.com/atlanta/$slug</loc>
		<lastmod>$stamp</lastmod>
		<changefreq>always</changefreq>
		<priority>0.8</priority>
	</url>
XMLOUT;

	}
}

// =====================================================
// OUTPUT THE SUB-CATEGORIES
// =====================================================

$types = new Pod('venue_types');
$types->findRecords( '', 0, '', 'SELECT slug FROM wp_pod_tbl_venue_types ORDER BY name');
if( $types->getTotalRows() > 0 ) {
	while ( $types->fetchRecord() ) {
		$slug = $types->get_field('slug');

echo <<<XMLOUT
	<url>
		<loc>http://$subdomain.occasionsonline.com/atlanta/venues/$slug</loc>
		<lastmod>$stamp</lastmod>
		<changefreq>always</changefreq>
		<priority>0.7</priority>
	</url>
XMLOUT;

	}
}

echo <<<XMLOUT

</urlset>
XMLOUT;


