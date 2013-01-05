<?php

class Action extends ActionAbstract
{
    function index(){
	?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
		<head>
			<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
			<title>Atom Admin</title>
			<link rel="stylesheet" href="style/css/960.css" type="text/css" media="screen" charset="utf-8" />
			<link rel="stylesheet" href="style/css/template.css" type="text/css" media="screen" charset="utf-8" />
			<link rel="stylesheet" href="style/css/colour.css" type="text/css" media="screen" charset="utf-8" />
			<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
			<script>!window.jQuery && document.write('<script src="http://code.jquery.com/jquery-1.4.2.min.js"><\/script>');</script>
				<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
				<script type="text/javascript" src="/js/plupload/plupload.js"></script>
				<script type="text/javascript" src="/js/plupload/plupload.gears.js"></script>
				<script type="text/javascript" src="/js/plupload/plupload.silverlight.js"></script>
				<script type="text/javascript" src="/js/plupload/plupload.flash.js"></script>
				<script type="text/javascript" src="/js/plupload/plupload.browserplus.js"></script>
				<script type="text/javascript" src="/js/plupload/plupload.html4.js"></script>
				<script type="text/javascript" src="/js/plupload/plupload.html5.js"></script>

				<!-- <script type="text/javascript"  src="http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js"></script> -->
		</head>
		<body>
			<h1 id="head">愤毛阿青thumbs</h1>
			<ul id="navigation">
				<li><a href="?url=admin/index">Dashboard</a></li>
				<li><a href="?url=admin/album/getList">相册列表</a></li>
			</ul>
			<div id="foot">
				<div class="container_16 clearfix">
					<div class="grid_16">
						<a href="#">Contact Me</a>
					</div>
				</div>
			</div>
	        <script type="text/javascript" charset="utf-8">
	        var c = '{$controller->controller}';
	        $('#navigation a').removeClass('active');
	        $('#navigation a').each(function(){
	            if (this.href.indexOf(c) != -1) {
	                $(this).addClass('active');
	            }
	        });
	        $('a.delete').click(function(){
	            return confirm('确定删除?该操作不能恢复');
	        });
	        </script>

		
		<div id="content" class="container_16 clearfix">

		 	<div class="grid_16">
		        <p>
		            <label for="title">上传文件 <small>upload.</small></label>
		        </p>
				<p><input type='text' id='pathUpload' name='pathUpload' value='/'></p>
		    </div>

			<div id="container" class="grid_16">
			    <div id="filelist">No runtime found.</div>
			    <br />
			    <a id="pickfiles" href="javascript:;">[Select files]</a> 
			    <a id="uploadfiles" href="javascript:;">[Upload files]</a>
			</div>


			<script type="text/javascript">

			function $(id) {
				return document.getElementById(id);	
			}
			
			var uploader = new plupload.Uploader({
				runtimes : 'gears,html5,flash,silverlight,browserplus',
				browse_button : 'pickfiles',
				container: 'container',
				max_file_size : '10mb',
				url : '/?url=upload/upload&pathUpload=/upload',
				resize : { width : 320, height : 240, quality : 90 },
				flash_swf_url : '/js/plupload/plupload.flash.swf',
				silverlight_xap_url : '/js/plupload/plupload.silverlight.xap',
				filters : [
					{ title : "Image files", extensions : "jpg,gif,png" },
					{ title : "code files", extensions : "php,js,html,css" },
					{ title : "Zip files", extensions : "zip" }
				]

			});

			uploader.bind('Init', function(up, params) {
				$('filelist').innerHTML = "<div>Current runtime: " + params.runtime + "</div>";
			});

			uploader.bind('FilesAdded', function(up, files) {
				for (var i in files) {
					$('filelist').innerHTML += '<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>';
				}
			});

			uploader.bind('UploadProgress', function(up, file) {
				$(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
			});
			
			uploader.bind('BeforeUpload', function(up, file) {
				var pathUpload = $('pathUpload').value;
			    up.settings.url = "/?url=upload/upload&pathUpload="+pathUpload;
			});

			$('uploadfiles').onclick = function() {
				uploader.start();
				return false;
			};

			uploader.init();

			</script>


		</div>
		</body>
	</html>	
	<?php
	}

	function upload($pathUpload = DIRECTORY_SEPARATOR)
	{
		/**
		 * upload.php
		 *
		 * Copyright 2009, Moxiecode Systems AB
		 * Released under GPL License.
		 *
		 * License: http://www.plupload.com/license
		 * Contributing: http://www.plupload.com/contributing
		 */

		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		// Settings
		//$targetDir = ini_get("upload_tmp_dir") . DIRECTORY_SEPARATOR . "plupload";
		$targetDir = ROOT_PATH . $pathUpload;

		$cleanupTargetDir = true; // Remove old files
		$maxFileAge = 5 * 3600; // Temp file age in seconds

		// 5 minutes execution time
		@set_time_limit(5 * 60);

		// Uncomment this one to fake upload time
		// usleep(5000);

		// Get parameters
		$chunk = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

		// Clean the fileName for security reasons
		$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);

		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
			$ext = strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);

			$count = 1;
			while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
				$count++;

			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}

		$filePath = $targetDir . DIRECTORY_SEPARATOR . $fileName;

		// Create target dir
		if (!file_exists($targetDir))
			@mkdir($targetDir);

		// Remove old temp files	
		if ($cleanupTargetDir && is_dir($targetDir) && ($dir = opendir($targetDir))) {
			while (($file = readdir($dir)) !== false) {
				$tmpfilePath = $targetDir . DIRECTORY_SEPARATOR . $file;

				// Remove temp file if it is older than the max age and is not the current file
				if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
					@unlink($tmpfilePath);
				}
			}

			closedir($dir);
		} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');


		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
			$contentType = $_SERVER["HTTP_CONTENT_TYPE"];

		if (isset($_SERVER["CONTENT_TYPE"]))
			$contentType = $_SERVER["CONTENT_TYPE"];

		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				// Open temp file
				$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = fopen($_FILES['file']['tmp_name'], "rb");

					if ($in) {
						while ($buff = fread($in, 4096))
							fwrite($out, $buff);
					} else
						die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
					fclose($in);
					fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
		} else {
			// Open temp file
			$out = fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = fopen("php://input", "rb");

				if ($in) {
					while ($buff = fread($in, 4096))
						fwrite($out, $buff);
				} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');

				fclose($in);
				fclose($out);
			} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}

		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
		}
		
		//$finfo = finfo_open(FILEINFO_MIME_TYPE);
	    //$mime = finfo_file($finfo, $filePath);
		//$exif = exif_read_data($filePath, 'IFD0');
		// Return JSON-RPC response
		die('{"jsonrpc" : "2.0", "result" : null, "id" : "id"}');

	}

}