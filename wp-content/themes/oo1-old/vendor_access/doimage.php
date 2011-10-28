<?php
//echo '1';
//exit;

// this is our profile ID
$pid = pods_url_variable(2);

foreach($_REQUEST as $key => $value) {
	$get[$key] = filter($value);
}

if(isset($get['cmd']) && $get['cmd'] == 'delete') {

	$ret = '1';
	$img_name = $get['image'];
	if(is_file("$img_dst_thumb/$pid/$img_name")) {
		if (!unlink("$img_dst_thumb/$pid/$img_name")) {
			$ret = '0';
		}
	}
	if(is_file("$img_dst_large/$pid/$img_name")) {
		if (!unlink("$img_dst_large/$pid/$img_name")) {
			$ret = '0';
		}
	}
	if(is_file("$img_dst_source/$pid/$img_name")) {
		if (!unlink("$img_dst_source/$pid/$img_name")) {
			$ret = '0';
		}
	}
	$img_name = str_replace('_', '-', $img_name);
	if(is_file("$img_dst_source/$pid/$img_name")) {
		if (!unlink("$img_dst_source/$pid/$img_name")) {
			$ret = '0';
		}
	}
	echo $ret;
	exit;
}

if(isset($get['cmd']) && $get['cmd'] == 'deletelogo') {

	$ret = '1';
	if(is_file("$img_dst_logo/$pid/logo.jpg")) {
		if (!unlink("$img_dst_logo/$pid/logo.jpg")) {
			$ret = '0';
		}
	}
	echo $ret;
	exit;
}

if(isset($get['cmd']) && $get['cmd'] == 'deletevideo') {

	$ret = '1';
	$vid_name = $get['video'];
	if(is_file("$img_dst_video/$pid/$vid_name")) {
		if (!unlink("$img_dst_video/$pid/$vid_name")) {
			$ret = '0';
		}
	}
	echo $ret;
	exit;
}

if(isset($get['cmd']) && $get['cmd'] == 'get_thumbnail_block') {

	$profile = new Pod('vendor_profiles');
	$profile->findRecords( 'id', -1, "t.id = '$pid'");
	$total = $profile->getTotalRows();
	if( $total > 0 ) {
		$profile->fetchRecord();
		$a = get_vendorfields($profile);
		load_vendorimages($profile, $a);
	}
	echo get_thumbnail_block($a);
	exit;
}

if(isset($get['cmd']) && $get['cmd'] == 'get_logo_block') {

	$profile = new Pod('vendor_profiles');
	$profile->findRecords( 'id', -1, "t.id = '$pid'");
	$total = $profile->getTotalRows();
	if( $total > 0 ) {
		$profile->fetchRecord();
		$a = get_vendorfields($profile);
		load_vendorimages($profile, $a);
	}
	echo get_logo_block($a);
	exit;
}

if(isset($get['cmd']) && $get['cmd'] == 'get_video_block') {

	$profile = new Pod('vendor_profiles');
	$profile->findRecords( 'id', -1, "t.id = '$pid'");
	$total = $profile->getTotalRows();
	if( $total > 0 ) {
		$profile->fetchRecord();
		$a = get_vendorfields($profile);
		load_vendorimages($profile, $a);
	}
	echo get_video_block($a);
	exit;
}

include_once($img_base_path . '/media/js/class.upload.php');

if(isset($get['cmd']) && $get['cmd'] == 'upload_images') {

	if (!empty($_FILES)) {
	
		// first make our directories
		if (!is_dir("$img_dst_source/$pid")) {mkdir("$img_dst_source/$pid", 0755);}
		if (!is_dir("$img_dst_large/$pid")) {mkdir("$img_dst_large/$pid", 0755);}
		if (!is_dir("$img_dst_thumb/$pid")) {mkdir("$img_dst_thumb/$pid", 0755);}
	
		$file_name = sanitize_file_name($_FILES["Filedata"]["name"]);
	
		// Check the upload
		if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
			file_put_contents("$img_dst_source/$pid/$file_name.log", "Upload appears to me invalid.", FILE_APPEND);
			echo "ERROR: invalid upload";
			exit(0);
		}
	
		//move_uploaded_file($_FILES['Filedata']['tmp_name'], "$img_dst_source/$pid/$file_name");
		//file_put_contents("$img_dst_source/$pid/$file_name.log", $foo->error, FILE_APPEND);
		
		//copy($img_base_path . $img, "$img_dst_source/{$a['id']}/$file_name");
	
		if (!move_uploaded_file($_FILES['Filedata']['tmp_name'], "$img_dst_source/$pid/$file_name")) {
			file_put_contents("$img_dst_source/$pid/$file_name.log", "Error attempting to execute move_uploaded_file().", FILE_APPEND);
			echo "ERROR: error in upload";
			exit(0);
		}
	
		$foo = new upload("$img_dst_source/$pid/$file_name");
		//if ($foo->uploaded) {
	
			// save uploaded image unaltered to our source directory for safe keeping
			//$foo->file_overwrite		= true;
			//$foo->Process("$img_dst_source/$pid");
			//if (!$foo->processed) {
			//	echo 'ERROR ON SOURCE : ' . $foo->error;
			//	exit;
			//}
	  
			// produce the large image. fit image to 600w x 400h
			$foo->file_overwrite		= true;
			$foo->file_auto_rename		= false;
			$foo->auto_create_dir		= false;
			$foo->dir_auto_chmod		= false;
			$foo->dir_chmod				= 0755;
			$foo->image_convert 		= 'jpg';
			$foo->file_safe_name		= false;
			$foo->jpeg_quality			= 85;
			$foo->mime_check			= false;
			$foo->image_resize			= true;
			$foo->image_ratio			= true;
			$foo->image_x				= 600;
			$foo->image_y				= 400;
			$foo->Process("$img_dst_large/$pid/");
			if (!$foo->processed) {
				file_put_contents("$img_dst_source/$pid/$file_name.log", $foo->error, FILE_APPEND);
				//echo 'ERROR ON LARGE: ' . $foo->error;
				//exit(0);
			}
			
			// produce the thumbnail image. resize and crop to 125w x 125h
			$foo->file_overwrite		= true;
			$foo->file_auto_rename		= false;
			$foo->auto_create_dir		= false;
			$foo->dir_auto_chmod		= false;
			$foo->dir_chmod				= 0755;
			$foo->image_convert 		= 'jpg';
			$foo->file_safe_name		= false;
			$foo->jpeg_quality			= 85;
			$foo->mime_check			= false;
			$foo->image_resize			= true;
			$foo->image_ratio_crop		= true;
			$foo->image_y				= 125;
			$foo->image_x				= 125;
			$foo->Process("$img_dst_thumb/$pid/");
			if (!$foo->processed) {
				file_put_contents("$img_dst_source/$pid/$file_name.log", $foo->error, FILE_APPEND);
				//echo 'ERROR ON THUMBNAIL: ' . $foo->error;
				//exit(0);
			}
			
			echo '1';
			exit;
		//}
	}
}

