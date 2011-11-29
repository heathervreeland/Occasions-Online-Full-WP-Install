<?php
		$got_thumb = false;
		$gallery_id = get_post_meta($post->ID, 'gallery_id', true);
		
		// first get any post thumbnail that might exist
		if (has_post_thumbnail()) {
			the_post_thumbnail(array(125,125));
			$got_thumb = true;;
		}
		
		// ...then try to get the thumb from the first thumbnail from the gallery, if one exists
		if (!$got_thumb && $gallery_id) {
			$gallery_image = oo_get_first_gallery_thumbnail($gallery_id);
			if ($gallery_image) {
				echo $gallery_image;
				$got_thumb = true;
			}
		}

		// ...and if all else fails, use the default thumb
		if (!$got_thumb) {
			echo '<img src="/media/images/mag-thumb.png" class="attachment-145x145 wp-post-image" alt="" title="">';
		}
?>
