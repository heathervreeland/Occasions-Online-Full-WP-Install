<?php

if (!isset($_REQUEST['acct']) || $_REQUEST['acct'] == '') {
	exit(0);
}

$acct = sanitizeFilename($_REQUEST['acct']);

if(isset($_REQUEST['cmd']) && $_REQUEST['cmd'] == 'upload_files') {

	if (!empty($_FILES)) {
	
		$dst = "/home/oonline/ftp-dropbox/$acct";
		
		// first make our directory
		if (!is_dir("$dst")) {mkdir("$dst", 0755);}
	
		$file_name = sanitizeFilename($_FILES["Filedata"]["name"]);
	
		// Check the upload
		if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
			file_put_contents("$dst/$file_name.log", "Upload appears to me invalid.", FILE_APPEND);
			echo "ERROR: invalid upload";
			exit(0);
		}

		if (!move_uploaded_file($_FILES['Filedata']['tmp_name'], "$dst/$file_name")) {
			file_put_contents("$dst/$file_name.log", "Error attempting to execute move_uploaded_file().", FILE_APPEND);
			echo "ERROR: error in upload";
			exit(0);
		}
	
		echo '1';
		exit;
	}
}
echo '0';

// *************************************************************************************
// generate a sane filename by replacing all 'bad' characters with a sane replacment
// params: 
//   a starting filename.ext
//   optional PCRE-compatible regex of pattern to be replaced (defalult is anything other than alphanum, '.' and '_')
//   optional character/string to use for replacements (default is '_', empty string '' ok, no regex)
// returns your clean filename if success, or FALSE if the input is bad or the resulting name is unusable
function sanitizeFilename($file_name, $pattern_to_replace=NULL, $replacement=NULL) {
    // input must at least appear to have filename and an extension separated by a period
    //$filename_plus_ext_pattern = '/^.*?[a-zA-Z0-9]+.*?\..*?[a-zA-Z0-9]+.*?$/';
    $filename_plus_ext_pattern = '/^.*?[a-zA-Z0-9]+.*?$/';
    if (0 === preg_match($filename_plus_ext_pattern, $file_name)) { // strict equivalence (0=no match, FALSE=bad pattern)
        trigger_error('sanitizeFilename: input filename \''.$file_name.'\' is unusable (must have a filename and an extension)', E_USER_WARNING); return false;
    }
    // establish defaults for what to strip out and what to replace it with
    // (yes, PCRE \w includes underscore, but explicitely allow for clarity)
    $pattern_to_replace = (NULL !== $pattern_to_replace) ? $pattern_to_replace : '/[^\w\._]/';
    $replacement = (NULL !== $replacement) ? $replacement : '_'; // default to underscore
    $file_name = strtolower($file_name); // convert to all lowercase
    // do the actual replacement of bad characters
    $file_name = preg_replace($pattern_to_replace,$replacement,$file_name);
    if(NULL == $file_name) { // NULL return from preg_replace indicates error
        trigger_error('sanitizeFilename: passed regex pattern_to_replace \''.$pattern_to_replace.'\' produces an error', E_USER_WARNING);
    }
    if('' !== $replacement) { // not empty string, which would indicate just remove offending characters
        // escape replacement so it can be used as a match pattern, regardless of what it is
        // (if programmer passes something like a period as the replacement char)
        $replacement = preg_quote($replacement);
        // replace two ore more adjacent replacement characters/strings with a single one
        $p='/('.$replacement.'){2,}/'; $file_name = preg_replace($p,$replacement,$file_name);
        // clean up filenames with replacement chars/strings at the beginning or end ( _dumbfile.ext_ )
        $p='/(^'.$replacement.')|('.$replacement.'$)/'; $file_name = preg_replace($p,'',$file_name);
        // ... and those with the replacements adjacent to any period ( dumbfile_._ext )
        $p='/('.$replacement.'\.)|(\.'.$replacement.')/'; $file_name = preg_replace($p,'.',$file_name);
    } // end if replacement not empty string
    // output must at least appear to have filename and an extension
    if (0 === preg_match($filename_plus_ext_pattern, $file_name)) { // strict equivalence (0=no match, FALSE=bad pattern)
        trigger_error('sanitizeFilename: resulting output filename \''.$file_name.'\' is unusable (must have a filename and an extension)', E_USER_WARNING); return false;
    } else {
      return $file_name;
    } // output filename is okay
} // end sanitizeFilename


?>