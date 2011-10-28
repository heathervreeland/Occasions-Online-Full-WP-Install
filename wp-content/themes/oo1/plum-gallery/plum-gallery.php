<?php

function plumgallery_func($atts) {
	global $wpdb, $data_ngs;  

	// these are the shortcode default/allowed attributes
  	$attributes = shortcode_atts(array(
		'id' => '0',
		'theme' => 'default',
		'width' => '600',
		'height' => '400',
		'hide_controls' => false,
		'hide_nav' => false,
		'fill_stage' => 'false',
		'animation_speed' => '1000',
		'slideshow_enable' => 'true',
		'slideshow_autostart' => 'true',
		'slideshow_speed' => '4000',
		'slideshow_start_label' => 'Start',
		'slideshow_stop_label' => 'Stop',
		'slideshow_stop_on_scroll' => 'true',
		'slideshow_countdown_prefix' => '(',
		'slideshow_countdown_sufix' => ')',
		'num_images' => '0'
	), $atts);

	extract($attributes);

  // defined in nextgen gallery view
	extract($data_ngs);
	
	// Get the pictures
	if ($id != '0') {
		$sql = "SELECT t.*, tt.* FROM $wpdb->nggallery AS t INNER JOIN $wpdb->nggpictures AS tt ON t.gid = tt.galleryid WHERE t.gid = '$id' AND tt.exclude != 1 ";// ORDER BY tt.$ngg_options[galSort] $ngg_options[galSortDir] ";
		if ($num_images != '0') {
			$sql .= " LIMIT 0,$num_images";
		}
		$pictures    = $wpdb->get_results($sql);
				   
		$final = array();
		$img_list = '';
		foreach($pictures as $picture) {
			$aux = array();
			$aux["title"] = $picture->alttext; // $picture->alttext;
			$aux["desc"]  = $picture->description;
			$aux["link"]  = BASE_URL . "/" . $picture->path ."/" . $picture->filename;
			$aux["img"]   = BASE_URL . "/" . $picture->path ."/" . $picture->filename;
			$aux["thumb"] = BASE_URL . "/" . $picture->path ."/thumbs/thumbs_" . $picture->filename;
			
			$final[] = $aux;
			
			$img_list .= "<li><a href=\"{$aux['img']}\"><img src=\"{$aux['thumb']}\" title=\"{$aux['title']}\"></a></li>";
		}	
		$pictures = $final;
	}

  // pulling theme from /plug_gallery
	include("theme_$theme.php");
	$theme_function = "render_$theme";
	return call_user_func($theme_function, $attributes, $pictures);

}
add_shortcode('plumgallery', 'plumgallery_func');

?>
