<?php 
	function dump_var( $obj = null ) {
		  echo '<pre>';
		  var_dump($obj);
		  echo '</pre>';
		  return null;
		}

		function log_now($log_text){
			file_put_contents( 
					'log_s.txt', 
					$log_text . ' | Date: ' . date("l jS \of F Y h:i:s A") . "\n\n" . PHP_EOL, 
					FILE_APPEND | LOCK_EX 
			);
		}

		//--Image compressor
		function compress_image($source, $destination, $quality) {

		    $info = getimagesize($source);

		    if ($info['mime'] == 'image/jpeg') 
		        $image = imagecreatefromjpeg($source);

		    elseif ($info['mime'] == 'image/gif') 
		        $image = imagecreatefromgif($source);

		    elseif ($info['mime'] == 'image/png') 
		        $image = imagecreatefrompng($source);

		    imagejpeg($image, $destination, $quality);

		    return $destination;
		}