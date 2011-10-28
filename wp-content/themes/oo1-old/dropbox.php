<?php
/*
Template Name: Dropbox
*/

$a = explode( '/', $_SERVER['REQUEST_URI']);
$acct = $a[2];
if ($acct) {
	$acct = sanitizeFilename($acct);
}

if ($_REQUEST['submitted'] == '1') {
	
	if ($_REQUEST['comp'] == '') {
		$acct = '';
	}
	else {
		header('Location: http://www.occasionsonline.com/dropbox/' . sanitizeFilename($_REQUEST['comp']) );
		exit;
	}
}

get_header(); 
?>
	<div class="ruled left"><span class="head2 ruled-text-left">FTP Dropbox</span></div>
<?php

if ($acct == '') {
?>
			<form method="POST" name="searchform" id="searchform" action="/dropbox/" >
			<p>Enter the name of your company and the project name:</p>
			<input type="text" class="vendor_txt" value="" name="comp" size="50"  />
			<p class="oo-color-slate"><i>e.g.&nbsp;&nbsp;&nbsp;ABC Photography, Smith-Jones Atlanta Wedding</i></p>
			<input type="hidden" name="submitted" value="1"/>
			<p>&nbsp;<br /><input id="vendor_submit" type="submit" value="Proceed to upload..." /></p>
			</form>


<?php
} else {
?>
			<p>Click the button below to select one or more files to upload.<br />&nbsp;<br />(NOTE: Maximum filesize is 40 MB per file.)<br />&nbsp;</p>
			<p class="vendor_uploadflash"><span id="spanButtonPlaceholder"></span></p>
			<div id="divFileProgressContainer"></div>

<script language="JavaScript" type="text/javascript"> 
	var swfu1;
	window.onload = function () {
		swfu1 = new SWFUpload({
			// Backend Settings
			upload_url: '<?php bloginfo('stylesheet_directory'); ?>/dropbox-upload.php',
			post_params: {"cmd": "upload_files", "acct": "<?php echo $acct;?>"},

			// File Upload Settings
			file_size_limit : "40 MB",	// 2MB
			file_types : "*.*",
			file_types_description : "Readable Formats",
			file_upload_limit : "0",

			// Event Handler Settings - these functions as defined in swfhandlers.js
			//  The handlers are not part of SWFUpload but are part of my website and control how
			//  my website reacts to the SWFUpload events.
			file_queue_error_handler : fileQueueError,
			file_dialog_complete_handler : fileDialogComplete,
			upload_progress_handler : uploadProgress,
			upload_error_handler : uploadError,
			upload_success_handler : uploadSuccess,
			upload_complete_handler : uploadComplete,

			// Button Settings
			button_placeholder_id : "spanButtonPlaceholder",
			button_width: 120,
			button_height: 18,
			button_text : '<span class="swf_button">UPLOAD FILES</span>',
			button_text_style : '.swf_button { font-family: Verdana; color: #666666; font-weight: bold; font-size: 10pt; }',
			button_text_top_padding: 0,
			button_text_left_padding: 8,
			button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
			button_cursor: SWFUpload.CURSOR.HAND,
			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES,
			
			// Flash Settings
			flash_url : "/media/flash/swfupload.swf",

			custom_settings : {
				upload_target : "divFileProgressContainer"
			},
			
			// Debug Settings
			debug: false
		});
	};

	function fileQueueError(file, errorCode, message) {
		try {
			var imageName = "error.gif";
			var errorName = "";
			if (errorCode === SWFUpload.errorCode_QUEUE_LIMIT_EXCEEDED) {
				errorName = "You have attempted to queue too many files.";
			}
	
			if (errorName !== "") {
				alert("Queue Error Specified: " + errorName);
				return;
			}
	
			switch (errorCode) {
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
				imageName = "zerobyte.gif";
				break;
			case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
				imageName = "toobig.gif";
				break;
			case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
			case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
			default:
				alert("Queue Error: " + message);
				break;
			}
	
			//addImage("images/" + imageName);
	
		} catch (ex) {
			this.debug(ex);
		}
	
	}
	
	function fileDialogComplete(numFilesSelected, numFilesQueued) {
		try {
			if (numFilesQueued > 0) {
				this.startUpload();
			}
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadProgress(file, bytesLoaded) {
	
		try {
			var percent = Math.ceil((bytesLoaded / file.size) * 100);
	
			var progress = new FileProgress(file,  this.customSettings.upload_target);
			progress.setProgress(percent);
			if (percent === 100) {
				progress.setStatus("Upload completing...");
				progress.toggleCancel(false, this);
			} else {
				progress.setStatus("Uploading...");
				progress.toggleCancel(true, this);
			}
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadSuccess(file, serverData) {
		try {
			var progress = new FileProgress(file,  this.customSettings.upload_target);
	
			if (serverData.substring(0, 1) === "1") {
				//addImage("thumbnail.php?id=" + serverData.substring(7));
				progress.setStatus("Upload complete.");
				progress.toggleCancel(false);
			} else {
				//addImage("images/error.gif");
				progress.setStatus("Error.");
				progress.toggleCancel(false);
				alert("uS Error:: " + serverData.substring(0,100));
	
			}
	
	
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadComplete(file) {
		try {
			/*  I want the next upload to continue automatically so I'll call startUpload here */
			if (this.getStats().files_queued > 0) {
				this.startUpload();
			} else {
				var progress = new FileProgress(file,  this.customSettings.upload_target);
				progress.setComplete();
				progress.setStatus("");
				progress.toggleCancel(false);
				$(".progressName").html('Image upload finished.');
			}
		} catch (ex) {
			this.debug(ex);
		}
	}
	
	function uploadError(file, errorCode, message) {
		var imageName =  "error.gif";
		var progress;
		try {
			switch (errorCode) {
			case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
				try {
					progress = new FileProgress(file,  this.customSettings.upload_target);
					progress.setCancelled();
					progress.setStatus("Cancelled");
					progress.toggleCancel(false);
				}
				catch (ex1) {
					this.debug(ex1);
				}
				break;
			case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
				try {
					progress = new FileProgress(file,  this.customSettings.upload_target);
					progress.setCancelled();
					progress.setStatus("Stopped");
					progress.toggleCancel(true);
				}
				catch (ex2) {
					this.debug(ex2);
				}
			case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
				imageName = "uploadlimit.gif";
				break;
			default:
				alert("uE Error: " + message);
				break;
			}
	
			//addImage("images/" + imageName);
	
		} catch (ex3) {
			this.debug(ex3);
		}
	
	}
	
	
	function fadeIn(element, opacity) {
		var reduceOpacityBy = 5;
		var rate = 30;	// 15 fps
	
	
		if (opacity < 100) {
			opacity += reduceOpacityBy;
			if (opacity > 100) {
				opacity = 100;
			}
	
			if (element.filters) {
				try {
					element.filters.item("DXImageTransform.Microsoft.Alpha").opacity = opacity;
				} catch (e) {
					// If it is not set initially, the browser will throw an error.  This will set it if it is not set yet.
					element.style.filter = 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')';
				}
			} else {
				element.style.opacity = opacity / 100;
			}
		}
	
		if (opacity < 100) {
			setTimeout(function () {
				fadeIn(element, opacity);
			}, rate);
		}
	}
	
	
	
	/* ******************************************
	 *	FileProgress Object
	 *	Control object for displaying file info
	 * ****************************************** */
	
	function FileProgress(file, targetID) {
		this.fileProgressID = "divFileProgress";
	
		this.fileProgressWrapper = document.getElementById(this.fileProgressID);
		if (!this.fileProgressWrapper) {
			this.fileProgressWrapper = document.createElement("div");
			this.fileProgressWrapper.className = "progressWrapper";
			this.fileProgressWrapper.id = this.fileProgressID;
	
			this.fileProgressElement = document.createElement("div");
			this.fileProgressElement.className = "progressContainer";
	
			var progressCancel = document.createElement("a");
			progressCancel.className = "progressCancel";
			progressCancel.href = "#";
			progressCancel.style.visibility = "hidden";
			progressCancel.appendChild(document.createTextNode(" "));
	
			var progressText = document.createElement("div");
			progressText.className = "progressName";
			progressText.appendChild(document.createTextNode(file.name));
	
			var progressBar = document.createElement("div");
			progressBar.className = "progressBarInProgress";
	
			var progressStatus = document.createElement("div");
			progressStatus.className = "progressBarStatus";
			progressStatus.innerHTML = "&nbsp;";
	
			this.fileProgressElement.appendChild(progressCancel);
			this.fileProgressElement.appendChild(progressText);
			this.fileProgressElement.appendChild(progressStatus);
			this.fileProgressElement.appendChild(progressBar);
	
			this.fileProgressWrapper.appendChild(this.fileProgressElement);
	
			document.getElementById(targetID).appendChild(this.fileProgressWrapper);
			fadeIn(this.fileProgressWrapper, 0);
	
		} else {
			this.fileProgressElement = this.fileProgressWrapper.firstChild;
			this.fileProgressElement.childNodes[1].firstChild.nodeValue = file.name;
		}
	
		this.height = this.fileProgressWrapper.offsetHeight;
	
	}
	FileProgress.prototype.setProgress = function (percentage) {
		this.fileProgressElement.className = "progressContainer green";
		this.fileProgressElement.childNodes[3].className = "progressBarInProgress";
		this.fileProgressElement.childNodes[3].style.width = percentage + "%";
	};
	FileProgress.prototype.setComplete = function () {
		this.fileProgressElement.className = "progressContainer blue";
		this.fileProgressElement.childNodes[3].className = "progressBarComplete";
		this.fileProgressElement.childNodes[3].style.width = "";
	
	};
	FileProgress.prototype.setError = function () {
		this.fileProgressElement.className = "progressContainer red";
		this.fileProgressElement.childNodes[3].className = "progressBarError";
		this.fileProgressElement.childNodes[3].style.width = "";
	
	};
	FileProgress.prototype.setCancelled = function () {
		this.fileProgressElement.className = "progressContainer";
		this.fileProgressElement.childNodes[3].className = "progressBarError";
		this.fileProgressElement.childNodes[3].style.width = "";
	
	};
	FileProgress.prototype.setStatus = function (status) {
		this.fileProgressElement.childNodes[2].innerHTML = status;
		this.fileProgressElement.childNodes[2].visibility =  status ? "visible" : "hidden";
	};
	
	FileProgress.prototype.toggleCancel = function (show, swfuploadInstance) {
		this.fileProgressElement.childNodes[0].style.visibility = show ? "visible" : "hidden";
		if (swfuploadInstance) {
			var fileID = this.fileProgressID;
			this.fileProgressElement.childNodes[0].onclick = function () {
				swfuploadInstance.cancelUpload(fileID);
				return false;
			};
		}
	};
</script> 

<?php
}
get_footer();


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