if(isset($get['cmd']) && $get['cmd'] == 'upload_logo') {

	if (!empty($_FILES)) {
	
		// first make our logo directories
		if (!is_dir("$img_dst_logosource/$pid")) {mkdir("$img_dst_logosource/$pid", 0755);}
		if (!is_dir("$img_dst_logo/$pid")) {mkdir("$img_dst_logo/$pid", 0755);}
	
		$file_name = sanitize_file_name($_FILES["Filedata"]["name"]);
	
		// Check the upload
		if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
			file_put_contents("$img_dst_logosource/$pid/$file_name.log", "Upload appears to me invalid.", FILE_APPEND);
			echo "ERROR: invalid upload";
			exit(0);
		}
	
		//move_uploaded_file($_FILES['Filedata']['tmp_name'], "$img_dst_source/$pid/$file_name");
		//file_put_contents("$img_dst_source/$pid/$file_name.log", $foo->error, FILE_APPEND);
		
		//copy($img_base_path . $img, "$img_dst_source/{$a['id']}/$file_name");
	
		if (!move_uploaded_file($_FILES['Filedata']['tmp_name'], "$img_dst_logosource/$pid/$file_name")) {
			file_put_contents("$img_dst_logosource/$pid/$file_name.log", "Error attempting to execute move_uploaded_file().", FILE_APPEND);
			echo "ERROR: error in upload";
			exit(0);
		}
	
		$foo = new upload("$img_dst_logosource/$pid/$file_name");
	  
		// produce the thumbnail image. resize and crop to 125w x 125h
		$foo->file_new_name_body	= 'logo';
		$foo->file_new_name_ext		= 'jpg';
		$foo->file_overwrite		= true;
		$foo->file_auto_rename		= false;
		$foo->auto_create_dir		= false;
		$foo->dir_auto_chmod		= false;
		$foo->dir_chmod				= 0755;
		$foo->image_convert 		= 'jpg';
		$foo->file_safe_name		= false;
		$foo->jpeg_quality			= 85;
		$foo->mime_check			= false;
		$foo->image_resize			= true;
		$foo->image_ratio_crop		= true;
		$foo->image_y				= 125;
		$foo->image_x				= 125;
		$foo->Process("$img_dst_logo/$pid/");
		if (!$foo->processed) {
			file_put_contents("$img_dst_logosource/$pid/$file_name.log", $foo->error, FILE_APPEND);
			//echo 'ERROR ON LOGO THUMBNAIL: ' . $foo->error;
			//exit(0);
		}
		unlink("$img_dst_logosource/$pid/$file_name");
		
		echo '1';
		exit;
	}
}

if(isset($get['cmd']) && $get['cmd'] == 'upload_videos') {

	if (!empty($_FILES)) {
	
		// first make our directory
		if (!is_dir("$img_dst_video/$pid")) {mkdir("$img_dst_video/$pid", 0755);}
	
		$file_name = sanitize_file_name($_FILES["Filedata"]["name"]);
	
		// Check the upload
		if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
			file_put_contents("$img_dst_video/$pid/$file_name.log", "Upload appears to me invalid.", FILE_APPEND);
			echo "ERROR: invalid upload";
			exit(0);
		}

		if (!move_uploaded_file($_FILES['Filedata']['tmp_name'], "$img_dst_video/$pid/$file_name")) {
			file_put_contents("$img_dst_video/$pid/$file_name.log", "Error attempting to execute move_uploaded_file().", FILE_APPEND);
			echo "ERROR: error in upload";
			exit(0);
		}
	
		echo '1';
		exit;
	}
}

echo '0';

?>