<?php

function render_default($attributes, $pictures) {

	// first make all our attributes available locally
	extract($attributes);

	// build our images list from the $pictures array
	foreach($pictures as $picture) {
		$img_list .= "<li><a href=\"{$picture['img']}\"><img src=\"{$picture['thumb']}\" title=\"{$picture['title']}\"></a></li>";
	}	

	// pull our CSS file and store it's output
	ob_start();
	include "theme_default.css";
	$gallery_html = ob_get_clean();

	$loader = get_bloginfo('stylesheet_directory') . '/plum-gallery/loader.gif';
	$gallery_html .= <<<GHTML
<div class="ad-gallery">
  <div class="ad-image-wrapper">
  </div>
  <div class="ad-controls">
  </div>
  <div class="ad-nav">
    <div class="ad-thumbs">
      <ul class="ad-thumb-list">
        $img_list
      </ul>
    </div>
  </div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
	var galleries = $('.ad-gallery').adGallery({
	  loader_image: '$loader',
	  fill_stage: $fill_stage,
	  width: $width, // Width of the image, set to false and it will read the CSS width
	  height: $height, // Height of the image, set to false and it will read the CSS height
	  thumb_opacity: 0.4, // Opacity that the thumbs fades to/from, (1 removes fade effect)
						  // Note that this effect combined with other effects might be resource intensive
						  // and make animations lag
	  start_at_index: 0, // Which image should be displayed at first? 0 is the first image
	  description_wrapper: $('#descriptions'), // Either false or a jQuery object, if you want the image descriptions
											   // to be placed somewhere else than on top of the image
	  animate_first_image: false, // Should first image just be displayed, or animated in?
	  animation_speed: $animation_speed, // Which ever effect is used to switch images, how long should it take?
	  display_next_and_prev: true, // Can you navigate by clicking on the left/right on the image?
	  display_back_and_forward: true, // Are you allowed to scroll the thumb list?
	  scroll_jump: 0, // If 0, it jumps the width of the container
	  slideshow: {
		enable: $slideshow_enable,
		autostart: $slideshow_autostart,
		speed: $slideshow_speed,
		start_label: '$slideshow_start_label',
		stop_label: '$slideshow_stop_label',
		stop_on_scroll: $slideshow_stop_on_scroll, // Should the slideshow stop if the user scrolls the thumb list?
		countdown_prefix: '$slideshow_countdown_prefix', // Wrap around the countdown
		countdown_sufix: '$slideshow_countdown_sufix'
		//onStart: function() {
		  // Do something wild when the slideshow starts
		//},
		//onStop: function() {
		  // Do something wild when the slideshow stops
		//}
	  },
	  effect: 'fade', // or 'slide-vert', 'slide-hori', 'resize', 'none' or false
	  enable_keyboard_move: true, // Move to next/previous image with keyboard arrows?
	  cycle: true // If set to false, you can't go from the last image to the first, and vice versa
	});
GHTML;

	$gallery_html .= <<<GHTML
});
</script>

GHTML;

	return $gallery_html;
}
?>